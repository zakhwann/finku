<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finku — {{ $title ?? 'Dashboard' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600&display=swap');
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f0f2f8;
            color: #1a1f2e;
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 230px;
            background: #0f1a3a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: fixed;
            top: 0; left: 0; bottom: 0;
        }

        .logo {
            padding: 28px 24px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .logo-mark { display: flex; align-items: center; gap: 10px; }

        .logo-icon {
            width: 32px; height: 32px;
            background: #2e5fba;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }

        .logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .logo-sub {
            font-size: 10px;
            color: #5a7ab5;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .nav { padding: 20px 12px; flex: 1; }

        .nav-label {
            font-size: 10px;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #3d5480;
            padding: 0 12px;
            margin-bottom: 6px;
            margin-top: 16px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13.5px;
            color: #7a98cc;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
            margin-bottom: 2px;
            font-weight: 400;
        }

        .nav-item:hover { background: rgba(255,255,255,0.06); color: #c8d8f0; }

        .nav-item.active {
            background: #2e5fba;
            color: #fff;
            font-weight: 500;
        }

        .nav-icon { width: 16px; text-align: center; font-size: 15px; }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        .user-chip { display: flex; align-items: center; gap: 10px; }

        .avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: #2e5fba;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 600; color: #fff;
            flex-shrink: 0;
        }

        .user-name { font-size: 13px; font-weight: 500; color: #c8d8f0; }
        .user-role { font-size: 11px; color: #4a6a9e; }

        .logout-btn {
            background: none; border: none;
            color: #4a6a9e; font-size: 11px;
            cursor: pointer; padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin-top: 8px;
            display: block;
            transition: color 0.15s;
        }
        .logout-btn:hover { color: #c8d8f0; }

        /* ── Main ── */
        .main {
            margin-left: 230px;
            flex: 1;
            padding: 32px 36px;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            color: #1a1f2e;
            letter-spacing: -0.5px;
        }

        .page-sub { font-size: 13px; color: #8a96b0; margin-top: 3px; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 18px; border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px; font-weight: 500;
            cursor: pointer; text-decoration: none;
            border: none; transition: all 0.15s;
        }

        .btn-primary { background: #2e5fba; color: #fff; }
        .btn-primary:hover { background: #2452a8; }
        .btn-danger { background: #fee2e2; color: #b91c1c; }
        .btn-danger:hover { background: #fecaca; }
        .btn-secondary { background: #fff; color: #4a5568; border: 1px solid #e2e8f0; }
        .btn-secondary:hover { background: #f8fafc; }

        /* ── Cards ── */
        .card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e4e9f5;
            padding: 24px;
        }

        .card-header {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 20px;
        }

        .card-title { font-size: 14px; font-weight: 600; color: #1a1f2e; }
        .card-link { font-size: 12px; color: #2e5fba; text-decoration: none; font-weight: 500; }
        .card-link:hover { text-decoration: underline; }

        /* ── Alert ── */
        .alert {
            padding: 12px 16px; border-radius: 8px;
            font-size: 13px; margin-bottom: 20px;
        }
        .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── Forms ── */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px; }
        .form-control {
            width: 100%; padding: 10px 14px;
            border: 1px solid #e2e8f0; border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px;
            color: #1a1f2e; transition: border 0.15s;
            background: #fff;
        }
        .form-control:focus { outline: none; border-color: #2e5fba; box-shadow: 0 0 0 3px rgba(46,95,186,0.1); }
        .form-error { font-size: 12px; color: #dc2626; margin-top: 4px; }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th {
            text-align: left; font-size: 11px; font-weight: 600;
            letter-spacing: 0.5px; text-transform: uppercase;
            color: #8a96b0; padding: 10px 14px;
            border-bottom: 1px solid #e4e9f5;
        }
        td { padding: 13px 14px; font-size: 13.5px; border-bottom: 1px solid #f0f4fc; color: #374151; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8faff; }

        /* ── Badges ── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11.5px; font-weight: 500;
        }
        .badge-income { background: #ecfdf5; color: #065f46; }
        .badge-expense { background: #fef2f2; color: #991b1b; }

        /* ── Amount colors ── */
        .amount-income { color: #059669; font-weight: 600; }
        .amount-expense { color: #dc2626; font-weight: 600; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <div class="logo-mark">
                <div class="logo-icon">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M8 2L13 5V11L8 14L3 11V5L8 2Z" stroke="#90b4f0" stroke-width="1.5" fill="none"/>
                        <circle cx="8" cy="8" r="2" fill="#90b4f0"/>
                    </svg>
                </div>
                <div>
                    <div class="logo-text">Finku</div>
                    <div class="logo-sub">Personal Finance</div>
                </div>
            </div>
        </div>

        <nav class="nav">
            <div class="nav-label">Menu</div>
            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">◉</span> Dashboard
            </a>
            <a href="{{ route('transactions.index') }}"
               class="nav-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                <span class="nav-icon">↕</span> Transaksi
            </a>
            <a href="{{ route('categories.index') }}"
               class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <span class="nav-icon">◈</span> Kategori
            </a>

            <div class="nav-label">Akun</div>
            <span class="nav-item" style="cursor:default;opacity:0.5;">
                <span class="nav-icon">◎</span> {{ Auth::user()->name }}
            </span>
        </nav>

        <div class="sidebar-footer">
            <div class="user-chip">
                <div class="avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div>
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">Personal Finance</div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">Keluar →</button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main -->
    <main class="main">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>