<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Architect;
use App\Http\Requests\ArchitectRequest;
use App\Services\Crm\ArchitectService;
use Illuminate\Http\Request;

class ArchitectController extends Controller
{
    protected $architectService;

    public function __construct(ArchitectService $architectService)
    {
        $this->architectService = $architectService;
    }

    public function index(Request $request)
    {
        $query = Architect::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('specialty', 'like', '%' . $request->search . '%');
        }

        if ($request->has('specialty')) {
            $query->where('specialty', $request->specialty);
        }

        $architects = $query->paginate(10);

        return view('admin.architects.index', compact('architects'));
    }

    public function create()
    {
        return view('admin.architects.create');
    }

    public function store(ArchitectRequest $request)
    {
        Architect::create($request->validated());
        return redirect()->route('crm.architects.index')->with('success', 'Arquiteto cadastrado com sucesso.');
    }

    public function show(Architect $architect)
    {
        $architect->load('opportunities');
        
        $totalSales = $this->architectService->calculateTotalSales($architect);
        $totalCommission = $this->architectService->calculateTotalCommission($architect);
        
        return view('admin.architects.show', compact('architect', 'totalSales', 'totalCommission'));
    }

    public function edit(Architect $architect)
    {
        return view('admin.architects.edit', compact('architect'));
    }

    public function update(ArchitectRequest $request, Architect $architect)
    {
        $architect->update($request->validated());
        return redirect()->route('crm.architects.index')->with('success', 'Arquiteto atualizado com sucesso.');
    }

    public function destroy(Architect $architect)
    {
        $architect->delete();
        return redirect()->route('crm.architects.index')->with('success', 'Arquiteto removido com sucesso.');
    }
}
