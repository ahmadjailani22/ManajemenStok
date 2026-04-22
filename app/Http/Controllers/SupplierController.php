<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $search    = request('search');
        $suppliers = Supplier::when($search, function ($query) use ($search) {
                        $query->where('nama_supplier', 'like', "%{$search}%")
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
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_supplier' => 'required|unique:supplier,kode_supplier',
            'nama_supplier' => 'required',
            'telepon'       => 'nullable',
            'email'         => 'nullable|email',
            'alamat'        => 'nullable',
            'status'        => 'required|in:aktif,nonaktif',
        ], [
            'kode_supplier.required' => 'Kode supplier wajib diisi.',
            'kode_supplier.unique'   => 'Kode supplier sudah digunakan.',
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
        ]);

        Supplier::create($request->except('_token'));

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    // Gunakan type-hint Supplier $supplier
    public function show(Supplier $supplier)
    {
        return view('supplier.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'kode_supplier' => 'required|unique:supplier,kode_supplier,' . $supplier->id_supplier . ',id_supplier',
            'nama_supplier' => 'required',
            'telepon'       => 'nullable',
            'email'         => 'nullable|email',
            'alamat'        => 'nullable',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        $supplier->update($request->except('_token', '_method'));

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->update(['status' => 'nonaktif']);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil dinonaktifkan.');
    }
}