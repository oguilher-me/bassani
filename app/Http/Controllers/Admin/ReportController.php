<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function customerReports()
    {
        $activeCustomers = Customer::where('status', 'Ativo')->count();
        $inactiveCustomers = Customer::where('status', 'Inativo')->count();

        // Novos clientes por período (ex: últimos 30 dias)
        $newCustomersLast30Days = Customer::where('created_at', '>=', now()->subDays(30))->count();

        return view('admin.reports.customers', compact('activeCustomers', 'inactiveCustomers', 'newCustomersLast30Days'));
    }
}