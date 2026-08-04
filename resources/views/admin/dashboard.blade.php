@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Admin')

@section('content')
<!-- 1. METRIK KRITIS (Highlight Actionable Items) -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card border-warning" style="border-left-width: 4px;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-semibold">Transaksi Pending</small>
                    <h4 class="mb-0 text-warning">{{ $totalPending }}</h4>
                </div>
                <div class="icon bg-warning bg-opacity-10 p-3 rounded">
                    <i class="fas fa-clock text-warning"></i>
                </div>
            </div>
            <small class="text-muted">PR: {{ $pendingPR }} | PG: {{ $pendingPG }} | BM: {{ $pendingBM }}</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card border-danger" style="border-left-width: 4px; background-color: #fff5f5;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-danger fw-semibold">Stok KOSONG (Habis)</small>
                    <h4 class="mb-0 text-danger">{{ $stokKosong }}</h4>
                </div>
                <div class="icon bg-danger text-white p-3 rounded">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
            <small class="text-danger">Segera lakukan pengadaan!</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card border-primary" style="border-left-width: 4px;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-semibold">Aktivitas Hari Ini</small>
                    <h4 class="mb-0 text-primary">{{ $transaksiHariIni }}</h4>
                </div>
                <div class="icon bg-primary bg-opacity-10 p-3 rounded">
                    <i class="fas fa-calendar-day text-primary"></i>
                </div>
            </div>
            <small class="text-muted">Total transaksi dari semua divisi</small>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- KOLOM KIRI: Transaksi Terbaru -->
    <div class="col-md-7">
        <div class="card-custom h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-history me-2 text-primary"></i> Log Transaksi Terbaru</h6>
                <a href="{{ route('admin.history.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            @if($recentTransactions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-custom table-hover">
                        <thead>
                            <tr>
                                <th>No Transaksi</th>
                                <th>Waktu</th>
                                <th>Divisi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $item)
                            <tr>
                                <td class="fw-semibold text-primary">{{ $item->no_transaksi }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal)->diffForHumans() }}
                                </td>
                                <td><span class="badge bg-secondary">{{ $item->jenis }}</span></td>
                                <td>
                                    <span class="badge-status 
                                        @if($item->status == 'Menunggu' || $item->status == 'Draft') badge-menunggu
                                        @elseif($item->status == 'Diproses') badge-diproses
                                        @elseif($item->status == 'Selesai' || $item->status == 'Diverifikasi') badge-selesai
                                        @elseif($item->status == 'Ditolak') badge-ditolak
                                        @else badge-sebagian @endif">
                                        {{ $item->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted py-4">Belum ada transaksi di sistem.</div>
            @endif
        </div>
    </div>

    <!-- KOLOM KANAN: Alert Stok -->
    <div class="col-md-5">
        <div class="card-custom h-100 border-top border-danger border-4 shadow-sm">
            <h6 class="fw-bold text-danger mb-3">
                <i class="fas fa-exclamation-circle me-2"></i> Bahan Stok Menipis / Kritis
            </h6>
            @if($stokMenipis->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($stokMenipis as $item)
                    <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center border-bottom-0">
                        <div>
                            <div class="fw-semibold">{{ $item->nama_bahan }}</div>
                            <small class="text-muted">{{ $item->kode_bahan }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-danger rounded-pill px-3">{{ $item->stok_saat_ini }} {{ $item->satuan }}</span>
                            <small class="d-block text-muted mt-1" style="font-size: 10px;">Min: {{ $item->stok_minimum }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.bahan.index') }}" class="btn btn-outline-danger btn-sm fw-bold w-100">Kelola Master Bahan</a>
                </div>
            @else
                <div class="text-center text-success py-5">
                    <i class="fas fa-check-circle fs-1 mb-3"></i>
                    <p class="mb-0 fw-semibold">Semua stok bahan dalam kondisi aman!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
