<x-mail::message>
# Akun Anda Telah Disetujui

Halo **{{ $user->name }}**,

Akun KIP UNSIKA Anda telah disetujui oleh admin. Anda sekarang bisa login dan mengakses semua layanan.

<x-mail::button :url="url('/login')">
Login Sekarang
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
