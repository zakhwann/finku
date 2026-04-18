<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finku — Daftar</title>
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
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,0.4);
        }

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

        .steps { position: relative; z-index: 1; }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 18px;
        }

        .step-num {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: #2e5fba;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .step-title { font-size: 13px; font-weight: 600; color: #c8d8f0; }
        .step-desc { font-size: 12px; color: #5a7ab5; margin-top: 2px; }

        .auth-right {
            width: 420px;
            background: #fff;
            padding: 48px 44px;
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
            margin-bottom: 28px;
        }

        .form-group { margin-bottom: 16px; }

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
            margin-top: 8px;
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
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <!-- Kiri -->
        <div class="auth-left">
            <div class="brand">
                <div style="width:36px;height:36px;flex-shrink:0;">
    <svg width="36" height="36" viewBox="0 0 32 32" fill="none">
        <circle cx="16" cy="16" r="14" fill="#2e5fba"/>
        <circle cx="16" cy="16" r="10" fill="none" stroke="#5a8ee8" stroke-width="1" stroke-dasharray="2 2"/>
        <text x="16" y="21" text-anchor="middle" font-family="serif" font-size="14" font-weight="600" fill="#fff">F</text>
    </svg>
</div>
                <div class="brand-name">Finku</div>
            </div>

            <div class="auth-hero">
                <div class="auth-hero-title">Mulai perjalanan finansialmu hari ini.</div>
                <div class="auth-hero-sub">Daftar gratis dan mulai catat keuanganmu dalam hitungan menit.</div>
            </div>

            <div class="steps">
                <div class="step">
                    <div class="step-num">1</div>
                    <div>
                        <div class="step-title">Buat akun gratis</div>
                        <div class="step-desc">Daftar hanya butuh 30 detik</div>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div>
                        <div class="step-title">Buat kategori</div>
                        <div class="step-desc">Sesuaikan dengan kebiasaanmu</div>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <div>
                        <div class="step-title">Catat transaksi</div>
                        <div class="step-desc">Pantau keuanganmu setiap hari</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kanan -->
        <div class="auth-right">
            <div class="auth-title">Buat akun baru</div>
            <div class="auth-sub">Gratis selamanya, tanpa kartu kredit</div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control"
                           placeholder="Zakhwan" value="{{ old('name') }}" required autofocus>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           placeholder="nama@email.com" value="{{ old('email') }}" required>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="Min. 8 karakter" required>
                    @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="Ulangi password" required>
                </div>

                <button type="submit" class="btn-auth">Daftar Sekarang →</button>
            </form>

            <div class="auth-switch">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>
</body>
</html>