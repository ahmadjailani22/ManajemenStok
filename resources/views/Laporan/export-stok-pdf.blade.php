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

</body>
</html>