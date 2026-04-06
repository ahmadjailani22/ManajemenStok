<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — StockManager</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }
        .brand { display: flex; align-items: center; gap: 10px; margin-bottom: 1.5rem; }
        .brand-icon {
            width: 36px; height: 36px;
            background: #1D9E75; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 18px;
        }
        h2 { font-size: 1.2rem; margin-bottom: 4px; }
        .subtitle { font-size: 0.85rem; color: #6b7280; margin-bottom: 1.5rem; }
        .field { margin-bottom: 1rem; }
        label { display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 6px; color: #374151; }
        input[type=email], input[type=password] {
            width: 100%; padding: 9px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px; font-size: 0.9rem;
            outline: none; transition: border .2s;
        }
        input:focus { border-color: #1D9E75; box-shadow: 0 0 0 2px rgba(29,158,117,.15); }
        .row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; font-size: 0.85rem; }
        .check-wrap { display: flex; align-items: center; gap: 6px; color: #6b7280; }
        .forgot { color: #1D9E75; text-decoration: none; }
        .btn {
            width: 100%; padding: 10px;
            background: #1D9E75; color: white;
            font-size: 0.95rem; font-weight: 500;
            border: none; border-radius: 8px; cursor: pointer;
        }
        .btn:hover { background: #0F6E56; }
        .alert-error {
            background: #fef2f2; border: 1px solid #fecaca;
            border-radius: 8px; padding: 10px 14px;
            color: #b91c1c; font-size: 0.85rem; margin-bottom: 1rem;
        }
        .invalid-feedback { color: #dc2626; font-size: 0.8rem; margin-top: 4px; }
        .footer { text-align: center; font-size: 0.78rem; color: #9ca3af; margin-top: 1.25rem; }
    </style>
</head>
<body>
<div class="card">
    <div class="brand">
        <div class="brand-icon">&#9632;</div>
        <div>
            <strong>StockManager</strong>
            <div style="font-size:0.78rem;color:#6b7280">Sistem Manajemen Stok</div>
        </div>
    </div>

    {{-- Tampilkan error dari session --}}
    @if ($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <h2>Masuk ke Akun</h2>
    <p class="subtitle">Silakan login untuk melanjutkan</p>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="field">
            <label for="username">Username</label>
            <input type="text" id="username" name="username"
                value="{{ old('username') }}"
                placeholder="Masukkan username"
                autocomplete="username" autofocus>
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="••••••••"
                   autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <label class="check-wrap">
                <input type="checkbox" name="remember"> Ingat saya
            </label>
            <a href="#" class="forgot">Lupa password?</a>
        </div>

        <button type="submit" class="btn">Masuk</button>
    </form>

    <div class="footer">&copy; {{ date('Y') }} StockManager — Hak akses hanya untuk staff toko</div>
</div>
</body>
</html>