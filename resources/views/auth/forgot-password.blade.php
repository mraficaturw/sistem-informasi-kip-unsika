@extends('layouts.app')
@section('title', 'Lupa Password')
@section('content')
<div class="register-page">
    <div class="container py-5">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="card shadow-lg border-0" style="border-radius: 1rem;">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div class="register-icon mx-auto mb-3">
                                <i class="bi bi-key"></i>
                            </div>
                            <h4 class="fw-bold">Lupa Password</h4>
                            <p class="text-muted small">Masukkan email Anda untuk menerima link reset password</p>
                        </div>

                        @if(session('status'))
                            <div class="alert alert-success border-0">
                                <i class="bi bi-check-circle me-1"></i>{{ session('status') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger border-0">
                                @foreach($errors->all() as $error)
                                    <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="npm@student.unsika.ac.id">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-danger w-100 py-2 fw-semibold">
                                <i class="bi bi-send me-2"></i>Kirim Link Reset
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-muted small"><i class="bi bi-arrow-left me-1"></i>Kembali ke Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
