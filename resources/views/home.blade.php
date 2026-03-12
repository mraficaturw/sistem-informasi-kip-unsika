@extends('layouts.app')
@section('title', 'Beranda')
@section('content')

{{-- 1. Hero Section --}}
<section class="hero-section" id="hero">
    <div class="hero-bg" style="background-image: url('{{ asset('images/hero-bg.jpg') }}')"></div>
    <div class="hero-content">
        <h1 class="hero-title">Pusat Informasi<br>KIP Kuliah UNSIKA</h1>
        <p class="hero-subtitle">Satu sumber informasi terpercaya untuk seluruh mahasiswa penerima KIP Kuliah Universitas Singaperbangsa Karawang. Akses informasi, pendataan IPS, pantau pencairan, bertanya dan banyak lagi.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            @guest
                <a href="{{ route('register') }}" class="btn btn-hero btn-hero-primary">
                    <i class="bi bi-person-plus me-2"></i>Daftar Akun
                </a>
                <a href="{{ route('login') }}" class="btn btn-hero btn-hero-outline">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-hero btn-hero-primary">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            @endguest
        </div>
    </div>
</section>

{{-- 2. Berita Terbaru (Carousel) --}}
<section class="news-carousel-section" id="berita">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="section-label">Berita</span>
                <h2 class="section-title mb-0">Berita Terbaru</h2>
            </div>
            <div class="d-none d-md-flex gap-2">
                <button class="carousel-nav-btn" id="newsScrollLeft"><i class="bi bi-chevron-left"></i></button>
                <button class="carousel-nav-btn" id="newsScrollRight"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>

        @if(isset($announcements) && $announcements->count() > 0)
        <div class="news-scroll-wrapper" id="newsScrollWrapper">
            <div class="news-scroll-track">
                @foreach($announcements as $announcement)
                <div class="news-scroll-item">
                    <div class="news-card">
                        @if($announcement->cover_image)
                        <div class="news-cover">
                            <img src="{{ asset('storage/' . $announcement->cover_image) }}" alt="{{ $announcement->title }}">
                        </div>
                        @else
                        <div class="news-cover">
                            <div class="news-cover-placeholder">
                                <i class="bi bi-newspaper"></i>
                                <span>NO COVER</span>
                            </div>
                        </div>
                        @endif
                        <div class="card-body">
                            @if($announcement->category)
                            <div class="news-category">{{ ucfirst($announcement->category) }}</div>
                            @endif
                            <h5 class="news-title">{{ $announcement->title }}</h5>
                            <p class="news-excerpt">{!! Str::limit(strip_tags($announcement->content, '<b><strong><i><em><u><br>'), 100) !!}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="news-date"><i class="bi bi-calendar3 me-1"></i>{{ $announcement->publish_date->format('d M Y') }}</span>
                                <a href="{{ route('announcements.show', $announcement) }}" class="btn-read-more">
                                    Baca <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-newspaper fs-1 text-muted d-block mb-3"></i>
            <h5 class="text-muted">Belum ada berita terbaru</h5>
        </div>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('announcements.index') }}" class="btn btn-outline-danger px-4">
                Lihat Semua Berita <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

{{-- 3. Persyaratan KIP --}}
<section class="persyaratan-section" id="persyaratan">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <span class="section-label">Informasi</span>
                <h2 class="section-title">Persyaratan Pemegang KIP Kuliah</h2>
                <p class="text-muted mb-4">Setiap penerima KIP Kuliah wajib memenuhi persyaratan berikut untuk mempertahankan status beasiswa dan mendapatkan pencairan dana tepat waktu.</p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="persyaratan-item">
                            <div class="persyaratan-icon"><i class="bi bi-journal-check"></i></div>
                            <div>
                                <h6>Upload KHS Setiap Semester</h6>
                                <p>Wajib mengunggah KHS dalam format PDF melalui sistem setiap semester berjalan.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="persyaratan-item">
                            <div class="persyaratan-icon"><i class="bi bi-graph-up-arrow"></i></div>
                            <div>
                                <h6>IPS Minimum</h6>
                                <p>Mempertahankan Indeks Prestasi Semester (IPS) sesuai ketentuan yang berlaku.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="persyaratan-item">
                            <div class="persyaratan-icon"><i class="bi bi-clock-history"></i></div>
                            <div>
                                <h6>Tepat Waktu</h6>
                                <p>Mengumpulkan dokumen dan data sebelum batas waktu yang ditentukan admin.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="persyaratan-item">
                            <div class="persyaratan-icon"><i class="bi bi-person-check"></i></div>
                            <div>
                                <h6>Status Mahasiswa Aktif</h6>
                                <p>Harus terdaftar sebagai mahasiswa aktif di Universitas Singaperbangsa Karawang.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="persyaratan-item">
                            <div class="persyaratan-icon"><i class="bi bi-shield-check"></i></div>
                            <div>
                                <h6>Tidak Melanggar Aturan</h6>
                                <p>Tidak pernah melakukan pelanggaran akademik atau non-akademik yang berat.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="persyaratan-item">
                            <div class="persyaratan-icon"><i class="bi bi-envelope-check"></i></div>
                            <div>
                                <h6>Email Resmi</h6>
                                <p>Registrasi hanya menggunakan email @student.unsika.ac.id.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 4. Counter Mahasiswa KIP Terdaftar --}}
<section class="stats-section" id="stats">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-number" data-count="{{ $stats['totalStudents'] ?? 0 }}">0</div>
                    <div class="stat-label">Mahasiswa KIP Unsika Terdaftar di Website ini</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 5. Timeline Tracking Pencairan --}}
<section class="tracking-section" id="tracking">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <span class="section-label justify-content-center">Tracking</span>
                    <h2 class="section-title">Status Pencairan KIP</h2>
                    <p class="section-subtitle mx-auto">Pantau progress pencairan beasiswa KIP Kuliah secara transparan. Status diperbarui oleh admin secara berkala.</p>
                </div>

                @if(isset($trackingStages) && count($trackingStages) > 0)
                <div class="position-relative">
                    @if(!auth()->check() || !auth()->user()->isApproved() || !(isset($alreadySubmitted) && $alreadySubmitted))
                    <div class="tracking-overlay d-flex flex-column align-items-center justify-content-center text-center p-4">
                        <i class="bi bi-lock-fill fs-1 text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5 class="fw-bold">Informasi Terkunci</h5>
                        <p class="text-muted small mb-0">Tracking pencairan hanya dapat dilihat oleh mahasiswa KIP yang telah login, akun disetujui, dan telah mengisi form pendataan KHS semester ini.</p>
                        @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-danger btn-sm mt-3">Login Sekarang</a>
                        @endguest
                    </div>
                    @endif
                <div class="timeline-vertical {{ (!auth()->check() || !auth()->user()->isApproved() || !(isset($alreadySubmitted) && $alreadySubmitted)) ? 'tracking-obfuscated' : '' }}">
                    @foreach($trackingStages as $stage)
                    <div class="timeline-step {{ $stage->status }}">
                        <div class="timeline-dot">
                            @if($stage->status === 'completed')
                                <i class="bi bi-check"></i>
                            @elseif($stage->status === 'active')
                                <i class="bi bi-three-dots"></i>
                            @endif
                        </div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="timeline-title">{{ $stage->title }}</div>
                                @if($stage->date)
                                <span class="timeline-date">{{ $stage->date->format('d M Y') }}</span>
                                @endif
                            </div>
                            @if($stage->description)
                            <div class="timeline-notes">{{ $stage->description }}</div>
                            @endif
                            @if($stage->notes)
                            <div class="timeline-notes"><em>{{ $stage->notes }}</em></div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                </div>
                @else
                <div class="text-center py-4">
                    <div class="mb-3">
                        <i class="bi bi-hourglass-split fs-1 text-muted"></i>
                    </div>
                    <h5 class="text-muted">Belum ada data tracking pencairan</h5>
                    <p class="text-muted">Status akan diperbarui oleh admin saat proses pencairan berjalan.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- 6. Form Pendataan --}}
<section class="pendataan-section" id="pendataan">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="pendataan-card">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <span class="section-label" style="color: rgba(255,255,255,0.6);">Pendataan</span>
                            <h2 class="mb-3" style="font-size: 1.75rem;">Form Pendataan Semester</h2>
                            <p class="mb-4" style="opacity: 0.75;">Upload KHS dan isi IPS semester ini untuk memenuhi persyaratan pendataan penerima KIP Kuliah. Form ini hanya bisa diisi sekali setiap periode aktif.</p>
                            @auth
                                @if(auth()->user()->isApproved())
                                    @if(isset($formPendataanActive) && $formPendataanActive)
                                        @if(isset($alreadySubmitted) && $alreadySubmitted)
                                            <button type="button" class="btn btn-pendataan" disabled>
                                                <i class="bi bi-check-circle me-2"></i>Sudah Diisi
                                            </button>
                                            <p class="mt-2 mb-0" style="opacity: 0.6; font-size: 0.85rem;">Anda sudah mengisi form untuk periode ini. Lihat status di Dashboard.</p>
                                        @elseif(isset($resubmitAt) && $resubmitAt && now()->lessThan($resubmitAt))
                                            <button type="button" class="btn btn-pendataan" disabled>
                                                <i class="bi bi-clock-history me-2"></i>Akses Ditangguhkan
                                            </button>
                                            <div class="mt-3 text-start p-3 rounded" style="background: rgba(255,255,255,0.1); border-left: 4px solid #ff4757;">
                                                <p class="mb-1" style="font-size: 0.9rem;"><strong>Form KHS Anda ditolak.</strong></p>
                                                @if(isset($rejectedNotes) && $rejectedNotes)
                                                    <p class="mb-1 text-warning" style="font-size: 0.85rem;">Alasan: {{ $rejectedNotes }}</p>
                                                @endif
                                                <p class="mb-0" style="font-size: 0.85rem; opacity: 0.9;">Anda bisa mencoba mengisi kembali dalam <strong id="countdown-timer" class="text-white" data-time="{{ $resubmitAt->toIso8601String() }}">{{ $resubmitAt->diffForHumans(['parts' => 2, 'short' => true]) }}</strong>.</p>
                                            </div>
                                        @else
                                            <button type="button" class="btn btn-pendataan" data-bs-toggle="modal" data-bs-target="#modalPendataan">
                                                <i class="bi bi-upload me-2"></i>Isi Form Pendataan
                                            </button>
                                        @endif
                                    @else
                                        <button type="button" class="btn btn-pendataan" disabled>
                                            <i class="bi bi-lock me-2"></i>Form Ditutup
                                        </button>
                                        <p class="mt-2 mb-0" style="opacity: 0.6; font-size: 0.85rem;">Form pendataan sedang tidak aktif. Silakan tunggu pengumuman dari admin.</p>
                                    @endif
                                @else
                                    <button type="button" class="btn btn-pendataan" disabled>
                                        <i class="bi bi-shield-lock me-2"></i>Akun Belum Disetujui
                                    </button>
                                    <p class="mt-2 mb-0" style="opacity: 0.6; font-size: 0.85rem;">Akun Anda sedang dalam proses verifikasi oleh admin.</p>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-pendataan">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login untuk Isi Form
                                </a>
                            @endauth
                        </div>
                        <div class="col-lg-5 text-center d-none d-lg-block">
                            <i class="bi bi-file-earmark-arrow-up" style="font-size: 8rem; opacity: 0.1;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Modal Pendataan KHS --}}
@auth
<div class="modal fade modal-pendataan" id="modalPendataan" tabindex="-1" aria-labelledby="modalPendataanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPendataanLabel">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Form Pendataan KHS
                    @if(isset($currentFormPeriod) && $currentFormPeriod)
                    <span class="badge bg-danger ms-2">{{ $currentFormPeriod }}</span>
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('khs.store') }}" method="POST" enctype="multipart/form-data" id="formPendataan">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-select" id="semester" name="semester" required>
                            @for($i = 1; $i <= 14; $i++)
                                <option value="{{ $i }}">Semester {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ips" class="form-label">IPS (Indeks Prestasi Semester)</label>
                        <input type="number" class="form-control" id="ips" name="ips" step="0.01" min="0" max="4.00" placeholder="Contoh: 3.50" required>
                        <div class="form-text">Masukkan IPS semester ini (0.00 - 4.00)</div>
                    </div>
                    <div class="mb-3">
                        <label for="khs_file" class="form-label">Upload File KHS (PDF)</label>
                        <input type="file" class="form-control" id="khs_file" name="khs_file" accept=".pdf" required>
                        <div class="form-text">Format file: PDF, maksimal 2MB</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-upload me-2"></i>Kirim Pendataan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth

{{-- 7. Unduh SK --}}
<section class="sk-section" id="unduh-sk">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label justify-content-center">Dokumen</span>
            <h2 class="section-title">Unduh SK Penerima KIP</h2>
            <p class="section-subtitle mx-auto">Unduh dokumen Surat Keputusan (SK) Penerima KIP Kuliah yang berlaku saat ini.</p>
        </div>

        <div class="row justify-content-center">
            @if(isset($latestDocument) && $latestDocument)
            <div class="col-md-6 col-lg-4">
                <div class="sk-card card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="sk-icon mx-auto mb-3">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </div>
                        <h5 class="fw-bold">{{ $latestDocument->name }}</h5>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-calendar3 me-1"></i>Diupload {{ $latestDocument->created_at->format('d M Y') }}
                        </p>
                        <div>
                            @auth
                                @if(auth()->user()->isApproved())
                                <a href="{{ asset('storage/' . $latestDocument->file) }}" class="btn btn-danger btn-sm px-4" download="{{ $latestDocument->name . '.' . pathinfo($latestDocument->file, PATHINFO_EXTENSION) }}">
                                    <i class="bi bi-download me-1"></i>Unduh SK
                                </a>
                                @else
                                <button type="button" class="btn btn-outline-secondary btn-sm px-4" disabled>
                                    <i class="bi bi-shield-lock me-1"></i>Akun Belum Disetujui
                                </button>
                                @endif
                            @else
                            <a href="{{ route('login') }}" class="btn btn-outline-danger btn-sm px-4">
                                <i class="bi bi-lock me-1"></i>Login untuk Unduh
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-md-6">
                <div class="sk-card card text-center p-4">
                    <div class="card-body">
                        <div class="sk-icon mx-auto mb-3">
                            <i class="bi bi-file-earmark-x"></i>
                        </div>
                        <h5 class="fw-bold text-muted">Belum Ada Dokumen SK</h5>
                        <p class="text-muted small">Dokumen SK akan tersedia setelah diunggah oleh admin.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- 8. FAQ --}}
<section class="faq-section" id="faq">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <span class="section-label justify-content-center">FAQ</span>
                    <h2 class="section-title">Pertanyaan yang Sering Ditanyakan</h2>
                </div>

                <div class="accordion" id="homeFaqAccordion">
                    @if(isset($faqs) && $faqs->count() > 0)
                        @foreach($faqs as $index => $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading{{ $faq->id }}">
                                <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $faq->id }}">
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="faqCollapse{{ $faq->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                data-bs-parent="#homeFaqAccordion">
                                <div class="accordion-body">{!! $faq->answer !!}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-question-circle fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">Belum ada FAQ</h5>
                        </div>
                    @endif
                </div>

                <div class="faq-cta">
                    <h5 class="fw-bold mb-2">Pertanyaan belum terjawab?</h5>
                    <p class="text-muted mb-3">Hubungi layanan FORMADIKIP (Abitha) untuk bantuan lebih lanjut.</p>
                    <a href="https://wa.me/6283185132009" target="_blank" class="btn btn-abitha">
                        <i class="bi bi-whatsapp me-2"></i>Chat Abitha
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
.tracking-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.85);
    z-index: 10;
    border-radius: 12px;
}
.tracking-obfuscated {
    filter: blur(6px);
    pointer-events: none;
    user-select: none;
}
[data-bs-theme="dark"] .tracking-overlay {
    background: rgba(30, 30, 30, 0.85);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. News Carousel Scroll Logic
    const scrollWrapper = document.getElementById('newsScrollWrapper');
    const scrollLeftBtn = document.getElementById('newsScrollLeft');
    const scrollRightBtn = document.getElementById('newsScrollRight');

    if (scrollWrapper && scrollLeftBtn && scrollRightBtn) {
        const scrollAmount = 300; // Match card width roughly
        
        scrollLeftBtn.addEventListener('click', () => {
            scrollWrapper.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });
        
        scrollRightBtn.addEventListener('click', () => {
            scrollWrapper.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });
    }

    // 2. KHS File Size Validation & Form Confirmation
    const khsFileInput = document.getElementById('khs_file');
    const khsForm = document.getElementById('formPendataan');

    if (khsFileInput) {
        khsFileInput.addEventListener('change', function() {
            if (this.files[0] && this.files[0].size > 2097152) { // 2MB
                alert('Ukuran file KHS maksimal 2MB!');
                this.value = '';
            }
        });
    }

    if (khsForm) {
        const submitBtn = khsForm.querySelector('button[type="submit"]');
        
        khsForm.addEventListener('submit', function(e) {
            if (!khsForm.dataset.confirmed) {
                e.preventDefault();
                
                // Cek modal konfirmasi sudah ada belum biar ngga numpuk
                if (!document.getElementById('confirmSubmitModal')) {
                    const confirmModalHtml = `
                    <div class="modal fade" id="confirmSubmitModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold">Konfirmasi Pendataan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah Anda yakin data IPS dan dokumen KHS yang diunggah sudah benar?</p>
                                    <p class="text-muted small mb-0">Pastikan file dapat terbaca dengan jelas dan IPS sesuai dengan KHS.</p>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-secondary w-100 mb-2 py-2" id="btnConfirmSubmit">Yakin, Kirim</button>
                                    <button type="button" class="btn btn-outline-danger w-100 m-0 py-2 fw-bold" data-bs-dismiss="modal" autofocus>Cek Lagi</button>
                                </div>
                            </div>
                        </div>
                    </div>`;
                    document.body.insertAdjacentHTML('beforeend', confirmModalHtml);
                    
                    document.getElementById('btnConfirmSubmit').addEventListener('click', function() {
                        khsForm.dataset.confirmed = 'true';
                        // Re-trigger submit programmatically
                        khsForm.submit();
                        
                        // Change button state
                        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim...';
                        this.disabled = true;
                    });
                }
                
                // Show modal
                const confirmModal = new bootstrap.Modal(document.getElementById('confirmSubmitModal'));
                confirmModal.show();
            }
        });
    }

    // 3. Countdown Timer Logic
    const timerElement = document.getElementById('countdown-timer');
    if (timerElement) {
        const targetTime = new Date(timerElement.dataset.time).getTime();
        
        const countdownInterval = setInterval(function() {
            const now = new Date().getTime();
            const distance = targetTime - now;
            
            if (distance < 0) {
                clearInterval(countdownInterval);
                timerElement.innerHTML = 'Sekarang (Silakan refresh halaman)';
                return;
            }
            
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            let displayTime = '';
            if (hours > 0) displayTime += hours + " jam ";
            if (minutes > 0) displayTime += minutes + " menit ";
            displayTime += seconds + " detik";
            
            timerElement.innerHTML = displayTime;
        }, 1000);
    }
});
</script>
@endpush

@endsection
