<?php

namespace App\Http\Controllers;

use App\Models\AssemblySchedule;
use App\Models\Sale;
use App\Models\Assembler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\AssemblyScheduleEvaluation;
use App\Enums\OrderStatusEnum;

class AssemblyScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('assembly-schedules.index');
    }

    public function mySchedule(Request $request)
    {
        $user = Auth::user();

        // dd($user);
        if (!$user || !$user->assembler) {
            abort(403, 'Você não está associado a um montador.');
        }

        $assembler = $user->assembler;
        $today = now()->toDateString();
        $startOfWeek = now()->startOfWeek()->toDateString();
        $endOfWeek = now()->endOfWeek()->toDateString();

        $dailySchedules = $assembler->assemblySchedules()
                                    ->whereDate('scheduled_date', $today)
                                    ->with('sale')
                                    ->get();

        $weeklySchedules = $assembler->assemblySchedules()
                                     ->whereBetween('scheduled_date', [$startOfWeek, $endOfWeek])
                                     ->with('sale')
                                     ->orderBy('scheduled_date')
                                     ->get();

        return view('assembler.my-schedule', compact('dailySchedules', 'weeklySchedules', 'assembler'));
    }

    public function confirmPresence(Request $request)
    {
        $request->validate([
            'assembly_schedule_id' => 'required|exists:assembly_schedules,id',
            'action' => 'required|in:confirm,cancel',
        ]);

        $user = Auth::user();
        if (!$user || !$user->assembler) {
            return back()->withErrors(['message' => 'Você não está associado a um montador.']);
        }

        $assembler = $user->assembler;
        $assemblyScheduleId = $request->assembly_schedule_id;
        $action = $request->action;

        $pivot = DB::table('assembly_schedule_assembler')
                    ->where('assembler_id', $assembler->id)
                    ->where('assembly_schedule_id', $assemblyScheduleId)
                    ->first();

        if (!$pivot) {
            return back()->withErrors(['message' => 'Agendamento não encontrado para este montador.']);
        }

        $status = ($action === 'confirm') ? 'confirmed' : 'cancelled';

        DB::table('assembly_schedule_assembler')
            ->where('assembler_id', $assembler->id)
            ->where('assembly_schedule_id', $assemblyScheduleId)
            ->update(['confirmation_status' => $status]);

        return back()->with('success', 'Status de presença atualizado com sucesso!');
    }

    public function saveNotes(Request $request)
    {
        $request->validate([
            'assembly_schedule_id' => 'required|exists:assembly_schedules,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        if (!$user || !$user->assembler) {
            return back()->withErrors(['message' => 'Você não está associado a um montador.']);
        }

        $assembler = $user->assembler;
        $assemblyScheduleId = $request->assembly_schedule_id;
        $notes = $request->notes;

        $pivot = DB::table('assembly_schedule_assembler')
                    ->where('assembler_id', $assembler->id)
                    ->where('assembly_schedule_id', $assemblyScheduleId)
                    ->first();

        if (!$pivot) {
            return back()->withErrors(['message' => 'Agendamento não encontrado para este montador.']);
        }

        DB::table('assembly_schedule_assembler')
            ->where('assembler_id', $assembler->id)
            ->where('assembly_schedule_id', $assemblyScheduleId)
            ->update(['assembler_notes' => $notes]);

        return back()->with('success', 'Observações salvas com sucesso!');
    }

    public function startAssembly(Request $request)
    {
        $request->validate([
            'assembly_schedule_id' => 'required|exists:assembly_schedules,id',
            'start_photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'start_latitude' => 'required|numeric|between:-90,90',
            'start_longitude' => 'required|numeric|between:-180,180',
            'start_accuracy' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();
        if (!$user || !$user->assembler) {
            return back()->withErrors(['message' => 'Você não está associado a um montador.']);
        }

        $assemblySchedule = AssemblySchedule::with('assemblers')->findOrFail($request->assembly_schedule_id);

        $loggedInAssembler = $assemblySchedule->assemblers->where('user_id', $user->id)->first();

        if (!$loggedInAssembler || $loggedInAssembler->pivot->confirmation_status !== 'confirmed') {
            return back()->withErrors(['message' => 'Você não tem permissão para iniciar esta montagem ou a confirmação não foi realizada.']);
        }

        if (!$loggedInAssembler || $loggedInAssembler->pivot->confirmation_status === 'started') {
            return back()->withErrors(['message' => 'A montagem já foi iniciada.']);
        }

        $photoPath = $request->file('start_photo')->store('assembly-start-photos', 'public');

        $loggedInAssembler->pivot->confirmation_status = 'started';
        $loggedInAssembler->pivot->started_at = now();
        $loggedInAssembler->pivot->start_photo_path = $photoPath;
        $loggedInAssembler->pivot->start_latitude = $request->start_latitude;
        $loggedInAssembler->pivot->start_longitude = $request->start_longitude;
        $loggedInAssembler->pivot->start_accuracy = $request->start_accuracy;
        $loggedInAssembler->pivot->save();

        $sale = Sale::find($assemblySchedule->sale_id);
        if ($sale) {
            $sale->order_status = OrderStatusEnum::InAssembly;
            $sale->save();
        }

        return redirect()->route('assembly-schedules.showDetails', $assemblySchedule->id)
            ->with('success', 'Montagem iniciada com sucesso!');
    }

    public function startForm(AssemblySchedule $assemblySchedule)
    {
        $user = Auth::user();
        if (!$user || !$user->assembler) {
            return redirect()->route('assembly-schedules.showDetails', $assemblySchedule->id)
                ->withErrors(['message' => 'Apenas montadores podem iniciar a montagem.']);
        }

        $assemblySchedule->load('assemblers');
        $loggedInAssembler = $assemblySchedule->assemblers->where('user_id', $user->id)->first();
        if (!$loggedInAssembler) {
            return redirect()->route('assembly-schedules.showDetails', $assemblySchedule->id)
                ->withErrors(['message' => 'Você não está atribuído a este agendamento.']);
        }
        if ($loggedInAssembler->pivot->confirmation_status !== 'confirmed') {
            return redirect()->route('assembly-schedules.showDetails', $assemblySchedule->id)
                ->withErrors(['message' => 'Confirme sua presença antes de iniciar a montagem.']);
        }

        return view('assembler.start-assembly', compact('assemblySchedule'));
    }

    public function finishForm(AssemblySchedule $assemblySchedule)
    {
        $user = Auth::user();
        if (!$user || !$user->assembler) {
            return redirect()->route('assembly-schedules.showDetails', $assemblySchedule->id)
                ->withErrors(['message' => 'Apenas montadores podem concluir a montagem.']);
        }

        $assemblySchedule->load('assemblers');
        $loggedInAssembler = $assemblySchedule->assemblers->where('user_id', $user->id)->first();
        if (!$loggedInAssembler) {
            return redirect()->route('assembly-schedules.showDetails', $assemblySchedule->id)
                ->withErrors(['message' => 'Você não está atribuído a este agendamento.']);
        }
        if ($loggedInAssembler->pivot->confirmation_status !== 'started') {
            return redirect()->route('assembly-schedules.showDetails', $assemblySchedule->id)
                ->withErrors(['message' => 'A montagem precisa estar iniciada para ser concluída.']);
        }

        return view('assembler.finish-assembly', compact('assemblySchedule'));
    }

    public function finishAssembly(Request $request)
    {
        $type = $request->input('complete_type', 'full');
        $rules = [
            'assembly_schedule_id' => 'required|exists:assembly_schedules,id',
            'finish_notes' => 'nullable|string|max:2000',
            'complete_type' => 'required|in:full,pending',
        ];
        if ($type === 'full') {
            $rules['finish_photos'] = 'required';
            $rules['finish_photos.*'] = 'image|mimes:jpeg,png,jpg,gif,webp|max:5120';
        } else {
            $rules['finish_photos'] = 'nullable';
            $rules['finish_photos.*'] = 'image|mimes:jpeg,png,jpg,gif,webp|max:5120';
            $rules['pending_reason'] = 'required|string|max:2000';
        }
        $request->validate($rules);

        $user = Auth::user();
        if (!$user || !$user->assembler) {
            return back()->withErrors(['message' => 'Você não está associado a um montador.']);
        }

        $assemblySchedule = AssemblySchedule::with('assemblers')->findOrFail($request->assembly_schedule_id);
        $loggedInAssembler = $assemblySchedule->assemblers->where('user_id', $user->id)->first();
        if (!$loggedInAssembler || $loggedInAssembler->pivot->confirmation_status !== 'started') {
            return back()->withErrors(['message' => 'Você não tem permissão para concluir esta montagem.']);
        }

        $paths = [];
        if ($request->hasFile('finish_photos')) {
            foreach ($request->file('finish_photos') as $file) {
                $paths[] = $file->store('assembly-finish-photos', 'public');
            }
        }

        $loggedInAssembler->pivot->confirmation_status = $type === 'full' ? 'completed' : 'completed_with_pendencies';
        $loggedInAssembler->pivot->finished_at = now();
        $loggedInAssembler->pivot->finish_notes = $request->finish_notes;
        $loggedInAssembler->pivot->finish_pending_reason = $type === 'pending' ? $request->pending_reason : null;
        $loggedInAssembler->pivot->finish_photo_paths = json_encode($paths);
        $loggedInAssembler->pivot->save();

        $existingEval = AssemblyScheduleEvaluation::where('assembly_schedule_id', $assemblySchedule->id)->first();
        if (!$existingEval) {
            AssemblyScheduleEvaluation::create([
                'assembly_schedule_id' => $assemblySchedule->id,
                'token' => Str::random(64),
            ]);
        }

        $pendingCount = AssemblySchedule::where('sale_id', $assemblySchedule->sale_id)
            ->whereHas('assemblers', function($q){
                $q->whereNotIn('confirmation_status', ['completed','completed_with_pendencies','cancelled']);
            })
            ->count();
        if ($pendingCount === 0) {
            $sale = Sale::find($assemblySchedule->sale_id);
            if ($sale) {
                $sale->order_status = OrderStatusEnum::Completed;
                $sale->save();
            }
        }

        return redirect()->route('assembly-schedules.showDetails', $assemblySchedule->id)
            ->with('success', 'Montagem concluída e documentada com sucesso!');
    }

    public function allSchedules(Request $request)
    {
        $user = Auth::user();
        // dd($user);
        $query = AssemblySchedule::with('sale', 'assemblers');

        $request->assembler_id = $user->role_id == 4 ? $user->assembler->id : $request->assembler_id;

        if ($user->role_id == 4 || $request->filled('assembler_id')) {
            $query->whereHas('assemblers', function ($q) use ($request) {
                $q->where('assemblers.id', $request->assembler_id);
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('scheduled_date', $request->date);
        }

        if ($request->filled('status')) {
            $query->whereHas('assemblers', function ($q) use ($request) {
                $q->where('confirmation_status', $request->status);
            });
        }

        $schedules = $query->orderBy('scheduled_date')->orderBy('start_time')->get();

        $assemblers = Assembler::all(); // Para o filtro de montadores
    
        return view('admin.assembly-schedules.all', compact('schedules', 'assemblers'));
    }

    public function getCalendarEvents(Request $request)
    {
        $query = AssemblySchedule::with('sale', 'assemblers');

        if ($request->filled('assembler_id')) {
            $query->whereHas('assemblers', function ($q) use ($request) {
                $q->where('assemblers.id', $request->assembler_id);
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('scheduled_date', $request->date);
        }

        if ($request->status) { // Alterado de $request->filled('status') para $request->status
            $query->whereHas('assemblers', function ($q) use ($request) {
                $q->where('confirmation_status', $request->status);
            });
        }

        $schedules = $query->orderBy('scheduled_date')->orderBy('start_time')->get();

        $events = $schedules->map(function ($schedule) {

            $productName = $schedule->sale->product ? $schedule->sale->product->name : 'N/A';
            $customerName = $schedule->sale->customer->customer_type === 'PF' ? $schedule->sale->customer->full_name : $schedule->sale->customer->company_name;
            $title = $customerName;
            $start = $schedule->scheduled_date->format('Y-m-d') . 'T' . \Carbon\Carbon::parse($schedule->start_time)->format('H:i:s');
            $end = $schedule->scheduled_date->format('Y-m-d') . 'T' . \Carbon\Carbon::parse($schedule->end_time)->format('H:i:s');

            return [
                'id' => $schedule->id,
                'title' => $title,
                'start' => $start,
                'end' => $end,
                'allDay' => false,
                'extendedProps' => [
                    'sale_id' => $schedule->sale->id,
                    'customer_name' => $customerName,
                    'product_name' => $productName,
                    'status' => $schedule->assemblers->first() ? ucfirst($schedule->assemblers->first()->pivot->confirmation_status) : 'N/A',
                    'notes' => $schedule->assemblers->first() ? $schedule->assemblers->first()->pivot->assembler_notes ?? 'N/A' : 'N/A',
                    'assemblers' => $schedule->assemblers->map(function ($assembler) {
                        return [
                            'id' => $assembler->id,
                            'name' => $assembler->name,
                            'confirmation_status' => $assembler->pivot->confirmation_status,
                        ];
                    }),
                ],
            ];
        });
        return response()->json($events);
    }

    public function showDetails(string $id)
    {
        $assemblySchedule = AssemblySchedule::with(['sale.customer', 'sale.saleItems', 'assemblers'])->findOrFail($id);
        return view('admin.assembly-schedules.show-details', compact('assemblySchedule'));
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'scheduled_date' => 'required|date',
            'estimated_duration' => 'required|numeric|min:1',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
            'assemblers' => 'required|array|min:1',
            'assemblers.*' => 'exists:assemblers,id',
            'commissions' => 'required|array',
            'commissions.*' => 'required|numeric|min:0',
        ]);

        $scheduledDateTimeStart = \Carbon\Carbon::parse($request->scheduled_date . ' ' . $request->start_time);
        $scheduledDateTimeEnd = \Carbon\Carbon::parse($request->scheduled_date . ' ' . $request->end_time);

        // Check for scheduling conflicts
        foreach ($request->assemblers as $assemblerId) {
            $conflictingSchedules = AssemblySchedule::whereHas('assemblers', function ($query) use ($assemblerId) {
                $query->where('assembler_id', $assemblerId);
            })
            ->where('scheduled_date', $request->scheduled_date)
            ->where(function ($query) use ($scheduledDateTimeStart, $scheduledDateTimeEnd) {
                $query->whereBetween('start_time', [$scheduledDateTimeStart, $scheduledDateTimeEnd->subSecond()])
                      ->orWhereBetween('end_time', [$scheduledDateTimeStart->addSecond(), $scheduledDateTimeEnd])
                      ->orWhere(function ($query) use ($scheduledDateTimeStart, $scheduledDateTimeEnd) {
                          $query->where('start_time', '<=', $scheduledDateTimeStart)
                                ->where('end_time', '>=', $scheduledDateTimeEnd);
                      });
            })
            ->count();

            if ($conflictingSchedules > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Conflito de agendamento detectado para o montador selecionado. Por favor, verifique os horários.',
                    'errors' => ['assemblers' => ['Um ou mais montadores já possuem um agendamento neste período.']]
                ], 409);
            }
        }

        DB::beginTransaction();
        try {
            $assemblySchedule = AssemblySchedule::create([
                'sale_id' => $request->sale_id,
                'scheduled_date' => $request->scheduled_date,
                'estimated_duration' => $request->estimated_duration,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'notes' => $request->notes,
            ]);

            $sale = Sale::find($request->sale_id);
            if ($sale) {
                $sale->order_status = OrderStatusEnum::Delivered;
                $sale->save();
            }

            $assemblerCommissions = [];
            foreach ($request->assemblers as $assemblerId) {
                $assemblerCommissions[$assemblerId] = ['commission_value' => $request->commissions[$assemblerId]];
            }
            $assemblySchedule->assemblers()->sync($assemblerCommissions);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Agendamento de montagem criado com sucesso!',
                'redirect' => route('sales.show', $request->sale_id)
            ]); 
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar agendamento de montagem: ' . $e->getMessage(),
                'errors' => ['server' => [$e->getMessage()]]
            ], 500);
        }
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        
        // If user is Montador, filter by their assembler_id automatically
        $assemblerId = $request->input('assembler_id');
        if ($user->hasRole('Montador') && $user->assembler) {
            $assemblerId = $user->assembler->id;
        }
        
        $statusFilter = $request->input('status');
        $city = $request->input('city');

        $baseQuery = AssemblySchedule::with(['sale.customer', 'assemblers'])
            ->when($start && $end, function($q) use ($start, $end) {
                $q->whereBetween('scheduled_date', [$start, $end]);
            })
            ->when($assemblerId, function($q) use ($assemblerId) {
                $q->whereHas('assemblers', function($qq) use ($assemblerId) { $qq->where('assemblers.id', $assemblerId); });
            })
            ->when($city, function($q) use ($city) {
                $q->whereHas('sale.customer', function($qq) use ($city) { $qq->where('address_city', $city); });
            });

        $schedules = clone $baseQuery;
        $schedules = $schedules->get();

        $today = now()->toDateString();

        $totalMonth = AssemblySchedule::whereBetween('scheduled_date', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $completed = $baseQuery->clone()->whereHas('assemblers', function($q){ $q->whereIn('confirmation_status', ['completed','completed_with_pendencies']); })->count();
        $late = $baseQuery->clone()->whereDate('scheduled_date', '<', $today)->whereDoesntHave('assemblers', function($q){ $q->whereIn('confirmation_status', ['completed','completed_with_pendencies','cancelled']); })->count();
        $next7 = AssemblySchedule::whereBetween('scheduled_date', [now()->toDateString(), now()->addDays(7)->toDateString()])->count();
        $completionRate = $schedules->count() ? round(($completed / $schedules->count()) * 100, 2) : 0;

        $evalQuery = \App\Models\AssemblyScheduleEvaluation::whereHas('schedule', function($q) use ($start,$end,$assemblerId){
            $q->when($start && $end, function($qq) use ($start,$end){ $qq->whereBetween('scheduled_date', [$start,$end]); })
              ->when($assemblerId, function($qq) use ($assemblerId){ $qq->whereHas('assemblers', function($q3) use ($assemblerId){ $q3->where('assemblers.id',$assemblerId); }); });
        })->whereNotNull('submitted_at');
        $npsAvg = round($evalQuery->avg('nps_score'), 2);

        $npsByAssembler = [];
        foreach ($schedules as $s) {
            $eval = \App\Models\AssemblyScheduleEvaluation::where('assembly_schedule_id', $s->id)->whereNotNull('submitted_at')->first();
            if (!$eval) continue;
            foreach ($s->assemblers as $a) {
                if (!isset($npsByAssembler[$a->id])) $npsByAssembler[$a->id] = ['name'=>$a->name,'sum'=>0,'count'=>0];
                $npsByAssembler[$a->id]['sum'] += $eval->nps_score;
                $npsByAssembler[$a->id]['count'] += 1;
            }
        }
        $npsByAssembler = array_map(function($v){ $v['avg'] = $v['count'] ? round($v['sum']/$v['count'],2) : 0; return $v; }, $npsByAssembler);

        $completedByAssembler = [];
        foreach ($schedules as $s) {
            foreach ($s->assemblers as $a) {
                $isCompleted = in_array($a->pivot->confirmation_status, ['completed','completed_with_pendencies']);
                if (!isset($completedByAssembler[$a->id])) $completedByAssembler[$a->id] = ['name'=>$a->name,'count'=>0,'late'=>0,'started'=>0];
                if ($isCompleted) $completedByAssembler[$a->id]['count'] += 1;
                if ($s->scheduled_date && $a->pivot->finished_at && \Carbon\Carbon::parse($a->pivot->finished_at)->gt($s->scheduled_date)) $completedByAssembler[$a->id]['late'] += 1;
                if ($a->pivot->confirmation_status === 'started') $completedByAssembler[$a->id]['started'] += 1;
            }
        }

        $statusDist = [
            'planejada' => 0,
            'em_andamento' => 0,
            'aguardando_cliente' => 0,
            'atrasada' => 0,
            'concluida' => 0,
            'cancelada' => 0,
        ];
        foreach ($schedules as $s) {
            $hasCompleted = $s->assemblers->contains(function($a){ return in_array($a->pivot->confirmation_status,['completed','completed_with_pendencies']); });
            $hasStarted = $s->assemblers->contains(function($a){ return $a->pivot->confirmation_status==='started'; });
            $hasCancelled = $s->assemblers->contains(function($a){ return $a->pivot->confirmation_status==='cancelled'; });
            $hasConfirmed = $s->assemblers->contains(function($a){ return $a->pivot->confirmation_status==='confirmed'; });
            $isLate = $s->scheduled_date && $s->scheduled_date->isPast() && !$hasCompleted && !$hasCancelled;
            if ($hasCompleted) $statusDist['concluida']++;
            elseif ($hasStarted) $statusDist['em_andamento']++;
            elseif ($isLate) $statusDist['atrasada']++;
            elseif ($hasConfirmed) $statusDist['aguardando_cliente']++;
            else $statusDist['planejada']++;
            if ($hasCancelled) $statusDist['cancelada']++;
        }

        $timeline = [];
        $periodStart = $start ? \Carbon\Carbon::parse($start) : now()->startOfMonth();
        $periodEnd = $end ? \Carbon\Carbon::parse($end) : now()->endOfMonth();
        $cursor = $periodStart->copy();
        while ($cursor->lte($periodEnd)) { $timeline[$cursor->toDateString()] = 0; $cursor->addDay(); }
        foreach ($schedules as $s) { $d = optional($s->scheduled_date)->toDateString(); if ($d && isset($timeline[$d])) $timeline[$d]++; }

        $onTime = 0; $delayed = 0; $delayHoursSum = 0; $delayCount = 0;
        foreach ($schedules as $s) {
            foreach ($s->assemblers as $a) {
                if (in_array($a->pivot->confirmation_status,['completed','completed_with_pendencies'])) {
                    $finish = $a->pivot->finished_at ? \Carbon\Carbon::parse($a->pivot->finished_at) : null;
                    $sched = $s->scheduled_date;
                    if ($finish && $sched) {
                        if ($finish->lte($sched)) $onTime++; else { $delayed++; $delayHoursSum += $finish->diffInHours($sched); $delayCount++; }
                    }
                }
            }
        }
        $delayAvg = $delayCount ? round($delayHoursSum / $delayCount, 2) : 0;

        $evals = \App\Models\AssemblyScheduleEvaluation::whereHas('schedule', function($q) use ($start,$end){
            $q->when($start && $end, function($qq) use ($start,$end){ $qq->whereBetween('scheduled_date', [$start,$end]); });
        })->whereNotNull('submitted_at')->latest()->take(10)->get();

        $promoters = $evalQuery->clone()->whereBetween('nps_score',[9,10])->count();
        $passives = $evalQuery->clone()->whereBetween('nps_score',[7,8])->count();
        $detractors = $evalQuery->clone()->whereBetween('nps_score',[0,6])->count();

        $todayList = AssemblySchedule::with('sale')->whereDate('scheduled_date', now()->toDateString())->get();
        $nextDays = AssemblySchedule::with('sale')->whereBetween('scheduled_date', [now()->addDay()->toDateString(), now()->addDays(7)->toDateString()])->get();
        $inProgress = AssemblySchedule::with('assemblers')->whereHas('assemblers', function($q){ $q->where('confirmation_status','started'); })->get();
        $needsReschedule = AssemblySchedule::with('assemblers')->whereDate('scheduled_date','<',now()->toDateString())->whereDoesntHave('assemblers', function($q){ $q->whereIn('confirmation_status',['completed','completed_with_pendencies']); })->get();

        $assemblers = Assembler::all();

        return view('admin.assembly-schedules.dashboard', [
            'filters' => [ 'start'=>$start, 'end'=>$end, 'assembler_id'=>$assemblerId, 'status'=>$statusFilter, 'city'=>$city ],
            'cards' => [
                'totalMonth' => $totalMonth,
                'completed' => $completed,
                'late' => $late,
                'next7' => $next7,
                'completionRate' => $completionRate,
                'npsAvg' => $npsAvg,
            ],
            'npsByAssembler' => $npsByAssembler,
            'completedByAssembler' => $completedByAssembler,
            'statusDist' => $statusDist,
            'timeline' => $timeline,
            'delays' => [ 'onTime'=>$onTime, 'delayed'=>$delayed, 'avgHours'=>$delayAvg ],
            'evals' => $evals,
            'todayList' => $todayList,
            'nextDays' => $nextDays,
            'inProgress' => $inProgress,
            'needsReschedule' => $needsReschedule,
            'assemblers' => $assemblers,
            'promoters' => $promoters,
            'passives' => $passives,
            'detractors' => $detractors,
        ]);
    }

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssemblySchedule $assemblySchedule)
    {
        try {
            $assemblySchedule->delete();
            return response()->json(['success' => true, 'message' => 'Agendamento de montagem excluído com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir agendamento de montagem: ' . $e->getMessage()], 500);
        }
    }
}
