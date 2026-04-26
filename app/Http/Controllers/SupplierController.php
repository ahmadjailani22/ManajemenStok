<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $search = $request->get('search');

        $suppliers = Supplier::query()
            ->when($search, function ($query, $search) {
                $query->where('kode_supplier', 'like', "%{$search}%")
                      ->orWhere('nama_supplier', 'like', "%{$search}%")
                      ->orWhere('telepon', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('kode_supplier')
            ->paginate(10)
            ->withQueryString();

        $totalAktif    = Supplier::where('status', 'aktif')->count();
        $totalNonaktif = Supplier::where('status', 'nonaktif')->count();

        return view('supplier.index', compact(
            'suppliers', 'search', 'filterStatus', 'totalAktif', 'totalNonaktif'
        ));
    }

    public function show(Supplier $supplier)
    {
        return view('supplier.show', compact('supplier'));
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:150',
            'alamat'        => 'nullable|string',
            'telepon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:100',
        ]);

        $validated['kode_supplier'] = Supplier::generateKode();
        Supplier::create($validated);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:150',
            'alamat'        => 'nullable|string',
            'telepon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:100',
        ]);

        $supplier->update($request->except('_token', '_method'));

        return redirect()->route('supplier.index')->with('success', 'Data supplier berhasil diperbarui!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->update(['status' => 'nonaktif']);

        return redirect()->route('supplier.index')->with('success', "Supplier \"{$nama}\" berhasil dihapus.");
    }

    /**
     * Toggle status aktif / nonaktif langsung dari tabel
     */
    public function toggleStatus(Supplier $supplier)
    {
        $supplier->update([
            'status' => $supplier->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        $label = $supplier->status === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Supplier \"{$supplier->nama_supplier}\" berhasil {$label}.");
    }

    /**
     * Export ke CSV (bisa dibuka di Excel)
     */
    public function exportExcel()
    {
        $suppliers = Supplier::orderBy('kode_supplier')->get();
        $filename  = 'supplier_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($suppliers) {
            $file = fopen('php://output', 'w');

            // BOM UTF-8 supaya Excel bisa baca karakter Indonesia
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['Kode Supplier', 'Nama Supplier', 'Alamat', 'Telepon', 'Email', 'Status', 'Tanggal Dibuat']);

            foreach ($suppliers as $s) {
                fputcsv($file, [
                    $s->kode_supplier,
                    $s->nama_supplier,
                    $s->alamat   ?? '-',
                    $s->telepon  ?? '-',
                    $s->email    ?? '-',
                    ucfirst($s->status),
                    $s->created_at->format('d/m/Y'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export ke PDF (print browser)
     */
    public function exportPdf()
    {
        $suppliers = Supplier::orderBy('kode_supplier')->get();
        return view('supplier.export-pdf', compact('suppliers'));
    }
}
