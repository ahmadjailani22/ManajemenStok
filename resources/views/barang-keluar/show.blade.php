{{-- resources/views/barang-keluar/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Barang Keluar')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye text-info mr-2"></i>Detail Barang Keluar
        </h1>
        <a href="{{ route('barang-keluar.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-danger text-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-arrow-circle-up mr-2"></i>{{ $barangKeluar->kode_keluar }}
                    </h6>
                    <span class="badge badge-light text-danger">{{ $barangKeluar->tanggal_keluar->format('d M Y') }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted font-weight-bold text-uppercase">Barang</small>
                            <p class="font-weight-bold mb-0">{{ $barangKeluar->barang->nama_barang ?? '-' }}</p>
                            <small class="text-muted">{{ $barangKeluar->barang->kode_barang ?? '' }}</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted font-weight-bold text-uppercase">Kategori</small>
                            <p class="mb-0">{{ $barangKeluar->barang->kategori->nama_kategori ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted font-weight-bold text-uppercase">Dicatat Oleh</small>
                            <p class="mb-0">{{ $barangKeluar->user->nama_lengkap ?? '-' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-md-4">
                            <small class="text-muted font-weight-bold text-uppercase d-block">Jumlah</small>
                            <span class="badge badge-warning" style="font-size:1.1em">{{ number_format($barangKeluar->jumlah) }}</span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted font-weight-bold text-uppercase d-block">Harga Jual</small>
                            <p class="font-weight-bold mb-0">Rp {{ number_format($barangKeluar->harga_jual, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted font-weight-bold text-uppercase d-block">Total Harga</small>
                            <p class="font-weight-bold text-danger h5 mb-0">Rp {{ number_format($barangKeluar->total_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    @if($barangKeluar->keterangan)
                    <hr>
                    <small class="text-muted font-weight-bold text-uppercase">Keterangan</small>
                    <p class="mb-0">{{ $barangKeluar->keterangan }}</p>
                    @endif

                    <hr>
                    <div class="row small text-muted">
                        <div class="col-sm-6">
                            <i class="fas fa-calendar-plus mr-1"></i>
                            Dibuat: {{ $barangKeluar->created_at->format('d M Y, H:i') }}
                        </div>
                        <div class="col-sm-6">
                            <i class="fas fa-calendar-check mr-1"></i>
                            Diperbarui: {{ $barangKeluar->updated_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection