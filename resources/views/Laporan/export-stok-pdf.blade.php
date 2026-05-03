<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Barang</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 6px 8px; border: 1px solid #ddd; }
        thead tr { background: #343a40; color: white; }
        tr.habis { background: #f8d7da; }
        tr.menipis { background: #fff3cd; }
        tr:nth-child(even) { background: #f9f9f9; }
        tr.habis:nth-child(even) { background: #f8d7da; }
        tr.menipis:nth-child(even) { background: #fff3cd; }
        @media print {
            button { display: none; }
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">LAPORAN STOK BARANG</h2>
        <p style="margin: 5px 0;">Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
        <p style="margin: 5px 0;">Dicetak oleh: {{ auth()->user()->name }}</p>
        <hr>
    </div>

    {{-- Ringkasan Statistik --}}
    <table style="width: 100%; margin-bottom: 20px; border-collapse: collapse;">
        <tr>
            <td style="width: 25%; text-align: center; padding: 10px; background: #17a2b8; color: white; border-radius: 5px;">
                <strong>Total Barang</strong><br>
                <span style="font-size: 24px;">{{ $barang->count() }}</span>
            </td>
            <td style="width: 5%;"></td>
            <td style="width: 25%; text-align: center; padding: 10px; background: #28a745; color: white; border-radius: 5px;">
                <strong>Stok Aman</strong><br>
                <span style="font-size: 24px;">{{ $barang->filter(fn($b) => $b->statusStok() === 'aman')->count() }}</span>
            </td>
            <td style="width: 5%;"></td>
            <td style="width: 25%; text-align: center; padding: 10px; background: #ffc107; color: white; border-radius: 5px;">
                <strong>Stok Menipis</strong><br>
                <span style="font-size: 24px;">{{ $barang->filter(fn($b) => $b->statusStok() === 'menipis')->count() }}</span>
            </td>
            <td style="width: 5%;"></td>
            <td style="width: 25%; text-align: center; padding: 10px; background: #dc3545; color: white; border-radius: 5px;">
                <strong>Stok Habis</strong><br>
                <span style="font-size: 24px;">{{ $barang->filter(fn($b) => $b->statusStok() === 'habis')->count() }}</span>
            </td>
        </tr>
    </table>

    {{-- Tabel Data --}}
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Stok Min</th>
                <th>Stok Saat Ini</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $index => $b)
            @php $status = $b->statusStok(); @endphp
            <tr class="{{ $status }}">
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $b->kode_barang }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                <td style="text-align: center;">{{ $b->satuan }}</td>
                <td>Rp {{ number_format($b->harga_beli, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                <td style="text-align: center;">{{ $b->stok_minimum }}</td>
                <td style="text-align: center;">{{ $b->stok_saat_ini }}</td>
                <td style="text-align: center;">{{ ucfirst($status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>