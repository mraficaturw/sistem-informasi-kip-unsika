<x-mail::message>
# Berita Baru dari KIP UNSIKA

Halo,

Ada berita terbaru yang mungkin penting untuk Anda:

**{{ $announcement->title }}**

{!! Str::limit(strip_tags($announcement->content), 200) !!}

<x-mail::button :url="url('/announcements/' . $announcement->id)">
Baca Selengkapnya
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}

<small>Anda menerima email ini karena mengaktifkan notifikasi email. Jika tidak ingin menerima email lagi, silakan ubah preferensi di akun Anda.</small>
</x-mail::message>
