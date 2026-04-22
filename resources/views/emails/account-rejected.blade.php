<x-mail::message>
# Pendaftaran Akun Ditolak

Halo **{{ $user->name }}**,

Mohon maaf, pendaftaran akun KIP UNSIKA Anda tidak dapat disetujui saat ini. Silakan hubungi pihak KIP UNSIKA untuk informasi lebih lanjut.

<x-mail::button :url="url('/login')">
Kunjungi Website
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
