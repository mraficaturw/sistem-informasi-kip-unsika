<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Document;
use App\Models\Faq;
use App\Models\KhsSubmission;
use App\Models\Setting;
use App\Models\TrackingStage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Roles ────────────────────────────────────────────────
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // ─── Admin User ──────────────────────────────────────────
        $admin = User::create([
            'name' => 'Admin KIP UNSIKA',
            'email' => 'adminkipunsika@staff.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'approved',
        ]);
        $admin->assignRole('admin');

        // ─── Approved Students ──────────────────────────────────
        $students = [
            ['npm' => '2210631170086', 'name' => 'Rafi Catur', 'email' => '2210631170086@student.unsika.ac.id', 'password' => 'Raficatur2004', 'faculty' => 'FASILKOM', 'study_program' => 'Informatika', 'cohort' => '2022'],
            ['npm' => '2210631170001', 'name' => 'Siti Nurhaliza', 'email' => '2210631170001@student.unsika.ac.id', 'password' => 'Password1', 'faculty' => 'FASILKOM', 'study_program' => 'Sistem Informasi', 'cohort' => '2022'],
            ['npm' => '2110631040012', 'name' => 'Budi Santoso', 'email' => '2110631040012@student.unsika.ac.id', 'password' => 'Password1', 'faculty' => 'Fakultas Teknik', 'study_program' => 'Teknik Mesin', 'cohort' => '2021'],
            ['npm' => '2310631060023', 'name' => 'Dewi Lestari', 'email' => '2310631060023@student.unsika.ac.id', 'password' => 'Password1', 'faculty' => 'Fakultas Ekonomi dan Bisnis', 'study_program' => 'Akuntansi', 'cohort' => '2023'],
            ['npm' => '2310631020034', 'name' => 'Rizky Pratama', 'email' => '2310631020034@student.unsika.ac.id', 'password' => 'Password1', 'faculty' => 'FKIP', 'study_program' => 'Pendidikan Bahasa Inggris', 'cohort' => '2023'],
            ['npm' => '2210631080045', 'name' => 'Putri Ayu Rahmawati', 'email' => '2210631080045@student.unsika.ac.id', 'password' => 'Password1', 'faculty' => 'Fakultas Ilmu Kesehatan', 'study_program' => 'Farmasi', 'cohort' => '2022'],
            ['npm' => '2210631010056', 'name' => 'Muhammad Arif', 'email' => '2210631010056@student.unsika.ac.id', 'password' => 'Password1', 'faculty' => 'Fakultas Hukum', 'study_program' => 'Ilmu Hukum', 'cohort' => '2022'],
        ];

        foreach ($students as $index => $data) {
            $student = User::create([
                'npm' => $data['npm'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'student',
                'status' => 'approved',
                'faculty' => $data['faculty'],
                'study_program' => $data['study_program'],
                'cohort' => $data['cohort'],
                // Variasi consent: ganjil opt-in, genap opt-out (untuk testing)
                'email_opt_in' => $index % 2 === 0,
            ]);
            $student->assignRole('student');
        }

        // Pending student
        $pending = User::create([
            'npm' => '2410631170099', 'name' => 'Rina Novita', 'email' => '2410631170099@student.unsika.ac.id',
            'password' => Hash::make('Password1'), 'role' => 'student', 'status' => 'pending',
            'faculty' => 'FASILKOM', 'study_program' => 'Informatika', 'cohort' => '2024',
        ]);
        $pending->assignRole('student');

        // Rejected student
        $rejected = User::create([
            'npm' => '2410631060088', 'name' => 'Dimas Kurniawan', 'email' => '2410631060088@student.unsika.ac.id',
            'password' => Hash::make('Password1'), 'role' => 'student', 'status' => 'rejected',
            'faculty' => 'Fakultas Ekonomi dan Bisnis', 'study_program' => 'Manajemen', 'cohort' => '2024',
        ]);
        $rejected->assignRole('student');

        // ─── Announcements ──────────────────────────────────────
        $announcements = [
            ['title' => 'Pencairan Dana KIP Semester Genap 2025 Sudah Dimulai', 'content' => '<p>Dengan ini kami informasikan bahwa proses pencairan dana KIP Kuliah untuk semester genap tahun 2025 telah resmi dimulai. Mahasiswa penerima KIP diharapkan telah menyelesaikan proses pendataan dan upload KHS sebelum batas waktu yang ditentukan.</p><p>Pastikan data akademik dan rekening bank Anda sudah terverifikasi oleh admin.</p>', 'category' => 'pencairan', 'publish_date' => '2026-03-01'],
            ['title' => 'Batas Akhir Upload KHS Semester Ganjil 2025/2026', 'content' => '<p>Kepada seluruh mahasiswa penerima KIP Kuliah UNSIKA, kami mengingatkan bahwa batas akhir pengumpulan KHS semester ganjil 2025/2026 adalah <strong>tanggal 15 Maret 2026</strong>.</p><p>Mahasiswa yang belum mengumpulkan KHS akan dikenakan sanksi sesuai ketentuan yang berlaku.</p>', 'category' => 'administrasi', 'publish_date' => '2026-02-25'],
            ['title' => 'Kebijakan Baru Terkait Verifikasi Data Mahasiswa KIP', 'content' => '<p>Mulai semester genap 2025/2026, proses verifikasi data mahasiswa KIP Kuliah akan dilakukan secara lebih ketat. Setiap mahasiswa wajib melampirkan KHS asli yang telah ditandatangani oleh dosen wali.</p><p>Kebijakan ini bertujuan untuk meningkatkan akurasi data dan mencegah penyalahgunaan beasiswa.</p>', 'category' => 'kebijakan', 'publish_date' => '2026-02-20'],
            ['title' => 'Pengumuman Jadwal Rapat FORMADIKIP Maret 2026', 'content' => '<p>Rapat koordinasi FORMADIKIP bulan Maret 2026 akan dilaksanakan pada:</p><ul><li>Hari: Sabtu, 8 Maret 2026</li><li>Waktu: 09.00 WIB</li><li>Tempat: Ruang Serbaguna Gedung Rektorat</li></ul><p>Kehadiran seluruh pengurus FORMADIKIP sangat diharapkan.</p>', 'category' => 'internal', 'publish_date' => '2026-03-02'],
            ['title' => 'Tips Memaksimalkan Beasiswa KIP Kuliah', 'content' => '<p>Berikut beberapa tips untuk memaksimalkan manfaat beasiswa KIP Kuliah:</p><ol><li>Selalu update data akademik tepat waktu</li><li>Jaga IPK agar tetap memenuhi syarat</li><li>Manfaatkan dana beasiswa untuk kebutuhan akademik</li><li>Ikuti setiap kegiatan pembinaan yang diadakan</li><li>Jaga komunikasi dengan koordinator KIP</li></ol>', 'category' => 'lainnya', 'publish_date' => '2026-03-05'],
            ['title' => 'Pendaftaran Kegiatan Bakti Sosial KIP UNSIKA 2026', 'content' => '<p>FORMADIKIP UNSIKA mengadakan kegiatan bakti sosial pada bulan April 2026. Mahasiswa penerima KIP Kuliah diundang untuk berpartisipasi dalam kegiatan ini sebagai bentuk pengabdian masyarakat.</p><p>Pendaftaran dibuka mulai 10 Maret - 25 Maret 2026. Hubungi Abitha untuk informasi lebih lanjut.</p>', 'category' => 'lainnya', 'publish_date' => '2026-03-06'],
            ['title' => 'Informasi Perpanjangan Beasiswa KIP Kuliah 2026', 'content' => '<p>Bagi mahasiswa yang masa beasiswa KIP Kuliah-nya akan berakhir di semester ini, silakan segera mengajukan perpanjangan melalui sistem SIMPKIP nasional.</p><p>Berkas yang diperlukan: KHS terakhir, surat aktif kuliah, dan formulir perpanjangan.</p>', 'category' => 'administrasi', 'publish_date' => '2026-02-28'],
            ['title' => 'Hasil Pendataan Semester Ganjil 2025/2026', 'content' => '<p>Kami menginformasikan bahwa hasil pendataan semester ganjil 2025/2026 telah selesai diverifikasi. Dari total 250 mahasiswa penerima KIP, sebanyak 240 mahasiswa telah berhasil diverifikasi.</p><p>Mahasiswa yang belum terverifikasi harap segera menghubungi admin melalui menu kontak.</p>', 'category' => 'administrasi', 'publish_date' => '2026-03-03'],
        ];

        foreach ($announcements as $a) {
            Announcement::create(array_merge($a, [
                'created_by' => $admin->id,
                'is_published' => true,
            ]));
        }

        // ─── Tracking Stages ────────────────────────────────────
        $stages = [
            ['title' => 'Pengumpulan Data Mahasiswa', 'description' => 'Mahasiswa mengisi form pendataan dan upload KHS melalui sistem.', 'date' => '2026-02-01', 'status' => 'completed', 'sort_order' => 1],
            ['title' => 'Verifikasi oleh Admin Prodi', 'description' => 'Admin memverifikasi kelengkapan dan kebenaran data yang diajukan.', 'date' => '2026-02-15', 'status' => 'completed', 'sort_order' => 2],
            ['title' => 'Proses oleh Universitas', 'description' => 'Data yang lolos verifikasi diteruskan ke bagian keuangan universitas.', 'date' => '2026-03-01', 'status' => 'active', 'sort_order' => 3, 'notes' => 'Sedang dalam proses review oleh Biro Keuangan'],
            ['title' => 'Pengajuan ke Pusat (SIMPKIP)', 'description' => 'Data diajukan ke SIMPKIP Nasional untuk proses pencairan dana.', 'date' => null, 'status' => 'upcoming', 'sort_order' => 4],
            ['title' => 'Pencairan Dana ke Rekening', 'description' => 'Dana KIP Kuliah dicairkan langsung ke rekening mahasiswa.', 'date' => null, 'status' => 'upcoming', 'sort_order' => 5],
        ];

        foreach ($stages as $stage) {
            TrackingStage::create($stage);
        }

        // ─── Settings ───────────────────────────────────────────
        Setting::set('form_pendataan_active', '1');
        Setting::set('form_pendataan_period', 'Genap 2025/2026');

        // ─── Documents (SK per Angkatan) ────────────────────────
        $skDocuments = [
            ['name' => 'SK Penerima KIP Kuliah UNSIKA - Angkatan 2021', 'file' => 'documents/sk-kip-angkatan-2021.pdf', 'angkatan' => '2021'],
            ['name' => 'SK Penerima KIP Kuliah UNSIKA - Angkatan 2022', 'file' => 'documents/sk-kip-angkatan-2022.pdf', 'angkatan' => '2022'],
            ['name' => 'SK Penerima KIP Kuliah UNSIKA - Angkatan 2023', 'file' => 'documents/sk-kip-angkatan-2023.pdf', 'angkatan' => '2023'],
            ['name' => 'SK Penerima KIP Kuliah UNSIKA - Angkatan 2024', 'file' => 'documents/sk-kip-angkatan-2024.pdf', 'angkatan' => '2024'],
        ];

        foreach ($skDocuments as $doc) {
            Document::create($doc);
        }

        // ─── FAQ ────────────────────────────────────────────────
        $faqs = [
            ['question' => 'Bagaimana cara mendaftar akun di website KIP UNSIKA?', 'answer' => 'Klik tombol "Daftar Akun" di halaman utama, lalu isi formulir pendaftaran dengan data yang valid. Gunakan email @student.unsika.ac.id dan tunggu verifikasi admin.', 'sort_order' => 1],
            ['question' => 'Berapa lama proses verifikasi akun?', 'answer' => 'Proses verifikasi akun biasanya memakan waktu 1-3 hari kerja. Anda akan mendapat notifikasi melalui email setelah akun diverifikasi.', 'sort_order' => 2],
            ['question' => 'Bagaimana cara upload KHS?', 'answer' => 'Setelah login, buka halaman beranda dan klik "Isi Form Pendataan" pada section Form Pendataan. Upload file KHS dalam format PDF (maksimal 2MB) dan masukkan IPS semester terkait.', 'sort_order' => 3],
            ['question' => 'Apa yang terjadi jika saya terlambat upload KHS?', 'answer' => 'Keterlambatan upload KHS dapat menyebabkan penundaan proses pencairan. Selalu perhatikan batas waktu yang diumumkan oleh admin.', 'sort_order' => 4],
            ['question' => 'Bagaimana cara mengunduh SK Penerima KIP?', 'answer' => 'Setelah login, scroll ke section "Unduh SK" di halaman beranda, lalu klik tombol "Download". Dokumen hanya tersedia untuk mahasiswa yang sudah terverifikasi.', 'sort_order' => 5],
            ['question' => 'Siapa yang bisa saya hubungi jika ada masalah?', 'answer' => 'Anda bisa menghubungi layanan FORMADIKIP melalui Abitha (chatbot WhatsApp) yang tersedia di bagian FAQ halaman utama, atau datang langsung ke sekretariat FORMADIKIP.', 'sort_order' => 6],
        ];

        foreach ($faqs as $faq) {
            Faq::create(array_merge($faq, ['is_active' => true]));
        }

        // ─── Sample KHS Submissions ─────────────────────────────
        $mainStudent = User::where('npm', '2210631170086')->first();
        if ($mainStudent) {
            KhsSubmission::create([
                'user_id' => $mainStudent->id,
                'semester' => 5,
                'ips' => 3.65,
                'ipk' => 3.60,
                'khs_file' => 'khs/sample-khs-5.pdf',
                'status' => 'verified',
                'form_period' => 'Ganjil 2025/2026',
                'submitted_at' => '2025-09-15 10:30:00',
            ]);
            KhsSubmission::create([
                'user_id' => $mainStudent->id,
                'semester' => 6,
                'ips' => 3.70,
                'ipk' => 3.62,
                'khs_file' => 'khs/sample-khs-6.pdf',
                'status' => 'pending',
                'form_period' => 'Genap 2025/2026',
                'submitted_at' => '2026-03-07 08:00:00',
            ]);
        }

        $student2 = User::where('npm', '2210631170001')->first();
        if ($student2) {
            KhsSubmission::create([
                'user_id' => $student2->id,
                'semester' => 5,
                'ips' => 3.45,
                'ipk' => 3.40,
                'khs_file' => 'khs/sample-khs-siti.pdf',
                'status' => 'verified',
                'form_period' => 'Ganjil 2025/2026',
                'submitted_at' => '2025-09-14 09:00:00',
            ]);
        }

        $student3 = User::where('npm', '2110631040012')->first();
        if ($student3) {
            KhsSubmission::create([
                'user_id' => $student3->id,
                'semester' => 7,
                'ips' => 3.20,
                'ipk' => 2.85,
                'khs_file' => 'khs/sample-khs-budi.pdf',
                'status' => 'rejected',
                'admin_notes' => 'File KHS tidak terbaca, harap upload ulang dengan file yang lebih jelas.',
                'form_period' => 'Genap 2025/2026',
                'submitted_at' => '2026-03-06 14:20:00',
            ]);
        }

        // Dewi — IPK tinggi, disetujui (kondisi ideal)
        $student4 = User::where('npm', '2310631060023')->first();
        if ($student4) {
            KhsSubmission::create([
                'user_id' => $student4->id,
                'semester' => 3,
                'ips' => 3.80,
                'ipk' => 3.75,
                'khs_file' => 'khs/sample-khs-dewi.pdf',
                'status' => 'verified',
                'form_period' => 'Ganjil 2025/2026',
                'submitted_at' => '2025-09-16 11:00:00',
            ]);
        }

        // Rizky — IPS & IPK rendah, pending (kondisi warning)
        $student5 = User::where('npm', '2310631020034')->first();
        if ($student5) {
            KhsSubmission::create([
                'user_id' => $student5->id,
                'semester' => 3,
                'ips' => 2.90,
                'ipk' => 2.95,
                'khs_file' => 'khs/sample-khs-rizky.pdf',
                'status' => 'pending',
                'form_period' => 'Genap 2025/2026',
                'submitted_at' => '2026-03-08 09:30:00',
            ]);
        }
    }
}
