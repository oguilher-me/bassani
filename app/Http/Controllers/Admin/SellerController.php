<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SellerController extends Controller
{
    /**
     * Display a listing of the sellers.
     */
    public function index()
    {
        $this->authorizeAdmin();

        $sellers = Seller::with('user')->withCount(['opportunities', 'leads'])->get();
        return view('admin.sellers.index', compact('sellers'));
    }

    /**
     * Show the form for creating a new seller.
     */
    public function create()
    {
        $this->authorizeAdmin();
        return view('admin.sellers.form', ['seller' => new Seller(), 'isEdit' => false]);
    }

    /**
     * Store a newly created seller in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:sellers,email',
            'cpf' => 'required|string|unique:sellers,cpf',
            'phone' => 'nullable|string',
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'photo' => 'nullable|image|max:2048',
            'password' => 'required|min:8|confirmed'
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // 1. Create User
                $roleComercial = Role::where('name', 'Comercial')->first();
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role_id' => $roleComercial ? $roleComercial->id : null,
                    'status' => 1
                ]);

                // 2. Handle Photo
                $photoPath = null;
                if ($request->hasFile('photo')) {
                    $photoPath = $request->file('photo')->store('sellers/photos', 'public');
                }

                // 3. Create Seller
                $seller = Seller::create([
                    'name' => $request->name,
                    'cpf' => $request->cpf,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'user_id' => $user->id,
                    'commission_percentage' => $request->commission_percentage,
                    'photo' => $photoPath,
                    'status' => 'active'
                ]);

                return redirect()->route('crm.sellers.index')->with('success', 'Vendedor criado com sucesso!');
            });
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'Erro ao criar vendedor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified seller.
     */
    public function show(Seller $seller)
    {
        $this->authorizeSellerAccess($seller);

        $seller->load(['opportunities' => function($q) {
            $q->where('status', 'open')->latest()->take(10);
        }, 'user']);

        return view('admin.sellers.show', compact('seller'));
    }

    /**
     * Show the form for editing the specified seller.
     */
    public function edit(Seller $seller)
    {
        $this->authorizeAdmin();
        return view('admin.sellers.form', ['seller' => $seller, 'isEdit' => true]);
    }

    /**
     * Update the specified seller in storage.
     */
    public function update(Request $request, Seller $seller)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $seller->user_id . '|unique:sellers,email,' . $seller->id,
            'cpf' => 'required|string|unique:sellers,cpf,' . $seller->id,
            'phone' => 'nullable|string',
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'photo' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            DB::transaction(function () use ($request, $seller) {
                // Update User
                $seller->user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);

                if ($request->filled('password')) {
                    $seller->user->update(['password' => Hash::make($request->password)]);
                }

                // Handle Photo
                $data = $request->only(['name', 'cpf', 'email', 'phone', 'commission_percentage', 'status']);
                if ($request->hasFile('photo')) {
                    if ($seller->photo) Storage::disk('public')->delete($seller->photo);
                    $data['photo'] = $request->file('photo')->store('sellers/photos', 'public');
                }

                $seller->update($data);
            });

            return redirect()->route('crm.sellers.index')->with('success', 'Vendedor atualizado com sucesso!');
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'Erro ao atualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified seller from storage.
     */
    public function destroy(Seller $seller)
    {
        $this->authorizeAdmin();

        try {
            $seller->delete(); // Soft delete
            return redirect()->route('crm.sellers.index')->with('success', 'Vendedor desativado com sucesso!');
        } catch (Throwable $e) {
            return back()->with('error', 'Erro ao excluir: ' . $e->getMessage());
        }
    }

    /**
     * Authorization helper (Admin/Master only actions)
     */
    private function authorizeAdmin()
    {
        $user = auth()->user();
        if (!$user->hasRole('admin') && !$user->hasRole('master')) {
            abort(403, 'Acesso restrito a administradores.');
        }
    }

    /**
     * Authorization helper (Seller, Admin or Master)
     */
    private function authorizeSellerAccess(Seller $seller)
    {
        $user = auth()->user();
        if (!$user->hasRole('admin') && !$user->hasRole('master') && $user->id !== $seller->user_id) {
            abort(403, 'Você não tem permissão para visualizar este perfil.');
        }
    }
}
