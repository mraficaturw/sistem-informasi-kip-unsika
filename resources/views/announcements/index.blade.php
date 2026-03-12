@extends('layouts.app')
@section('title', 'Berita')
@section('content')
<div class="page-header"><div class="container"><h4 class="fw-bold mb-4">Berita</h4><p class="text-muted mb-0">Informasi terbaru seputar KIP Kuliah UNSIKA</p></div></div>
<div class="container pb-5">
    {{-- Search & Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('announcements.index') }}" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <div class="search-box">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari berita...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="category">
                        <option value="">Semua Kategori</option>
                        <option value="pencairan" {{ request('category') == 'pencairan' ? 'selected' : '' }}>Pencairan</option>
                        <option value="administrasi" {{ request('category') == 'administrasi' ? 'selected' : '' }}>Administrasi</option>
                        <option value="kebijakan" {{ request('category') == 'kebijakan' ? 'selected' : '' }}>Kebijakan</option>
                        <option value="internal" {{ request('category') == 'internal' ? 'selected' : '' }}>Internal</option>
                        <option value="lainnya" {{ request('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-danger w-100"><i class="bi bi-search me-1"></i>Cari</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Announcement List --}}
    <div class="row g-4">
        @forelse($announcements as $a)
        <div class="col-md-6 col-lg-4">
            <div class="card announcement-card h-100 hover-lift overflow-hidden">
                @if($a->cover_image)
                <div class="card-img-top" style="height: 180px; overflow: hidden;">
                    <img src="{{ asset('storage/' . $a->cover_image) }}" alt="{{ $a->title }}"
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                @endif
                <div class="card-body">
                    @if($a->category)
                    <span class="card-category text-primary">{{ ucfirst($a->category) }}</span>
                    @endif
                    <h5 class="card-title mt-2">{{ $a->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit(strip_tags($a->content), 120) }}</p>
                </div>
                <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                    <span class="card-date"><i class="bi bi-calendar me-1"></i>{{ $a->publish_date->format('d M Y') }}</span>
                    <a href="{{ route('announcements.show', $a) }}" class="btn btn-sm btn-outline-danger">Baca <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12"><div class="card"><div class="card-body text-center py-5">
            <i class="bi bi-newspaper fs-1 text-muted d-block mb-3"></i><h5>Belum Ada Berita</h5>
            <p class="text-muted">Berita akan ditampilkan di sini setelah dipublikasikan.</p>
        </div></div></div>
        @endforelse
    </div>

    @if($announcements->hasPages())
    <div class="mt-4">{{ $announcements->links() }}</div>
    @endif
</div>
@endsection
