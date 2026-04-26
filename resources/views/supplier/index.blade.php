{{-- resources/views/supplier/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Supplier')
@section('page-title', 'Data Supplier')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">Supplier</li>
@endsection

@section('content')

{{-- Alert --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

{{-- Stat Cards --}}
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-truck"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Supplier</span>
                <span class="info-box-number">{{ $totalAktif + $totalNonaktif }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Supplier Aktif</span>
                <span class="info-box-number">{{ $totalAktif }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-times-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Supplier Nonaktif</span>
                <span class="info-box-number">{{ $totalNonaktif }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Card Tabel --}}
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title pt-1">
            <i class="fas fa-list mr-1"></i> Daftar Supplier
        </h3>
        <div class="card-tools">
            <a href="{{ route('supplier.export.excel') }}" class="btn btn-sm btn-success" title="Export Excel">
                <i class="fas fa-file-excel mr-1"></i> Excel
            </a>
            <a href="{{ route('supplier.export.pdf') }}" target="_blank" class="btn btn-sm btn-danger" title="Export PDF">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>
            <a href="{{ route('supplier.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus mr-1"></i> Tambah Supplier
            </a>
        </div>
    </div>

    <div class="card-body pb-0">
        {{-- Filter & Search --}}
        <form method="GET" action="{{ route('supplier.index') }}" class="form-inline mb-3">
            <div class="input-group input-group-sm mr-2" style="width: 260px;">
                <input type="text" class="form-control" name="search"
                    placeholder="Cari nama, kode, telepon..."
                    value="{{ $search }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <select name="status" class="form-control form-control-sm mr-2"
                style="width: auto;" onchange="this.form.submit()">
                <option value="">-- Semua Status --</option>
                <option value="aktif"    {{ $filterStatus === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $filterStatus === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            @if($search || $filterStatus)
                <a href="{{ route('supplier.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times mr-1"></i>Reset
                </a>
            @endif
        </form>
    </div>

    <div class="card-body p-0">
        @if($suppliers->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-truck fa-3x mb-3" style="opacity:.3;"></i>
                <p class="font-weight-bold mb-1">
                    {{ $search || $filterStatus ? 'Tidak ada hasil yang ditemukan.' : 'Belum ada data supplier.' }}
                </p>
                @if($search || $filterStatus)
                    <p class="small"><a href="{{ route('supplier.index') }}">Tampilkan semua</a></p>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th class="pl-3" width="45">#</th>
                            <th width="110">Kode</th>
                            <th>Nama Supplier</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th class="text-center" width="100">Status</th>
                            <th class="text-center" width="130">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $i => $supplier)
                            <tr>
                                <td class="pl-3 text-muted small">{{ $suppliers->firstItem() + $i }}</td>
                                <td>
                                    <span class="badge badge-primary px-2 py-1">
                                        {{ $supplier->kode_supplier }}
                                    </span>
                                </td>
                                <td class="font-weight-bold">{{ $supplier->nama_supplier }}</td>
                                <td class="small text-muted">{{ $supplier->telepon ?? '-' }}</td>
                                <td class="small">
                                    @if($supplier->email)
                                        <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- Klik badge untuk toggle status --}}
                                    <form action="{{ route('supplier.toggle-status', $supplier->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="badge badge-{{ $supplier->isAktif() ? 'success' : 'secondary' }} border-0"
                                            style="cursor:pointer; font-size:.8rem; padding:5px 10px;"
                                            title="Klik untuk ubah status">
                                            <i class="fas fa-{{ $supplier->isAktif() ? 'check' : 'times' }} mr-1"></i>
                                            {{ $supplier->isAktif() ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('supplier.show', $supplier->id) }}"
                                           class="btn btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('supplier.edit', $supplier->id) }}"
                                           class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-danger btn-delete"
                                            data-id="{{ $supplier->id }}"
                                            data-nama="{{ $supplier->nama_supplier }}"
                                            data-toggle="modal"
                                            data-target="#modalHapus"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($suppliers->hasPages())
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <small class="text-muted">
                        Menampilkan {{ $suppliers->firstItem() }}–{{ $suppliers->lastItem() }}
                        dari {{ $suppliers->total() }} data
                    </small>
                    {{ $suppliers->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="modalHapus" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                <p class="mb-1">Anda yakin ingin menghapus supplier:</p>
                <p class="font-weight-bold h5 mb-1" id="namaSupplierHapus">-</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Batal
                </button>
                <form id="formHapus" method="POST" class="d-inline">
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
        btn.addEventListener('click', function () {
            document.getElementById('namaSupplierHapus').textContent = this.dataset.nama;
            document.getElementById('formHapus').action = '/supplier/' + this.dataset.id;
        });
    });
</script>
@endpush
