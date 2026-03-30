<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ChecklistItemController extends Controller
{
    /**
     * Display a listing of checklist items.
     */
    public function index(): View
    {
        $checklistItems = ChecklistItem::orderBy('id')->paginate(10);

        return view('admin.checklist-items.index', compact('checklistItems'));
    }

    /**
     * Show the form for creating a new checklist item.
     */
    public function create(): View
    {
        return view('admin.checklist-items.create');
    }

    /**
     * Store a newly created checklist item.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'is_restrictive' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        ChecklistItem::create($validated);

        return redirect()->route('checklist-items.index')
            ->with('success', 'Item do checklist criado com sucesso!');
    }

    /**
     * Show the form for editing the specified checklist item.
     */
    public function edit(ChecklistItem $checklistItem): View
    {
        return view('admin.checklist-items.edit', compact('checklistItem'));
    }

    /**
     * Update the specified checklist item.
     */
    public function update(Request $request, ChecklistItem $checklistItem): RedirectResponse
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'is_restrictive' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $checklistItem->update($validated);

        return redirect()->route('checklist-items.index')
            ->with('success', 'Item do checklist atualizado com sucesso!');
    }

    /**
     * Remove the specified checklist item.
     */
    public function destroy(ChecklistItem $checklistItem): RedirectResponse
    {
        $checklistItem->delete();

        return redirect()->route('checklist-items.index')
            ->with('success', 'Item do checklist excluído com sucesso!');
    }
}
