<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmOpportunity;
use App\Models\CrmOpportunityAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class CrmAttachmentController extends Controller
{
    /**
     * Store an attachment for an opportunity.
     */
    public function store(Request $request, CrmOpportunity $opportunity)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,ppt,pptx,txt'
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $fileType = $file->getClientOriginalExtension();
                $fileSize = $file->getSize();
                
                // Store file
                $path = $file->store('crm/attachments/' . $opportunity->id, 'public');

                $attachment = CrmOpportunityAttachment::create([
                    'opportunity_id' => $opportunity->id,
                    'user_id' => auth()->id(),
                    'file_name' => $fileName,
                    'file_path' => $path,
                    'file_type' => $fileType,
                    'file_size' => $fileSize
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Arquivo enviado com sucesso!',
                        'attachment' => $attachment,
                        'formatted_size' => $attachment->formatted_size,
                        'url' => asset('storage/' . $attachment->file_path)
                    ]);
                }

                return back()->with('success', 'Arquivo enviado com sucesso!');
            }

            return back()->with('error', 'Nenhun arquivo enviado.');

        } catch (Throwable $e) {
            Log::error("Error in CrmAttachmentController@store: " . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao enviar arquivo: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    /**
     * Remove the specified attachment.
     */
    public function destroy(CrmOpportunityAttachment $attachment)
    {
        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            $attachment->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Arquivo removido com sucesso!'
                ]);
            }

            return back()->with('success', 'Arquivo removido com sucesso!');
        } catch (Throwable $e) {
            Log::error("Error in CrmAttachmentController@destroy: " . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao remover arquivo: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }
}
