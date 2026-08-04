@extends('layouts.app')

@section('title', 'Laporan Pengeluaran Barang')
@section('page-title', '📤 Laporan Pengeluaran Barang')

@section('content')
<!-- Filter -->
<div class="card-custom mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small">Tanggal Selesai Awal</label>
            <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}" class="form-control form-control-sm" onchange="this.form.submit()">
        </div>
        <div class="col-md-4">
            <label class="form-label small">Tanggal Selesai Akhir</label>
            <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="form-control form-control-sm" onchange="this.form.submit()">
        </div>
        <div class="col-md-4">
            <a href="{{ route('gudang.pengeluaran-history.index') }}" class="btn btn-sm btn-secondary w-100">Reset</a>
        </div>
    </form>
</div>

<!-- Daftar Pengeluaran -->
<div class="card-custom">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold mb-0">Daftar Pengeluaran</h6>
    <a href="{{ route('gudang.pengeluaran-history.export-pdf', request()->query()) }}" class="btn btn-sm btn-danger">
        <i class="fas fa-file-pdf me-1"></i> Export PDF
    </a>
</div>
    @forelse($pengeluaran as $item)
    <div class="border-bottom pb-3 mb-3">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="fw-bold mb-1">{{ $item->kode_pr }}</h6>
                <div class="text-muted small">
                    <span><i class="fas fa-user me-1"></i> Produksi: {{ $item->userProduksi->nama_lengkap ?? '-' }}</span>
                    <span class="ms-3"><i class="fas fa-check-circle text-success me-1"></i> Selesai: {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y H:i') }}</span>
                </div>
                @if($item->keterangan)
                <p class="text-muted small mb-1">📝 {{ $item->keterangan }}</p>
                @endif
            </div>
            <div>
                <span class="badge-status badge-selesai">Selesai</span>
                <!-- TAMBAH TOMBOL DETAIL -->
                <button type="button" class="btn btn-sm btn-outline-primary mt-1" data-bs-toggle="collapse" data-bs-target="#detail-{{ $item->id }}">
                    <i class="fas fa-eye me-1"></i> Detail
                </button>
            </div>
        </div>


        <!-- DETAIL PANEL - COLLAPSE -->
        <div class="collapse mt-3" id="detail-{{ $item->id }}">
            <div class="card card-body bg-light">
                <h6 class="fw-bold mb-2">📋 Detail Pengeluaran</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode PR</th>
                                <th>Dari Produksi</th>
                                <th>Tanggal Dibuat</th>
                                <th>Tanggal Diproses</th>
                                <th>Tanggal Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->kode_pr }}</td>
                                <td>{{ $item->userProduksi->nama_lengkap ?? '-' }}</td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $item->tanggal_diproses ? \Carbon\Carbon::parse($item->tanggal_diproses)->format('d/m/Y H:i') : '-' }}</td>
                                <td>{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y H:i') : '-' }}</td>
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
                                <th>Diminta</th>
                                <th>Dikeluarkan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detail->bahan->nama_bahan ?? '-' }}</td>
                                <td>{{ $detail->bahan->satuan ?? '-' }}</td>
                                <td>{{ $detail->jumlah_diminta }}</td>
                                <td>
                                    <span class="fw-bold text-success">{{ $detail->jumlah_dikeluarkan }}</span>
                                </td>
                                <td>
                                    <span class="badge-status 
                                        @if($detail->status_item == 'Dikeluarkan') badge-selesai
                                        @elseif($detail->status_item == 'Tidak Tersedia') badge-ditolak
                                        @else badge-menunggu @endif">
                                        {{ $detail->status_item ?? 'Menunggu' }}
                                    </span>
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
    <div class="text-center text-muted py-4">Belum ada pengeluaran barang</div>
    @endforelse

    <div class="mt-3">
        {{ $pengeluaran->appends(request()->query())->links() }}
    </div>
</div>
@endsection
