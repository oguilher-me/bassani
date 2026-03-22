<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Enums\ResponsibleForPayment;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\VehicleFine;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class VehicleFineController extends Controller
{
    public function index()
    {
        $fines = VehicleFine::with(['vehicle', 'driver'])->get();
        return view('admin.vehicle_fines.index', compact('fines'));
    }

    public function create()
    {
        $vehicles = Vehicle::all();
        $drivers = Driver::all();
        $paymentStatuses = PaymentStatus::cases();
        $responsibleForPayments = ResponsibleForPayment::cases();
        return view('admin.vehicle_fines.create', compact('vehicles', 'drivers', 'paymentStatuses', 'responsibleForPayments'));
    }

    public function store(Request $request)
    {
        $rules = [
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'fine_number' => 'required|string|max:255|unique:vehicle_fines,fine_number',
            'infraction_date' => 'required|date',
            'notification_date' => 'nullable|date',
            'due_date' => 'required|date|after_or_equal:infraction_date',
            'payment_date' => 'nullable|date|after_or_equal:infraction_date',
            'fine_type' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'authority' => 'required|string|max:255',
            'points' => 'required|integer|min:0',
            'fine_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_status' => ['required', new Enum(PaymentStatus::class)],
            'responsible_for_payment' => ['required', new Enum(ResponsibleForPayment::class)],
            'document_reference' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048', // Alterado para aceitar arquivo
            'comments' => 'nullable|string',
        ];

        if ($request->input('payment_status') === PaymentStatus::Contested->value) {
            $rules['document_reference'] = 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048';
        }

        $validatedData = $request->validate($rules);


        if ($request->hasFile('document_reference')) {
            $filePath = $request->file('document_reference')->store('document_references', 'public');
            // dd(file_exists(storage_path('app/' . $filePath))); // Debug: Verificar se o arquivo existe no caminho absoluto
            $validatedData['document_reference'] = str_replace('public/', '', $filePath);
        }

        if ($validatedData['payment_status'] === PaymentStatus::Paid->value && empty($validatedData['payment_date'])) {
            $validatedData['payment_date'] = now();
        }

        VehicleFine::create($validatedData);

        return redirect()->route('vehicle_fines.index')->with('success', 'Multa de veículo criada com sucesso!');
    }

    public function show(VehicleFine $vehicleFine)
    {
        return view('admin.vehicle_fines.show', compact('vehicleFine'));
    }

    public function edit(VehicleFine $vehicleFine)
    {
        $vehicles = Vehicle::all();
        $drivers = Driver::all();
        $paymentStatuses = PaymentStatus::cases();
        $responsibleForPayments = ResponsibleForPayment::cases();
        return view('admin.vehicle_fines.edit', compact('vehicleFine', 'vehicles', 'drivers', 'paymentStatuses', 'responsibleForPayments'));
    }

    public function update(Request $request, string $id)
    {
        $rules = [
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'fine_number' => ['required', 'string', 'max:255', Rule::unique('vehicle_fines')->ignore($id)],
            'infraction_date' => 'required|date',
            'notification_date' => 'nullable|date',
            'due_date' => 'required|date|after_or_equal:infraction_date',
            'payment_date' => 'nullable|date|after_or_equal:infraction_date',
            'fine_type' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'authority' => 'required|string|max:255',
            'points' => 'required|integer|min:0',
            'fine_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_status' => ['required', new Enum(PaymentStatus::class)],
            'responsible_for_payment' => ['required', new Enum(ResponsibleForPayment::class)],
            'document_reference' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048', // Alterado para aceitar arquivo
            'comments' => 'nullable|string',
        ];

        if ($request->input('payment_status') === PaymentStatus::Contested->value) {
            $rules['document_reference'] = 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048';
        }

        $validatedData = $request->validate($rules);
         // Debug: Verificar os dados validados


        $vehicleFine = VehicleFine::findOrFail($id);

        if ($request->hasFile('document_reference')) {
            // Excluir o arquivo antigo se existir
            if ($vehicleFine->document_reference) {
                Storage::delete(str_replace('/storage', 'public', $vehicleFine->document_reference));
            }
            $filePath = $request->file('document_reference')->store('document_references', 'public');
            // dd(file_exists(storage_path('app/' . $filePath))); // Debug: Verificar se o arquivo existe no caminho absoluto
            $validatedData['document_reference'] = str_replace('public/', '', $filePath);
        } else {
            // Manter o arquivo existente se nenhum novo arquivo for enviado e o campo não for nulo
            $validatedData['document_reference'] = $vehicleFine->document_reference;
        }

        if ($vehicleFine->payment_status === PaymentStatus::Paid && $request->input('payment_status') !== PaymentStatus::Paid->value) {
            return redirect()->back()->withErrors(['payment_status' => 'Não é possível alterar o status de uma multa já paga.']);
        }

        if ($validatedData['payment_status'] === PaymentStatus::Paid->value && empty($validatedData['payment_date'])) {
            $validatedData['payment_date'] = now();
        }

        $vehicleFine->update($validatedData);

        return redirect()->route('vehicle_fines.index')->with('success', 'Multa de veículo atualizada com sucesso!');
    }

    public function destroy(string $id)
    {
        $vehicleFine = VehicleFine::findOrFail($id);

        if ($vehicleFine->payment_status === PaymentStatus::Paid) {
            return redirect()->back()->with('error', 'Não é possível excluir uma multa que já foi paga.');
        }

        $vehicleFine->delete();

        return redirect()->route('vehicle_fines.index')->with('success', 'Multa de veículo excluída com sucesso!');
    }

    public function data()
    {
        $vehicleFines = VehicleFine::with(['vehicle', 'driver'])->select('vehicle_fines.*');

        return Datatables::of($vehicleFines)
            ->addColumn('fine_number', function ($vehicleFine) {
                return $vehicleFine->fine_number;
            })
            ->addColumn('infraction_date', function ($vehicleFine) {
                return $vehicleFine->infraction_date->format('d/m/Y');
            })
            ->addColumn('vehicle_info', function ($vehicleFine) {
                $modelo = $vehicleFine->vehicle->modelo ?? 'N/A';
                $placa = $vehicleFine->vehicle->placa ?? 'N/A';
                return $modelo . ' (' . $placa . ')';
            })
            ->addColumn('driver_name', function ($vehicleFine) {
                return $vehicleFine->driver->full_name ?? 'N/A';
            })
            ->addColumn('fine_amount', function ($vehicleFine) {
                return 'R$ ' . number_format($vehicleFine->fine_amount, 2, ',', '.');
            })
            ->addColumn('payment_status', function ($vehicleFine) {
                $status = $vehicleFine->payment_status;
                $label = $status->getLabel();
                $badgeClass = match ($status) {
                    PaymentStatus::Pending => 'bg-label-warning',
                    PaymentStatus::Paid => 'bg-label-success',
                    PaymentStatus::Contested => 'bg-label-info',
                    PaymentStatus::Cancelled => 'bg-label-danger',
                };
                return '<span class="badge ' . $badgeClass . '">' . $label . '</span>';
            })
            ->addColumn('responsible_for_payment', function ($vehicleFine) {
                return $vehicleFine->responsible_for_payment->getLabel();
            })
            ->addColumn('actions', function ($vehicleFine) {
                $showUrl = route('vehicle_fines.show', $vehicleFine->id);
                $editUrl = route('vehicle_fines.edit', $vehicleFine->id);
                $deleteUrl = route('vehicle_fines.destroy', $vehicleFine->id);

                return '
                    <div class="d-inline-flex">
                        <a href="' . $showUrl . '" class="btn btn-icon btn-outline-secondary btn-sm me-1"><i class="bx bx-show"></i></a>
                        <a href="' . $editUrl . '" class="btn btn-icon btn-outline-primary btn-sm me-1"><i class="bx bx-edit"></i></a>
                        <form action="' . $deleteUrl . '" method="POST" class="delete-form">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-icon btn-outline-danger btn-sm"><i class="bx bx-trash"></i></button>
                        </form>
                    </div>';
            })
            ->rawColumns(['actions', 'payment_status'])
            ->make(true);
    }
}
