<x-mail::message>
# Form Pendataan KHS Telah Dibuka

Halo,

Form pendataan KHS untuk periode **{{ $period }}** telah dibuka. Segera isi form pendataan Anda sebelum batas waktu yang ditentukan.

**Yang perlu Anda siapkan:**
- File KHS semester terbaru (format PDF, maks. 2MB)
- Data IPS dan IPK terbaru

<x-mail::button :url="url('/dashboard')">
Isi Form Pendataan
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}

<small>Anda menerima email ini karena mengaktifkan notifikasi email. Jika tidak ingin menerima email lagi, silakan ubah preferensi di akun Anda.</small>
</x-mail::message>
