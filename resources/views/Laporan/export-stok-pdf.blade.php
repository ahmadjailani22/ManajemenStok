<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Barang</title>
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
    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <thead>
            <tr style="background: #343a40; color: white;">
                <th style="padding: 8px; border: 1px solid #ddd;">No</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Kode</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Nama Barang</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Kategori</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Satuan</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Harga Beli</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Harga Jual</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Stok Min</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Stok Saat Ini</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $index => $b)
            <tr>
                <td style="padding: 6px; border: 1px solid #ddd; text-align: center;">{{ $index + 1 }}</td>
                <td style="padding: 6px; border: 1px solid #ddd;">{{ $b->kode_barang }}</td>
                <td style="padding: 6px; border: 1px solid #ddd;">{{ $b->nama_barang }}</td>
                <td style="padding: 6px; border: 1px solid #ddd;">{{ $b->kategori->nama_kategori ?? '-' }}</td>
                <td style="padding: 6px; border: 1px solid #ddd; text-align: center;">{{ $b->satuan }}</td>
                <td style="padding: 6px; border: 1px solid #ddd;">Rp {{ number_format($b->harga_beli, 0, ',', '.') }}</td>
                <td style="padding: 6px; border: 1px solid #ddd;">Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                <td style="padding: 6px; border: 1px solid #ddd; text-align: center;">{{ $b->stok_minimum }}</td>
                <td style="padding: 6px; border: 1px solid #ddd; text-align: center;">{{ $b->stok_saat_ini }}</td>
                <td style="padding: 6px; border: 1px solid #ddd; text-align: center;">{{ ucfirst($b->statusStok()) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>