{{-- resources/views/supplier/export-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Supplier</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; background: #fff; }

        .no-print { padding: 12px 16px; background: #f8f9fa; border-bottom: 1px solid #dee2e6; display: flex; gap: 8px; }
        .no-print button, .no-print a {
            padding: 6px 16px; border-radius: 4px; cursor: pointer;
            font-size: 12px; text-decoration: none; border: none;
        }
        .btn-print  { background: #dc3545; color: #fff; }
        .btn-back   { background: #6c757d; color: #fff; }

        .content { padding: 24px 32px; }

        .header { text-align: center; padding-bottom: 14px; border-bottom: 2px solid #2c3e50; margin-bottom: 16px; }
        .header h2 { font-size: 17px; font-weight: bold; margin-bottom: 4px; }
        .header p  { font-size: 11px; color: #666; }

        .meta { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 11px; color: #555; }

        .summary { font-size: 11px; color: #555; margin-bottom: 14px; }
        .summary strong { color: #333; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #2c3e50; color: #fff; }
        thead th { padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: .05em; }
        tbody tr:nth-child(even) { background: #f8f9fa; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e9ecef; vertical-align: top; font-size: 11px; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .badge-aktif    { background: #d4edda; color: #155724; }
        .badge-nonaktif { background: #e2e3e5; color: #383d41; }

        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #999; }

        @media print {
            .no-print { display: none !important; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            thead tr { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">
            🖨️ Print / Simpan PDF
        </button>
        <a href="{{ route('supplier.index') }}" class="btn-back">
            ← Kembali
        </a>
    </div>

    <div class="content">
        <div class="header">
            <h2>LAPORAN DATA SUPPLIER</h2>
            <p>StockManager — Sistem Manajemen Stok Toko</p>
        </div>

        <div class="meta">
            <span>Tanggal Cetak: {{ now()->format('d M Y, H:i') }} WIB</span>
            <span>Total Data: {{ $suppliers->count() }} supplier</span>
        </div>

        <div class="summary">
            Aktif: <strong>{{ $suppliers->where('status', 'aktif')->count() }}</strong>
            &nbsp;|&nbsp;
            Nonaktif: <strong>{{ $suppliers->where('status', 'nonaktif')->count() }}</strong>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="35">#</th>
                    <th width="95">Kode</th>
                    <th>Nama Supplier</th>
                    <th>Alamat</th>
                    <th width="115">Telepon</th>
                    <th width="155">Email</th>
                    <th width="70" style="text-align:center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $i => $supplier)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $supplier->kode_supplier }}</strong></td>
                        <td>{{ $supplier->nama_supplier }}</td>
                        <td style="color:#666;">{{ $supplier->alamat ?? '-' }}</td>
                        <td>{{ $supplier->telepon ?? '-' }}</td>
                        <td style="color:#0066cc;">{{ $supplier->email ?? '-' }}</td>
                        <td style="text-align:center;">
                            <span class="badge badge-{{ $supplier->status }}">
                                {{ ucfirst($supplier->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:20px; color:#999;">
                            Tidak ada data supplier.
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
