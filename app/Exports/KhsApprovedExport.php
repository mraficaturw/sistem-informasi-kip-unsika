<?php

namespace App\Exports;

use App\Models\KhsSubmission;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class KhsApprovedExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function query()
    {
        $currentPeriod = \App\Models\Setting::get('form_pendataan_period', '');
        return KhsSubmission::where('status', 'verified')
            ->where('form_period', $currentPeriod)
            ->with('user')
            ->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Fakultas',
            'Program Studi',
            'Angkatan',
            'Semester',
            'IPS',
            'File KHS',
            'Periode',
            'Tanggal Submit',
        ];
    }

    public function map($submission): array
    {
        return [
            $submission->user->name ?? '-',
            $submission->user->faculty ?? '-',
            $submission->user->study_program ?? '-',
            $submission->user->cohort ?? '-',
            $submission->semester,
            $submission->ips,
            $submission->khs_file,
            $submission->form_period ?? '-',
            $submission->submitted_at?->format('d/m/Y H:i') ?? '-',
        ];
    }
}
