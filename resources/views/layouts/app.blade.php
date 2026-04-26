<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'StockManager')</title>

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        {{-- Navbar --}}
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                {{-- Info user --}}
                <li class="nav-item">
                    <span class="nav-link">
                        <i class="fas fa-user-circle mr-1"></i>
                        <strong>{{ Auth::user()->nama_lengkap }}</strong>
                        <span class="badge badge-success ml-1">{{ Auth::user()->role }}</span>
                    </span>
                </li>

                {{-- Tombol logout langsung (tanpa dropdown) --}}
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-danger"
                            style="border:none; background:none; cursor:pointer;"
                            onclick="return confirm('Yakin ingin logout?')">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        {{-- Sidebar --}}
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ route('dashboard') }}" class="brand-link">
                <span class="brand-text font-weight-bold ml-3">
                    <i class="fas fa-boxes mr-1"></i> StockManager
                </span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">

                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-header">MASTER DATA</li>

                        <li class="nav-item">
                            <a href="{{ route('barang.index') }}"
                                class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-box"></i>
                                <p>Data Barang</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('kategori.index') }}" class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Kategori</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('supplier.index') }}"
                                class="nav-link {{ request()->routeIs('supplier.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-truck"></i>
                                <p>Supplier</p>
                            </a>
                        </li>

                        <li class="nav-header">TRANSAKSI</li>

                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ request()->routeIs('barang-masuk.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-arrow-circle-down text-success"></i>
                                <p>Barang Masuk</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ request()->routeIs('barang-keluar.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-arrow-circle-up text-danger"></i>
                                <p>Barang Keluar</p>
                            </a>
                        </li>

                        <li class="nav-header">LAPORAN</li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>Laporan Stok</p>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>

        {{-- Content Wrapper --}}
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page-title')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @yield('breadcrumb')
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="main-footer">
            <strong>&copy; {{ date('Y') }} StockManager.</strong> Sistem Manajemen Stok Toko.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>

    @stack('scripts')
</body>

</html>
