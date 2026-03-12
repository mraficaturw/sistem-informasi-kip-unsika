<?php

namespace App\Exports;

use App\Models\KhsSubmission;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class StudentsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function query()
    {
        return KhsSubmission::query()
            ->with('user')
            ->where('status', 'verified');
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NPM',
            'IPS',
            'Semester',
            'Status',
        ];
    }

    public function map($submission): array
    {
        return [
            $submission->user->name,
            $submission->user->npm,
            $submission->ips,
            $submission->semester,
            $submission->status,
        ];
    }
}
