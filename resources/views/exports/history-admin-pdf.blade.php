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
        .header h3 { font-size: 13px; margin: 5px 0; }
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
        table td { padding: 5px 8px; border-bottom: 1px solid #ddd; font-size: 9px; }
        table tr:nth-child(even) { background: #f9f9f9; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge-selesai { color: #065f46; font-weight: bold; }
        .badge-menunggu { color: #92400e; font-weight: bold; }
        .badge-diproses { color: #1e40af; font-weight: bold; }
        .badge-ditolak { color: #991b1b; font-weight: bold; }
        .badge-sebagian { color: #92400e; font-weight: bold; }
        .status { font-weight: bold; }
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
        .jenis-badge {
            background: #e5e7eb;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            color: #374151;
        }
    </style>
</head>
<body>

<div class="header">
    <h2> SR Wood Craft</h2>
    <h3>{{ $title }}</h3>
    <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
    @if(request('tanggal_awal') || request('tanggal_akhir'))
    <p style="font-size: 9px; color: #666;">
        Periode: 
        {{ request('tanggal_awal') ? \Carbon\Carbon::parse(request('tanggal_awal'))->format('d/m/Y') : 'Awal' }}
        s/d
        {{ request('tanggal_akhir') ? \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') : 'Akhir' }}
    </p>
    @endif
</div>

<table>
    <thead>
        <tr>
            <th style="width: 10%;">No Transaksi</th>
            <th style="width: 10%;">Jenis</th>
            <th style="width: 12%;">Tanggal</th>
            <th style="width: 12%;">Dari</th>
            <th style="width: 12%;">Untuk</th>
            <th style="width: 8%;">Mutasi</th>
            <th style="width: 8%;">Jumlah</th>
            <th style="width: 10%;">Status</th>
            <th style="width: 8%;">Bahan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($history as $row)
        <tr>
            <td>{{ $row->no_transaksi }}</td>
            <td><span class="jenis-badge">{{ $row->jenis_transaksi }}</span></td>
            <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y H:i') }}</td>
            <td>{{ $row->dari }}</td>
            <td>{{ $row->untuk }}</td>
            <td class="text-center">
                <span style="color: {{ $row->jenis_mutasi == 'Masuk' ? 'green' : 'red' }}; font-weight: bold;">
                    {{ $row->jenis_mutasi }}
                </span>
            </td>
            <td class="text-center">
                @php
                    $totalJumlah = 0;
                    foreach($row->details as $detail) {
                        if(isset($detail->jumlah_diminta)) $totalJumlah += $detail->jumlah_diminta;
                        elseif(isset($detail->jumlah_diterima)) $totalJumlah += $detail->jumlah_diterima;
                    }
                @endphp
                {{ $totalJumlah }}
            </td>
            <td>
                <span class="status 
                    @if($row->status == 'Menunggu' || $row->status == 'Draft') badge-menunggu
                    @elseif($row->status == 'Diproses') badge-diproses
                    @elseif($row->status == 'Selesai' || $row->status == 'Diverifikasi') badge-selesai
                    @elseif($row->status == 'Ditolak') badge-ditolak
                    @else badge-sebagian @endif">
                    {{ $row->status }}
                </span>
            </td>
            <td>
                @foreach($row->details as $detail)
                    {{ $detail->nama_bahan }}@if(!$loop->last), @endif
                @endforeach
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="8" class="text-right">Total Transaksi:</td>
            <td>{{ $history->count() }} transaksi</td>
        </tr>
    </tfoot>
</table>

<div class="footer">
    <p>Dicetak dari Sistem SR Wood Craft | {{ now()->format('d/m/Y H:i:s') }}</p>
</div>

</body>
</html>
