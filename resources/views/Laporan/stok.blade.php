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
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection