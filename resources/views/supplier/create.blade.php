{{-- resources/views/supplier/create.blade.php --}}

@extends('layouts.app')

@section('title', 'Tambah Supplier')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">
                <i class="fas fa-plus-circle me-2 text-primary"></i>Tambah Supplier
            </h1>
            <nav aria-label="breadcrumb" class="mt-1">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Master Data</li>
                    <li class="breadcrumb-item"><a href="{{ route('supplier.index') }}">Supplier</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('supplier.index') }}" class="btn btn-outline-secondary mt-2 mt-sm-0">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="m-0 fw-semibold text-primary">
                        <i class="fas fa-truck me-2"></i>Form Tambah Supplier
                    </h6>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('supplier.store') }}" method="POST" novalidate>
                        @csrf

                        {{-- Kode Supplier (auto-generate) --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small text-uppercase tracking-wide">
                                Kode Supplier
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input
                                    type="text"
                                    class="form-control bg-light text-muted fw-bold"
                                    value="{{ $kodeSupplier }}"
                                    readonly
                                    disabled
                                >
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Kode supplier dibuat otomatis oleh sistem.
                            </div>
                        </div>

                        {{-- Nama Supplier --}}
                        <div class="mb-3">
                            <label for="nama_supplier" class="form-label fw-semibold">
                                Nama Supplier <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-building text-muted"></i>
                                </span>
                                <input
                                    type="text"
                                    class="form-control @error('nama_supplier') is-invalid @enderror"
                                    id="nama_supplier"
                                    name="nama_supplier"
                                    value="{{ old('nama_supplier') }}"
                                    placeholder="Masukkan nama supplier"
                                    autofocus
                                    required
                                >
                                @error('nama_supplier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="mb-3">
                            <label for="alamat" class="form-label fw-semibold">Alamat</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light align-items-start pt-2">
                                    <i class="fas fa-map-marker-alt text-muted"></i>
                                </span>
                                <textarea
                                    class="form-control @error('alamat') is-invalid @enderror"
                                    id="alamat"
                                    name="alamat"
                                    rows="3"
                                    placeholder="Masukkan alamat lengkap supplier"
                                >{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3">
                            {{-- Telepon --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telepon" class="form-label fw-semibold">Telepon</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-phone text-muted"></i>
                                        </span>
                                        <input
                                            type="text"
                                            class="form-control @error('telepon') is-invalid @enderror"
                                            id="telepon"
                                            name="telepon"
                                            value="{{ old('telepon') }}"
                                            placeholder="08xx-xxxx-xxxx"
                                        >
                                        @error('telepon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-envelope text-muted"></i>
                                        </span>
                                        <input
                                            type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            id="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            placeholder="contoh@email.com"
                                        >
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <hr class="my-4">

                        {{-- Tombol Aksi --}}
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('supplier.index') }}" class="btn btn-light px-4">
                                <i class="fas fa-times me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i>Simpan Supplier
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
