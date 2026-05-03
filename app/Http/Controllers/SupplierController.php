<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $search = request('search');
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
        $kodeSupplier = Supplier::generateKode();

        return view('supplier.create', compact('kodeSupplier'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required',
            'telepon' => 'nullable',
            'email' => 'nullable|email',
            'alamat' => 'nullable',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        Supplier::create([
            'kode_supplier' => Supplier::generateKode(),
            'nama_supplier' => $request->nama_supplier,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ]);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

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
            'telepon' => 'nullable',
            'email' => 'nullable|email',
            'alamat' => 'nullable',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $supplier->update($request->except('_token', '_method'));

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }

    public function toggleStatus(Supplier $supplier)
    {
        $supplier->status = $supplier->status === 'aktif' ? 'nonaktif' : 'aktif';
        $supplier->save();

        return redirect()->route('supplier.index')
            ->with('success', 'Status supplier berhasil diperbarui.');
    }
}