{{-- resources/views/barang-masuk/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Barang Masuk')

@section('content')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-3">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-eye text-info mr-2"></i>Detail Barang Masuk
            </h1>
            <a href="{{ route('barang-masuk.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-arrow-circle-down mr-2"></i>{{ $barangMasuk->kode_masuk }}
                        </h6>
                        <span
                            class="badge badge-light text-success">{{ $barangMasuk->tanggal_masuk->format('d M Y') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <small class="text-muted font-weight-bold text-uppercase">Barang</small>
                                <p class="font-weight-bold mb-0">{{ $barangMasuk->barang->nama_barang ?? '-' }}</p>
                                <small class="text-muted">{{ $barangMasuk->barang->kode_barang ?? '' }}</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted font-weight-bold text-uppercase">Kategori</small>
                                <p class="mb-0">{{ $barangMasuk->barang->kategori->nama_kategori ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted font-weight-bold text-uppercase">Supplier</small>
                                <p class="mb-0">{{ $barangMasuk->supplier->nama_supplier ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted font-weight-bold text-uppercase">Dicatat Oleh</small>
                                <p class="mb-0">{{ $barangMasuk->user->nama_lengkap ?? '-' }}</p>
                            </div>
                        </div>

                        <hr>

                        <div class="row text-center">
                            <div class="col-md-4">
                                <small class="text-muted font-weight-bold text-uppercase d-block">Jumlah</small>
                                <span class="badge badge-primary"
                                    style="font-size:1.1em">{{ number_format($barangMasuk->jumlah) }}</span>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted font-weight-bold text-uppercase d-block">Harga Beli</small>
                                <p class="font-weight-bold mb-0">Rp
                                    {{ number_format($barangMasuk->harga_beli, 0, ',', '.') }}</p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted font-weight-bold text-uppercase d-block">Total Harga</small>
                                <p class="font-weight-bold text-success h5 mb-0">Rp
                                    {{ number_format($barangMasuk->total_harga, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        @if($barangMasuk->keterangan)
                            <hr>
                            <small class="text-muted font-weight-bold text-uppercase">Keterangan</small>
                            <p class="mb-0">{{ $barangMasuk->keterangan }}</p>
                        @endif

                        <hr>
                        <div class="row small text-muted">
                            <div class="col-sm-6">
                                <i class="fas fa-calendar-plus mr-1"></i>
                                Dibuat: {{ $barangMasuk->created_at->format('d M Y, H:i') }}
                            </div>
                            <div class="col-sm-6">
                                <i class="fas fa-calendar-check mr-1"></i>
                                Diperbarui: {{ $barangMasuk->updated_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection