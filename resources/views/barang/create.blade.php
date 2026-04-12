@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('page-title', 'Tambah Barang')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Data Barang</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Form Tambah Barang</h3>
    </div>
    <form action="{{ route('barang.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kode Barang <span class="text-danger">*</span></label>
                        <input type="text" name="kode_barang"
                               class="form-control @error('kode_barang') is-invalid @enderror"
                               value="{{ old('kode_barang') }}" placeholder="contoh: BRG-005">
                        @error('kode_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang"
                               class="form-control @error('nama_barang') is-invalid @enderror"
                               value="{{ old('nama_barang') }}" placeholder="Nama barang">
                        @error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Barcode</label>
                        <input type="text" name="barcode"
                               class="form-control" value="{{ old('barcode') }}" placeholder="Opsional">
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="id_kategori" class="form-control">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id_kategori }}"
                                    {{ old('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="satuan"
                               class="form-control @error('satuan') is-invalid @enderror"
                               value="{{ old('satuan') }}" placeholder="contoh: pcs, kg, lusin">
                        @error('satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Harga Beli <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" name="harga_beli"
                                   class="form-control @error('harga_beli') is-invalid @enderror"
                                   value="{{ old('harga_beli') }}" min="0">
                            @error('harga_beli')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Harga Jual <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" name="harga_jual"
                                   class="form-control @error('harga_jual') is-invalid @enderror"
                                   value="{{ old('harga_jual') }}" min="0">
                            @error('harga_jual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Stok Saat Ini <span class="text-danger">*</span></label>
                        <input type="number" name="stok_saat_ini"
                               class="form-control @error('stok_saat_ini') is-invalid @enderror"
                               value="{{ old('stok_saat_ini', 0) }}" min="0">
                        @error('stok_saat_ini')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Stok Minimum <span class="text-danger">*</span></label>
                        <input type="number" name="stok_minimum"
                               class="form-control @error('stok_minimum') is-invalid @enderror"
                               value="{{ old('stok_minimum', 0) }}" min="0">
                        @error('stok_minimum')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Letak Rak</label>
                        <input type="text" name="letak_rak"
                               class="form-control" value="{{ old('letak_rak') }}" placeholder="contoh: A-01">
                    </div>
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control">
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Simpan
            </button>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary ml-2">
                <i class="fas fa-arrow-left mr-1"></i> Batal
            </a>
        </div>
    </form>
</div>
@endsection