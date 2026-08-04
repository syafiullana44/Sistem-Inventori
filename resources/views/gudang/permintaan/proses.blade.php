@extends('layouts.app')

@section('title', 'Proses Permintaan')
@section('page-title', '⚙️ Proses Permintaan Produksi')

@section('content')
<div class="card-custom">
    <div class="mb-3">
        <h5 class="fw-bold">{{ $permintaan->kode_pr ?? '-' }}</h5>
        <p class="text-muted mb-1">
            <i class="fas fa-user me-1"></i> Dari: {{ $permintaan->userProduksi->nama_lengkap ?? '-' }}
        </p>
        <p class="text-muted">
            <i class="far fa-calendar-alt me-1"></i> Tanggal: {{ $permintaan->created_at->format('d/m/Y H:i') }}
        </p>
        @if($permintaan->keterangan)
        <p class="text-muted small">📝 {{ $permintaan->keterangan }}</p>
        @endif
    </div>

    <h6 class="fw-bold mb-2">Cek Stok Bahan:</h6>
    @foreach($cekStok as $item)
    <div class="d-flex justify-content-between align-items-center p-2 rounded mb-2 {{ $item['cukup'] ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
        <div>
            <span class="fw-semibold">{{ $item['bahan'] }}</span>
            <small class="text-muted d-block">Diminta: {{ $item['diminta'] }} {{ $item['satuan'] }}</small>
        </div>
        <div class="text-end">
            <span class="fw-bold {{ $item['cukup'] ? 'text-success' : 'text-danger' }}">
                {{ $item['tersedia'] }} {{ $item['satuan'] }}
            </span>
            <small class="text-muted d-block">Tersedia</small>
            @if(!$item['cukup'])
            <small class="text-danger">⚠️ Kurang {{ $item['diminta'] - $item['tersedia'] }}</small>
            @endif
        </div>
    </div>
    @endforeach

    @if($stokCukup)
    <form action="{{ route('gudang.permintaan-produksi.proses-fifo', $permintaan->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i> Proses FIFO</button>
        <a href="{{ route('gudang.permintaan-produksi.index') }}" class="btn btn-secondary">Batal</a>
    </form>
    @else
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-1"></i> Stok tidak mencukupi! Silakan tolak permintaan ini.
    </div>
    <button onclick="showTolak()" class="btn btn-danger"><i class="fas fa-times me-1"></i> Tolak</button>
    <a href="{{ route('gudang.permintaan-produksi.index') }}" class="btn btn-secondary">Kembali</a>

    <form id="formTolak" action="{{ route('gudang.permintaan-produksi.tolak', $permintaan->id) }}" method="POST" class="mt-3 d-none">
        @csrf
        <div class="row g-2">
            <div class="col-md-8">
                <input type="text" name="alasan" class="form-control" placeholder="Alasan penolakan" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-danger w-100">Konfirmasi Tolak</button>
            </div>
        </div>
    </form>
    @endif
</div>

<script>
    function showTolak() {
        document.getElementById('formTolak').classList.toggle('d-none');
    }
</script>
@endsection
