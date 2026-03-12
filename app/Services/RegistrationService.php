<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegistrationService
{
    /**
     * Register a new student account.
     *
     * @throws ValidationException
     */
    public function registerStudent(array $data): User
    {
        // Check for duplicate NPM
        if (User::where('npm', $data['npm'])->exists()) {
            throw ValidationException::withMessages([
                'npm' => ['NPM sudah terdaftar dalam sistem.'],
            ]);
        }

        $user = User::create([
            'npm' => $data['npm'],
            'name' => $data['name'],
            'email' => $data['email'],
            'faculty' => $data['faculty'],
            'study_program' => $data['study_program'],
            'cohort' => $data['cohort'],
            'password' => Hash::make($data['password']),
            'role' => 'student',
            'status' => 'pending',
        ]);

        $user->assignRole('student');

        return $user;
    }
}
