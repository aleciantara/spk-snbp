<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Surat;
use App\Models\TemplateSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();// Get the Siswa and related Rapor data by $nisn

        $templates = TemplateSurat::all();
        $surats = Surat::all();

        if ($user->role == "siswa") {
            $nisn = $user->email;
            $surats = Surat::whereHas('siswa', function ($query) use ($nisn) {
                $query->where('nisn', $nisn);
            })->orderBy('updated_at', 'desc') // Sort by created_at in descending order
            ->paginate(30);
        }else{
            $searchQuery = $request->input('search');
            $statusFilters = $request->input('status', []);

            $query = Surat::query();

            // Apply search condition
            if ($searchQuery) {
                $query->where(function ($subQuery) use ($searchQuery) {
                    $subQuery->where('surats.judul', 'like', "%$searchQuery%")
                        ->orWhereHas('siswa', function ($subSubQuery) use ($searchQuery) {
                            $subSubQuery->where('nama', 'like', "%$searchQuery%");
                        });
                });
            }

            // Apply status filters
            if (!empty($statusFilters)) {
                $query->whereIn('status', $statusFilters);
            }

            // Fetch the data
            $surats = $query->get();

        }
        return view("surat/index", compact("surats", "templates"));


    }

    public function storeTemplateSurat(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'surat' => 'required|string|max:255',
            'file' => 'required|mimes:pdf,docx|max:2048', // Adjust the allowed file types and size as needed
        ]);

        // Get the file extension
        $fileExtension = $request->file('file')->getClientOriginalExtension();

        // Store the file in the public/templates directory
        $filePath = $request->file('file')->storeAs('public/templates', "template_{$validatedData['surat']}.{$fileExtension}");

        // Create a new TemplateSurat record
        TemplateSurat::create([
            'surat' => $validatedData['surat'],
            'file' => "template_{$validatedData['surat']}.{$fileExtension}",
        ]);

        return redirect()->back()->with('success', 'Template Surat berhasil diupload.');
    }


    public function downloadTemplate($templateId)
    {
        $template = TemplateSurat::findOrFail($templateId);

        $download_path = ( public_path() . '\\storage\\templates\\' . $template->file );
        return( Response::download( $download_path ) );
    }

    public function destroyTemplate($templateId)
    {
        $template = TemplateSurat::findOrFail($templateId);

        // Delete the file from the storage
        $filePath = public_path("storage\\templates\\{$template->file}");
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Delete the TemplateSurat record from the database
        $template->delete();

        return redirect()->back()->with('success', 'Template Surat berhasil dihapus.');
    }

    public function storeSurat(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'catatan' => 'required|string|max:315',
            'file' => 'required|mimes:pdf,docx|max:2048', // Adjust the allowed file types and size as needed
        ]);

        $user = Auth::user();// Get the Siswa and related Rapor data by $nisn

        // Get the file extension
        $fileExtension = $request->file('file')->getClientOriginalExtension();

        // Store the file in the public/templates directory
        $filePath = $request->file('file')->storeAs('public/surats', "surat_{$user->email}_{$validatedData['judul']}.{$fileExtension}");

        // Create a new TemplateSurat record
        Surat::create([
            'nisn' => $user->email,
            'judul' => $validatedData['judul'],
            'catatan' => $validatedData['catatan'],
            'file' => "surat_{$user->email}_{$validatedData['judul']}.{$fileExtension}",
        ]);

        return redirect()->back()->with('success', 'Surat berhasil diupload.');
    }

    public function downloadSurat($suratId)
    {
        $surat = Surat::findOrFail($suratId);

        $download_path = ( public_path() . '\\storage\\surats\\' .$surat->file );
        return( Response::download( $download_path ) );
    }
    public function editSurat($suratId)
    {
        $surat =  Surat::findOrFail($suratId);

        return view('surat.edit', compact('surat'));

    }

    public function updateSurat(Request $request, $id)
    {
        $this->validate($request, [
            'catatan' => 'required|max:315',
            'status' => 'required|in:verified,unverified,denied',
        ]);

        $surat = Surat::findOrFail($id);
        $surat->catatan = $request->input('catatan');
        $surat->status = $request->input('status');
        
        // Handle file update if needed

        $surat->save();

        return redirect()->route('surat.index')->with('success', 'Surat Berhasil Diupdate.');
    }

    public function destroySurat($suratId)
    {
        $surat =  Surat::findOrFail($suratId);

        // Delete the file from the storage
        $filePath = public_path("storage\\surats\\{$surat->file}");
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Delete the suratSurat record from the database
        $surat->delete();

        return redirect()->back()->with('success', 'Surat berhasil dihapus.');
    }
}
