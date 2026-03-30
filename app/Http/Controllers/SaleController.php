<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Exports\SalesExport;
use App\Models\Sale;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderStatuses = collect(\App\Enums\OrderStatusEnum::cases())
            ->filter(fn ($status) => ! in_array($status, [\App\Enums\OrderStatusEnum::Completed, \App\Enums\OrderStatusEnum::Cancelled]))
            ->values();

        return view('sales.index', compact('orderStatuses'));
    }

    public function data()
    {
        try {
            $sales = Sale::with('customer')->select(['sales.*', DB::raw('CASE WHEN customers.customer_type = "PF" THEN customers.full_name ELSE customers.company_name END AS customer_name')])
                ->leftJoin('customers', 'sales.customer_id', '=', 'customers.id');

            return DataTables::of($sales)
                ->addColumn('sale_number', function ($sale) {
                    return $sale->erp_code; // Ou o campo que representa o número da venda
                })
                ->addColumn('sale_date', function ($sale) {
                    return $sale->issue_date ? $sale->issue_date->format('d/m/Y') : '';
                })
                ->addColumn('expected_delivery_date', function ($sale) {
                    return $sale->expected_delivery_date ? $sale->expected_delivery_date->format('d/m/Y') : '';
                })
                ->addColumn('status', function ($sale) {
                    $status = $sale->order_status;
                    $badgeClass = match ($status) {
                        OrderStatusEnum::Open => 'bg-label-info',
                        OrderStatusEnum::InProduction => 'bg-label-primary',
                        OrderStatusEnum::InTransit => 'bg-label-secondary',
                        OrderStatusEnum::Cancelled => 'bg-label-danger',
                        OrderStatusEnum::Delivered => 'bg-label-warning',
                        OrderStatusEnum::InAssembly => 'bg-label-dark',
                        OrderStatusEnum::Completed => 'bg-label-success',
                        default => 'bg-label-secondary', // Fallback para status não reconhecidos
                    };

                    return '<span class="badge '.$badgeClass.'">'.ucfirst($status->label()).'</span>';
                })
                ->addColumn('actions', function ($sale) {
                    $actions = '<a href="'.route('sales.show', $sale->id).'" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect" title="Ver Detalhes"><i class="bx bx-show"></i></a>';
                    $actions .= '<a href="'.route('sales.edit', $sale->id).'" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect" title="Editar"><i class="bx bx-edit"></i></a>';
                    $actions .= '<form action="'.route('sales.destroy', $sale->id).'" method="POST" class="d-inline delete-form">';
                    $actions .= csrf_field();
                    $actions .= method_field('DELETE');
                    $actions .= '<button type="submit" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect" title="Inativar"><i class="bx bx-trash"></i></button>';
                    $actions .= '</form>';

                    return $actions;
                })
                ->rawColumns(['status', 'actions'])
                ->orderColumn('sale_number', 'erp_code $1')
                ->orderColumn('customer_name', 'customer_name $1')
                ->orderColumn('sale_date', 'issue_date $1')
                ->orderColumn('expected_delivery_date', 'expected_delivery_date $1')
                ->orderColumn('status', 'order_status $1')
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Erro no método data() do SaleController: '.$e->getMessage());

            return response()->json(['error' => 'Ocorreu um erro ao carregar os dados das vendas.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = \App\Models\Customer::all();
        $representatives = \App\Models\Representative::all();
        $carriers = \App\Models\Carrier::all();
        $paymentTerms = \App\Models\PaymentTerm::all();
        $salesDivisions = \App\Enums\SalesDivisionEnum::cases();
        $orderStatuses = \App\Enums\OrderStatusEnum::cases();
        $deliveryStatuses = \App\Enums\DeliveryStatusEnum::cases();
        $shippingMethods = \App\Enums\ShippingMethodEnum::cases();
        $paymentStatuses = \App\Enums\PaymentStatusEnum::cases();
        $paymentMethods = \App\Enums\PaymentMethodEnum::cases();

        return view('sales.create', compact(
            'customers',
            'representatives',
            'carriers',
            'paymentTerms',
            'salesDivisions',
            'orderStatuses',
            'deliveryStatuses',
            'shippingMethods',
            'paymentStatuses',
            'paymentMethods'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'issue_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:issue_date',
            'actual_delivery_date' => 'nullable|date|after_or_equal:issue_date|after_or_equal:expected_delivery_date',
            'sales_responsible' => 'nullable|string|max:255',
            'representative_id' => 'nullable|exists:representatives,id',
            'sales_division' => 'required|in:'.implode(',', array_column(\App\Enums\SalesDivisionEnum::cases(), 'value')),
            'carrier_id' => 'nullable|exists:carriers,id',
            'payment_term_id' => 'nullable|exists:payment_terms,id',
            'currency' => 'required|string|max:3',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'payment_method' => 'required|in:'.implode(',', array_column(\App\Enums\PaymentMethodEnum::cases(), 'value')),
            'purchase_order' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'erp_code' => 'nullable|string|max:255',
            'total_items' => 'nullable|numeric|min:0',
            'total_discounts' => 'nullable|numeric|min:0',
            'total_freight' => 'required|numeric|min:0',
            'total_ipi' => 'nullable|numeric|min:0',
            'total_icms' => 'required|numeric|min:0',
            'total_icms_st' => 'required|numeric|min:0',
            'total_difal' => 'required|numeric|min:0',
            'grand_total' => 'nullable|numeric|min:0',
            'gross_weight' => 'nullable|numeric|min:0',
            'net_weight' => 'nullable|numeric|min:0',
            'cubic_volume' => 'nullable|numeric|min:0',
            'packages' => 'nullable|integer|min:0',
            'order_status' => 'required|in:'.implode(',', array_column(\App\Enums\OrderStatusEnum::cases(), 'value')),
            'delivery_status' => 'required|in:'.implode(',', array_column(\App\Enums\DeliveryStatusEnum::cases(), 'value')),
            'shipping_method' => 'required|in:'.implode(',', array_column(\App\Enums\ShippingMethodEnum::cases(), 'value')),
            'tracking_code' => 'nullable|string|max:255',
            'payment_method' => 'required|in:'.implode(',', array_column(\App\Enums\PaymentMethodEnum::cases(), 'value')),
            'sale_items' => 'required|array|min:1',
            'sale_items.*.product_id' => 'required|exists:products,id',
            'sale_items.*.description' => 'nullable|string|max:255',
            'sale_items.*.ipi' => 'required|numeric|min:0',
            'sale_items.*.quantity' => 'required|integer|min:1',
            'sale_items.*.unit_price' => 'required|numeric|min:0',
            'sale_items.*.item_discount' => 'nullable|numeric|min:0',
        ]);

        $totalItems = 0;
        $totalDiscounts = 0;
        $totalIpi = 0;

        foreach ($validatedData['sale_items'] as $item) {
            $subtotal = ($item['quantity'] * $item['unit_price']) - ($item['item_discount'] ?? 0);
            $totalItems += $subtotal;
            $totalDiscounts += ($item['item_discount'] ?? 0);
            $totalIpi += ($item['ipi'] * $item['quantity']);
        }

        $validatedData['total_items'] = $totalItems;
        $validatedData['total_discounts'] = $totalDiscounts;
        $validatedData['total_ipi'] = $totalIpi;

        $validatedData['grand_total'] = $totalItems
                                        - $totalDiscounts
                                        + $validatedData['total_freight']
                                        + $totalIpi
                                        + $validatedData['total_icms']
                                        + $validatedData['total_icms_st']
                                        + $validatedData['total_difal'];

        $sale = Sale::create($validatedData);

        foreach ($validatedData['sale_items'] as $item) {
            $sale->saleItems()->create($item);
        }

        return redirect()->route('sales.show', $sale->id)
            ->with('success', 'Venda criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sale = Sale::findOrFail($id);

        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sale = Sale::findOrFail($id);
        $customers = \App\Models\Customer::all();
        $representatives = \App\Models\Representative::all();
        $carriers = \App\Models\Carrier::all();
        $paymentTerms = \App\Models\PaymentTerm::all();
        $salesDivisions = \App\Enums\SalesDivisionEnum::cases();
        $orderStatuses = \App\Enums\OrderStatusEnum::cases();
        $deliveryStatuses = \App\Enums\DeliveryStatusEnum::cases();
        $shippingMethods = \App\Enums\ShippingMethodEnum::cases();
        $paymentStatuses = \App\Enums\PaymentStatusEnum::cases();
        $paymentMethods = \App\Enums\PaymentMethodEnum::cases();
        $products = \App\Models\Product::all();

        return view('sales.edit', compact(
            'sale',
            'customers',
            'representatives',
            'carriers',
            'paymentTerms',
            'salesDivisions',
            'orderStatuses',
            'deliveryStatuses',
            'shippingMethods',
            'paymentStatuses',
            'paymentMethods',
            'products'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sale = Sale::findOrFail($id);

        $rules = [
            'customer_id' => 'required|exists:customers,id',
            'issue_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:issue_date',
            'actual_delivery_date' => 'nullable|date|after_or_equal:issue_date|after_or_equal:expected_delivery_date',
            'sales_responsible' => 'nullable|string|max:255',
            'representative_id' => 'nullable|exists:representatives,id',
            'sales_division' => 'required|in:'.implode(',', array_column(\App\Enums\SalesDivisionEnum::cases(), 'value')),
            'carrier_id' => 'nullable|exists:carriers,id',
            'payment_term_id' => 'nullable|exists:payment_terms,id',
            'currency' => 'required|string|max:3',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'purchase_order' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'erp_code' => 'nullable|string|max:255',
            'total_items' => 'nullable|numeric|min:0',
            'total_discounts' => 'nullable|numeric|min:0',
            'total_freight' => 'required|numeric|min:0',
            'total_ipi' => 'nullable|numeric|min:0',
            'total_icms' => 'required|numeric|min:0',
            'total_icms_st' => 'required|numeric|min:0',
            'total_difal' => 'required|numeric|min:0',
            'grand_total' => 'nullable|numeric|min:0',
            'gross_weight' => 'nullable|numeric|min:0',
            'net_weight' => 'nullable|numeric|min:0',
            'cubic_volume' => 'nullable|numeric|min:0',
            'packages' => 'nullable|integer|min:0',
            'order_status' => 'required|in:'.implode(',', array_column(\App\Enums\OrderStatusEnum::cases(), 'value')),
            'delivery_status' => 'required|in:'.implode(',', array_column(\App\Enums\DeliveryStatusEnum::cases(), 'value')),
            'shipping_method' => 'required|in:'.implode(',', array_column(\App\Enums\ShippingMethodEnum::cases(), 'value')),
            'tracking_code' => 'nullable|string|max:255',
            'payment_method' => 'required|in:'.implode(',', array_column(\App\Enums\PaymentMethodEnum::cases(), 'value')),
            'sale_items' => 'required|array|min:1',
            'sale_items.*.product_id' => 'required|exists:products,id',
            'sale_items.*.description' => 'nullable|string|max:255',
            'sale_items.*.ipi' => 'required|numeric|min:0',
            'sale_items.*.quantity' => 'required|integer|min:1',
            'sale_items.*.unit_price' => 'required|numeric|min:0',
            'sale_items.*.item_discount' => 'nullable|numeric|min:0',
        ];

        if ($request->input('order_status') === \App\Enums\OrderStatusEnum::InAssembly->value) {
            $rules['actual_delivery_date'] = 'required|date|after_or_equal:issue_date|after_or_equal:expected_delivery_date';
        }

        $validatedData = $request->validate($rules);

        $totalItems = 0;
        $totalDiscounts = 0;
        $totalIpi = 0;

        foreach ($validatedData['sale_items'] as $item) {
            $subtotal = ($item['quantity'] * $item['unit_price']) - ($item['item_discount'] ?? 0);
            $totalItems += $subtotal;
            $totalDiscounts += ($item['item_discount'] ?? 0);
            $totalIpi += ($item['ipi'] * $item['quantity']);
        }

        $validatedData['total_items'] = $totalItems;
        $validatedData['total_discounts'] = $totalDiscounts;
        $validatedData['total_ipi'] = $totalIpi;

        $validatedData['grand_total'] = $totalItems
                                        - $totalDiscounts
                                        + $validatedData['total_freight']
                                        + $totalIpi
                                        + $validatedData['total_icms']
                                        + $validatedData['total_icms_st']
                                        + $validatedData['total_difal'];

        $sale->update($validatedData);

        $sale->saleItems()->delete(); // Remove existing items
        foreach ($validatedData['sale_items'] as $item) {
            $sale->saleItems()->create($item);
        }

        return redirect()->route('sales.show', $sale->id)
            ->with('success', 'Venda atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Venda excluída com sucesso!');
    }

    public function exportExcel()
    {
        return Excel::download(new SalesExport, 'sales.xlsx');
    }

    public function exportPdf()
    {
        $sales = Sale::all();
        $pdf = SnappyPdf::loadView('sales.export_pdf', compact('sales'));

        return $pdf->download('sales.pdf');
    }

    public function kanbanData()
    {
        $sales = Sale::with('customer')
            ->whereNotIn('order_status', [OrderStatusEnum::Completed, OrderStatusEnum::Cancelled])
            ->orderBy('issue_date', 'desc')
            ->get();

        return response()->json($sales);
    }

    public function updateStatus(Request $request, Sale $sale)
    {
        $request->validate([
            'status' => 'required|in:'.implode(',', array_column(OrderStatusEnum::cases(), 'value')),
            'actual_delivery_date' => 'nullable|date',
        ]);

        $newStatus = OrderStatusEnum::from($request->status);
        $sale->order_status = $newStatus;

        // Se o novo status for 'In Assembly' e a data de entrega real não foi fornecida, preenche com a data atual
        if ($newStatus === OrderStatusEnum::Delivered && ! $request->has('actual_delivery_date')) {
            $sale->actual_delivery_date = now();
        } elseif ($request->has('actual_delivery_date')) {
            $sale->actual_delivery_date = $request->actual_delivery_date;
        }

        $sale->save();

        return response()->json(['message' => 'Status da venda atualizado com sucesso!']);
    }

    public function scheduleAssemblyCreate(Sale $sale)
    {
        return view('sales.schedule-assembly', compact('sale'));
    }

    /**
     * Import a sale from an Excel file
     */
    public function import(Request $request)
    {
        $request->validate([
            'sale_file' => 'required|file|max:10240',
        ]);

        $file = $request->file('sale_file');
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, ['xls', 'xlsx'])) {
            return back()
                ->withInput()
                ->withErrors(['sale_file' => 'O campo sale file deve ser um arquivo do tipo: xls, xlsx.']);
        }

        try {
            // Get the uploaded file directly
            $tempPath = $file->getRealPath();

            // Import using our importer
            $importer = new \App\Modules\SalesOrderImporter($tempPath, auth()->id() ?? 1);
            $saleId = $importer->import();

            // dd($saleId);
            if ($saleId !== false) {
                return redirect()->route('sales.show', $saleId)
                    ->with('success', 'Venda importada com sucesso!');
            } else {
                return back()
                    ->withInput()
                    ->withErrors(['sale_file' => 'Falha ao processar o arquivo. Verifique o formato e tente novamente.']);
            }
        } catch (\Exception $e) {
            \Log::Error('Erro na importação de venda: '.$e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['sale_file' => 'Ocorreu um erro ao processar o arquivo: '.$e->getMessage()]);
        }
    }
}
