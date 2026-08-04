@extends('layouts.app')

@section('title', 'Monitoring Stok')
@section('page-title', 'Monitoring Stok & Batch')

@section('content')
<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Bahan</th>
                    <th>Deskripsi/Ukuran</th>
                    <th>Satuan</th>
                    <th class="text-center">Total Stok</th>
                    <th class="text-center">Stok Minimum</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Jumlah Batch</th>
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
                        <span class="fw-bold {{ $item->total_stok <= $item->stok_minimum ? 'text-danger' : 'text-success' }}">
                            {{ $item->total_stok }}
                        </span>
                    </td>
                    <td class="text-center">{{ $item->stok_minimum }}</td>
                    <td class="text-center">
                        <span class="badge bg-{{ $item->total_stok <= $item->stok_minimum ? 'danger' : 'success' }}">
                            {{ $item->total_stok <= $item->stok_minimum ? 'Menipis' : 'Aman' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-info">{{ $item->stokBatch->count() }}</span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('gudang.stok.batch', $item->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye me-1"></i> Detail Batch
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
