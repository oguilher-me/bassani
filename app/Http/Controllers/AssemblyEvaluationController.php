<?php

namespace App\Http\Controllers;

use App\Models\AssemblyScheduleEvaluation;
use App\Models\AssemblySchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssemblyEvaluationController extends Controller
{
    public function show(string $token)
    {
        $evaluation = AssemblyScheduleEvaluation::where('token', $token)
            ->with(['schedule.sale.customer', 'schedule.sale.saleItems.product', 'schedule.assemblers'])
            ->firstOrFail();
        if ($evaluation->submitted_at) {
            return view('evaluation.thanks');
        }
        return view('evaluation.form', compact('evaluation'));
    }

    public function submit(Request $request, string $token)
    {
        $evaluation = AssemblyScheduleEvaluation::where('token', $token)->firstOrFail();
        if ($evaluation->submitted_at) {
            return view('evaluation.thanks');
        }

        $request->validate([
            'nps_score' => 'required|integer|min:0|max:10',
            'comments' => 'nullable|string|max:2000',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $paths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $paths[] = $file->store('assembly-evaluation-photos', 'public');
            }
        }

        $evaluation->nps_score = (int)$request->nps_score;
        $evaluation->comments = $request->comments;
        $evaluation->photo_paths = json_encode($paths);
        $evaluation->submitted_at = now();
        $evaluation->save();

        return view('evaluation.thanks');
    }
}
