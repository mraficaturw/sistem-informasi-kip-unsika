@extends('layouts.app')
@section('title', 'Pengaturan')
@section('content')
<div class="page-header">
    <div class="container">
        <h4 class="fw-bold mb-4">Pengaturan</h4>
        <p class="text-muted mb-0">Kelola akun dan preferensi Anda</p>
    </div>
</div>
<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">

            {{-- Section: Notifikasi Email --}}
            <div class="card shadow-sm mb-4" id="notifications">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-bell me-2"></i>Notifikasi Email</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Tentukan apakah Anda ingin menerima notifikasi email saat ada berita baru, pembukaan periode pendataan, dan informasi penting lainnya.</p>

                    <form method="POST" action="{{ route('settings.email') }}">
                        @csrf
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="email_opt_in"
                                   name="email_opt_in" value="1"
                                   {{ auth()->user()->email_opt_in ? 'checked' : '' }}
                                   onchange="this.form.submit()">
                            <label class="form-check-label fw-semibold" for="email_opt_in">
                                Terima notifikasi email
                            </label>
                        </div>
                        @if(!auth()->user()->email_opt_in)
                            <input type="hidden" name="email_opt_in" value="0">
                        @endif
                    </form>

                    <div class="alert alert-light border small mb-0">
                        <i class="bi bi-info-circle me-1 text-primary"></i>
                        Status saat ini:
                        @if(auth()->user()->email_opt_in)
                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Nonaktif</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Section: Ubah Password --}}
            <div class="card shadow-sm mb-4" id="password">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-key me-2"></i>Ubah Password</h6>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger border-0">
                            @foreach($errors->all() as $error)
                                <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('settings.password') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="current_password" name="current_password" required placeholder="Masukkan password saat ini">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="Huruf besar, kecil, dan angka">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-key-fill"></i></span>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password baru">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger py-2 fw-semibold">
                            <i class="bi bi-check-circle me-2"></i>Simpan Password Baru
                        </button>
                    </form>
                </div>
            </div>

            {{-- Section: Informasi Akun --}}
            <div class="card shadow-sm mb-4" id="account">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-person me-2"></i>Informasi Akun</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-0">Nama</label>
                            <p class="fw-semibold mb-0">{{ auth()->user()->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-0">NPM</label>
                            <p class="fw-semibold mb-0">{{ auth()->user()->npm }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-0">Email</label>
                            <p class="fw-semibold mb-0">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-0">Angkatan</label>
                            <p class="fw-semibold mb-0">{{ auth()->user()->cohort }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-0">Fakultas</label>
                            <p class="fw-semibold mb-0">{{ auth()->user()->faculty }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-0">Program Studi</label>
                            <p class="fw-semibold mb-0">{{ auth()->user()->study_program }}</p>
                        </div>
                    </div>
                    <hr>
                    <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i>Hubungi admin jika ada data yang perlu diperbaiki.</p>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('dashboard') }}" class="text-muted small"><i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection
