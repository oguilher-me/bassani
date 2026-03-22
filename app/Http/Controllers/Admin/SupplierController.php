<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::all();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'document_number' => 'required|string|max:255|unique:suppliers',
            'supplier_type' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'address_number' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
            'services_offered' => 'nullable|string',
            'status' => 'required|string|in:Ativo,Inativo',
            // 'documents' => 'nullable|array', // Será tratado separadamente para upload
        ]);

        Supplier::create($validatedData);

        return redirect()->route('suppliers.index')->with('success', 'Fornecedor criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return view('admin.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'document_number' => 'required|string|max:255|unique:suppliers,document_number,' . $supplier->id,
            'supplier_type' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'address_number' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
            'services_offered' => 'nullable|string',
            'status' => 'required|string|in:Ativo,Inativo',
            // 'documents' => 'nullable|array', // Será tratado separadamente para upload
        ]);

        $supplier->update($validatedData);

        return redirect()->route('suppliers.index')->with('success', 'Fornecedor atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Fornecedor excluído com sucesso!');
    }
}
