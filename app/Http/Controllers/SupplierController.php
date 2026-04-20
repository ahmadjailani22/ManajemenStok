<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
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

        return view('supplier.index', compact('suppliers', 'search'));
    }

    public function create()
    {
        $kodeSupplier = Supplier::generateKode();
        return view('supplier.create', compact('kodeSupplier'));
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

        $supplier->update($validated);

        return redirect()->route('supplier.index')->with('success', 'Data supplier berhasil diperbarui!');
    }

    public function destroy(Supplier $supplier)
    {
        $nama = $supplier->nama_supplier;
        $supplier->delete();

        return redirect()->route('supplier.index')->with('success', "Supplier \"{$nama}\" berhasil dihapus.");
    }
}