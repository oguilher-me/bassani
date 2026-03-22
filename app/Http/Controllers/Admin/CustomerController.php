<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.customers.index');
    }

    public function data(Request $request)
    {
        $query = Customer::select(['id', 'customer_type', 'full_name', 'company_name', 'cpf', 'cnpj', 'email', 'phone', 'status', 'customer_type']);

        // Apply search if provided by DataTables
        if ($request->has('search') && !empty($request->input('search.value'))) {
            $searchValue = $request->input('search.value');
            $query->where(function($q) use ($searchValue) {
                $q->where('full_name', 'like', "%{$searchValue}%")
                  ->orWhere('company_name', 'like', "%{$searchValue}%")
                  ->orWhere('cpf', 'like', "%{$searchValue}%")
                  ->orWhere('cnpj', 'like', "%{$searchValue}%")
                  ->orWhere('email', 'like', "%{$searchValue}%")
                  ->orWhere('phone', 'like', "%{$searchValue}%");
            });
        }

        // Apply ordering if provided by DataTables
        if ($request->has('order')) {
            $orderColumnIndex = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');
            $columns = $request->input('columns');
            $orderColumnName = $columns[$orderColumnIndex]['data'];

            // Map 'full_name' and 'document' to actual database columns
            if ($orderColumnName === 'fullName') {
                $query->orderByRaw("CASE WHEN customer_type = 'PF' THEN full_name ELSE company_name END {$orderDirection}");
            } elseif ($orderColumnName === 'document') {
                $query->orderByRaw("CASE WHEN customer_type = 'PF' THEN cpf ELSE cnpj END {$orderDirection}");
            } else {
                $query->orderBy($orderColumnName, $orderDirection);
            }
        }

        $totalRecords = $query->count();

        $customers = $query->offset($request->input('start'))
                           ->limit($request->input('length'))
                           ->get();

        $data = $customers->map(function ($customer) {
            
            $fullName = $customer->customer_type == 'PF' ? $customer->full_name : $customer->company_name;
            $document = $customer->customer_type == 'PF' ? $customer->cpf : $customer->cnpj;

            // Format document (CPF/CNPJ)
            if ($customer->customer_type == 'PF') {
                $document = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $document);
            } else {
                $document = preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $document);
            }

            // Format phone number
            $phone = preg_replace('/^(\\d{2})(\\d{4,5})(\\d{4})$/', '($1) $2-$3', $customer->phone);

            if ($customer->status == 'Ativo') {
                $statusBadge = '<span class="badge bg-success">Ativo</span>';
            } else {
                $statusBadge = '<span class="badge bg-danger">Inativo</span>';
            }

            $actions = '<a href="' . route('customers.show', $customer->id) . '" class="btn btn-icon item-show"><i class="icon-base bx bx-show icon-sm text-primary"></i></a>'
                     . '<a href="' . route('customers.edit', $customer->id) . '" class="btn btn-icon item-edit"><i class="icon-base bx bx-edit icon-sm text-warning"></i></a>'
                     . '<form action="' . route('customers.destroy', $customer->id) . '" method="POST" class="d-inline delete-form">'
                     . '<input type="hidden" name="_token" value="' . csrf_token() . '">'
                     . '<input type="hidden" name="_method" value="DELETE">'
                     . '<button type="submit" class="btn btn-icon item-delete"><i class="icon-base bx bx-trash icon-sm text-danger"></i></button>'
                     . '</form>';

            return [
                'id' => $customer->id,
                'customer_type' => $customer->customer_type,
                'fullName' => $fullName,
                'document' => $document,
                'email' => $customer->email,
                'phone' => $phone,
                'status' => $statusBadge,
                'actions' => $actions,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => intval($totalRecords),
            'recordsFiltered' => intval($totalRecords), // For now, no additional filtering beyond search
            'data' => $data,
        ]);
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_type' => 'required|in:PF,PJ',
            'full_name' => 'required_if:customer_type,PF|string|max:255',
            'company_name' => 'required_if:customer_type,PJ|string|max:255',
            'cpf' => [
                'required_if:customer_type,PF',
                'string',
                'max:14',
                'unique:customers',
                'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/' // Basic CPF format validation
            ],
            'cnpj' => [
                'required_if:customer_type,PJ',
                'string',
                'max:18',
                'unique:customers',
                'regex:/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/' // Basic CNPJ format validation
            ],
            'rg' => 'nullable|string|max:20',
            'ie' => 'nullable|string|max:20',
            'representative_name' => 'required_if:customer_type,PJ|string|max:255',
            'email' => 'required|email|unique:customers',
            'phone' => 'required|string|max:20',
            'address_street' => 'required|string|max:255',
            'address_number' => 'required|string|max:10',
            'address_neighborhood' => 'required|string|max:255',
            'address_city' => 'required|string|max:255',
            'address_state' => 'required|string|max:2',
            'address_zip_code' => 'required|string|max:9',
            'status' => 'required|in:Ativo,Inativo',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Cliente criado com sucesso!');
    }

    public function show(Customer $customer)
    {
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        try {
            $request->validate([
                'customer_type' => 'required|in:PF,PJ',
                'full_name' => 'required_if:customer_type,PF|string|max:255',
                'company_name' => ['nullable', 'required_if:customer_type,PJ', 'string', 'max:255'],
                'cpf' => [
                    'nullable',
                    'required_if:customer_type,PF',
                    'string',
                    'max:14',
                    'unique:customers,cpf,' . $customer->id,
                    'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/' // Basic CPF format validation
                ],
                'cnpj' => [
                    'nullable',
                    'required_if:customer_type,PJ',
                    'string',
                    'max:18',
                    'unique:customers,cnpj,' . $customer->id,
                    'regex:/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/' // Basic CNPJ format validation
                ],
                'rg' => 'nullable|string|max:20',
                'ie' => 'nullable|string|max:20',
                'representative_name' => ['nullable', 'required_if:customer_type,PJ', 'string', 'max:255'],
                'email' => 'required|email|unique:customers,email,' . $customer->id,
                'phone' => 'required|string|max:20',
                'address_street' => 'required|string|max:255',
                'address_number' => 'required|string|max:10',
                'address_neighborhood' => 'required|string|max:255',
                'address_city' => 'required|string|max:255',
                'address_state' => 'required|string|max:2',
                'address_zip_code' => 'required|string|max:9',
                'status' => 'required|in:Ativo,Inativo',
            ]);

            $customer->update($request->all());
            return redirect()->route('customers.index')->with('success', 'Cliente atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    public function destroy(Customer $customer)
    {
        $customer->update(['status' => 'Inativo']);
        return redirect()->route('customers.index')->with('success', 'Cliente inativado com sucesso!');
    }
}
