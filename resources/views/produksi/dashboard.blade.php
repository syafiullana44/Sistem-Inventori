@extends('layouts.app')

@section('title', 'Produksi Dashboard')
@section('page-title', 'Dashboard Produksi')

@section('content')
<div class="row">
    <div class="col-md-7">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-boxes me-2"></i> Stok Bahan</h6>
                <a href="{{ route('produksi.permintaan.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Buat Permintaan
                </a>
            </div>
            @foreach($bahan as $item)
            <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                <div>
                    <span class="fw-semibold">{{ $item->nama_bahan }}</span>
                    <small class="text-muted d-block">{{ $item->kode_bahan }} · {{ $item->satuan }}</small>
                </div>
                <div class="text-end">
                    <span class="fw-bold {{ $item->stok_saat_ini <= $item->stok_minimum ? 'text-danger' : 'text-success' }}">
                        {{ $item->stok_saat_ini }}
                    </span>
                    <small class="text-muted d-block">Min: {{ $item->stok_minimum }}</small>
                </div>
            </div>
            @endforeach
            <div class="mt-3 d-flex justify-content-end">
                {{ $bahan->links() }}
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card-custom">
            <h6 class="fw-bold mb-3"><i class="fas fa-clipboard-list me-2"></i> Laporan Permintaan</h6>
            @forelse($permintaan as $item)
            <div class="border-bottom pb-2 mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fw-semibold">{{ $item->kode_pr }}</span>
                        <small class="text-muted d-block">{{ $item->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <span class="badge-status 
                        @if($item->status == 'Menunggu') badge-menunggu
                        @elseif($item->status == 'Diproses') badge-diproses
                        @elseif($item->status == 'Selesai') badge-selesai
                        @else badge-ditolak @endif">
                        {{ $item->status }}
                    </span>
                </div>
                @if($item->status == 'Ditolak' && $item->keterangan)
                <small class="text-danger">Alasan: {{ $item->keterangan }}</small>
                @endif
            </div>
            @empty
            <div class="text-center text-muted py-3">Belum ada permintaan</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
