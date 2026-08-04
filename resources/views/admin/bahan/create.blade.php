@extends('layouts.app')

@section('title', 'Tambah Bahan')
@section('page-title', 'Tambah Bahan')

@section('content')
<div class="card-custom" style="max-width:600px; margin:0 auto;">
    <form method="POST" action="{{ route('admin.bahan.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Kode Bahan</label>
            <input type="text" name="kode_bahan" class="form-control" placeholder="Contoh: KJT-001" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Bahan</label>
            <input type="text" name="nama_bahan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Satuan</label>
            <input type="text" name="satuan" list="satuan-options" class="form-control" placeholder="Pilih atau ketik satuan (misal: m, cm, gram, Pcs)" required>
            <datalist id="satuan-options">
                <option value="Pcs">
                <option value="Unit">
                <option value="Lembar">
                <option value="Botol">
                <option value="m">
                <option value="cm">
                <option value="mm">
                <option value="Kg">
                <option value="gram">
                <option value="Liter">
                <option value="ml">
            </datalist>
        </div>
        <div class="mb-3">
            <label class="form-label">Stok Minimum</label>
            <input type="number" name="stok_minimum" class="form-control" value="0" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="2"></textarea>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.bahan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
