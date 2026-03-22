<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryWindow;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DeliveryWindowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deliveryWindows = DeliveryWindow::all();
        return view('admin.delivery_windows.index', compact('deliveryWindows'));
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = DeliveryWindow::select(['id', 'start_time', 'end_time', 'day_of_week', 'status']);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($row){
                    $btn = '<a href="' . route('delivery_windows.show', $row->id) . '" class="btn btn-info btn-sm">Ver</a> ';
                    $btn .= '<a href="' . route('delivery_windows.edit', $row->id) . '" class="btn btn-warning btn-sm">Editar</a> ';
                    $btn .= '<form action="' . route('delivery_windows.destroy', $row->id) . '" method="POST" style="display:inline;">'
                        . csrf_field() . method_field('DELETE')
                        . '<button type="submit" class="btn btn-danger btn-sm">Excluir</button>'
                        . '</form>';
                    return $btn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.delivery_windows.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'day_of_week' => 'required|integer|between:1,7',
            'status' => 'required|in:Ativo,Inativo',
        ]);

        DeliveryWindow::create($validatedData);

        return redirect()->route('delivery_windows.index')
            ->with('success', 'Janela de Entrega criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(DeliveryWindow $deliveryWindow)
    {
        return view('admin.delivery_windows.show', compact('deliveryWindow'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DeliveryWindow $deliveryWindow)
    {
        return view('admin.delivery_windows.edit', compact('deliveryWindow'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DeliveryWindow $deliveryWindow)
    {
        $validatedData = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'day_of_week' => 'required|integer|between:1,7',
            'status' => 'required|in:Ativo,Inativo',
        ]);

        $deliveryWindow->update($validatedData);

        return redirect()->route('delivery_windows.index')
            ->with('success', 'Janela de Entrega atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeliveryWindow $deliveryWindow)
    {
        $deliveryWindow->delete();

        return redirect()->route('delivery_windows.index')
            ->with('success', 'Janela de Entrega excluída com sucesso!');
    }
}
