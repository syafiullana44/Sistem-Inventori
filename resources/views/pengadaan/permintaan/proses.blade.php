@extends('layouts.app')

@section('title', 'Proses Permintaan')
@section('page-title', '⚙️ Proses Permintaan Pengadaan')

@section('content')
<div class="card-custom">
    <div class="mb-3">
        <h5 class="fw-bold">{{ $permintaan->kode_pg }}</h5>
        <p class="text-muted">
            <i class="fas fa-user me-1"></i> Dari: {{ $permintaan->userGudang->nama_lengkap ?? '-' }} (Gudang)
        </p>
        <p class="text-muted">
            <i class="far fa-calendar-alt me-1"></i> Tanggal: {{ $permintaan->created_at->format('d/m/Y H:i') }}
        </p>
        @if($permintaan->keterangan)
        <p class="text-muted small">📝 {{ $permintaan->keterangan }}</p>
        @endif
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle me-1"></i> 
        Input jumlah barang yang datang. <strong>Jika jumlah kurang dari permintaan, status akan menjadi "Sebagian"</strong>.
    </div>

    <form method="POST" action="{{ route('pengadaan.barang-masuk.store', $permintaan->id) }}" id="formBarangMasuk">
        @csrf

        <h6 class="fw-bold mb-2">Input Barang Datang:</h6>
        @foreach($permintaan->details as $detail)
        <div class="row g-2 align-items-center p-2 bg-light rounded mb-2">
            <div class="col-md-5">
                <span class="fw-semibold">{{ $detail->bahan->nama_bahan ?? '-' }}</span>
                <small class="text-muted d-block">Diminta: {{ $detail->jumlah_diminta }} {{ $detail->bahan->satuan ?? '' }}</small>
                @if($detail->jumlah_datang > 0)
                <small class="text-success">Sudah datang: {{ $detail->jumlah_datang }}</small>
                @endif
            </div>
            <div class="col-md-3">
                <input type="number" name="jumlah[{{ $detail->id }}]" 
                       class="form-control" 
                       value="{{ $detail->jumlah_diminta - $detail->jumlah_datang }}" 
                       min="0" max="{{ $detail->jumlah_diminta - $detail->jumlah_datang }}"
                       onchange="checkJumlah(this, {{ $detail->jumlah_diminta }}, '{{ $detail->id }}')">
            </div>
            <div class="col-md-3">
                <span id="status-{{ $detail->id }}" class="text-success">✅ Sesuai</span>
            </div>
        </div>
        @endforeach

        <div class="mb-3 mt-3">
            <label class="form-label">Catatan (Opsional)</label>
            <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan untuk gudang..."></textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" id="btnKirimBM" class="btn btn-success">
                <i class="fas fa-paper-plane me-1"></i> Kirim ke Gudang
            </button>
            <a href="{{ route('pengadaan.permintaan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>

<script>
    // Anti double-submit
    document.getElementById('formBarangMasuk').addEventListener('submit', function () {
        const btn = document.getElementById('btnKirimBM');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Mengirim...';
    });

    function checkJumlah(input, diminta, id) {
        const value = parseInt(input.value) || 0;
        const statusEl = document.getElementById('status-' + id);

        if (value === 0) {
            statusEl.innerHTML = '❌ Tidak Datang';
            statusEl.className = 'text-danger';
        } else if (value < diminta) {
            statusEl.innerHTML = '⚠️ Kurang (' + value + '/' + diminta + ')';
            statusEl.className = 'text-warning';
        } else {
            statusEl.innerHTML = '✅ Sesuai';
            statusEl.className = 'text-success';
        }
    }
</script>
@endsection
