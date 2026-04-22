@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-header">
    <div class="container">
        <h4 class="fw-bold mb-4">Dashboard</h4>
        <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }}</p>
    </div>
</div>
<div class="container pb-5">
    {{-- Stats Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Semester Saat Ini</p>
                            <h3 class="fw-bold mb-0">{{ $currentSemester }}</h3>
                        </div>
                        <i class="bi bi-mortarboard text-primary fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-start border-{{ explode(' ', $ipsAlertColor ?? 'success')[0] }} border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">IPS Terakhir</p>
                            <div class="d-flex align-items-center gap-2">
                                <h3 class="fw-bold mb-0">{{ $latestIps }}</h3>
                                @if(!empty($ipsAlertMessage))
                                    <span class="badge bg-{{ $ipsAlertColor }}">{{ $ipsAlertMessage }}</span>
                                @endif
                            </div>
                        </div>
                        <i class="bi bi-graph-up text-{{ explode(' ', $ipsAlertColor ?? 'success')[0] }} fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-start border-{{ explode(' ', $ipkAlertColor ?? 'success')[0] }} border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">IPK Terakhir</p>
                            <div class="d-flex align-items-center gap-2">
                                <h3 class="fw-bold mb-0">{{ $latestIpk }}</h3>
                                @if(!empty($ipkAlertMessage))
                                    <span class="badge bg-{{ $ipkAlertColor }}">{{ $ipkAlertMessage }}</span>
                                @endif
                            </div>
                        </div>
                        <i class="bi bi-bar-chart-line text-{{ explode(' ', $ipkAlertColor ?? 'success')[0] }} fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-start border-{{ explode(' ', $currentPeriodStatusColor)[0] }} border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Pendataan ({{ $currentFormPeriod ?: 'N/A' }})</p>
                            <h3 class="fw-bold mb-0 fs-5">
                                <span class="badge bg-{{ explode(' ', $currentPeriodStatusColor)[0] }}">{{ $currentPeriodStatus }}</span>
                            </h3>
                        </div>
                        <i class="bi {{ $currentPeriodStatusIcon }} text-{{ explode(' ', $currentPeriodStatusColor)[0] }} fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($resubmitAt) && $resubmitAt && now()->lessThan($resubmitAt))
    <div class="alert alert-danger shadow-sm d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-3 fs-3"></i>
        <div>
            <h5 class="alert-heading fw-bold mb-1">Form KHS Ditolak</h5>
            <p class="mb-1">Alasan: <strong>{{ $rejectedNotes ?? 'IPS di bawah ketentuan atau dokumen tidak valid.' }}</strong></p>
            <hr class="my-2 opacity-25">
            <p class="mb-0 small">Anda bisa mengirim ulang KHS dalam <strong id="dashboard-countdown" data-time="{{ $resubmitAt->toIso8601String() }}">{{ $resubmitAt->diffForHumans(['parts' => 2, 'short' => true]) }}</strong>.</p>
        </div>
    </div>
    @endif

    <div class="row g-4">
        {{-- Riwayat Form Pendataan --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-clock-history me-2"></i>Riwayat Form Pendataan</h6>
                    @if($formPendataanActive ?? false)
                    <span class="badge bg-success"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>Form Aktif</span>
                    @else
                    <span class="badge bg-secondary"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>Form Tidak Aktif</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if(isset($khsHistory) && $khsHistory->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Periode</th>
                                    <th>Semester</th>
                                    <th>IPS</th>
                                    <th>IPK</th>
                                    <th>Tanggal Submit</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($khsHistory as $index => $khs)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $khs->form_period ?: '-' }}</td>
                                    <td>{{ $khs->semester }}</td>
                                    <td>{{ number_format($khs->ips, 2) }}</td>
                                    <td>{{ number_format($khs->ipk, 2) }}</td>
                                    <td>{{ $khs->submitted_at?->format('d M Y') ?? $khs->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if($khs->status === 'verified')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($khs->status === 'rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($khs->status === 'rejected' && $khs->admin_notes)
                                            <span class="text-danger small"><i class="bi bi-exclamation-circle me-1"></i>{{ $khs->admin_notes }}</span>
                                        @elseif($khs->status === 'verified')
                                            <span class="text-success small"><i class="bi bi-check-circle me-1"></i>Dokumen disetujui</span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada riwayat form pendataan.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Announcements --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-megaphone me-2"></i>Pengumuman Terbaru</h6>
                    <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-outline-primary">Semua</a>
                </div>
                <div class="card-body p-0">
                    @forelse($recentAnnouncements ?? [] as $a)
                    <div class="d-flex align-items-start p-3 border-bottom">
                        <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                            <i class="bi bi-megaphone text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <a href="{{ route('announcements.show', $a) }}" class="fw-semibold text-decoration-none">{{ $a->title }}</a>
                            <p class="text-muted small mb-0">{{ $a->publish_date->format('d M Y') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada pengumuman.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const timerElement = document.getElementById('dashboard-countdown');
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
