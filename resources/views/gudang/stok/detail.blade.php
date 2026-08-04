@extends('layouts.app')

@section('title', 'Detail Batch - ' . $bahan->nama_bahan)
@section('page-title', 'Detail Batch: ' . $bahan->nama_bahan)

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card-custom">
            <h6 class="fw-bold mb-3">Informasi Bahan</h6>
            <table class="table table-sm">
                <tr>
                    <td><strong>Kode</strong></td>
                    <td>{{ $bahan->kode_bahan }}</td>
                </tr>
                <tr>
                    <td><strong>Nama</strong></td>
                    <td>{{ $bahan->nama_bahan }}</td>
                </tr>
                <tr>
                    <td><strong>Satuan</strong></td>
                    <td>{{ $bahan->satuan }}</td>
                </tr>
                <tr>
                    <td><strong>Total Stok</strong></td>
                    <td class="fw-bold text-success">{{ $totalStok }} {{ $bahan->satuan }}</td>
                </tr>
                <tr>
                    <td><strong>Stok Minimum</strong></td>
                    <td>{{ $bahan->stok_minimum }} {{ $bahan->satuan }}</td>
                </tr>
                <tr>
                    <td><strong>Status</strong></td>
                    <td>
                        <span class="badge bg-{{ $totalStok <= $bahan->stok_minimum ? 'danger' : 'success' }}">
                            {{ $totalStok <= $bahan->stok_minimum ? 'Menipis' : 'Aman' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card-custom">
            <h6 class="fw-bold mb-3">
                <i class="fas fa-boxes me-2"></i> Daftar Batch Stok Bahan
            </h6>

            @if($batches->count() > 0)
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Batch</th>
                            <th>Tanggal Masuk</th>
                            <th class="text-center">Jumlah Masuk</th>
                            <th class="text-center">Sisa Stok</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batches as $index => $batch)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $batch->kode_batch }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($batch->tanggal_masuk)->format('d/m/Y') }}</td>
                            <td class="text-center">{{ $batch->jumlah_masuk }}</td>
                            <td class="text-center">
                                <span class="fw-bold text-success">{{ $batch->sisa_stok }}</span>
                            </td>
                            <td class="text-center">
                                @if($batch->sisa_stok > 0)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Habis</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-light">
                            <th colspan="4" class="text-end">Total Stok:</th>
                            <th class="text-center text-success fw-bold">{{ $totalStok }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                Stok bahan akan di ambil dari batch terakhir
            </div>
            @else
            <div class="text-center text-muted py-4">
                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                Belum ada batch untuk bahan ini
            </div>
            @endif

            <div class="mt-3">
                <a href="{{ route('gudang.stok.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
