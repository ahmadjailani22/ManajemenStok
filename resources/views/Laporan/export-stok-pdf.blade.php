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

</body>
</html>