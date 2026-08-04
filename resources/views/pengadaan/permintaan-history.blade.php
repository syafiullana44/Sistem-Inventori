@extends('layouts.app')

@section('title', 'Laporan Permintaan Pengadaan')
@section('page-title', '📋 Laporan Permintaan Pengadaan (Diproses)')

@section('content')
<!-- Filter -->
<div class="card-custom mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small">Tanggal Awal</label>
            <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}" class="form-control form-control-sm" onchange="this.form.submit()">
        </div>
        <div class="col-md-3">
            <label class="form-label small">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="form-control form-control-sm" onchange="this.form.submit()">
        </div>
        <div class="col-md-3">
            <label class="form-label small">Status</label>
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Sebagian" {{ request('status') == 'Sebagian' ? 'selected' : '' }}>Sebagian</option>
                <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="col-md-3">
            <a href="{{ route('pengadaan.permintaan-history.index') }}" class="btn btn-sm btn-secondary w-100">Reset</a>
        </div>
    </form>
</div>

<!-- Daftar Permintaan -->
<div class="card-custom">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold mb-0">Daftar Permintaan</h6>
    <a href="{{ route('pengadaan.permintaan-history.export-pdf', request()->query()) }}" class="btn btn-sm btn-danger">
        <i class="fas fa-file-pdf me-1"></i> Export PDF
    </a>
</div>
    @forelse($permintaan as $item)
    <div class="border-bottom pb-3 mb-3">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="fw-bold mb-1">{{ $item->kode_pg }}</h6>
                <div class="text-muted small">
                    <span><i class="fas fa-user me-1"></i> Dari Gudang: {{ $item->userGudang->nama_lengkap ?? '-' }}</span>
                    <span class="ms-3"><i class="far fa-calendar-alt me-1"></i> Dibuat: {{ $item->created_at->format('d/m/Y H:i') }}</span>
                    @if($item->tanggal_selesai)
                    <span class="ms-3"><i class="fas fa-check me-1"></i> Selesai: {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y H:i') }}</span>
                    @endif
                </div>
                @if($item->keterangan)
                <p class="text-muted small mb-1">📝 {{ $item->keterangan }}</p>
                @endif
            </div>
            <div class="text-end">
                <span class="badge-status 
                    @if($item->status == 'Selesai') badge-selesai
                    @elseif($item->status == 'Sebagian') badge-sebagian
                    @else badge-ditolak @endif">
                    {{ $item->status }}
                </span>
                <!-- TAMBAH TOMBOL DETAIL -->
                <button type="button" class="btn btn-sm btn-outline-primary mt-1" data-bs-toggle="collapse" data-bs-target="#detail-pg-{{ $item->id }}">
                    <i class="fas fa-eye me-1"></i> Detail
                </button>
            </div>
        </div>


        <!-- DETAIL PANEL - COLLAPSE -->
        <div class="collapse mt-3" id="detail-pg-{{ $item->id }}">
            <div class="card card-body bg-light">
                <h6 class="fw-bold mb-2">📋 Detail Permintaan Pengadaan</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode PG</th>
                                <th>Dari Gudang</th>
                                <th>Tanggal Dibuat</th>
                                <th>Status</th>
                                <th>Tanggal Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->kode_pg }}</td>
                                <td>{{ $item->userGudang->nama_lengkap ?? '-' }}</td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge-status 
                                        @if($item->status == 'Selesai') badge-selesai
                                        @elseif($item->status == 'Sebagian') badge-sebagian
                                        @else badge-ditolak @endif">
                                        {{ $item->status }}
                                    </span>
                                </td>
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
                                <th>Datang</th>
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
                                    <span class="fw-bold text-success">{{ $detail->jumlah_datang }}</span>
                                </td>
                                <td>
                                    <span class="badge-status 
                                        @if($detail->status_item == 'Datang') badge-selesai
                                        @elseif($detail->status_item == 'Tidak Datang') badge-ditolak
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
    <div class="text-center text-muted py-4">Belum ada permintaan pengadaan yang diproses</div>
    @endforelse

    <div class="mt-3">
        {{ $permintaan->appends(request()->query())->links() }}
    </div>
</div>
@endsection
