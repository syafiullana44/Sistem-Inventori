@extends('layouts.app')

@section('title', 'Permintaan Produksi')
@section('page-title', '📥 Permintaan Produksi Masuk')

@section('content')
<div class="card-custom">
    @if(isset($permintaan) && $permintaan->count() > 0)
        @foreach($permintaan as $item)
        <div class="border-bottom pb-3 mb-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="fw-bold mb-1">{{ $item->kode_pr }}</h6>
                    <div class="text-muted small">
                        <span><i class="fas fa-user me-1"></i> Dari: {{ $item->userProduksi->nama_lengkap ?? '-' }}</span>
                        <span class="mx-2">|</span>
                        <span><i class="far fa-calendar-alt me-1"></i> {{ $item->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($item->keterangan)
                    <p class="text-muted small mb-1">📝 {{ $item->keterangan }}</p>
                    @endif
                </div>
                <div class="text-end">
                    <span class="badge-status 
                        @if($item->status == 'Menunggu') badge-menunggu
                        @elseif($item->status == 'Diproses') badge-diproses
                        @elseif($item->status == 'Selesai') badge-selesai
                        @else badge-ditolak @endif">
                        {{ $item->status }}
                    </span>
                    @if($item->status == 'Menunggu' || $item->status == 'Diproses')
                    <br>
                    <!-- PERBAIKI: gunakan route yang benar -->
                    <a href="{{ route('gudang.permintaan-produksi.proses', $item->id) }}" class="btn btn-sm btn-primary mt-1">
                        <i class="fas fa-play me-1"></i> Proses
                    </a>
                    @endif
                </div>
            </div>
            <div class="mt-2">
                @foreach($item->details as $detail)
                <span class="badge bg-light text-dark me-1 p-2">
                    {{ $detail->bahan->nama_bahan ?? '-' }}: 
                    <span class="fw-semibold">{{ $detail->jumlah_diminta }}</span> {{ $detail->bahan->satuan ?? '' }}
                    @if($detail->jumlah_dikeluarkan > 0)
                    <span class="text-success">({{ $detail->jumlah_dikeluarkan }} keluar)</span>
                    @endif
                </span>
                @endforeach
            </div>
            @if($item->status == 'Ditolak' && $item->keterangan)
            <div class="mt-2 text-danger small">
                <i class="fas fa-exclamation-circle me-1"></i> Alasan: {{ $item->keterangan }}
            </div>
            @endif
        </div>
        @endforeach
    @else
        <div class="text-center text-muted py-4">
            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
            Tidak ada permintaan produksi yang masuk
        </div>
    @endif
</div>
@endsection
