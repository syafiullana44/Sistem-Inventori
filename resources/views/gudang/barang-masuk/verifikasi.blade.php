@extends('layouts.app')

@section('title', 'Verifikasi Barang Masuk')
@section('page-title', 'Verifikasi Barang Masuk')

@section('content')
<div class="card-custom">
    <div class="mb-3">
        <h5 class="fw-bold">{{ $barangMasuk->kode_brg_masuk }}</h5>
        <p class="text-muted">Dari: {{ $barangMasuk->userPengadaan->nama_lengkap }} (Pengadaan)</p>
        <p class="text-muted">Tanggal: {{ $barangMasuk->created_at->format('d/m/Y H:i') }}</p>
        @if($barangMasuk->catatan)
        <p class="text-muted small">{{ $barangMasuk->catatan }}</p>
        @endif
    </div>

    <h6 class="fw-bold mb-2">Daftar Barang:</h6>
    @foreach($barangMasuk->details as $detail)
    <div class="d-flex justify-content-between align-items-center p-2 bg-success bg-opacity-10 rounded mb-2 border-start border-success border-4">
        <div>
            <span class="fw-semibold">{{ $detail->bahan->nama_bahan }}</span>
            <small class="text-muted d-block">{{ $detail->bahan->kode_bahan }}</small>
        </div>
        <div class="text-end">
            <span class="fw-bold text-success">+{{ $detail->jumlah_diterima }}</span>
            <small class="text-muted d-block">{{ $detail->bahan->satuan }}</small>
        </div>
    </div>
    @endforeach

    <div class="alert alert-warning mt-3">
        <i class="fas fa-info-circle me-1"></i> Pastikan barang fisik sudah sesuai. Stok akan bertambah otomatis setelah diverifikasi.
    </div>

    <div class="d-flex gap-2">
        <form action="{{ route('gudang.barang-masuk.konfirmasi', $barangMasuk->id) }}" method="POST" id="formKonfirmasi">
            @csrf
            <button type="submit" id="btnKonfirmasi" class="btn btn-success">
                <i class="fas fa-check me-1"></i> Konfirmasi & Update Stok
            </button>
        </form>
        <form action="{{ route('gudang.barang-masuk.tolak', $barangMasuk->id) }}" method="POST" id="formTolak">
            @csrf
            <button type="submit" id="btnTolak" class="btn btn-danger" onclick="return confirm('Tolak barang masuk ini?')">
                <i class="fas fa-times me-1"></i> Tolak
            </button>
        </form>
        <a href="{{ route('gudang.barang-masuk.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<script>
    document.getElementById('formKonfirmasi').addEventListener('submit', function () {
        const btn = document.getElementById('btnKonfirmasi');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Memproses...';
        // Disable tombol tolak juga
        document.getElementById('btnTolak').disabled = true;
    });

    document.getElementById('formTolak').addEventListener('submit', function () {
        const btn = document.getElementById('btnTolak');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menolak...';
        // Disable tombol konfirmasi juga
        document.getElementById('btnKonfirmasi').disabled = true;
    });
</script>
@endsection
