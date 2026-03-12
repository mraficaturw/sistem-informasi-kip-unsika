<?php

namespace App\Filament\Widgets;

use App\Models\KhsSubmission;
use App\Models\Setting;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminInsightsWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $currentPeriod = Setting::get('form_pendataan_period', '');

        $totalApprovedStudents = User::where('role', 'student')->where('status', 'approved')->count();
        $khsPendingThisPeriod = KhsSubmission::where('form_period', $currentPeriod)->where('status', 'pending')->count();
        $khsVerifiedThisPeriod = KhsSubmission::where('form_period', $currentPeriod)->where('status', 'verified')->count();

        return [
            Stat::make('Mahasiswa KIP Aktif', $totalApprovedStudents)
                ->description('Total akun mahasiswa disetujui')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
            
            Stat::make('KHS Menunggu Validasi', $khsPendingThisPeriod)
                ->description('Periode: ' . ($currentPeriod ?: 'N/A'))
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('KHS Disetujui', $khsVerifiedThisPeriod)
                ->description('Periode: ' . ($currentPeriod ?: 'N/A'))
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
