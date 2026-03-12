@extends('layouts.app')
@section('title', 'Daftar Akun')
@section('content')
<div class="register-page">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow-lg border-0" style="border-radius: 1rem;">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div class="register-icon mx-auto mb-3">
                                <i class="bi bi-person-plus-fill"></i>
                            </div>
                            <h4 class="fw-bold">Daftar Akun KIP UNSIKA</h4>
                            <p class="text-muted small">Khusus mahasiswa penerima KIP Kuliah UNSIKA</p>
                        </div>

                        <div class="alert alert-info small border-0" style="background: rgba(14,165,233,0.08); color: #0369a1;">
                            <i class="bi bi-shield-lock me-2"></i>
                            <strong>Keamanan Data:</strong> Pastikan Anda mengisi data sensitif (NPM, Nama, Email) dengan benar. Data ini akan diverifikasi oleh admin.
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger border-0">
                                @foreach($errors->all() as $error)
                                    <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" id="registerForm">
                            @csrf

                            {{-- NPM --}}
                            <div class="mb-3">
                                <label for="npm" class="form-label fw-semibold">NPM <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-credit-card-2-front"></i></span>
                                    <input type="text" class="form-control @error('npm') is-invalid @enderror" id="npm" name="npm" value="{{ old('npm') }}" required placeholder="Contoh: 1234567890" maxlength="20">
                                </div>
                                <div class="form-text"><i class="bi bi-info-circle me-1"></i>Masukkan NPM sesuai yang tertera di KTM.</div>
                                @error('npm') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Nama --}}
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="John Doe">
                                </div>
                                <div class="form-text"><i class="bi bi-shield-check me-1"></i>Nama akan diverifikasi dengan data universitas.</div>
                                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Fakultas & Prodi --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="faculty" class="form-label fw-semibold">Fakultas <span class="text-danger">*</span></label>
                                    <select class="form-select @error('faculty') is-invalid @enderror" id="faculty" name="faculty" required>
                                        <option value="">-- Pilih Fakultas --</option>
                                        <option value="Fakultas Hukum" {{ old('faculty') == 'Fakultas Hukum' ? 'selected' : '' }}>Fakultas Hukum</option>
                                        <option value="FKIP" {{ old('faculty') == 'FKIP' ? 'selected' : '' }}>FKIP</option>
                                        <option value="Fakultas Teknik" {{ old('faculty') == 'Fakultas Teknik' ? 'selected' : '' }}>Fakultas Teknik</option>
                                        <option value="Fakultas Ekonomi dan Bisnis" {{ old('faculty') == 'Fakultas Ekonomi dan Bisnis' ? 'selected' : '' }}>Fakultas Ekonomi dan Bisnis</option>
                                        <option value="FASILKOM" {{ old('faculty') == 'FASILKOM' ? 'selected' : '' }}>FASILKOM</option>
                                        <option value="Fakultas Ilmu Sosial dan Ilmu Politik" {{ old('faculty') == 'Fakultas Ilmu Sosial dan Ilmu Politik' ? 'selected' : '' }}>Fakultas Ilmu Sosial dan Ilmu Politik</option>
                                        <option value="Fakultas Pertanian" {{ old('faculty') == 'Fakultas Pertanian' ? 'selected' : '' }}>Fakultas Pertanian</option>
                                        <option value="Fakultas Ilmu Kesehatan" {{ old('faculty') == 'Fakultas Ilmu Kesehatan' ? 'selected' : '' }}>Fakultas Ilmu Kesehatan</option>
                                        <option value="Fakultas Agama Islam" {{ old('faculty') == 'Fakultas Agama Islam' ? 'selected' : '' }}>Fakultas Agama Islam</option>
                                    </select>
                                    @error('faculty') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="study_program" class="form-label fw-semibold">Program Studi <span class="text-danger">*</span></label>
                                    <select class="form-select @error('study_program') is-invalid @enderror" id="study_program" name="study_program" required>
                                        <option value="">-- Pilih Prodi --</option>
                                    </select>
                                    @error('study_program') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Angkatan --}}
                            <div class="mb-3">
                                <label for="cohort" class="form-label fw-semibold">Angkatan <span class="text-danger">*</span></label>
                                <select class="form-select @error('cohort') is-invalid @enderror" id="cohort" name="cohort" required>
                                    <option value="">-- Pilih Angkatan --</option>
                                    @for($y = date('Y'); $y >= date('Y') - 7; $y--)
                                        <option value="{{ $y }}" {{ old('cohort') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                                @error('cohort') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="npm@student.unsika.ac.id">
                                </div>
                                <div class="form-text"><i class="bi bi-exclamation-triangle me-1 text-warning"></i>Wajib menggunakan email <strong>@student.unsika.ac.id</strong></div>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Password --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Min. 8 karakter">
                                    </div>
                                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password">
                                    </div>
                                </div>
                            </div>
                            <div class="form-text mb-3"><i class="bi bi-info-circle me-1"></i>Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka.</div>

                            <button type="submit" class="btn btn-danger w-100 py-2 fw-semibold">
                                <i class="bi bi-person-plus me-2"></i>Daftar
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <p class="text-muted small">Sudah punya akun? <a href="{{ route('login') }}" class="fw-semibold text-danger">Login di sini</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.register-page {
    background: linear-gradient(135deg, #1a1d2e 0%, #2c3e50 100%);
    min-height: 100vh;
    padding-top: 80px;
}
.register-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: rgba(232, 69, 69, 0.1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: #e84545;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const prodiData = {
        'Fakultas Hukum': ['Ilmu Hukum'],
        'FKIP': ['Pendidikan Guru Sekolah Dasar', 'Pendidikan Bahasa Inggris', 'Pendidikan Bahasa dan Sastra Indonesia', 'Pendidikan Pancasila dan Kewarganegaraan', 'Pendidikan Matematika', 'Pendidikan IPA'],
        'Fakultas Teknik': ['Teknik Mesin', 'Teknik Elektro', 'Teknik Industri', 'Teknik Sipil', 'Teknik Kimia'],
        'Fakultas Ekonomi dan Bisnis': ['Akuntansi', 'Manajemen', 'Ekonomi Pembangunan'],
        'FASILKOM': ['Informatika', 'Sistem Informasi'],
        'Fakultas Ilmu Sosial dan Ilmu Politik': ['Ilmu Pemerintahan', 'Ilmu Komunikasi', 'Administrasi Publik'],
        'Fakultas Pertanian': ['Agroteknologi', 'Agribisnis'],
        'Fakultas Ilmu Kesehatan': ['Farmasi', 'Gizi'],
        'Fakultas Agama Islam': ['Pendidikan Agama Islam', 'Hukum Ekonomi Syariah']
    };

    const facultySelect = document.getElementById('faculty');
    const prodiSelect = document.getElementById('study_program');
    const oldProdi = "{{ old('study_program') }}";

    facultySelect.addEventListener('change', function() {
        prodiSelect.innerHTML = '<option value="">-- Pilih Prodi --</option>';
        const prodi = prodiData[this.value] || [];
        prodi.forEach(function(p) {
            const opt = document.createElement('option');
            opt.value = p;
            opt.textContent = p;
            if (p === oldProdi) opt.selected = true;
            prodiSelect.appendChild(opt);
        });
    });

    // Trigger on page load if faculty already selected
    if (facultySelect.value) {
        facultySelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection
