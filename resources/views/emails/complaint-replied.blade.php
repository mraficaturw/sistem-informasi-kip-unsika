<x-mail::message>
# Pengaduan Anda Telah Ditanggapi

Halo **{{ $complaint->user->name }}**,

Pengaduan Anda dengan judul **"{{ $complaint->title }}"** telah ditanggapi oleh admin.

**Balasan Admin:**

> {{ $complaint->admin_reply }}

<x-mail::button :url="url('/complaints')">
Lihat Pengaduan
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
