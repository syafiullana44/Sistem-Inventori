@extends('layouts.app')

@section('title', 'Permintaan Pengadaan')
@section('page-title', 'Buat Permintaan Pengadaan')

@section('content')
<div class="card-custom">
    <div class="alert alert-warning">
        <i class="fas fa-info-circle me-1"></i> Pilih bahan untuk mengajukan permintaan
    </div>

    <form method="POST" action="{{ route('gudang.permintaan-pengadaan.store') }}" id="formPermintaan">
        @csrf

        <div class="mb-3">
            <label class="form-label">Catatan / Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label mb-0">Pilih Bahan</label>
                <button type="button" id="tambahBahan" class="btn btn-sm btn-success">
                    <i class="fas fa-plus me-1"></i> Tambah Bahan
                </button>
            </div>

            <div id="daftarBahan">
                <div class="row bahan-row align-items-end g-2 p-2 bg-light rounded mb-2">
                    <div class="col-md-6">
                        <select name="bahan[0][id]" class="form-select" required>
                            <option value="">Pilih Bahan</option>
                            @foreach($semuaBahan as $item)
                            <option value="{{ $item->id }}" data-satuan="{{ $item->satuan }}"
                                {{ $item->stok_saat_ini <= $item->stok_minimum ? 'style=font-weight:bold;color:#dc2626' : '' }}>
                                {{ $item->kode_bahan }} - {{ $item->nama_bahan }} {{ $item->deskripsi ? '- ' . $item->deskripsi : '' }}
                                (Stok: {{ $item->stok_saat_ini }} / Min: {{ $item->stok_minimum }})
                                {{ $item->stok_saat_ini <= $item->stok_minimum ? '⚠️' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="bahan[0][jumlah]" class="form-control" placeholder="Jumlah" required min="1">
                    </div>
                    <div class="col-md-2 text-center satuan-display text-muted"></div>
                    <div class="col-md-1 text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger hapus-bahan">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('gudang.dashboard') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" id="btnKirimPG" class="btn btn-primary">
                <i class="fas fa-paper-plane me-1"></i> Kirim
            </button>
        </div>
    </form>
</div>

<script>
    let index = 1;

    // Anti double-submit
    document.getElementById('formPermintaan').addEventListener('submit', function () {
        const btn = document.getElementById('btnKirimPG');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Mengirim...';
    });

    document.getElementById('tambahBahan').addEventListener('click', function() {
        const container = document.getElementById('daftarBahan');
        const firstRow = container.querySelector('.bahan-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelectorAll('select, input').forEach(el => el.value = '');
        newRow.querySelector('.satuan-display').textContent = '';

        newRow.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, '[' + index + ']');
        });

        container.appendChild(newRow);
        index++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.hapus-bahan')) {
            const row = e.target.closest('.bahan-row');
            if (document.querySelectorAll('.bahan-row').length > 1) {
                row.remove();
            } else {
                alert('Minimal 1 bahan harus dipilih!');
            }
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.matches('select[name*="[id]"]')) {
            const row = e.target.closest('.bahan-row');
            const select = e.target;
            const satuanDisplay = row.querySelector('.satuan-display');
            const selected = select.options[select.selectedIndex];
            const satuan = selected.getAttribute('data-satuan') || '';
            satuanDisplay.textContent = satuan;
        }
    });
</script>
@endsection
