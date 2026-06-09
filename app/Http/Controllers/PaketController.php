<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Ruangan;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        $pakets = Paket::with(['ruangan', 'fasilitas'])->get();
        return view('admin.paket.index', compact('pakets'));
    }

    public function create()
    {
        $ruangans = Ruangan::all();
        $fasilitas = Fasilitas::all();
        return view('admin.paket.create', compact('ruangans', 'fasilitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ruangan_id' => 'required|exists:ruangans,id',
            'nama_paket' => 'required|string|max:255',
            'harga_paket' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'exists:fasilitas,id'
        ]);

        $paket = Paket::create($request->only(['ruangan_id', 'nama_paket', 'harga_paket', 'deskripsi']));

        if ($request->has('fasilitas')) {
            $paket->fasilitas()->sync($request->fasilitas);
        }

        return redirect()->route('admin.paket.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $paket = Paket::with('fasilitas')->findOrFail($id);
        $ruangans = Ruangan::all();
        $fasilitas = Fasilitas::all();
        return view('admin.paket.edit', compact('paket', 'ruangans', 'fasilitas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ruangan_id' => 'required|exists:ruangans,id',
            'nama_paket' => 'required|string|max:255',
            'harga_paket' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'exists:fasilitas,id'
        ]);

        $paket = Paket::findOrFail($id);
        $paket->update($request->only(['ruangan_id', 'nama_paket', 'harga_paket', 'deskripsi']));

        if ($request->has('fasilitas')) {
            $paket->fasilitas()->sync($request->fasilitas);
        } else {
            $paket->fasilitas()->detach();
        }

        return redirect()->route('admin.paket.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $paket = Paket::findOrFail($id);
        $paket->delete();
        return redirect()->route('admin.paket.index')->with('success', 'Paket berhasil dihapus.');
    }
}
