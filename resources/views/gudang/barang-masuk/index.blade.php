@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('page-title', 'Barang Masuk')

@section('content')
<div class="card-custom">
    @forelse($barangMasuk as $item)
    <div class="border-bottom pb-3 mb-3">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="fw-bold mb-1">{{ $item->kode_brg_masuk }}</h6>
                <div class="text-muted small">
                    <span>Dari: {{ $item->userPengadaan->nama_lengkap }} (Pengadaan)</span>
                    <span class="mx-2">|</span>
                    <span>{{ $item->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @if($item->catatan)
                <p class="text-muted small mb-1">{{ $item->catatan }}</p>
                @endif
            </div>
            <div class="text-end d-flex flex-column align-items-end gap-1">
                <span class="badge-status 
                    @if($item->status == 'Draft') badge-menunggu
                    @elseif($item->status == 'Diverifikasi') badge-selesai
                    @else badge-ditolak @endif">
                    {{ $item->status }}
                </span>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#detail-bm-{{ $item->id }}" aria-expanded="false">
                    <i class="fas fa-eye me-1"></i> Detail
                </button>
                @if($item->status == 'Draft')
                <a href="{{ route('gudang.barang-masuk.verifikasi', $item->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-check-double me-1"></i> Verifikasi
                </a>
                @endif
            </div>
        </div>

        {{-- Panel Detail (Collapse) --}}
        <div class="collapse mt-3" id="detail-bm-{{ $item->id }}">
            <div class="card card-body bg-light border-0">
                <h6 class="fw-bold mb-2"><i class="fas fa-boxes me-1 text-success"></i> Detail Barang</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th>Nama Bahan</th>
                                <th>Kode Bahan</th>
                                <th>Jumlah Diterima</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $detail->bahan->nama_bahan ?? '-' }}</td>
                                <td><code>{{ $detail->bahan->kode_bahan ?? '-' }}</code></td>
                                <td><span class="fw-bold text-success">+{{ $detail->jumlah_diterima }}</span></td>
                                <td>{{ $detail->bahan->satuan ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center text-muted py-4">Tidak ada barang masuk</div>
    @endforelse
</div>
@endsection
