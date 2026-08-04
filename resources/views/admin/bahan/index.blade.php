@extends('layouts.app')

@section('title', 'Master Bahan')
@section('page-title', 'Master Bahan')

@section('content')
<div class="card-custom">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Daftar Bahan</h6>
        <a href="{{ route('admin.bahan.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Deskripsi/Ukuran</th>
                    <th>Satuan</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Minimum</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bahan as $item)
                <tr>
                    <td>{{ $item->kode_bahan }}</td>
                    <td>{{ $item->nama_bahan }}</td>
                    <td>{{ $item->deskripsi ?: '-' }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td class="text-center">
                        <span class="{{ $item->stok_saat_ini <= $item->stok_minimum ? 'text-danger fw-bold' : '' }}">
                            {{ $item->stok_saat_ini }}
                        </span>
                    </td>
                    <td class="text-center">{{ $item->stok_minimum }}</td>
                    <td class="text-center">
                        <span class="badge bg-{{ $item->stok_saat_ini <= $item->stok_minimum ? 'danger' : 'success' }}">
                            {{ $item->stok_saat_ini <= $item->stok_minimum ? 'Menipis' : 'Aman' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.bahan.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.bahan.destroy', $item) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus bahan ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-3 d-flex justify-content-end">
        {{ $bahan->links() }}
    </div>
</div>
@endsection
