{{-- resources/views/barang-keluar/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Barang Keluar')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-arrow-circle-up text-danger mr-2"></i>Barang Keluar
        </h1>
        <a href="{{ route('barang-keluar.create') }}" class="btn btn-danger shadow-sm">
            <i class="fas fa-plus mr-1"></i> Tambah Transaksi
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-danger">
                <i class="fas fa-list mr-1"></i> Daftar Barang Keluar
                <span class="badge badge-danger ml-1">{{ $barangKeluar->total() }}</span>
            </h6>
            <form method="GET" action="{{ route('barang-keluar.index') }}" class="d-flex">
                <div class="input-group input-group-sm" style="width:300px">
                    <input type="text" class="form-control" name="search"
                        placeholder="Cari kode, nama barang..." value="{{ $search }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        @if($search)
                            <a href="{{ route('barang-keluar.index') }}" class="btn btn-outline-danger">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            @if($barangKeluar->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-arrow-circle-up fa-3x mb-3 opacity-50"></i>
                    <p class="font-weight-bold">Belum ada transaksi barang keluar</p>
                    <p class="small">Klik <strong>Tambah Transaksi</strong> untuk mulai mencatat.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="pl-4" width="50">#</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-right">Harga Jual</th>
                                <th class="text-right">Total</th>
                                <th class="text-center" width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangKeluar as $index => $item)
                            <tr>
                                <td class="pl-4 text-muted small">{{ $barangKeluar->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge badge-danger">{{ $item->kode_keluar }}</span>
                                </td>
                                <td class="small">{{ $item->tanggal_keluar->format('d M Y') }}</td>
                                <td class="font-weight-bold">{{ $item->barang->nama_barang ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge badge-warning">{{ number_format($item->jumlah) }}</span>
                                </td>
                                <td class="text-right small">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                <td class="text-right font-weight-bold">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('barang-keluar.show', $item->id_keluar) }}"
                                            class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete"
                                            data-id="{{ $item->id_keluar }}" data-kode="{{ $item->kode_keluar }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($barangKeluar->hasPages())
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <small class="text-muted">
                        Menampilkan {{ $barangKeluar->firstItem() }}–{{ $barangKeluar->lastItem() }}
                        dari {{ $barangKeluar->total() }} data
                    </small>
                    {{ $barangKeluar->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

{{-- Modal Hapus --}}
<div class="modal fade" id="modalHapus" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Hapus</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                <p class="mb-1">Yakin hapus transaksi: <strong id="kodeHapus"></strong>?</p>
                <p class="text-muted small">Stok barang akan dikembalikan sesuai jumlah transaksi.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Batal</button>
                <form id="formHapus" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="fas fa-trash mr-1"></i>Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('kodeHapus').textContent = this.dataset.kode;
            document.getElementById('formHapus').action = '/barang-keluar/' + this.dataset.id;
            $('#modalHapus').modal('show');
        });
    });
</script>
@endpush