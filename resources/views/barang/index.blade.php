@extends('layouts.app')

@section('title', 'Data Barang')
@section('page-title', 'Data Barang')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Barang</li>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-box mr-1"></i> Daftar Barang</h3>
        <div class="card-tools">
            <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Barang
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover table-striped">
            <thead class="thead-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Satuan</th>
                    <th class="text-right">Harga Beli</th>
                    <th class="text-right">Harga Jual</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barang as $item)
                <tr>
                    <td><code>{{ $item->kode_barang }}</code></td>
                    <td>
                        {{ $item->nama_barang }}
                        @if($item->letak_rak)
                            <br><small class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $item->letak_rak }}</small>
                        @endif
                    </td>
                    <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($item->stok_saat_ini <= 0)
                            <span class="badge badge-danger">{{ $item->stok_saat_ini }}</span>
                        @elseif($item->stok_saat_ini <= $item->stok_minimum)
                            <span class="badge badge-warning">{{ $item->stok_saat_ini }}</span>
                        @else
                            <span class="badge badge-success">{{ $item->stok_saat_ini }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($item->status == 'aktif')
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('barang.show', $item->id_barang) }}"
                           class="btn btn-info btn-xs" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('barang.edit', $item->id_barang) }}"
                           class="btn btn-warning btn-xs" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('barang.destroy', $item->id_barang) }}"
                              method="POST" style="display:inline"
                              onsubmit="return confirm('Nonaktifkan barang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-xs" title="Nonaktifkan">
                                <i class="fas fa-ban"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                        Belum ada data barang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $barang->links() }}
    </div>
</div>

@endsection