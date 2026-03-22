<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class DriverDocumentController extends Controller
{
    /**
     * Display a listing of documents for a given driver.
     */
    public function index(Driver $driver)
    {
        $documents = $driver->documents()->latest()->paginate(10);
        return view('admin.drivers.documents.index', compact('driver', 'documents'));
    }

    /**
     * Store a newly uploaded document.
     */
    public function store(Request $request, Driver $driver)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'category'    => 'required|in:CNH,CRLV,Contrato,Outros',
            'expires_at'  => 'nullable|date',
            'file'        => 'required|file|max:5120|mimes:pdf,jpg,jpeg,png',
        ]);

        try {
            $file     = $request->file('file');
            $mimeType = $file->getMimeType();
            $fileType = ($mimeType === 'application/pdf') ? 'pdf' : 'image';

            $path = $file->store("drivers/{$driver->id}/documents", 'public');

            DriverDocument::create([
                'driver_id'   => $driver->id,
                'description' => $request->description,
                'file_path'   => $path,
                'file_type'   => $fileType,
                'category'    => $request->category,
                'expires_at'  => $request->expires_at ?: null,
            ]);

            return back()->with('success', 'Documento enviado com sucesso!');
        } catch (Throwable $e) {
            Log::error('DriverDocumentController@store: ' . $e->getMessage());
            return back()->with('error', 'Erro ao enviar documento: ' . $e->getMessage());
        }
    }

    /**
     * Remove a document and its physical file.
     */
    public function destroy(Driver $driver, DriverDocument $document)
    {
        try {
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $document->delete();

            return back()->with('success', 'Documento removido com sucesso!');
        } catch (Throwable $e) {
            Log::error('DriverDocumentController@destroy: ' . $e->getMessage());
            return back()->with('error', 'Erro ao remover documento: ' . $e->getMessage());
        }
    }
}
