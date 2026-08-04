<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * { font-family: Arial, sans-serif; font-size: 10px; }
        body { padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { font-size: 16px; margin: 0; }
        .header p { font-size: 10px; color: #666; margin: 5px 0 0; }
        table { width: 100%; border-collapse: collapse; }
        table th {
            background: #1a1a2e;
            color: #fff;
            padding: 6px 8px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
        }
        table td { padding: 5px 8px; border-bottom: 1px solid #ddd; }
        table tr:nth-child(even) { background: #f9f9f9; }
        .text-center { text-align: center; }
        .badge-selesai { color: #065f46; font-weight: bold; }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .total-row { background: #e5e7eb !important; font-weight: bold; }
        .total-row td { border-top: 2px solid #333; }
    </style>
</head>
<body>

<div class="header">
    <h2> SR Wood Craft</h2>
    <h3>{{ $title }}</h3>
    <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>Kode BM</th>
            <th>Kode PG</th>
            <th>Pengadaan</th>
            <th>Tanggal Input</th>
            <th>Tanggal Verifikasi</th>
            <th>Bahan</th>
            <th>Jumlah</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($barangMasuk as $item)
            @foreach($item->details as $index => $detail)
            <tr>
                @if($index == 0)
                    <td rowspan="{{ $item->details->count() }}">{{ $item->kode_brg_masuk }}</td>
                    <td rowspan="{{ $item->details->count() }}">{{ $item->permintaanGudang->kode_pg ?? '-' }}</td>
                    <td rowspan="{{ $item->details->count() }}">{{ $item->userPengadaan->nama_lengkap ?? '-' }}</td>
                    <td rowspan="{{ $item->details->count() }}">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td rowspan="{{ $item->details->count() }}">{{ $item->tanggal_diverifikasi ? \Carbon\Carbon::parse($item->tanggal_diverifikasi)->format('d/m/Y H:i') : '-' }}</td>
                @endif
                <td>{{ $detail->bahan->nama_bahan ?? '-' }}</td>
                <td class="text-center">+{{ $detail->jumlah_diterima }}</td>
                @if($index == 0)
                    <td rowspan="{{ $item->details->count() }}"><span class="badge-selesai">Diverifikasi</span></td>
                @endif
            </tr>
            @endforeach
        @empty
        <tr>
            <td colspan="8" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="7" class="text-right">Total Barang Masuk:</td>
            <td>{{ $barangMasuk->count() }} transaksi</td>
        </tr>
    </tfoot>
</table>

<div class="footer">
    <p>Dicetak dari Sistem SR Wood Craft | {{ now()->format('d/m/Y H:i:s') }}</p>
</div>

</body>
</html>
