<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:crm_leads,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'required|exists:users,id',
        ]);
        
        $lead = Lead::findOrFail($validated['lead_id']);

        $task = new Task();
        $task->fill($validated);
        $task->user_id = $validated['assigned_to']; // Currently mapping assigned_to from form to user_id
        $task->customer_id = $lead->customer_id;
        $task->status = 'pending';
        $task->save();

        return redirect()->back()->with('success', 'Task created successfully.');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed',
        ]);

        $task->update($validated);

        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully.');
    }
}
