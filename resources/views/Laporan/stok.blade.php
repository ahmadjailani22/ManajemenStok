{{-- resources/views/laporan/stok.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item">Laporan</li>
    <li class="breadcrumb-item active">Laporan Stok</li>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-boxes"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Barang</span>
                <span class="info-box-number">{{ $totalBarang }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Stok Aman</span>
                <span class="info-box-number">{{ $totalAman }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Stok Menipis</span>
                <span class="info-box-number">{{ $totalMenipis }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Stok Habis</span>
                <span class="info-box-number">{{ $totalHabis }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Card Tabel --}}
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title pt-1">
            <i class="fas fa-chart-bar mr-1"></i> Laporan Stok Barang
        </h3>
        <div class="card-tools">
            <a href="{{ route('laporan.stok.excel') }}" class="btn btn-sm btn-success">
                <i class="fas fa-file-excel mr-1"></i> Excel
            </a>
            <a href="{{ route('laporan.stok.pdf') }}" target="_blank" class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>
        </div>
    </div>

    <div class="card-body pb-0">
        {{-- Filter & Search --}}
        <form method="GET" action="{{ route('laporan.stok') }}" class="form-inline mb-3 flex-wrap" style="gap:8px;">
            {{-- Search --}}
            <div class="input-group input-group-sm" style="width:240px;">
                <input type="text" class="form-control" name="search"
                    placeholder="Cari kode / nama barang..."
                    value="{{ $search }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            {{-- Filter Kategori --}}
            <select name="kategori" class="form-control form-control-sm"
                style="width:auto;" onchange="this.form.submit()">
                <option value="">-- Semua Kategori --</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat->id_kategori }}"
                        {{ $filterKat == $kat->id_kategori ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                @endforeach
            </select>

            {{-- Filter Status Stok --}}
            <select name="status" class="form-control form-control-sm"
                style="width:auto;" onchange="this.form.submit()">
                <option value="">-- Semua Status --</option>
                <option value="aman"     {{ $filterStatus === 'aman'     ? 'selected' : '' }}>Aman</option>
                <option value="menipis"  {{ $filterStatus === 'menipis'  ? 'selected' : '' }}>Menipis</option>
                <option value="habis"    {{ $filterStatus === 'habis'    ? 'selected' : '' }}>Habis</option>
            </select>

            @if($search || $filterKat || $filterStatus)
                <a href="{{ route('laporan.stok') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times mr-1"></i>Reset
                </a>
            @endif
        </form>
    </div>

    <div class="card-body p-0">
        @if($barang->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-boxes fa-3x mb-3" style="opacity:.3;"></i>
                <p class="font-weight-bold mb-1">Tidak ada data barang ditemukan.</p>
                @if($search || $filterKat || $filterStatus)
                    <p class="small"><a href="{{ route('laporan.stok') }}">Tampilkan semua</a></p>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th class="pl-3" width="45">#</th>
                            <th width="100">Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th class="text-right">Harga Beli</th>
                            <th class="text-right">Harga Jual</th>
                            <th class="text-center">Min</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barang as $i => $b)
                            @php
                                $statusStok = $b->statusStok();
                            @endphp
                            <tr class="{{ $statusStok === 'habis' ? 'table-danger' : ($statusStok === 'menipis' ? 'table-warning' : '') }}">
                                <td class="pl-3 text-muted small">{{ $barang->firstItem() + $i }}</td>
                                <td>
                                    <span class="badge badge-primary px-2 py-1">
                                        {{ $b->kode_barang }}
                                    </span>
                                </td>
                                <td class="font-weight-bold">
                                    {{ $b->nama_barang }}
                                    @if($b->letak_rak)
                                        <br><small class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i>{{ $b->letak_rak }}</small>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $b->kategori->nama_kategori ?? '-' }}</td>
                                <td class="small">{{ $b->satuan }}</td>
                                <td class="text-right small">Rp {{ number_format($b->harga_beli, 0, ',', '.') }}</td>
                                <td class="text-right small">Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                                <td class="text-center small">{{ $b->stok_minimum }}</td>
                                <td class="text-center font-weight-bold">
                                    <span class="text-{{ $statusStok === 'habis' ? 'danger' : ($statusStok === 'menipis' ? 'warning' : 'success') }}">
                                        {{ $b->stok_saat_ini }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($statusStok === 'habis')
                                        <span class="badge badge-danger px-2 py-1">Habis</span>
                                    @elseif($statusStok === 'menipis')
                                        <span class="badge badge-warning px-2 py-1">Menipis</span>
                                    @else
                                        <span class="badge badge-success px-2 py-1">Aman</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($barang->hasPages())
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <small class="text-muted">
                        Menampilkan {{ $barang->firstItem() }}–{{ $barang->lastItem() }}
                        dari {{ $barang->total() }} barang
                    </small>
                    {{ $barang->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

@endsection
