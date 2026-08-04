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
        .badge-sebagian { color: #92400e; font-weight: bold; }
        .badge-ditolak { color: #991b1b; font-weight: bold; }
        .badge-diproses { color: #1e40af; font-weight: bold; }
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
            <th>Kode PG</th>
            <th>Tanggal Dibuat</th>
            <th>Status</th>
            <th>Bahan</th>
            <th>Diminta</th>
            <th>Datang</th>
            <th>Tanggal Selesai</th>
        </tr>
    </thead>
    <tbody>
        @forelse($permintaan as $item)
            @foreach($item->details as $index => $detail)
            <tr>
                @if($index == 0)
                    <td rowspan="{{ $item->details->count() }}">{{ $item->kode_pg }}</td>
                    <td rowspan="{{ $item->details->count() }}">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td rowspan="{{ $item->details->count() }}">
                        <span class="
                            @if($item->status == 'Selesai') badge-selesai
                            @elseif($item->status == 'Sebagian') badge-sebagian
                            @elseif($item->status == 'Diproses') badge-diproses
                            @else badge-ditolak @endif">
                            {{ $item->status }}
                        </span>
                    </td>
                @endif
                <td>{{ $detail->bahan->nama_bahan ?? '-' }}</td>
                <td class="text-center">{{ $detail->jumlah_diminta }}</td>
                <td class="text-center">{{ $detail->jumlah_datang }}</td>
                @if($index == 0)
                    <td rowspan="{{ $item->details->count() }}">{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y H:i') : '-' }}</td>
                @endif
            </tr>
            @endforeach
        @empty
        <tr>
            <td colspan="7" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="6" class="text-right">Total Permintaan:</td>
            <td>{{ $permintaan->count() }} permintaan</td>
        </tr>
    </tfoot>
</table>

<div class="footer">
    <p>Dicetak dari Sistem SR Wood Craft | {{ now()->format('d/m/Y H:i:s') }}</p>
</div>

</body>
</html>
