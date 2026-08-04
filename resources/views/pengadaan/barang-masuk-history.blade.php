@extends('layouts.app')

@section('title', 'Laporan Input Barang')
@section('page-title', '📦 Laporan Input Barang (Pengadaan)')

@section('content')
<!-- Filter -->
<div class="card-custom mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small">Tanggal Input Awal</label>
            <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}" class="form-control form-control-sm" onchange="this.form.submit()">
        </div>
        <div class="col-md-3">
            <label class="form-label small">Tanggal Input Akhir</label>
            <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="form-control form-control-sm" onchange="this.form.submit()">
        </div>
        <div class="col-md-3">
            <label class="form-label small">Status</label>
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft (Menunggu Verifikasi)</option>
                <option value="Diverifikasi" {{ request('status') == 'Diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="col-md-3">
            <a href="{{ route('pengadaan.barang-masuk-history.index') }}" class="btn btn-sm btn-secondary w-100">Reset</a>
        </div>
    </form>
</div>

<!-- Daftar Input Barang -->
<div class="card-custom">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold mb-0">Daftar Input Barang</h6>
    <a href="{{ route('pengadaan.barang-masuk-history.export-pdf', request()->query()) }}" class="btn btn-sm btn-danger">
        <i class="fas fa-file-pdf me-1"></i> Export PDF
    </a>
</div>
    @forelse($barangMasuk as $item)
    <div class="border-bottom pb-3 mb-3">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="fw-bold mb-1">{{ $item->kode_brg_masuk }}</h6>
                <div class="text-muted small">
                    <span><i class="fas fa-user me-1"></i> Untuk Gudang: {{ $item->userGudang->nama_lengkap ?? '-' }}</span>
                    <span class="ms-3"><i class="far fa-calendar-alt me-1"></i> Diinput: {{ $item->created_at->format('d/m/Y H:i') }}</span>
                    @if($item->tanggal_diverifikasi)
                    <span class="ms-3"><i class="fas fa-check-circle text-success me-1"></i> Diverifikasi: {{ \Carbon\Carbon::parse($item->tanggal_diverifikasi)->format('d/m/Y H:i') }}</span>
                    @endif
                </div>
                @if($item->catatan)
                <p class="text-muted small mb-1">📝 {{ $item->catatan }}</p>
                @endif
                <div class="mt-1">
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-clipboard-list me-1"></i> {{ $item->permintaanGudang->kode_pg ?? '-' }}
                    </span>
                </div>
            </div>
            <div class="text-end">
                <span class="badge-status 
                    @if($item->status == 'Draft') badge-menunggu
                    @elseif($item->status == 'Diverifikasi') badge-selesai
                    @else badge-ditolak @endif">
                    {{ $item->status }}
                </span>
                <!-- TAMBAH TOMBOL DETAIL -->
                <button type="button" class="btn btn-sm btn-outline-primary mt-1" data-bs-toggle="collapse" data-bs-target="#detail-bm-{{ $item->id }}">
                    <i class="fas fa-eye me-1"></i> Detail
                </button>
            </div>
        </div>


        <!-- DETAIL PANEL - COLLAPSE -->
        <div class="collapse mt-3" id="detail-bm-{{ $item->id }}">
            <div class="card card-body bg-light">
                <h6 class="fw-bold mb-2">📋 Detail Input Barang</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode BM</th>
                                <th>Kode PG</th>
                                <th>Tanggal Input</th>
                                <th>Status</th>
                                <th>Tanggal Verifikasi</th>
                                <th>Diverifikasi Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->kode_brg_masuk }}</td>
                                <td>{{ $item->permintaanGudang->kode_pg ?? '-' }}</td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge-status 
                                        @if($item->status == 'Draft') badge-menunggu
                                        @elseif($item->status == 'Diverifikasi') badge-selesai
                                        @else badge-ditolak @endif">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td>{{ $item->tanggal_diverifikasi ? \Carbon\Carbon::parse($item->tanggal_diverifikasi)->format('d/m/Y H:i') : '-' }}</td>
                                <td>{{ $item->userGudang->nama_lengkap ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h6 class="fw-bold mt-3 mb-2">📦 Detail Bahan</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Bahan</th>
                                <th>Satuan</th>
                                <th>Jumlah Diterima</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detail->bahan->nama_bahan ?? '-' }}</td>
                                <td>{{ $detail->bahan->satuan ?? '-' }}</td>
                                <td>
                                    <span class="fw-bold text-success">+{{ $detail->jumlah_diterima }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center text-muted py-4">Belum ada input barang</div>
    @endforelse

    <div class="mt-3">
        {{ $barangMasuk->appends(request()->query())->links() }}
    </div>
</div>
@endsection
