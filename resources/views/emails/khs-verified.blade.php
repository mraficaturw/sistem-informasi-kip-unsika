<x-mail::message>
# KHS Anda Telah Diverifikasi

Halo **{{ $submission->user->name }}**,

KHS Anda untuk **Semester {{ $submission->semester }}** telah diverifikasi oleh admin.

**Detail:**
- Semester: {{ $submission->semester }}
- IPS: {{ $submission->ips }}
- Status: Terverifikasi ✅

<x-mail::button :url="url('/khs')">
Lihat Detail
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
