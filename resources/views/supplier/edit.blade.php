{{-- resources/views/supplier/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Supplier')
@section('page-title', 'Edit Supplier')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('supplier.index') }}">Supplier</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="callout callout-warning py-2 px-3 mb-3">
            <i class="fas fa-info-circle mr-2"></i>
            Mengedit: <strong>{{ $supplier->nama_supplier }}</strong>
            <span class="badge badge-primary ml-1">{{ $supplier->kode_supplier }}</span>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title pt-1">
                    <i class="fas fa-edit mr-2 text-warning"></i>Form Edit Supplier
                </h3>
                <div class="card-tools">
                    <a href="{{ route('supplier.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('supplier.update', $supplier->id) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Kode Supplier (readonly) --}}
                    <div class="form-group">
                        <label class="text-muted small font-weight-bold text-uppercase">Kode Supplier</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                            </div>
                            <input type="text" class="form-control bg-light text-muted font-weight-bold"
                                value="{{ $supplier->kode_supplier }}" readonly disabled>
                        </div>
                        <small class="text-muted"><i class="fas fa-lock mr-1"></i>Kode tidak dapat diubah.</small>
                    </div>

                    {{-- Nama Supplier --}}
                    <div class="form-group">
                        <label for="nama_supplier" class="font-weight-bold">
                            Nama Supplier <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light"><i class="fas fa-building text-muted"></i></span>
                            </div>
                            <input type="text"
                                class="form-control @error('nama_supplier') is-invalid @enderror"
                                id="nama_supplier" name="nama_supplier"
                                value="{{ old('nama_supplier', $supplier->nama_supplier) }}"
                                placeholder="Masukkan nama supplier" autofocus required>
                            @error('nama_supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="form-group">
                        <label for="alamat" class="font-weight-bold">Alamat</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light" style="align-items:flex-start; padding-top:8px;">
                                    <i class="fas fa-map-marker-alt text-muted"></i>
                                </span>
                            </div>
                            <textarea class="form-control @error('alamat') is-invalid @enderror"
                                id="alamat" name="alamat" rows="3"
                                placeholder="Masukkan alamat lengkap">{{ old('alamat', $supplier->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        {{-- Telepon --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telepon" class="font-weight-bold">Telepon</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                                    </div>
                                    <input type="text"
                                        class="form-control @error('telepon') is-invalid @enderror"
                                        id="telepon" name="telepon"
                                        value="{{ old('telepon', $supplier->telepon) }}"
                                        placeholder="08xx-xxxx-xxxx">
                                    @error('telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="font-weight-bold">Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                    </div>
                                    <input type="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email"
                                        value="{{ old('email', $supplier->email) }}"
                                        placeholder="contoh@email.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="form-group">
                        <label class="font-weight-bold">Status <span class="text-danger">*</span></label>
                        <div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="custom-control-input" type="radio"
                                    id="status_aktif" name="status" value="aktif"
                                    {{ old('status', $supplier->status) === 'aktif' ? 'checked' : '' }}>
                                <label class="custom-control-label text-success font-weight-bold" for="status_aktif">
                                    <i class="fas fa-check-circle mr-1"></i>Aktif
                                </label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="custom-control-input" type="radio"
                                    id="status_nonaktif" name="status" value="nonaktif"
                                    {{ old('status', $supplier->status) === 'nonaktif' ? 'checked' : '' }}>
                                <label class="custom-control-label text-secondary font-weight-bold" for="status_nonaktif">
                                    <i class="fas fa-times-circle mr-1"></i>Nonaktif
                                </label>
                            </div>
                        </div>
                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Meta Info --}}
                    <div class="bg-light rounded p-3 mb-3 small text-muted">
                        <div class="row">
                            <div class="col-sm-6">
                                <i class="fas fa-calendar-plus mr-1"></i>
                                Dibuat: {{ $supplier->created_at->format('d M Y, H:i') }}
                            </div>
                            <div class="col-sm-6">
                                <i class="fas fa-calendar-check mr-1"></i>
                                Diperbarui: {{ $supplier->updated_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('supplier.index') }}" class="btn btn-light px-4 mr-2">
                            <i class="fas fa-times mr-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="fas fa-save mr-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
