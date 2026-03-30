<div align="center">

<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">

# 🎓 Sistem Informasi KIP Kuliah — UNSIKA

**Pusat Informasi Mahasiswa Penerima KIP Kuliah**  
Universitas Singaperbangsa Karawang (UNSIKA)

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

[🌐 Demo](#) · [📖 Dokumentasi](#dokumentasi) · [🐛 Laporkan Bug](../../issues) · [✨ Request Fitur](../../issues)

</div>

---

## 📋 Tentang Proyek

**Sistem Informasi KIP Kuliah UNSIKA** adalah platform web berbasis Laravel yang dirancang sebagai pusat informasi terpadu bagi mahasiswa penerima beasiswa KIP Kuliah di Universitas Singaperbangsa Karawang. Platform ini dikelola oleh **FORMADIKIP UNSIKA**.

### ✨ Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| 🏠 **Beranda Informatif** | Landing page lengkap dengan berita, persyaratan, tracking, dan FAQ |
| 📰 **Manajemen Berita** | CRUD berita & pengumuman dengan cover image dan kategori |
| 📊 **Pendataan KHS** | Upload KHS per semester dengan validasi, cooldown, dan konfirmasi |
| 🔄 **Tracking Pencairan** | Timeline visual status pencairan dana KIP yang transparan |
| 📄 **Unduh SK** | Download Surat Keputusan (SK) penerima KIP Kuliah |
| ❓ **FAQ Interaktif** | Accordion FAQ yang dapat dikelola admin |
| 🔒 **Autentikasi** | Register/Login dengan verifikasi email `@student.unsika.ac.id` |
| 👤 **Dashboard Mahasiswa** | Riwayat KHS, status verifikasi, dan info pencairan pribadi |
| ⚙️ **Panel Admin (Filament)** | Pengelolaan user, berita, tracking, SK, dan FAQ berbasis Filament |
| 🔧 **Halaman Maintenance** | Halaman maintenance yang konsisten dengan desain utama |

---

## 🏗️ Arsitektur Sistem

```
sistem-informasi-kip-unsika/
├── app/
│   ├── Http/
│   │   ├── Controllers/         # HomeController, AnnouncementController, dll.
│   │   ├── Middleware/          # EnsureApprovedStudent
│   │   └── Services/            # KhsService (business logic layer)
│   ├── Models/                  # User, Khs, Announcement, TrackingStage, dll.
│   └── Filament/                # Admin panel resources
├── resources/
│   ├── views/
│   │   ├── layouts/app.blade.php   # Layout utama dengan Navbar & Footer
│   │   ├── home.blade.php          # Landing page
│   │   ├── maintenance.blade.php   # Halaman maintenance
│   │   ├── auth/                   # Login, Register, Verification Status
│   │   ├── student/                # Dashboard mahasiswa
│   │   └── announcements/          # Daftar & detail berita
│   └── scss/app.scss               # Tema "Dewi-style" — Bootstrap 5 + Custom
├── routes/web.php
└── database/migrations/
```

### Stack Teknologi

- **Backend**: Laravel 11, PHP 8.2+
- **Frontend**: Bootstrap 5.3, Bootstrap Icons, SCSS (Raleway + Open Sans)
- **Admin Panel**: Filament 3.x
- **Database**: MySQL
- **Build Tool**: Vite + Laravel Vite Plugin
- **Deployment**: Laravel Herd / Nginx / Apache

---

## 🚀 Instalasi & Setup

### Prasyarat

- PHP `>= 8.2` dengan ekstensi: `pdo`, `mbstring`, `xml`, `gd`, `fileinfo`
- Composer `>= 2.x`
- Node.js `>= 18.x` & npm
- MySQL `>= 8.0`

### Langkah Instalasi

**1. Clone repositori**
```bash
git clone https://github.com/mraficaturw/sistem-informasi-kip-unsika.git
cd sistem-informasi-kip-unsika
```

**2. Install dependensi**
```bash
composer install
npm install
```

**3. Konfigurasi environment**
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` sesuai kebutuhan:
```env
APP_NAME="KIP UNSIKA"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kip_unsika
DB_USERNAME=root
DB_PASSWORD=
```

**4. Migrasi & seeding database**
```bash
php artisan migrate --seed
```

**5. Buat storage link**
```bash
php artisan storage:link
```

**6. Build aset frontend**
```bash
# Mode development
npm run dev

# Mode production
npm run build
```

**7. Jalankan server**
```bash
php artisan serve
```

Buka `http://localhost:8000` di browser.

---

## 🔧 Konfigurasi Tambahan

### Aktivasi Mode Maintenance

Untuk mengaktifkan halaman maintenance (`resources/views/maintenance.blade.php`):

```bash
# Aktifkan maintenance mode Laravel bawaan
php artisan down --render="maintenance" --retry=900

# Atau dengan estimasi waktu kembali
php artisan down --render="maintenance"
```

Untuk menonaktifkan:
```bash
php artisan up
```

> **Catatan:** Countdown timer pada halaman maintenance secara default menghitung mundur **6 jam dari waktu diakses**. Sesuaikan nilai `MAINTENANCE_END` di `maintenance.blade.php` jika diperlukan waktu spesifik.

### Konfigurasi File Upload

Batas ukuran upload KHS (PDF) dapat diubah di:
- `.env`: `UPLOAD_MAX_SIZE=2048` (dalam KB)
- `php.ini`: `upload_max_filesize` & `post_max_size`

### Email Mahasiswa

Sistem hanya mengizinkan registrasi dengan email `@student.unsika.ac.id`. Konfigurasi domain dapat diubah di `app/Http/Controllers/Auth/RegisterController.php`.

---

## 👥 Peran Pengguna

| Peran | Akses |
|-------|-------|
| **Guest** | Beranda, Berita, Landing page sections |
| **Mahasiswa (Pending)** | Menunggu verifikasi admin |
| **Mahasiswa (Approved)** | Dashboard, Upload KHS, Download SK, Tracking |
| **Admin** | Panel Filament — kelola semua data |

---

## 📁 Dokumentasi Tambahan

Repositori ini dilengkapi dokumen teknis berikut:

| Dokumen | Deskripsi |
|---------|-----------|
| [`DEPLOYMENT_GUIDE.md`](DEPLOYMENT_GUIDE.md) | Panduan deployment ke server produksi |
| [`DEVELOPER_GUIDE.md`](DEVELOPER_GUIDE.md) | Panduan pengembangan & kontribusi |
| [`MANUAL_ADMIN.md`](MANUAL_ADMIN.md) | Manual penggunaan panel admin |
| [`MANUAL_MAHASISWA.md`](MANUAL_MAHASISWA.md) | Manual penggunaan untuk mahasiswa |
| [`Analisis_KIP_Unsika.html`](Analisis_KIP_Unsika.html) | Analisis arsitektur & ERD sistem |

---

## 📸 Screenshots

> _Tambahkan screenshot di sini setelah deployment._

---

## 🤝 Kontribusi

Kontribusi sangat disambut! Silakan buat *issue* atau *pull request*.

1. Fork repositori ini
2. Buat branch fitur: `git checkout -b feature/NamaFitur`
3. Commit perubahan: `git commit -m 'feat: tambah NamaFitur'`
4. Push ke branch: `git push origin feature/NamaFitur`
5. Buka Pull Request

---

## 🛡️ Keamanan

Jika menemukan celah keamanan, **jangan** buat issue publik. Laporkan langsung ke:
- 📧 `formadikip@unsika.ac.id`
- 📱 [WhatsApp FORMADIKIP](https://wa.me/6283185132009)

---

## 📬 Kontak

**FORMADIKIP UNSIKA**

- 🌐 Instagram: [@formadikipunsika](https://www.instagram.com/formadikipunsika/)
- 💬 WhatsApp: [+62 831-8513-2009](https://wa.me/6283185132009)
- 📧 Email: formadikip@unsika.ac.id
- 📍 Alamat: Universitas Singaperbangsa Karawang, Jl. HS. Ronggowaluyo, Karawang

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).

---

<div align="center">

Dibuat oleh **MRAFICATURW** &copy; 2026

</div>
