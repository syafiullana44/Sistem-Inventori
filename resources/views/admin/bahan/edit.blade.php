@extends('layouts.app')

@section('title', 'Edit Bahan')
@section('page-title', 'Edit Bahan')

@section('content')
<div class="card-custom" style="max-width:600px; margin:0 auto;">
    <form method="POST" action="{{ route('admin.bahan.update', $bahan) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Kode Bahan</label>
            <input type="text" name="kode_bahan" value="{{ old('kode_bahan', $bahan->kode_bahan) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Bahan</label>
            <input type="text" name="nama_bahan" value="{{ old('nama_bahan', $bahan->nama_bahan) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Satuan</label>
            <input type="text" name="satuan" list="satuan-options" value="{{ old('satuan', $bahan->satuan) }}" class="form-control" placeholder="Pilih atau ketik satuan" required>
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
            <input type="number" name="stok_minimum" value="{{ old('stok_minimum', $bahan->stok_minimum) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stok Saat Ini</label>
            <input type="number" value="{{ $bahan->stok_saat_ini }}" class="form-control" disabled>
            <small class="text-muted">Stok diupdate otomatis oleh sistem (FIFO)</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi', $bahan->deskripsi) }}</textarea>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.bahan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection
