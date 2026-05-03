@extends('layouts.app')

@section('title', 'Laporan Stok Barang')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Laporan Stok Barang</h1>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">

            {{-- Kartu Statistik --}}
            <div class="row">
                <div class="col-md-3">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalBarang }}</h3>
                            <p>Total Barang</p>
                        </div>
                        <div class="icon"><i class="fas fa-boxes"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalAman }}</h3>
                            <p>Stok Aman</p>
                        </div>
                        <div class="icon"><i class="fas fa-check-circle"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $totalMenipis }}</h3>
                            <p>Stok Menipis</p>
                        </div>
                        <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $totalHabis }}</h3>
                            <p>Stok Habis</p>
                        </div>
                        <div class="icon"><i class="fas fa-times-circle"></i></div>
                    </div>
                </div>
            </div>

            {{-- Form Search & Filter --}}
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('laporan.stok') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari kode / nama barang..."
                                    value="{{ $search }}">
                            </div>
                            <div class="col-md-3">
                                <select name="kategori" class="form-control">
                                    <option value="">-- Semua Kategori --</option>
                                    @foreach($kategoris as $kat)
                                        <option value="{{ $kat->id_kategori }}"
                                            {{ $filterKat == $kat->id_kategori ? 'selected' : '' }}>
                                            {{ $kat->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">-- Semua Status --</option>
                                    <option value="aman" {{ $filterStatus == 'aman' ? 'selected' : '' }}>Aman</option>
                                    <option value="menipis" {{ $filterStatus == 'menipis' ? 'selected' : '' }}>Menipis</option>
                                    <option value="habis" {{ $filterStatus == 'habis' ? 'selected' : '' }}>Habis</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary mr-1">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('laporan.stok') }}" class="btn btn-secondary">
                                    <i class="fas fa-sync"></i>
                                </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <a href="{{ route('laporan.stok.excel') }}" class="btn btn-success">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </a>
                                <a href="{{ route('laporan.stok.pdf') }}" class="btn btn-danger ml-1">
                                    <i class="fas fa-file-pdf"></i> Export PDF
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabel Laporan --}}
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Stok Minimum</th>
                                <th>Stok Saat Ini</th>
                                <th>Status</th>
                                <th>Letak Rak</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barang as $index => $b)
                            @php $status = $b->statusStok(); @endphp
                            <tr class="{{ $status === 'habis' ? 'table-danger' : ($status === 'menipis' ? 'table-warning' : '') }}">
                                <td>{{ $barang->firstItem() + $index }}</td>
                                <td>{{ $b->kode_barang }}</td>
                                <td>{{ $b->nama_barang }}</td>
                                <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $b->satuan }}</td>
                                <td>Rp {{ number_format($b->harga_beli, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                                <td>{{ $b->stok_minimum }}</td>
                                <td>{{ $b->stok_saat_ini }}</td>
                                <td>
                                    @if($status === 'habis')
                                        <span class="badge badge-danger">Habis</span>
                                    @elseif($status === 'menipis')
                                        <span class="badge badge-warning">Menipis</span>
                                    @else
                                        <span class="badge badge-success">Aman</span>
                                    @endif
                                </td>
                                <td>{{ $b->letak_rak ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($barang->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada data barang ditemukan.</p>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    {{ $barang->links() }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection