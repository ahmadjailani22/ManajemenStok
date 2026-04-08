@extends('layouts.app')

@section('title', 'Dashboard - StockManager')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

{{-- Summary Cards --}}
<div class="row">

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $summary->total_barang ?? 0 }}</h3>
                <p>Total Barang Aktif</p>
            </div>
            <div class="icon"><i class="fas fa-box"></i></div>
            <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $summary->stok_menipis ?? 0 }}</h3>
                <p>Stok Menipis</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $summary->transaksi_masuk_hari_ini ?? 0 }}</h3>
                <p>Barang Masuk Hari Ini</p>
            </div>
            <div class="icon"><i class="fas fa-arrow-circle-down"></i></div>
            <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $summary->transaksi_keluar_hari_ini ?? 0 }}</h3>
                <p>Barang Keluar Hari Ini</p>
            </div>
            <div class="icon"><i class="fas fa-arrow-circle-up"></i></div>
            <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

</div>

{{-- Tabel Stok Menipis + Transaksi Terakhir --}}
<div class="row">

    {{-- Stok Menipis --}}
    <div class="col-md-6">
        <div class="card card-danger card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-circle mr-1"></i> Stok Menipis
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Minimum</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stok_menipis as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->kode_barang }}</strong><br>
                                <small class="text-muted">{{ $item->nama_barang }}</small>
                            </td>
                            <td class="text-center text-danger font-weight-bold">{{ $item->stok_saat_ini }}</td>
                            <td class="text-center">{{ $item->stok_minimum }}</td>
                            <td class="text-center">
                                @if($item->stok_saat_ini == 0)
                                    <span class="badge badge-danger">Habis</span>
                                @else
                                    <span class="badge badge-warning">Menipis</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                <i class="fas fa-check-circle text-success"></i> Semua stok aman
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Transaksi Terakhir --}}
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i> Transaksi Terakhir
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Barang</th>
                            <th class="text-center">Jml</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi_terakhir as $trx)
                        <tr>
                            <td>
                                <span class="badge {{ $trx->tipe == 'MASUK' ? 'badge-success' : 'badge-danger' }}">
                                    {{ $trx->tipe }}
                                </span><br>
                                <small>{{ $trx->kode_transaksi }}</small>
                            </td>
                            <td><small>{{ $trx->nama_barang }}</small></td>
                            <td class="text-center">{{ $trx->jumlah }}</td>
                            <td><small>{{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Belum ada transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection