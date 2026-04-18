<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finku — Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0f1a3a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-wrapper {
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 560px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,0.4);
        }

        /* Panel kiri */
        .auth-left {
            flex: 1;
            background: #162040;
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .auth-left::before {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(46,95,186,0.15);
            top: -80px; right: -80px;
        }

        .auth-left::after {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(46,95,186,0.1);
            bottom: -60px; left: -40px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
        }

        .brand-icon {
            width: 36px; height: 36px;
            background: #2e5fba;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }

        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            color: #fff;
        }

        .auth-hero { position: relative; z-index: 1; }

        .auth-hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #fff;
            line-height: 1.3;
            margin-bottom: 16px;
        }

        .auth-hero-sub {
            font-size: 14px;
            color: #5a7ab5;
            line-height: 1.7;
        }

        .auth-stats {
            display: flex;
            gap: 24px;
            position: relative;
            z-index: 1;
        }

        .auth-stat {
            background: rgba(46,95,186,0.2);
            border: 1px solid rgba(46,95,186,0.3);
            border-radius: 12px;
            padding: 14px 18px;
        }

        .auth-stat-val {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            color: #fff;
        }

        .auth-stat-label {
            font-size: 11px;
            color: #5a7ab5;
            margin-top: 2px;
        }

        /* Panel kanan */
        .auth-right {
            width: 400px;
            background: #fff;
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-title {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            color: #1a1f2e;
            margin-bottom: 6px;
        }

        .auth-sub {
            font-size: 13px;
            color: #8a96b0;
            margin-bottom: 32px;
        }

        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 7px;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            color: #1a1f2e;
            transition: all 0.15s;
            background: #f8faff;
        }

        .form-control:focus {
            outline: none;
            border-color: #2e5fba;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(46,95,186,0.1);
        }

        .form-error { font-size: 12px; color: #dc2626; margin-top: 4px; }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 13px;
            color: #2e5fba;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover { text-decoration: underline; }

        .btn-auth {
            width: 100%;
            padding: 12px;
            background: #0f1a3a;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
            margin-bottom: 20px;
        }

        .btn-auth:hover { background: #162040; }

        .auth-switch {
            text-align: center;
            font-size: 13px;
            color: #8a96b0;
        }

        .auth-switch a {
            color: #2e5fba;
            font-weight: 600;
            text-decoration: none;
        }

        .auth-switch a:hover { text-decoration: underline; }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <!-- Kiri -->
        <div class="auth-left">
            <div class="brand">
                <div class="brand-icon">
                    <svg width="18" height="18" viewBox="0 0 16 16" fill="none">
                        <path d="M8 2L13 5V11L8 14L3 11V5L8 2Z" stroke="#90b4f0" stroke-width="1.5" fill="none"/>
                        <circle cx="8" cy="8" r="2" fill="#90b4f0"/>
                    </svg>
                </div>
                <div class="brand-name">Finku</div>
            </div>

            <div class="auth-hero">
                <div class="auth-hero-title">Kelola keuanganmu dengan lebih cerdas.</div>
                <div class="auth-hero-sub">Catat setiap pemasukan dan pengeluaran, pantau tren keuanganmu, dan raih tujuan finansialmu.</div>
            </div>

            <div class="auth-stats">
                <div class="auth-stat">
                    <div class="auth-stat-val">100%</div>
                    <div class="auth-stat-label">Gratis</div>
                </div>
                <div class="auth-stat">
                    <div class="auth-stat-val">∞</div>
                    <div class="auth-stat-label">Transaksi</div>
                </div>
                <div class="auth-stat">
                    <div class="auth-stat-val">Aman</div>
                    <div class="auth-stat-label">Data Privat</div>
                </div>
            </div>
        </div>

        <!-- Kanan -->
        <div class="auth-right">
            <div class="auth-title">Selamat datang!</div>
            <div class="auth-sub">Masuk ke akun Finku kamu</div>

            @if($errors->any())
            <div class="alert-error">Email atau password salah.</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="••••••••" required>
                    @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-footer">
                    <label class="remember-label">
                        <input type="checkbox" name="remember">
                        Ingat saya
                    </label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-auth">Masuk →</button>
            </form>

            <div class="auth-switch">
                Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
            </div>
        </div>
    </div>
</body>
</html>