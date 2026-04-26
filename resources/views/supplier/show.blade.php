{{-- resources/views/supplier/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Supplier')
@section('page-title', 'Detail Supplier')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('supplier.index') }}">Supplier</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title pt-1 mb-0">
                    <i class="fas fa-truck mr-2"></i>Detail Supplier
                </h3>
                <span class="badge badge-{{ $supplier->isAktif() ? 'success' : 'secondary' }} px-3 py-2"
                    style="font-size:.85rem;">
                    <i class="fas fa-{{ $supplier->isAktif() ? 'check' : 'times' }}-circle mr-1"></i>
                    {{ $supplier->isAktif() ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            <div class="card-body p-0">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr class="border-bottom">
                            <td width="38%" class="pl-4 text-muted font-weight-bold small text-uppercase py-3">
                                <i class="fas fa-hashtag mr-2 text-primary"></i>Kode Supplier
                            </td>
                            <td class="py-3">
                                <span class="badge badge-primary px-2 py-1" style="font-size:.9rem;">
                                    {{ $supplier->kode_supplier }}
                                </span>
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="pl-4 text-muted font-weight-bold small text-uppercase py-3">
                                <i class="fas fa-building mr-2 text-primary"></i>Nama Supplier
                            </td>
                            <td class="py-3 font-weight-bold">{{ $supplier->nama_supplier }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="pl-4 text-muted font-weight-bold small text-uppercase py-3">
                                <i class="fas fa-map-marker-alt mr-2 text-primary"></i>Alamat
                            </td>
                            <td class="py-3">{{ $supplier->alamat ?? '-' }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="pl-4 text-muted font-weight-bold small text-uppercase py-3">
                                <i class="fas fa-phone mr-2 text-success"></i>Telepon
                            </td>
                            <td class="py-3">
                                @if($supplier->telepon)
                                    <a href="tel:{{ $supplier->telepon }}">{{ $supplier->telepon }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="pl-4 text-muted font-weight-bold small text-uppercase py-3">
                                <i class="fas fa-envelope mr-2 text-info"></i>Email
                            </td>
                            <td class="py-3">
                                @if($supplier->email)
                                    <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="pl-4 text-muted font-weight-bold small text-uppercase py-3">
                                <i class="fas fa-toggle-on mr-2 text-primary"></i>Status
                            </td>
                            <td class="py-3">
                                <span class="badge badge-{{ $supplier->isAktif() ? 'success' : 'secondary' }} px-2 py-1">
                                    {{ $supplier->isAktif() ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="pl-4 text-muted font-weight-bold small text-uppercase py-3">
                                <i class="fas fa-calendar-plus mr-2 text-primary"></i>Dibuat
                            </td>
                            <td class="py-3 text-muted small">
                                {{ $supplier->created_at->format('d M Y, H:i') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="pl-4 text-muted font-weight-bold small text-uppercase py-3">
                                <i class="fas fa-calendar-check mr-2 text-primary"></i>Diperbarui
                            </td>
                            <td class="py-3 text-muted small">
                                {{ $supplier->updated_at->format('d M Y, H:i') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center">
                <a href="{{ route('supplier.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
                <div>
                    {{-- Toggle Status --}}
                    <form action="{{ route('supplier.toggle-status', $supplier->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="btn btn-{{ $supplier->isAktif() ? 'warning' : 'success' }} mr-1">
                            <i class="fas fa-{{ $supplier->isAktif() ? 'ban' : 'check' }} mr-1"></i>
                            {{ $supplier->isAktif() ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
