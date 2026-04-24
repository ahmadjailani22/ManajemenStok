@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Data Kategori</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card card-warning card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Form Edit Kategori</h3>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('kategori.update', $kategori->id_kategori) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="nama_kategori" class="form-control" 
                    value="{{ old('nama_kategori', $kategori->nama_kategori) }}" placeholder="Masukkan nama kategori" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4" 
                    placeholder="Masukkan deskripsi kategori">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
            </div>
            <div class="form-group">
                <a href="{{ route('kategori.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save mr-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
