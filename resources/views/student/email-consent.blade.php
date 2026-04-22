@extends('layouts.app')
@section('title', 'Preferensi Notifikasi Email')
@section('content')
<div class="register-page">
    <div class="container py-5">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0" style="border-radius: 1rem;">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div class="register-icon mx-auto mb-3">
                                <i class="bi bi-envelope-heart"></i>
                            </div>
                            <h4 class="fw-bold">Notifikasi Email</h4>
                            <p class="text-muted small">Sebelum melanjutkan, pilih preferensi notifikasi Anda</p>
                        </div>

                        <div class="alert alert-light border mb-4">
                            <p class="mb-2 fw-semibold"><i class="bi bi-info-circle me-1 text-primary"></i>Jenis notifikasi yang akan dikirim:</p>
                            <ul class="mb-0 small">
                                <li><i class="bi bi-megaphone me-1 text-danger"></i>Berita & pengumuman terbaru</li>
                                <li><i class="bi bi-calendar-check me-1 text-success"></i>Pembukaan periode pendataan KHS</li>
                                <li><i class="bi bi-bell me-1 text-warning"></i>Informasi penting lainnya</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <form method="POST" action="{{ route('email.consent.store') }}">
                                @csrf
                                <input type="hidden" name="email_opt_in" value="1">
                                <button type="submit" class="btn btn-danger w-100 py-2 fw-semibold mb-2" id="btn-opt-in">
                                    <i class="bi bi-check-circle me-2"></i>Ya, Kirimkan Notifikasi
                                </button>
                            </form>
                            <form method="POST" action="{{ route('email.consent.store') }}">
                                @csrf
                                <input type="hidden" name="email_opt_in" value="0">
                                <button type="submit" class="btn btn-outline-secondary w-100 py-2 fw-semibold" id="btn-opt-out">
                                    <i class="bi bi-x-circle me-2"></i>Tidak, Terima Kasih
                                </button>
                            </form>
                        </div>

                        <p class="text-muted text-center mt-3" style="font-size: 0.75rem;">
                            <i class="bi bi-shield-check me-1"></i>Email Anda tidak akan dibagikan ke pihak ketiga.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
