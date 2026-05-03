{{-- resources/views/laporan/export-stok-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Barang</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }

        .no-print { padding: 10px 16px; background: #f8f9fa; border-bottom: 1px solid #dee2e6; display: flex; gap: 8px; }
        .no-print button, .no-print a {
            padding: 6px 16px; border-radius: 4px; cursor: pointer;
            font-size: 12px; text-decoration: none; border: none;
        }
        .btn-print { background: #dc3545; color: #fff; }
        .btn-back  { background: #6c757d; color: #fff; }

        .content { padding: 20px 28px; }

        .header { text-align: center; padding-bottom: 12px; border-bottom: 2px solid #2c3e50; margin-bottom: 14px; }
        .header h2 { font-size: 16px; font-weight: bold; margin-bottom: 3px; }
        .header p  { font-size: 10px; color: #666; }

        .meta { display: flex; justify-content: space-between; margin-bottom: 14px; font-size: 10px; color: #555; }

        .summary { display: flex; gap: 16px; margin-bottom: 14px; }
        .summary-item { padding: 6px 12px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .s-total   { background: #dbeafe; color: #1e40af; }
        .s-aman    { background: #d4edda; color: #155724; }
        .s-menipis { background: #fff3cd; color: #856404; }
        .s-habis   { background: #f8d7da; color: #721c24; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #2c3e50; color: #fff; }
        thead th { padding: 7px 8px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: .04em; }
        tbody tr:nth-child(even) { background: #f8f9fa; }
        tbody tr.menipis { background: #fff9e6; }
        tbody tr.habis   { background: #fde8e8; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #e9ecef; font-size: 10px; }

        .badge { display: inline-block; padding: 2px 7px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-aman    { background: #d4edda; color: #155724; }
        .badge-menipis { background: #fff3cd; color: #856404; }
        .badge-habis   { background: #f8d7da; color: #721c24; }

        .footer { margin-top: 18px; text-align: right; font-size: 9px; color: #999; }

        @media print {
            .no-print { display: none !important; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            thead tr { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Print / Simpan PDF</button>
        <a href="{{ route('laporan.stok') }}" class="btn-back">← Kembali</a>
    </div>

    <div class="content">
        <div class="header">
            <h2>LAPORAN STOK BARANG</h2>
            <p>StockManager — Sistem Manajemen Stok Toko</p>
        </div>

        <div class="meta">
            <span>Tanggal Cetak: {{ now()->format('d M Y, H:i') }} WIB</span>
            <span>Total: {{ $barang->count() }} barang</span>
        </div>

        <div class="summary">
            <div class="summary-item s-total">Total: {{ $barang->count() }}</div>
            <div class="summary-item s-aman">Aman: {{ $barang->filter(fn($b) => $b->statusStok() === 'aman')->count() }}</div>
            <div class="summary-item s-menipis">Menipis: {{ $barang->filter(fn($b) => $b->statusStok() === 'menipis')->count() }}</div>
            <div class="summary-item s-habis">Habis: {{ $barang->filter(fn($b) => $b->statusStok() === 'habis')->count() }}</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="30">#</th>
                    <th width="80">Kode</th>
                    <th>Nama Barang</th>
                    <th width="90">Kategori</th>
                    <th width="50">Satuan</th>
                    <th width="90" style="text-align:right;">Harga Beli</th>
                    <th width="90" style="text-align:right;">Harga Jual</th>
                    <th width="40" style="text-align:center;">Min</th>
                    <th width="40" style="text-align:center;">Stok</th>
                    <th width="60" style="text-align:center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barang as $i => $b)
                    @php $statusStok = $b->statusStok(); @endphp
                    <tr class="{{ $statusStok }}">
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $b->kode_barang }}</strong></td>
                        <td>{{ $b->nama_barang }}</td>
                        <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $b->satuan }}</td>
                        <td style="text-align:right;">Rp {{ number_format($b->harga_beli, 0, ',', '.') }}</td>
                        <td style="text-align:right;">Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                        <td style="text-align:center;">{{ $b->stok_minimum }}</td>
                        <td style="text-align:center;font-weight:bold;">{{ $b->stok_saat_ini }}</td>
                        <td style="text-align:center;">
                            <span class="badge badge-{{ $statusStok }}">
                                {{ ucfirst($statusStok) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align:center; padding:20px; color:#999;">
                            Tidak ada data barang.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            Dicetak oleh: {{ Auth::user()->nama_lengkap ?? 'Administrator' }}
            &nbsp;|&nbsp; {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

</body>
</html>
