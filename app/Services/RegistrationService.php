<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegistrationService
{
    /**
     * Daftarkan akun mahasiswa baru ke sistem.
     *
     * Logika:
     * 1. Cek apakah NPM sudah terdaftar (harus unik)
     * 2. Buat user baru dengan status 'pending' (menunggu persetujuan admin)
     * 3. Assign role 'student' via Spatie Permission
     *
     * @throws ValidationException jika NPM sudah terdaftar
     */
    public function registerStudent(array $data): User
    {
        // Pastikan NPM belum digunakan oleh mahasiswa lain
        if (User::where('npm', $data['npm'])->exists()) {
            throw ValidationException::withMessages([
                'npm' => ['NPM sudah terdaftar dalam sistem.'],
            ]);
        }

        // Buat akun mahasiswa baru; password di-hash otomatis via model cast
        $user = User::create([
            'npm'           => $data['npm'],
            'name'          => $data['name'],
            'email'         => $data['email'],
            'faculty'       => $data['faculty'],
            'study_program' => $data['study_program'],
            'cohort'        => $data['cohort'],
            'password'      => Hash::make($data['password']),
            'role'          => 'student',
            // Status 'pending': akun baru harus disetujui admin sebelum bisa login
            'status'        => 'pending',
        ]);

        // Assign role via Spatie Permission untuk kontrol akses berbasis role
        $user->assignRole('student');

        return $user;
    }
}
