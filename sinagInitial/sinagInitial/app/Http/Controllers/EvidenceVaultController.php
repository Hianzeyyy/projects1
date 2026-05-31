<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use App\Models\EvidenceVault;
use Illuminate\Support\Facades\Auth;

class EvidenceVaultController extends Controller
{
    public function index()
    {
        $files = EvidenceVault::where('user_id', Auth::id())->get();
        return view('evidence.index', compact('files'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,png,pdf,mp3,mp4,webm',
        ]);

        $user = Auth::user();
        $file = $request->file('file');
        $path = $file->store('evidence', ['disk' => 'local', 'visibility' => 'private']);

        // Optionally encrypt file contents here if needed

        $evidence = EvidenceVault::create([
            'user_id' => $user->id,
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'encrypted' => true,
        ]);

        return back()->with('success', 'File uploaded successfully.');
    }

    public function destroy($id)
    {
        $file = EvidenceVault::where('user_id', Auth::id())->findOrFail($id);
        Storage::disk('local')->delete($file->file_path);
        $file->delete();
        return back()->with('success', 'File deleted successfully.');
    }
}
