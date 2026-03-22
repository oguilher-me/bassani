<?php

namespace App\Http\Controllers;

use App\Models\Assembler;
use App\Models\User;
use App\Models\Role;
use App\Enums\AssemblerTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class AssemblerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $assemblers = Assembler::where('status', true)->get(['id', 'name']);
            return response()->json($assemblers);
        }
        return view('admin.assemblers.index');
    }

    public function create()
    {
        $types = AssemblerTypeEnum::cases();
        return view('admin.assemblers.create', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:assemblers,cpf',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|string|email|max:255|unique:assemblers,email|unique:users,email',
            'type' => ['required', Rule::enum(AssemblerTypeEnum::class)],
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed', // Adicionando validação de senha
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $role = Role::where('name', 'Montador')->first();
        if (!$role) {
            return redirect()->back()->withErrors(['role' => 'A role "Montador" não foi encontrada.']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
            'status' => true,
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos/assemblers', 'public');
        }

        Assembler::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'cpf' => $request->cpf,
            'phone' => $request->phone,
            'email' => $request->email,
            'type' => $request->type,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'status' => true,
            'photo' => $photoPath,
        ]);

        return redirect()->route('assemblers.index')->with('success', 'Montador criado com sucesso!');
    }

    public function show(Assembler $assembler)
    {
        return view('admin.assemblers.show', compact('assembler'));
    }

    public function edit(Assembler $assembler)
    {
        $types = AssemblerTypeEnum::cases();
        return view('admin.assemblers.edit', compact('assembler', 'types'));
    }

    public function update(Request $request, Assembler $assembler)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => ['required', 'string', 'max:14', Rule::unique('assemblers', 'cpf')->ignore($assembler->id)],
            'phone' => 'nullable|string|max:20',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('assemblers', 'email')->ignore($assembler->id), Rule::unique('users', 'email')->ignore($assembler->user_id)],
            'type' => ['required', Rule::enum(AssemblerTypeEnum::class)],
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'status' => 'boolean',
            'password' => 'nullable|string|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $assembler->update($request->except(['password', 'password_confirmation', 'photo']));

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($assembler->photo) {
                Storage::disk('public')->delete($assembler->photo);
            }
            $photoPath = $request->file('photo')->store('photos/assemblers', 'public');
            $assembler->update(['photo' => $photoPath]);
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $assembler->user->update($userData);

        return redirect()->route('assemblers.index')->with('success', 'Montador atualizado com sucesso!');
    }

    public function destroy(Assembler $assembler)
    {
        $assembler->delete();

        if ($assembler->user) {
            $assembler->user->status = 0;
            $assembler->user->save();
        }

        return response()->json(['success' => 'Montador movido para a lixeira com sucesso!']);
    }

    public function data(Request $request)
    {
        $assemblers = Assembler::select(['id', 'name', 'cpf', 'phone', 'email', 'type', 'status']);

        return DataTables::of($assemblers)
            ->addColumn('actions', function ($assembler) {
                $actions = '';
                $actions .= '<a href="' . route('assemblers.show', $assembler->id) . '" class="btn btn-sm btn-icon item-edit"><i class="bx bx-show"></i></a>';
                $actions .= '<a href="' . route('assemblers.edit', $assembler->id) . '" class="btn btn-sm btn-icon item-edit"><i class="bx bx-edit"></i></a>';
                $actions .= '<form action="' . route('assemblers.destroy', $assembler->id) . '" method="POST" style="display:inline;" class="delete-form">';
                $actions .= '@csrf';
                $actions .= '@method("DELETE")';
                $actions .= '<button type="submit" class="btn btn-sm btn-icon item-delete"><i class="bx bx-trash"></i></button>';
                $actions .= '</form>';
                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getAvailableAssemblers(Request $request)
    {
        $assemblers = Assembler::select(['id', 'name', 'photo'])->where('status', true)->get();

        $formattedAssemblers = $assemblers->map(function ($assembler) {
            return [
                'id' => $assembler->id,
                'text' => $assembler->name,
                'photo' => $assembler->photo,
            ];
        });

        return response()->json(['results' => $formattedAssemblers]);
    }
}
