<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ShipmentDestination;
use App\Models\PlannedShipment;
use App\Models\Driver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ShipmentDestinationEvaluation;

class DriverScheduleController extends Controller
{
    public function mySchedule(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->driver) {
            abort(403, 'Você não está associado a um motorista.');
        }
        $driver = $user->driver;

        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $dailyDestinations = ShipmentDestination::whereHas('plannedShipment', function($q) use ($driver){
                $q->where('driver_id', $driver->id);
            })
            ->whereDate('window_start', $today)
            ->with('plannedShipment.vehicle')
            ->get();

        $weeklyDestinations = ShipmentDestination::whereHas('plannedShipment', function($q) use ($driver){
                $q->where('driver_id', $driver->id);
            })
            ->whereBetween('window_start', [$startOfWeek, $endOfWeek])
            ->with('plannedShipment.vehicle')
            ->orderBy('window_start')
            ->get();

        return view('driver.my-schedule', compact('dailyDestinations', 'weeklyDestinations', 'driver'));
    }

    public function allSchedules(Request $request)
    {
        $query = ShipmentDestination::with(['plannedShipment.driver']);

        if ($request->filled('driver_id')) {
            $query->whereHas('plannedShipment', function($q) use ($request){
                $q->where('driver_id', $request->driver_id);
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('window_start', $request->date);
        }

        if ($request->filled('status')) {
            $query->whereHas('plannedShipment', function($q) use ($request){
                $q->where('status', $request->status);
            });
        }

        $destinations = $query->orderBy('window_start')->get();
        $drivers = Driver::all();

        return view('admin.driver-schedules.all', compact('destinations', 'drivers'));
    }

    public function getCalendarEvents(Request $request)
    {
        $query = ShipmentDestination::with(['plannedShipment.driver']);
        if ($request->filled('driver_id')) {
            $query->whereHas('plannedShipment', function($q) use ($request){
                $q->where('driver_id', $request->driver_id);
            });
        }
        if ($request->filled('date')) {
            $query->whereDate('window_start', $request->date);
        }
        if ($request->filled('status')) {
            $query->whereHas('plannedShipment', function($q) use ($request){
                $q->where('status', $request->status);
            });
        }

        $destinations = $query->orderBy('window_start')->get();
        $events = $destinations->map(function ($d) {
            $title = ($d->contact_name ?: 'Destino') . ' - ' . ($d->address ?: '');
            $start = $d->window_start ? Carbon::parse($d->window_start)->format('Y-m-d\TH:i:s') : null;
            $end = $d->window_end ? Carbon::parse($d->window_end)->format('Y-m-d\TH:i:s') : null;
            return [
                'id' => $d->id,
                'title' => $title,
                'start' => $start,
                'end' => $end,
                'allDay' => false,
                'extendedProps' => [
                    'driver' => optional($d->plannedShipment->driver)->full_name ?? optional($d->plannedShipment->driver)->name,
                    'status' => $d->plannedShipment->status,
                    'shipment_number' => $d->plannedShipment->shipment_number,
                    'remarks' => $d->plannedShipment->remarks,
                ],
            ];
        });
        return response()->json($events);
    }

    public function showDestination(ShipmentDestination $destination)
    {
        $destination->load(['plannedShipment.vehicle','plannedShipment.driver','plannedShipment.sales','items.product','items.sale']);
        $evaluation = \App\Models\ShipmentDestinationEvaluation::where('destination_id', $destination->id)->first();
        return view('driver.destinations.show', compact('destination','evaluation'));
    }

    public function startForm(ShipmentDestination $destination)
    {
        return view('driver.destinations.start', compact('destination'));
    }

    public function startDelivery(Request $request)
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:shipment_destinations,id',
            'start_photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'start_latitude' => 'nullable|numeric',
            'start_longitude' => 'nullable|numeric',
            'start_accuracy' => 'nullable|numeric',
        ]);
        $dest = ShipmentDestination::findOrFail($validated['destination_id']);
        $path = $request->file('start_photo')->store('delivery-start-photos', 'public');
        $dest->confirmation_status = 'started';
        $dest->started_at = now();
        $dest->start_photo_path = $path;
        $dest->start_latitude = $validated['start_latitude'] ?? null;
        $dest->start_longitude = $validated['start_longitude'] ?? null;
        $dest->start_accuracy = $validated['start_accuracy'] ?? null;
        $dest->save();

        $shipment = $dest->plannedShipment;
        if ($shipment && $shipment->status === 'Planned') {
            $shipment->status = 'In Transit';
            $shipment->save();
        }

        return redirect()->route('driver.destinations.show', $dest->id)->with('success', 'Entrega iniciada com sucesso.');
    }

    public function finishForm(ShipmentDestination $destination)
    {
        return view('driver.destinations.finish', compact('destination'));
    }

    public function finishDelivery(Request $request)
    {
        $type = $request->input('complete_type', 'full');
        $rules = [
            'destination_id' => 'required|exists:shipment_destinations,id',
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

        $dest = ShipmentDestination::with('plannedShipment')->findOrFail($request->destination_id);
        $paths = [];
        if ($request->hasFile('finish_photos')) {
            foreach ($request->file('finish_photos') as $file) {
                $paths[] = $file->store('delivery-finish-photos', 'public');
            }
        }
        $dest->confirmation_status = $type === 'full' ? 'completed' : 'completed_with_pendencies';
        $dest->finished_at = now();
        $dest->finish_notes = $request->finish_notes;
        $dest->finish_pending_reason = $type === 'pending' ? $request->pending_reason : null;
        $dest->finish_photo_paths = $paths;
        $dest->save();

        $existingEval = ShipmentDestinationEvaluation::where('destination_id', $dest->id)->first();
        if (!$existingEval) {
            ShipmentDestinationEvaluation::create([
                'destination_id' => $dest->id,
                'token' => Str::random(64),
            ]);
        }

        $pendingCount = ShipmentDestination::where('planned_shipment_id', $dest->planned_shipment_id)
            ->whereNotIn('confirmation_status', ['completed','completed_with_pendencies','cancelled'])
            ->count();
        if ($pendingCount === 0 && $dest->plannedShipment) {
            $shipment = $dest->plannedShipment;
            $shipment->status = 'Delivered';
            $shipment->actual_delivery_date = now();
            $shipment->save();
        }

        return redirect()->route('driver.destinations.show', $dest->id)->with('success', 'Entrega finalizada com sucesso.');
    }
}

