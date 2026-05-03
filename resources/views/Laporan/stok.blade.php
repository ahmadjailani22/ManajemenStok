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

        </div>
    </div>
</div>
@endsection