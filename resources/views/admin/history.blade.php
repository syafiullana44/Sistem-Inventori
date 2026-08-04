@extends('layouts.app')

@section('title', 'Laporan Semua Transaksi')
@section('page-title', '📊 Laporan Semua Transaksi')

@section('content')
<!-- Filter -->
<div class="card-custom mb-4">
    <form method="GET" action="{{ route('admin.history.index') }}" id="filterForm" class="row g-2 align-items-end">
        <div class="col-md-2">
            <label class="form-label small">Tanggal Awal</label>
            <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}" class="form-control form-control-sm" onchange="this.form.submit()">
        </div>
        <div class="col-md-2">
            <label class="form-label small">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="form-control form-control-sm" onchange="this.form.submit()">
        </div>
        <div class="col-md-2">
            <label class="form-label small">Bahan</label>
            <select name="bahan_id" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Bahan</option>
                @foreach($bahanList as $item)
                <option value="{{ $item->id }}" {{ request('bahan_id') == $item->id ? 'selected' : '' }}>
                    {{ $item->nama_bahan }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small">Jenis Transaksi</label>
            <select name="jenis" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="Permintaan Produksi" {{ request('jenis') == 'Permintaan Produksi' ? 'selected' : '' }}>Permintaan Produksi</option>
                <option value="Pengeluaran" {{ request('jenis') == 'Pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                <option value="Permintaan Pengadaan" {{ request('jenis') == 'Permintaan Pengadaan' ? 'selected' : '' }}>Permintaan Pengadaan</option>
                <option value="Barang Masuk" {{ request('jenis') == 'Barang Masuk' ? 'selected' : '' }}>Barang Masuk</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small">Jenis Mutasi</label>
            <select name="jenis_mutasi" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="Masuk" {{ request('jenis_mutasi') == 'Masuk' ? 'selected' : '' }}>Masuk</option>
                <option value="Keluar" {{ request('jenis_mutasi') == 'Keluar' ? 'selected' : '' }}>Keluar</option>
            </select>
        </div>
        <div class="col-md-2">
            <div class="d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('admin.history.index') }}" class="btn btn-sm btn-secondary w-100">
                    <i class="fas fa-undo me-1"></i> Reset
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Export & Info -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h6 class="fw-bold mb-0">Daftar Transaksi</h6>
        <small class="text-muted">Menampilkan {{ $history->total() }} transaksi</small>
    </div>
    <a href="{{ route('admin.history.export-pdf', request()->query()) }}" class="btn btn-sm btn-danger">
        <i class="fas fa-file-pdf me-1"></i> Export PDF
    </a>
</div>

<!-- Daftar Transaksi -->
<div class="card-custom">
    @forelse($history as $item)
    <div class="border-bottom pb-3 mb-3">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="fw-bold mb-1">
                    {{ $item->no_transaksi }}
                    <span class="badge bg-secondary ms-2" style="font-size: 10px;">{{ $item->jenis_transaksi }}</span>
                </h6>
                <div class="text-muted small">
                    <span><i class="fas fa-user me-1"></i> Dari: {{ $item->dari }}</span>
                    <span class="ms-3"><i class="fas fa-arrow-right me-1"></i> Untuk: {{ $item->untuk }}</span>
                    <span class="ms-3"><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y H:i') }}</span>
                    @if($item->tanggal_selesai && $item->jenis_transaksi != 'Permintaan Produksi')
                    <span class="ms-3"><i class="fas fa-check-circle text-success me-1"></i> Selesai: {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y H:i') }}</span>
                    @endif
                </div>
                @if($item->keterangan)
                <p class="text-muted small mb-1">📝 {{ $item->keterangan }}</p>
                @endif
            </div>
            <div class="text-end">
                <span class="badge-status 
                    @if($item->status == 'Menunggu' || $item->status == 'Draft') badge-menunggu
                    @elseif($item->status == 'Diproses') badge-diproses
                    @elseif($item->status == 'Selesai' || $item->status == 'Diverifikasi') badge-selesai
                    @elseif($item->status == 'Ditolak') badge-ditolak
                    @else badge-sebagian @endif">
                    {{ $item->status }}
                </span>
                <br>
                <span class="badge-status {{ $item->jenis_mutasi == 'Masuk' ? 'badge-selesai' : 'badge-diproses' }}">
                    {{ $item->jenis_mutasi }}
                </span>
                <!-- TOMBOL DETAIL -->
                <button type="button" class="btn btn-sm btn-outline-primary mt-1 d-block w-100" data-bs-toggle="collapse" data-bs-target="#detail-admin-{{ $loop->index }}">
                    <i class="fas fa-eye me-1"></i> Detail
                </button>
            </div>
        </div>


        <!-- DETAIL PANEL - COLLAPSE -->
        <div class="collapse mt-3" id="detail-admin-{{ $loop->index }}">
            <div class="card card-body bg-light">
                <h6 class="fw-bold mb-2">📋 Detail Transaksi</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No Transaksi</th>
                                <th>Jenis</th>
                                <th>Dari</th>
                                <th>Untuk</th>
                                <th>Tanggal</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $item->no_transaksi }}</td>
                                <td>{{ $item->jenis_transaksi }}</td>
                                <td>{{ $item->dari }}</td>
                                <td>{{ $item->untuk }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y H:i') }}</td>
                                <td>{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y H:i') : '-' }}</td>
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
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detail->nama_bahan }}</td>
                                <td>{{ $detail->satuan }}</td>
                                <td>
                                    @if($item->jenis_transaksi == 'Permintaan Produksi' || $item->jenis_transaksi == 'Pengeluaran')
                                        Diminta: {{ $detail->jumlah_diminta }} 
                                        @if(isset($detail->jumlah_dikeluarkan))
                                        | Dikeluarkan: <span class="fw-bold text-success">{{ $detail->jumlah_dikeluarkan }}</span>
                                        @endif
                                    @elseif($item->jenis_transaksi == 'Permintaan Pengadaan')
                                        Diminta: {{ $detail->jumlah_diminta }}
                                        @if(isset($detail->jumlah_datang))
                                        | Datang: <span class="fw-bold text-success">{{ $detail->jumlah_datang }}</span>
                                        @endif
                                    @elseif($item->jenis_transaksi == 'Barang Masuk')
                                        <span class="fw-bold text-success">+{{ $detail->jumlah_diterima }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-status 
                                        @if(isset($detail->status_item) && ($detail->status_item == 'Selesai' || $detail->status_item == 'Dikeluarkan' || $detail->status_item == 'Datang')) badge-selesai
                                        @elseif(isset($detail->status_item) && ($detail->status_item == 'Tidak Tersedia' || $detail->status_item == 'Tidak Datang')) badge-ditolak
                                        @else badge-menunggu @endif">
                                        {{ $detail->status_item ?? 'Selesai' }}
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
    <div class="text-center text-muted py-4">
        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
        Tidak ada data transaksi
    </div>
    @endforelse

    <div class="mt-3">
        {{ $history->appends(request()->query())->links() }}
    </div>
</div>
@endsection
