{{-- resources/views/supplier/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Data Supplier')

@section('content')
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800 fw-bold">
                    <i class="fas fa-truck me-2 text-primary"></i>Data Supplier
                </h1>
                <nav aria-label="breadcrumb" class="mt-1">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Master Data</li>
                        <li class="breadcrumb-item active">Supplier</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('supplier.create') }}" class="btn btn-primary shadow-sm mt-2 mt-sm-0">
                <i class="fas fa-plus me-1"></i> Tambah Supplier
            </a>
        </div>

        {{-- Alert Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Card Tabel --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h6 class="m-0 fw-semibold text-primary">
                    <i class="fas fa-list me-1"></i> Daftar Supplier
                    <span class="badge bg-primary ms-1">{{ $suppliers->total() }}</span>
                </h6>

                {{-- Search Form --}}
                <form method="GET" action="{{ route('supplier.index') }}" class="d-flex" role="search">
                    <div class="input-group input-group-sm" style="width: 280px;">
                        <input type="text" class="form-control border-end-0" name="search"
                            placeholder="Cari nama, kode, telepon..." value="{{ $search }}">
                        <button class="btn btn-outline-secondary border-start-0" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        @if ($search)
                            <a href="{{ route('supplier.index') }}" class="btn btn-outline-danger" title="Reset pencarian">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="card-body p-0">
                @if ($suppliers->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-truck fa-3x mb-3 opacity-25"></i>
                        <p class="mb-1 fw-semibold">
                            @if ($search)
                                Tidak ada hasil untuk "{{ $search }}"
                            @else
                                Belum ada data supplier
                            @endif
                        </p>
                        <p class="small">
                            @if ($search)
                                Coba kata kunci lain atau <a href="{{ route('supplier.index') }}">tampilkan semua</a>
                            @else
                                Klik tombol <strong>Tambah Supplier</strong> untuk menambah data pertama.
                            @endif
                        </p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" width="50">#</th>
                                    <th>Kode Supplier</th>
                                    <th>Nama Supplier</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th class="text-center" width="130">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $index => $supplier)
                                    <tr>
                                        <td class="ps-4 text-muted small">
                                            {{ $suppliers->firstItem() + $index }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-primary-subtle text-primary fw-semibold px-2 py-1 rounded-2">
                                                {{ $supplier->kode_supplier }}
                                            </span>
                                        </td>
                                        <td class="fw-semibold">{{ $supplier->nama_supplier }}</td>
                                        <td class="text-muted small" style="max-width: 200px;">
                                            <span class="text-truncate d-inline-block" style="max-width: 180px;"
                                                title="{{ $supplier->alamat }}">
                                                {{ $supplier->alamat ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">
                                            @if ($supplier->telepon)
                                                <i class="fas fa-phone me-1 text-success"></i>{{ $supplier->telepon }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="small">
                                            @if ($supplier->email)
                                                <a href="mailto:{{ $supplier->email }}" class="text-decoration-none">
                                                    <i class="fas fa-envelope me-1 text-info"></i>{{ $supplier->email }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('supplier.edit', $supplier->id) }}"
                                                    class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                    title="Hapus" data-id="{{ $supplier->id }}"
                                                    data-nama="{{ $supplier->nama_supplier }}" data-toggle="modal"
                                                    data-target="#modalHapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($suppliers->hasPages())
                        <div
                            class="card-footer bg-white border-top d-flex align-items-center justify-content-between flex-wrap gap-2 py-3 px-4">
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

    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-trash-alt fa-3x text-danger opacity-75"></i>
                    </div>
                    <p class="mb-1">Anda yakin ingin menghapus supplier:</p>
                    <p class="fw-bold fs-5 mb-1" id="namaSupplierHapus">-</p>
                    <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center gap-2">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <form id="formHapus" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="fas fa-trash me-1"></i>Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Inject data ke modal hapus
        document.querySelectorAll('.btn-delete').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const nama = this.dataset.nama;

                document.getElementById('namaSupplierHapus').textContent = nama;
                document.getElementById('formHapus').action = '/supplier/' + id;
            });
        });
    </script>
@endpush
