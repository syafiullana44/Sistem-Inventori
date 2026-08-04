@extends('layouts.app')

@section('title', 'Dashboard Gudang')
@section('page-title', 'Dashboard Gudang')

@section('content')
<div class="row g-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Total Bahan</small>
                    <h4 class="mb-0">{{ $total_bahan ?? 0 }}</h4>
                </div>
                <div class="icon bg-primary bg-opacity-10 p-3 rounded">
                    <i class="fas fa-boxes text-primary"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card border-danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Stok Menipis</small>
                    <h4 class="mb-0 text-danger">{{ $stok_menipis->count() ?? 0 }}</h4>
                </div>
                <div class="icon bg-danger bg-opacity-10 p-3 rounded">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card border-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Permintaan Masuk</small>
                    <h4 class="mb-0 text-warning">{{ $permintaan_masuk ?? 0 }}</h4>
                </div>
                <div class="icon bg-warning bg-opacity-10 p-3 rounded">
                    <i class="fas fa-clipboard-list text-warning"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card border-info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Pending Verifikasi</small>
                    <h4 class="mb-0 text-info">{{ $pending_verifikasi ?? 0 }}</h4>
                </div>
                <div class="icon bg-info bg-opacity-10 p-3 rounded">
                    <i class="fas fa-check-double text-info"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alert Stok Menipis -->
@if(isset($stok_menipis) && $stok_menipis->count() > 0)
<div class="card-custom mt-4">
    <h6 class="fw-bold text-danger mb-3"><i class="fas fa-exclamation-triangle me-2"></i> Alert Stok Menipis</h6>
    <div class="row g-2">
        @foreach($stok_menipis as $item)
        <div class="col-md-4">
            <div class="p-2 bg-danger bg-opacity-10 rounded border-start border-danger border-4">
                <span class="fw-semibold">{{ $item->nama_bahan }}</span>
                <small class="text-muted d-block">Stok: {{ $item->stok_saat_ini }} / Min: {{ $item->stok_minimum }}</small>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-3">
        <a href="{{ route('gudang.permintaan-pengadaan.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-shopping-cart me-1"></i> Buat Permintaan Pengadaan
        </a>
    </div>
</div>
@endif

<!-- Permintaan Produksi Masuk -->
<div class="card-custom mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0"><i class="fas fa-clipboard-list me-2"></i> Permintaan Produksi Masuk</h6>
        <a href="{{ route('gudang.permintaan-produksi.index') }}" class="btn btn-sm btn-outline-primary">
            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
    @if(isset($permintaan_produksi) && $permintaan_produksi->count() > 0)
        @foreach($permintaan_produksi as $item)
        <div class="border-bottom pb-2 mb-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-semibold">{{ $item->kode_pr }}</span>
                    <small class="text-muted d-block">{{ $item->userProduksi->nama_lengkap ?? '-' }} · {{ $item->created_at->format('d/m/Y H:i') }}</small>
                </div>
                <div class="text-end">
                    <span class="badge-status 
                        @if($item->status == 'Menunggu') badge-menunggu
                        @elseif($item->status == 'Diproses') badge-diproses
                        @else badge-selesai @endif">
                        {{ $item->status }}
                    </span>
                    <br>
                    <a href="{{ route('gudang.permintaan-produksi.proses', $item->id) }}" class="btn btn-sm btn-primary mt-1">
                        <i class="fas fa-play me-1"></i> Proses
                    </a>
                </div>
            </div>
            <div class="mt-1">
                @foreach($item->details as $detail)
                <small class="me-2">
                    {{ $detail->bahan->nama_bahan ?? '-' }}: 
                    <span class="fw-semibold">{{ $detail->jumlah_diminta }}</span> {{ $detail->bahan->satuan ?? '' }}
                </small>
                @endforeach
            </div>
        </div>
        @endforeach
    @else
        <div class="text-center text-muted py-3">Tidak ada permintaan masuk</div>
    @endif
</div>

<!-- Permintaan Pengadaan Aktif -->
<div class="card-custom mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0"><i class="fas fa-shopping-cart me-2"></i> Permintaan Pengadaan Aktif</h6>
        <a href="{{ route('gudang.permintaan-pengadaan-history.index') }}" class="btn btn-sm btn-outline-primary">
            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
    @if(isset($permintaan_pengadaan) && $permintaan_pengadaan->count() > 0)
        @foreach($permintaan_pengadaan as $item)
        <div class="border-bottom pb-2 mb-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-semibold">{{ $item->kode_pg }}</span>
                    <small class="text-muted d-block">{{ $item->created_at->format('d/m/Y H:i') }}</small>
                </div>
                <div class="text-end">
                    <span class="badge-status 
                        @if($item->status == 'Diproses') badge-diproses
                        @elseif($item->status == 'Sebagian') badge-sebagian
                        @else badge-selesai @endif">
                        {{ $item->status }}
                    </span>
                </div>
            </div>
            <div class="mt-1">
                @foreach($item->details as $detail)
                <small class="me-2">
                    {{ $detail->bahan->nama_bahan ?? '-' }}: 
                    <span class="fw-semibold">{{ $detail->jumlah_diminta }}</span> {{ $detail->bahan->satuan ?? '' }}
                    @if($detail->jumlah_datang > 0)
                    <span class="text-success">({{ $detail->jumlah_datang }} datang)</span>
                    @endif
                </small>
                @endforeach
            </div>
        </div>
        @endforeach
    @else
        <div class="text-center text-muted py-3">Tidak ada permintaan pengadaan aktif</div>
    @endif
</div>
@endsection
