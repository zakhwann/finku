<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finku — Kelola Keuanganmu dengan Cerdas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Fraunces:opsz,wght@9..144,300;9..144,400;9..144,600&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #fff;
            color: #1a1f2e;
            overflow-x: hidden;
        }

        /* ── Navbar ── */
        nav {
            position: fixed; top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 18px 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #f0f4fc;
        }

        .nav-brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }

        .nav-logo {
            width: 34px; height: 34px;
        }

        .nav-brand-text {
            font-family: 'Fraunces', serif;
            font-size: 20px;
            color: #0f1a3a;
        }

        .nav-links {
            display: flex; align-items: center; gap: 32px;
        }

        .nav-link {
            font-size: 14px;
            color: #5a6a8a;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s;
        }

        .nav-link:hover { color: #0f1a3a; }

        .nav-cta {
            display: flex; gap: 10px;
        }

        .btn-outline {
            padding: 8px 20px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px; font-weight: 500;
            color: #374151; background: #fff;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-outline:hover { border-color: #2e5fba; color: #2e5fba; }

        .btn-solid {
            padding: 8px 20px;
            border: none;
            border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px; font-weight: 600;
            color: #fff; background: #0f1a3a;
            text-decoration: none;
            transition: background 0.15s;
        }

        .btn-solid:hover { background: #2e5fba; }

        /* ── Hero ── */
        .hero {
            min-height: 100vh;
            background: #0f1a3a;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 120px 60px 80px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: rgba(46,95,186,0.15);
            top: -200px; right: -100px;
        }

        .hero::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(46,95,186,0.1);
            bottom: -150px; left: -100px;
        }

        .hero-content { position: relative; z-index: 1; max-width: 700px; }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(46,95,186,0.2);
            border: 1px solid rgba(46,95,186,0.3);
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 12px;
            color: #90b4f0;
            font-weight: 500;
            margin-bottom: 28px;
            letter-spacing: 0.3px;
        }

        .hero-title {
            font-family: 'Fraunces', serif;
            font-size: 58px;
            color: #fff;
            line-height: 1.15;
            letter-spacing: -1.5px;
            margin-bottom: 20px;
            font-weight: 400;
        }

        .hero-title span { color: #90b4f0; }

        .hero-sub {
            font-size: 16px;
            color: #7a98cc;
            line-height: 1.7;
            margin-bottom: 40px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            padding: 14px 32px;
            background: #2e5fba;
            color: #fff;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px; font-weight: 600;
            text-decoration: none;
            transition: background 0.15s;
            border: none;
        }

        .btn-hero-primary:hover { background: #2452a8; }

        .btn-hero-secondary {
            padding: 14px 32px;
            background: rgba(255,255,255,0.08);
            color: #c8d8f0;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px; font-weight: 500;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,0.12);
            transition: all 0.15s;
        }

        .btn-hero-secondary:hover { background: rgba(255,255,255,0.12); }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 48px;
            margin-top: 60px;
            padding-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        .hero-stat-val {
            font-family: 'Fraunces', serif;
            font-size: 28px;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .hero-stat-label {
            font-size: 12px;
            color: #5a7ab5;
            margin-top: 4px;
        }

        /* ── Features ── */
        .section {
            padding: 96px 60px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .section-badge {
            display: inline-block;
            background: #eff4ff;
            color: #2e5fba;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 20px;
            margin-bottom: 16px;
        }

        .section-title {
            font-family: 'Fraunces', serif;
            font-size: 40px;
            color: #0f1a3a;
            letter-spacing: -1px;
            line-height: 1.2;
            margin-bottom: 14px;
            font-weight: 400;
        }

        .section-sub {
            font-size: 15px;
            color: #8a96b0;
            line-height: 1.7;
            max-width: 520px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-top: 56px;
        }

        .feature-card {
            padding: 28px;
            border-radius: 16px;
            border: 1px solid #e4e9f5;
            background: #fff;
            transition: box-shadow 0.2s, border-color 0.2s;
        }

        .feature-card:hover {
            border-color: #b8cef0;
            box-shadow: 0 8px 32px rgba(46,95,186,0.08);
        }

        .feature-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            background: #eff4ff;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            margin-bottom: 18px;
        }

        .feature-title {
            font-size: 15px;
            font-weight: 600;
            color: #0f1a3a;
            margin-bottom: 8px;
        }

        .feature-desc {
            font-size: 13.5px;
            color: #8a96b0;
            line-height: 1.6;
        }

        /* ── How it works ── */
        .how-section {
            background: #f8faff;
            padding: 96px 60px;
        }

        .how-inner {
            max-width: 1100px;
            margin: 0 auto;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
            margin-top: 56px;
        }

        .step-card {
            text-align: center;
            padding: 32px 24px;
        }

        .step-num {
            width: 48px; height: 48px;
            border-radius: 50%;
            background: #0f1a3a;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-family: 'Fraunces', serif;
        }

        .step-title {
            font-size: 16px;
            font-weight: 600;
            color: #0f1a3a;
            margin-bottom: 8px;
        }

        .step-desc {
            font-size: 13.5px;
            color: #8a96b0;
            line-height: 1.6;
        }

        /* ── CTA Section ── */
        .cta-section {
            background: #0f1a3a;
            padding: 96px 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(46,95,186,0.2);
            top: -150px; right: -100px;
        }

        .cta-section::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(46,95,186,0.15);
            bottom: -100px; left: -80px;
        }

        .cta-content { position: relative; z-index: 1; }

        .cta-title {
            font-family: 'Fraunces', serif;
            font-size: 44px;
            color: #fff;
            letter-spacing: -1px;
            margin-bottom: 16px;
            font-weight: 400;
        }

        .cta-sub {
            font-size: 15px;
            color: #7a98cc;
            margin-bottom: 36px;
        }

        /* ── Footer ── */
        footer {
            background: #080f22;
            padding: 32px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-brand {
            font-family: 'Fraunces', serif;
            font-size: 18px;
            color: #fff;
        }

        .footer-copy {
            font-size: 12px;
            color: #3d5480;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav>
        <a href="/" class="nav-brand">
            <svg class="nav-logo" width="34" height="34" viewBox="0 0 32 32" fill="none">
                <circle cx="16" cy="16" r="14" fill="#2e5fba"/>
                <circle cx="16" cy="16" r="10" fill="none" stroke="#5a8ee8" stroke-width="1" stroke-dasharray="2 2"/>
                <text x="16" y="21" text-anchor="middle" font-family="serif" font-size="14" font-weight="600" fill="#fff">F</text>
            </svg>
            <span class="nav-brand-text">Finku</span>
        </a>
        <div class="nav-links">
            <a href="#fitur" class="nav-link">Fitur</a>
            <a href="#cara-kerja" class="nav-link">Cara Kerja</a>
        </div>
        <div class="nav-cta">
            <a href="{{ route('login') }}"    class="btn-outline">Masuk</a>
            <a href="{{ route('register') }}" class="btn-solid">Daftar Gratis</a>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge">
                ✦ Gratis Selamanya
            </div>
            <h1 class="hero-title">
                Kelola keuangan<br>
                dengan lebih <span>cerdas</span>
            </h1>
            <p class="hero-sub">
                Catat pemasukan & pengeluaran, pantau budget bulanan, dan dapatkan laporan keuangan lengkap dalam satu aplikasi.
            </p>
            <div class="hero-actions">
                <a href="{{ route('register') }}" class="btn-hero-primary">Mulai Gratis →</a>
                <a href="{{ route('login') }}"    class="btn-hero-secondary">Sudah punya akun</a>
            </div>
            <div class="hero-stats">
                <div>
                    <div class="hero-stat-val">100%</div>
                    <div class="hero-stat-label">Gratis</div>
                </div>
                <div>
                    <div class="hero-stat-val">∞</div>
                    <div class="hero-stat-label">Transaksi</div>
                </div>
                <div>
                    <div class="hero-stat-val">Aman</div>
                    <div class="hero-stat-label">Data Privat</div>
                </div>
                <div>
                    <div class="hero-stat-val">PDF</div>
                    <div class="hero-stat-label">Export Laporan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="section" id="fitur">
        <div class="section-badge">Fitur</div>
        <h2 class="section-title">Semua yang kamu butuhkan<br>dalam satu tempat</h2>
        <p class="section-sub">Finku dirancang khusus untuk mahasiswa dan anak muda yang ingin mulai mengatur keuangan dengan cara yang simpel.</p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <div class="feature-title">Dashboard Real-time</div>
                <div class="feature-desc">Lihat ringkasan saldo, pemasukan, dan pengeluaran bulan ini dalam sekali pandang.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💸</div>
                <div class="feature-title">Catat Transaksi</div>
                <div class="feature-desc">Catat setiap pemasukan dan pengeluaran dengan kategori custom yang bisa kamu atur sendiri.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <div class="feature-title">Budget Bulanan</div>
                <div class="feature-desc">Set batas pengeluaran per kategori dan pantau progress-nya agar tidak over budget.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📈</div>
                <div class="feature-title">Grafik Arus Kas</div>
                <div class="feature-desc">Visualisasi tren keuangan 6 bulan terakhir dengan grafik yang interaktif dan mudah dibaca.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📄</div>
                <div class="feature-title">Export Laporan</div>
                <div class="feature-desc">Download laporan keuangan bulanan dalam format PDF atau Excel kapan saja kamu butuhkan.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <div class="feature-title">Data Aman & Privat</div>
                <div class="feature-desc">Data keuangan kamu hanya bisa diakses oleh kamu sendiri, tidak ada pihak lain yang bisa melihat.</div>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section class="how-section" id="cara-kerja">
        <div class="how-inner">
            <div style="text-align:center;">
                <div class="section-badge">Cara Kerja</div>
                <h2 class="section-title">Mulai dalam 3 langkah mudah</h2>
                <p class="section-sub" style="margin: 0 auto;">Tidak perlu setup yang rumit. Daftar, buat kategori, dan langsung catat transaksimu.</p>
            </div>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-num">1</div>
                    <div class="step-title">Daftar Gratis</div>
                    <div class="step-desc">Buat akun dalam 30 detik. Tidak perlu kartu kredit atau informasi pembayaran apapun.</div>
                </div>
                <div class="step-card">
                    <div class="step-num">2</div>
                    <div class="step-title">Buat Kategori</div>
                    <div class="step-desc">Sesuaikan kategori pengeluaran dan pemasukan sesuai kebiasaan finansialmu.</div>
                </div>
                <div class="step-card">
                    <div class="step-num">3</div>
                    <div class="step-title">Catat & Pantau</div>
                    <div class="step-desc">Mulai catat transaksi harian dan lihat laporan keuanganmu berkembang dari waktu ke waktu.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
        <div class="cta-content">
            <h2 class="cta-title">Siap mulai mengatur<br>keuanganmu?</h2>
            <p class="cta-sub">Bergabung sekarang dan mulai perjalanan finansialmu hari ini.</p>
            <a href="{{ route('register') }}" class="btn-hero-primary" style="font-size:15px;padding:14px 36px;">
                Daftar Gratis Sekarang →
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-brand">Finku</div>
        <div class="footer-copy">© {{ now()->year }} Finku · Dibuat dengan ❤️ oleh Zakhwan</div>
    </footer>

</body>
</html>