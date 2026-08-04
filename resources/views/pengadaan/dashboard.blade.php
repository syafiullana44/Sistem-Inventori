@extends('layouts.app')

@section('title', 'Dashboard Pengadaan')
@section('page-title', 'Dashboard Pengadaan')

@section('content')
<div class="row g-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Menunggu</small>
                    <h4 class="mb-0 text-warning">{{ $menunggu }}</h4>
                </div>
                <div class="icon bg-warning bg-opacity-10 p-3 rounded">
                    <i class="fas fa-clock text-warning"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Sebagian</small>
                    <h4 class="mb-0 text-info">{{ $sebagian }}</h4>
                </div>
                <div class="icon bg-info bg-opacity-10 p-3 rounded">
                    <i class="fas fa-spinner text-info"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Selesai</small>
                    <h4 class="mb-0 text-success">{{ $selesai }}</h4>
                </div>
                <div class="icon bg-success bg-opacity-10 p-3 rounded">
                    <i class="fas fa-check-circle text-success"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Barang Masuk Draft</small>
                    <h4 class="mb-0 text-danger">{{ $barang_masuk_draft }}</h4>
                </div>
                <div class="icon bg-danger bg-opacity-10 p-3 rounded">
                    <i class="fas fa-box text-danger"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Permintaan -->
<div class="card-custom mt-4">
    <h6 class="fw-bold mb-3"><i class="fas fa-clipboard-list me-2"></i> Daftar Permintaan</h6>
    @forelse($permintaan as $item)
    <div class="border-bottom pb-3 mb-3">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="fw-bold mb-1">{{ $item->kode_pg }}</h6>
                <div class="text-muted small">
                    <span>Dari: {{ $item->userGudang->nama_lengkap }} (Gudang)</span>
                    <span class="mx-2">|</span>
                    <span>{{ $item->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @if($item->keterangan)
                <p class="text-muted small mb-1">{{ $item->keterangan }}</p>
                @endif
            </div>
            <div class="text-end">
                <span class="badge-status 
                    @if($item->status == 'Diproses') badge-diproses
                    @elseif($item->status == 'Sebagian') badge-sebagian
                    @else badge-selesai @endif">
                    {{ $item->status }}
                </span>
                @if($item->status == 'Diproses' || $item->status == 'Sebagian')
                <br>
                <a href="{{ route('pengadaan.permintaan.proses', $item->id) }}" class="btn btn-sm btn-primary mt-1">
                    <i class="fas fa-play me-1"></i> Proses
                </a>
                @endif
            </div>
        </div>
        <div class="mt-2">
            @foreach($item->details as $detail)
            <span class="badge bg-light text-dark me-1 p-2">
                {{ $detail->bahan->nama_bahan }}: {{ $detail->jumlah_diminta }} {{ $detail->bahan->satuan }}
                @if($detail->jumlah_datang > 0)
                <span class="text-success">({{ $detail->jumlah_datang }} datang)</span>
                @endif
            </span>
            @endforeach
        </div>
    </div>
    @empty
    <div class="text-center text-muted py-4">Tidak ada permintaan</div>
    @endforelse
</div>
@endsection
