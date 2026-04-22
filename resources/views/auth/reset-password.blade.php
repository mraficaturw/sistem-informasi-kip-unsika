@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')
<div class="register-page">
    <div class="container py-5">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="card shadow-lg border-0" style="border-radius: 1rem;">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div class="register-icon mx-auto mb-3">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <h4 class="fw-bold">Reset Password</h4>
                            <p class="text-muted small">Masukkan password baru untuk akun Anda</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger border-0">
                                @foreach($errors->all() as $error)
                                    <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $email ?? old('email') }}" required readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required autofocus placeholder="Huruf besar, kecil, dan angka">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password baru">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-danger w-100 py-2 fw-semibold">
                                <i class="bi bi-check-circle me-2"></i>Reset Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
