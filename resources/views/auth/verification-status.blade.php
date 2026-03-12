@extends('layouts.app')
@section('title', auth()->user()->status === 'pending' ? 'Menunggu Verifikasi' : 'Pendaftaran Ditolak')
@section('content')
    <div class="verification-status-page">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-md-6 col-lg-5">
                    @if(auth()->user()->status === 'pending')
                        {{-- PENDING STATE --}}
                        <div class="status-card pending-card text-center">
                            <div class="status-icon pending-icon">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Menunggu Verifikasi</h3>
                            <p class="text-muted mb-4">
                                Akun Anda sedang dalam proses verifikasi oleh admin.
                                Proses ini biasanya memakan waktu 1-3 hari kerja.
                                Anda akan mendapatkan notifikasi melalui email setelah akun diverifikasi.
                            </p>
                            <div class="status-info mb-4">
                                <div class="d-flex align-items-center justify-content-center gap-2 text-muted">
                                    <i class="bi bi-person"></i>
                                    <span>{{ auth()->user()->name }}</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-center gap-2 text-muted mt-1">
                                    <i class="bi bi-envelope"></i>
                                    <span>{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                            <div class="spinner-border text-warning mb-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="small text-muted mb-4">Silakan cek halaman ini secara berkala.</p>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- REJECTED STATE --}}
                        <div class="status-card rejected-card text-center">
                            <div class="status-icon rejected-icon">
                                <i class="bi bi-x-circle"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Pendaftaran Ditolak</h3>
                            <p class="text-muted mb-4">
                                Maaf, pendaftaran akun Anda ditolak oleh admin.
                                Jika Anda merasa ini adalah kesalahan, silakan hubungi tim Abitha
                                untuk informasi lebih lanjut.
                            </p>
                            <div class="status-info mb-4">
                                <div class="d-flex align-items-center justify-content-center gap-2 text-muted">
                                    <i class="bi bi-person"></i>
                                    <span>{{ auth()->user()->name }}</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-center gap-2 text-muted mt-1">
                                    <i class="bi bi-envelope"></i>
                                    <span>{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <a href="https://wa.me/6283185132009" target="_blank" class="btn btn-danger">
                                    <i class="bi bi-whatsapp me-2"></i>Hubungi Abitha
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .verification-status-page {
                background: linear-gradient(135deg, #1a1d2e 0%, #2c3e50 100%);
                min-height: 100vh;
                padding-top: 80px;
            }

            .status-card {
                background: #fff;
                border-radius: 1rem;
                padding: 3rem 2rem;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            }

            .status-icon {
                width: 90px;
                height: 90px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 2.5rem;
                margin-bottom: 1.5rem;
            }

            .pending-icon {
                background: rgba(217, 119, 6, 0.1);
                color: #d97706;
                animation: pulse-pending 2s infinite;
            }

            .rejected-icon {
                background: rgba(220, 38, 38, 0.1);
                color: #dc2626;
            }

            .status-info {
                background: #f8f9fa;
                border-radius: 0.5rem;
                padding: 1rem;
            }

            @keyframes pulse-pending {

                0%,
                100% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.05);
                }
            }
        </style>
    @endpush
@endsection