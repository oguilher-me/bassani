<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class DriverController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = Driver::all();
        $expiredCnhDrivers = $drivers->filter(function ($driver) {
            return $driver->status == 'Ativo' && Carbon::parse($driver->cnh_expiration_date)->isPast();
        });
        return view('admin.drivers.index', compact('expiredCnhDrivers'));
    }

    /**
     * Return data for DataTable.
     */
    public function data()
    {
        $drivers = Driver::select(['id', 'full_name', 'cnh_number', 'cnh_expiration_date', 'status']);

        return DataTables::of($drivers)
            ->editColumn('cnh_expiration_date', function ($driver) {
                return \Carbon\Carbon::parse($driver->cnh_expiration_date)->format('d/m/Y');
            })
            ->addColumn('actions', function ($driver) {
                return '<a href="'.route('drivers.show', $driver->id).'" class="btn btn-icon item-show"><i class="icon-base bx bx-show icon-sm text-info"></i></a>
                        <a href="'.route('drivers.edit', $driver->id).'" class="btn btn-icon item-edit"><i class="icon-base bx bx-edit icon-sm text-warning"></i></a>
                        <form action="'.route('drivers.destroy', $driver->id).'" method="POST" class="d-inline delete-form">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-icon item-delete"><i class="icon-base bx bx-trash icon-sm text-danger"></i></button>
                        </form>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.drivers.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'cpf' => 'required|string|max:14|unique:drivers,cpf',
            'cnh_number' => 'required|string|max:11|unique:drivers,cnh_number',
            'cnh_category' => 'required|string|max:5',
            'cnh_expiration_date' => 'required|date',
            'phone' => 'required|string|max:20',
            'status' => 'required|in:Ativo,Inativo,Suspenso',
        ]);

        $driverRole = Role::where('name', 'Motorista')->first();

        $user = User::create([
            'name' => $validatedData['full_name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role_id' => $driverRole->id, // Atribuir o role_id do motorista
        ]);

        $driver = new Driver($validatedData);
        $driver->user_id = $user->id;
        $driver->save();

        return redirect()->route('drivers.index')->with('success', 'Motorista criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $driver = Driver::findOrFail($id);
        return view('admin.drivers.show', compact('driver'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        return view('admin.drivers.edit', compact('driver'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Driver $driver)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $driver->user_id,
            'cpf' => 'required|string|max:14|unique:drivers,cpf,' . $driver->id,
            'cnh_number' => 'required|string|max:11|unique:drivers,cnh_number,' . $driver->id,
            'cnh_category' => 'required|string|max:5',
            'cnh_expiration_date' => 'required|date',
            'phone' => 'required|string|max:20',
            'status' => 'required|in:Ativo,Inativo,Suspenso',
        ]);

        $user = $driver->user;
        $user->update([
            'name' => $validatedData['full_name'],
            'email' => $validatedData['email'],
        ]);

        $driver->update($validatedData);

        return redirect()->route('drivers.index')->with('success', 'Motorista atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        $user = $driver->user;
        $driver->delete();
        if ($user) {
            $user->delete();
        }
        return redirect()->route('drivers.index')->with('success', 'Motorista excluído com sucesso!');
    }
}
