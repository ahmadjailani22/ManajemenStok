@extends('layouts.app')

@section('title', 'Detail Barang')
@section('page-title', 'Detail Barang')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Data Barang</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="card card-info card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Detail Barang</h3>
        <div class="card-tools">
            <a href="{{ route('barang.edit', $barang->id_barang) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-sm ml-1">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><th width="40%">Kode Barang</th><td>: <code>{{ $barang->kode_barang }}</code></td></tr>
                    <tr><th>Barcode</th><td>: {{ $barang->barcode ?? '-' }}</td></tr>
                    <tr><th>Nama Barang</th><td>: {{ $barang->nama_barang }}</td></tr>
                    <tr><th>Kategori</th><td>: {{ $barang->kategori->nama_kategori ?? '-' }}</td></tr>
                    <tr><th>Satuan</th><td>: {{ $barang->satuan }}</td></tr>
                    <tr><th>Letak Rak</th><td>: {{ $barang->letak_rak ?? '-' }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><th width="40%">Harga Beli</th><td>: Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td></tr>
                    <tr><th>Harga Jual</th><td>: Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td></tr>
                    <tr><th>Stok Saat Ini</th>
                        <td>:
                            @if($barang->stok_saat_ini <= 0)
                                <span class="badge badge-danger">{{ $barang->stok_saat_ini }} {{ $barang->satuan }}</span>
                            @elseif($barang->stok_saat_ini <= $barang->stok_minimum)
                                <span class="badge badge-warning">{{ $barang->stok_saat_ini }} {{ $barang->satuan }}</span>
                            @else
                                <span class="badge badge-success">{{ $barang->stok_saat_ini }} {{ $barang->satuan }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th>Stok Minimum</th><td>: {{ $barang->stok_minimum }} {{ $barang->satuan }}</td></tr>
                    <tr><th>Status</th>
                        <td>:
                            @if($barang->status == 'aktif')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection