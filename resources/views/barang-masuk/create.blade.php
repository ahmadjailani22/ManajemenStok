{{-- resources/views/barang-masuk/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Tambah Barang Masuk')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle text-success mr-2"></i>Tambah Barang Masuk
        </h1>
        <a href="{{ route('barang-masuk.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-arrow-circle-down mr-2"></i>Form Transaksi Barang Masuk
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('barang-masuk.store') }}" method="POST">
                        @csrf

                        {{-- Kode --}}
                        <div class="form-group">
                            <label class="font-weight-bold text-muted small text-uppercase">Kode Transaksi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                </div>
                                <input type="text" class="form-control bg-light font-weight-bold"
                                    value="{{ $kodeMasuk }}" readonly>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>Dibuat otomatis oleh sistem.
                            </small>
                        </div>

                        {{-- Pilih Barang --}}
                        <div class="form-group">
                            <label class="font-weight-bold">Barang <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-box"></i></span>
                                </div>
                                <select class="form-control @error('id_barang') is-invalid @enderror"
                                    id="id_barang" name="id_barang" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barangs as $barang)
                                        <option value="{{ $barang->id_barang }}"
                                            data-harga="{{ $barang->harga_beli }}"
                                            data-stok="{{ $barang->stok_saat_ini }}"
                                            {{ old('id_barang') == $barang->id_barang ? 'selected' : '' }}>
                                            {{ $barang->nama_barang }} (Stok: {{ $barang->stok_saat_ini }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Info Stok --}}
                        <div class="alert alert-info py-2 small d-none" id="infoStok">
                            <i class="fas fa-info-circle mr-1"></i>
                            Stok saat ini: <strong id="stokSaatIni">0</strong>
                        </div>

                        {{-- Supplier --}}
                        <div class="form-group">
                            <label class="font-weight-bold">Supplier <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-truck"></i></span>
                                </div>
                                <select class="form-control @error('id_supplier') is-invalid @enderror"
                                    name="id_supplier" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id_supplier }}"
                                            {{ old('id_supplier') == $supplier->id_supplier ? 'selected' : '' }}>
                                            {{ $supplier->nama_supplier }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_supplier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Tanggal --}}
                        <div class="form-group">
                            <label class="font-weight-bold">Tanggal Masuk <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                </div>
                                <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                    name="tanggal_masuk" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                                @error('tanggal_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            {{-- Jumlah --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Jumlah <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                        </div>
                                        <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                            id="jumlah" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" required>
                                        @error('jumlah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            {{-- Harga Beli --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Harga Beli <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" class="form-control @error('harga_beli') is-invalid @enderror"
                                            id="harga_beli" name="harga_beli" value="{{ old('harga_beli', 0) }}" min="0" required>
                                        @error('harga_beli')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Preview Total --}}
                        <div class="alert alert-success py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold">Total Harga:</span>
                                <span class="font-weight-bold h5 mb-0" id="previewTotal">Rp 0</span>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="form-group">
                            <label class="font-weight-bold">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                name="keterangan" rows="2"
                                placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('barang-masuk.index') }}" class="btn btn-light mr-2">
                                <i class="fas fa-times mr-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i>Simpan Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function hitungTotal() {
        const jumlah = parseFloat(document.getElementById('jumlah').value) || 0;
        const harga  = parseFloat(document.getElementById('harga_beli').value) || 0;
        document.getElementById('previewTotal').textContent =
            'Rp ' + (jumlah * harga).toLocaleString('id-ID');
    }

    document.getElementById('jumlah').addEventListener('input', hitungTotal);
    document.getElementById('harga_beli').addEventListener('input', hitungTotal);

    document.getElementById('id_barang').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        if (this.value) {
            document.getElementById('harga_beli').value = opt.dataset.harga;
            document.getElementById('stokSaatIni').textContent = opt.dataset.stok;
            document.getElementById('infoStok').classList.remove('d-none');
        } else {
            document.getElementById('infoStok').classList.add('d-none');
        }
        hitungTotal();
    });

    hitungTotal();
</script>
@endpush