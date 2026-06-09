<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FotoRuangan;
use Illuminate\Support\Facades\Storage;

class FotoRuanganController extends Controller
{
    public function destroy($id)
    {
        $foto = FotoRuangan::findOrFail($id);
        $ruanganId = $foto->ruangan_id;

        // Hapus file dari storage
        Storage::disk('public')->delete($foto->file_foto);
        $foto->delete();

        return redirect()->route('admin.ruangan.edit', $ruanganId)
            ->with('success', 'Foto berhasil dihapus.');
    }
}
