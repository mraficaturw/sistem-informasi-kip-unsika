<!DOCTYPE html>
<html lang="id">

<head>
    {{-- Character encoding & compatibility --}}
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO: Deskripsi halaman (dapat di-override per halaman via @section('description')) --}}
    <meta name="description" content="@yield('description', 'Pusat Informasi Mahasiswa KIP Kuliah Universitas Singaperbangsa Karawang (KIP UNSIKA) — berita, tracking pencairan, dan form pendataan KIP-K.')">
    <meta name="keywords" content="KIP Kuliah, UNSIKA, beasiswa, KIP UNSIKA, Universitas Singaperbangsa Karawang, FORMADIKIP">
    <meta name="robots" content="index, follow">
    <meta name="author" content="FORMADIKIP UNSIKA">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- SEO: Open Graph (preview saat dibagikan ke sosmed/WA) --}}
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="KIP UNSIKA">
    <meta property="og:title"       content="@yield('title', 'KIP UNSIKA') — Pusat Informasi KIP Kuliah">
    <meta property="og:description" content="@yield('description', 'Pusat Informasi Mahasiswa KIP Kuliah Universitas Singaperbangsa Karawang — berita, tracking pencairan, dan form pendataan KIP-K.')">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:image"       content="{{ asset('images/og-image.png') }}">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt"    content="KIP UNSIKA — Pusat Informasi KIP Kuliah Universitas Singaperbangsa Karawang">
    <meta property="og:locale"       content="id_ID">

    {{-- Twitter / X Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="@yield('title', 'KIP UNSIKA') — Pusat Informasi KIP Kuliah">
    <meta name="twitter:description" content="@yield('description', 'Pusat Informasi Mahasiswa KIP Kuliah Universitas Singaperbangsa Karawang — berita, tracking pencairan, dan form pendataan KIP-K.')">
    <meta name="twitter:image"       content="{{ asset('images/og-image.png') }}">

    {{-- PWA / Mobile Browser --}}
    <meta name="theme-color" content="#e84545">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="KIP UNSIKA">

    {{-- Favicon & Icons --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/og-image.png') }}">

    <title>@yield('title', 'KIP UNSIKA') — Pusat Informasi KIP Kuliah</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,400;0,600;0,700;0,800;1,400&family=Open+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    @stack('styles')
</head>

<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dewi {{ request()->is('/') ? 'transparent' : 'scrolled' }} fixed-top"
        id="mainNavbar"
        role="navigation"
        aria-label="Navigasi utama">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}" aria-label="KIP UNSIKA — Beranda">
                <i class="bi bi-mortarboard-fill me-2 fs-4" aria-hidden="true"></i>
                <span>KIP UNSIKA</span>
            </a>
            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('announcements*') ? 'active' : '' }}"
                            href="{{ route('announcements.index') }}">
                            Berita
                        </a>
                    </li>
                    @auth
                        @if(auth()->user()->isStudent() && auth()->user()->isApproved())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}"
                                    href="{{ route('dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-outline-light-custom px-3 py-2" href="{{ route('register') }}">
                                Daftar
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(auth()->user()->isStudent() && auth()->user()->isApproved())
                                <li>
                                    <a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('settings') }}"><i class="bi bi-gear me-2"></i>Pengaturan</a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                @elseif(auth()->user()->isAdmin())
                                <li>
                                    <a class="dropdown-item" href="{{ url('/admin') }}"><i class="bi bi-speedometer2 me-2"></i>Admin Panel</a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i
                                                class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="position-fixed top-0 start-50 translate-middle-x p-3 pt-5" style="z-index: 9999;">
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="position-fixed top-0 start-50 translate-middle-x p-3 pt-5" style="z-index: 9999;">
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="footer-brand mb-3">
                        <i class="bi bi-mortarboard-fill me-2"></i>KIP UNSIKA
                    </div>
                    <p class="mb-3" style="font-size: 0.9rem;">Pusat Informasi Mahasiswa KIP Kuliah Universitas
                        Singaperbangsa Karawang. Dikelola oleh FORMADIKIP UNSIKA.</p>
                    <div class="social-links">
                        <a href="https://www.instagram.com/formadikipunsika/"><i class="bi bi-instagram"></i></a>
                        <a href="https://wa.me/6283185132009"><i class="bi bi-whatsapp"></i></a>
                        <a href="#"><i class="bi bi-envelope"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h6 class="footer-title">Navigasi</h6>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Beranda</a></li>
                        <li><a href="{{ route('announcements.index') }}">Berita</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h6 class="footer-title">Informasi</h6>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}#persyaratan">Persyaratan KIP</a></li>
                        <li><a href="{{ url('/') }}#tracking">Tracking Pencairan</a></li>
                        <li><a href="{{ url('/') }}#unduh-sk">Unduh SK</a></li>
                        <li><a href="{{ url('/') }}#faq">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h6 class="footer-title">Kontak</h6>
                    <div class="footer-contact-item">
                        <i class="bi bi-geo-alt"></i>
                        <span>Universitas Singaperbangsa Karawang, Jl. HS. Ronggowaluyo, Karawang</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-envelope"></i>
                        <span>formadikip@unsika.ac.id</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-clock"></i>
                        <span>Senin - Jumat, 08:00 - 16:00 WIB</span>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; {{ date('Y') }} KIP UNSIKA. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0">Dikelola oleh FORMADIKIP UNSIKA</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- Back to Top --}}
    <button class="back-to-top" id="backToTop" title="Kembali ke atas">
        <i class="bi bi-arrow-up"></i>
    </button>

    @stack('scripts')
</body>

</html>