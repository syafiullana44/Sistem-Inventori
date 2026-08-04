@extends('layouts.app')

@section('title', 'Permintaan Masuk')
@section('page-title', '📥 Permintaan Pengadaan Masuk')

@section('content')
<div class="card-custom">
    @if(isset($permintaan) && $permintaan->count() > 0)
        @foreach($permintaan as $item)
        <div class="border-bottom pb-3 mb-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="fw-bold mb-1">{{ $item->kode_pg }}</h6>
                    <div class="text-muted small">
                        <span><i class="fas fa-user me-1"></i> Dari: {{ $item->userGudang->nama_lengkap ?? '-' }} (Gudang)</span>
                        <span class="mx-2">|</span>
                        <span><i class="far fa-calendar-alt me-1"></i> {{ $item->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($item->keterangan)
                    <p class="text-muted small mb-1">📝 {{ $item->keterangan }}</p>
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
                    <!-- TAMBAH TOMBOL DETAIL -->
                    <button type="button" class="btn btn-sm btn-outline-primary mt-1" data-bs-toggle="collapse" data-bs-target="#detail-pg-aktif-{{ $item->id }}">
                        <i class="fas fa-eye me-1"></i> Detail
                    </button>
                </div>
            </div>
            <div class="mt-2">
                @foreach($item->details as $detail)
                <span class="badge bg-light text-dark me-1 p-2">
                    {{ $detail->bahan->nama_bahan ?? '-' }}: {{ $detail->jumlah_diminta }} {{ $detail->bahan->satuan ?? '' }}
                    @if($detail->jumlah_datang > 0)
                    <span class="text-success">({{ $detail->jumlah_datang }} datang)</span>
                    @endif
                </span>
                @endforeach
            </div>

            <!-- DETAIL PANEL - COLLAPSE -->
            <div class="collapse mt-3" id="detail-pg-aktif-{{ $item->id }}">
                <div class="card card-body bg-light">
                    <h6 class="fw-bold mb-2">📋 Detail Permintaan</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode PG</th>
                                    <th>Dari Gudang</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Status</th>
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
                                            @if($item->status == 'Diproses') badge-diproses
                                            @elseif($item->status == 'Sebagian') badge-sebagian
                                            @else badge-selesai @endif">
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
        @endforeach
    @else
        <div class="text-center text-muted py-4">Tidak ada permintaan yang perlu diproses</div>
    @endif
</div>
@endsection
