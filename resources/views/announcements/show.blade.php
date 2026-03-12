@extends('layouts.app')
@section('title', $announcement->title)
@section('content')
<div class="page-header"><div class="container"><nav aria-label="breadcrumb"><ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="{{ route('announcements.index') }}">Berita</a></li><li class="breadcrumb-item active">Detail</li></ol></nav></div></div>
<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card overflow-hidden">
                @if($announcement->cover_image)
                <div style="max-height: 350px; overflow: hidden;">
                    <img src="{{ asset('storage/' . $announcement->cover_image) }}" alt="{{ $announcement->title }}"
                         style="width: 100%; height: auto; object-fit: cover;">
                </div>
                @endif
                <div class="card-body p-4">
                    @if($announcement->category)
                    <span class="badge bg-danger mb-3">{{ ucfirst($announcement->category) }}</span>
                    @endif
                    <h2 class="fw-bold mb-3">{{ $announcement->title }}</h2>
                    <div class="d-flex align-items-center text-muted mb-4">
                        <i class="bi bi-calendar me-2"></i>{{ $announcement->publish_date->format('d M Y') }}
                        <span class="mx-2">·</span>
                        <i class="bi bi-person me-2"></i>{{ $announcement->creator->name ?? 'Admin' }}
                    </div>
                    <div class="content">{!! $announcement->content !!}</div>
                </div>
            </div>
            <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary mt-3"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </div>
    </div>
</div>
@endsection
