<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;
use App\Models\FotoRuangan;
use Illuminate\Support\Facades\Storage;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::with('fotoRuangan')->orderBy('id', 'desc')->get();
        return view('admin.ruangan.index', compact('ruangans'));
    }

    public function create()
    {
        return view('admin.ruangan.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_ruangan'    => 'required|string|max:255',
            'kapasitas'       => 'required|integer|min:1',
            'lokasi_ruangan'  => 'required|string|max:255',
            'deskripsi'       => 'required|string',
            'status_tersedia' => 'required|in:kosong,tersedia',
            'harga_per_jam'   => 'required|integer|min:0',
            'tipe_ruangan'    => 'required|string|max:100',
            'fotos'           => 'nullable|array',
            'fotos.*'         => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $ruangan = Ruangan::create($request->all());

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                if ($foto->isValid()) {
                    $path = $foto->store('ruangan', 'public');
                    FotoRuangan::create([
                        'ruangan_id' => $ruangan->id,
                        'file_foto'  => $path,
                    ]);
                }
            }
        }

        return redirect()->route('admin.ruangan.index')
            ->with('success', "Ruangan \"{$ruangan->nama_ruangan}\" berhasil ditambahkan.");
    }

    public function edit($id)
    {
        $ruangan = Ruangan::with('fotoRuangan')->findOrFail($id);
        return view('admin.ruangan.edit', compact('ruangan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_ruangan'    => 'required|string|max:255',
            'kapasitas'       => 'required|integer|min:1',
            'lokasi_ruangan'  => 'required|string|max:255',
            'deskripsi'       => 'required|string',
            'status_tersedia' => 'required|in:kosong,tersedia',
            'harga_per_jam'   => 'required|integer|min:0',
            'tipe_ruangan'    => 'required|string|max:100',
            'fotos'           => 'nullable|array',
            'fotos.*'         => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $ruangan = Ruangan::findOrFail($id);
        $ruangan->update($request->only([
            'nama_ruangan', 'kapasitas', 'lokasi_ruangan',
            'deskripsi', 'status_tersedia', 'harga_per_jam',
            'tipe_ruangan',
        ]));

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                if ($foto->isValid()) {
                    $path = $foto->store('ruangan', 'public');
                    FotoRuangan::create([
                        'ruangan_id' => $ruangan->id,
                        'file_foto'  => $path,
                    ]);
                }
            }
        }

        return redirect()->route('admin.ruangan.index')
            ->with('success', "Ruangan \"{$ruangan->nama_ruangan}\" berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::with('fotoRuangan')->findOrFail($id);

        foreach ($ruangan->fotoRuangan as $foto) {
            Storage::disk('public')->delete($foto->file_foto);
        }

        $ruangan->delete();

        return redirect()->route('admin.ruangan.index')
            ->with('success', "Ruangan \"{$ruangan->nama_ruangan}\" berhasil dihapus.");
    }
}
