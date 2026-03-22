<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\AssemblyScheduleEvaluation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomersDashboardController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $city = $request->input('city');
        $type = $request->input('customer_type');

        $customersQuery = Customer::query()
            ->when($city, fn($q) => $q->where('address_city',$city))
            ->when($type, fn($q) => $q->where('customer_type',$type));
        $customers = $customersQuery->get();

        $totalCustomers = Customer::count();
        $activeCustomers = Customer::whereIn('status', ['Ativo','active'])->count();
        $inactiveCustomers = Customer::whereIn('status', ['Inativo','inactive'])->count();
        $newCustomersMonth = Customer::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();

        $recentCustomerIds = Sale::where('issue_date','>=', now()->subYear())->distinct()->pluck('customer_id');
        $retentionRate = $totalCustomers ? round(($recentCustomerIds->count() / $totalCustomers) * 100, 2) : 0;
        $churnRate = $totalCustomers ? round((($inactiveCustomers) / $totalCustomers) * 100, 2) : 0;

        $periodSales = Sale::when($start && $end, fn($q) => $q->whereBetween('issue_date', [$start,$end]))->get();
        $ticketMedio = $periodSales->count() ? round($periodSales->avg('grand_total'), 2) : 0;

        $evalQuery = AssemblyScheduleEvaluation::whereNotNull('submitted_at')
            ->when($start && $end, function($q) use ($start,$end){
                $q->whereHas('schedule', function($qq) use ($start,$end){
                    $qq->whereBetween('scheduled_date', [$start,$end]);
                });
            });
        $npsGeral = round($evalQuery->avg('nps_score'), 2);
        $npsMontagem = $npsGeral;
        $complaintsCount = $evalQuery->clone()->where('nps_score','<=',6)->whereNotNull('comments')->count();

        $geoDistribution = Customer::select('address_city', DB::raw('count(*) as total'))
            ->groupBy('address_city')->orderByDesc('total')->limit(10)->pluck('total','address_city')->toArray();
        $typeDistribution = Customer::select('customer_type', DB::raw('count(*) as total'))
            ->groupBy('customer_type')->pluck('total','customer_type')->toArray();

        $spendByCustomer = Sale::select('customer_id', DB::raw('SUM(grand_total) as total'))
            ->groupBy('customer_id')->get();
        $spendBuckets = ['0-2000'=>0,'2000-5000'=>0,'5000-10000'=>0,'10000-20000'=>0,'20000+'=>0];
        foreach ($spendByCustomer as $row) {
            $t = (float)$row->total;
            if ($t < 2000) $spendBuckets['0-2000']++;
            elseif ($t < 5000) $spendBuckets['2000-5000']++;
            elseif ($t < 10000) $spendBuckets['5000-10000']++;
            elseif ($t < 20000) $spendBuckets['10000-20000']++;
            else $spendBuckets['20000+']++;
        }

        $topCustomers = Sale::select('customer_id', DB::raw('SUM(grand_total) as total'))
            ->groupBy('customer_id')->orderByDesc('total')->limit(10)->get();
        $topCustomersData = $topCustomers->map(function($r){
            $c = Customer::find($r->customer_id);
            $name = $c ? ($c->full_name ?? $c->company_name ?? ('Cliente '.$c->id)) : ('ID '.$r->customer_id);
            return ['name'=>$name, 'total'=>round($r->total,2)];
        })->toArray();

        $salesDivision = Sale::select('sales_division', DB::raw('COUNT(*) as total'))
            ->groupBy('sales_division')->pluck('total','sales_division')->toArray();

        $monthlyRevenue = [];
        for ($i=11; $i>=0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $key = $m->format('M/Y');
            $monthlyRevenue[$key] = Sale::whereYear('issue_date',$m->year)->whereMonth('issue_date',$m->month)->sum('grand_total');
        }

        $promoters = $evalQuery->clone()->whereBetween('nps_score',[9,10])->count();
        $passives = $evalQuery->clone()->whereBetween('nps_score',[7,8])->count();
        $detractors = $evalQuery->clone()->whereBetween('nps_score',[0,6])->count();
        $evals = $evalQuery->clone()->latest()->take(10)->get();

        return view('admin.customers.dashboard', [
            'filters' => compact('start','end','city','type'),
            'cards' => [
                'totalCustomers' => $totalCustomers,
                'activeCustomers' => $activeCustomers,
                'inactiveCustomers' => $inactiveCustomers,
                'newCustomersMonth' => $newCustomersMonth,
                'retentionRate' => $retentionRate,
                'churnRate' => $churnRate,
                'ticketMedio' => $ticketMedio,
                'npsGeral' => $npsGeral,
                'npsMontagem' => $npsMontagem,
                'complaintsCount' => $complaintsCount,
            ],
            'geoDistribution' => $geoDistribution,
            'typeDistribution' => $typeDistribution,
            'spendBuckets' => $spendBuckets,
            'topCustomers' => $topCustomersData,
            'monthlyRevenue' => $monthlyRevenue,
            'salesDivision' => $salesDivision,
            'promoters' => $promoters,
            'passives' => $passives,
            'detractors' => $detractors,
            'evals' => $evals,
            'customers' => $customers,
        ]);
    }
}

