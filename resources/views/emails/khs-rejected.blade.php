<x-mail::message>
# Pendataan KHS Ditolak

Halo **{{ $submission->user->name }}**,

Pengajuan KHS Anda untuk **Semester {{ $submission->semester }}** pada periode **{{ $submission->form_period }}** ditolak oleh admin.

**Alasan Penolakan:**

> {{ $submission->admin_notes ?? 'Tidak ada catatan dari admin.' }}

Silakan perbaiki dan ajukan kembali setelah masa tunggu selesai.

<x-mail::button :url="url('/dashboard')">
Kembali ke Dashboard
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
