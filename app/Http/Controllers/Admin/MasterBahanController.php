<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterBahan;
use Illuminate\Http\Request;

class MasterBahanController extends Controller
{
    public function index()
    {
        return view('admin.bahan.index', ['bahan' => MasterBahan::paginate(50)]);
    }

    public function create()
    {
        return view('admin.bahan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_bahan' => 'required|unique:master_bahan',
            'nama_bahan' => 'required',
            'satuan' => 'required',
            'stok_minimum' => 'required|integer|min:0',
        ]);

        MasterBahan::create([
            'kode_bahan' => $request->kode_bahan,
            'nama_bahan' => $request->nama_bahan,
            'satuan' => $request->satuan,
            'stok_saat_ini' => 0,
            'stok_minimum' => $request->stok_minimum,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.bahan.index')->with('success', 'Bahan berhasil ditambahkan!');
    }

    public function edit(MasterBahan $bahan)
    {
        return view('admin.bahan.edit', compact('bahan'));
    }

    public function update(Request $request, MasterBahan $bahan)
    {
        $request->validate([
            'kode_bahan' => 'required|unique:master_bahan,kode_bahan,' . $bahan->id,
            'nama_bahan' => 'required',
            'satuan' => 'required',
            'stok_minimum' => 'required|integer|min:0',
        ]);

        $bahan->update([
            'kode_bahan' => $request->kode_bahan,
            'nama_bahan' => $request->nama_bahan,
            'satuan' => $request->satuan,
            'stok_minimum' => $request->stok_minimum,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.bahan.index')->with('success', 'Bahan berhasil diupdate!');
    }

    public function destroy(MasterBahan $bahan)
    {
        $bahan->delete();
        return redirect()->route('admin.bahan.index')->with('success', 'Bahan berhasil dihapus!');
    }
}