<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'npm' => ['required', 'string', 'max:20', 'unique:users,npm'],
            'name' => ['required', 'string', 'max:255'],
            'faculty' => ['required', 'string', 'max:255'],
            'study_program' => ['required', 'string', 'max:255'],
            'cohort' => ['required', 'string', 'size:4'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                'regex:/^[^@]+@student\.unsika\.ac\.id$/',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $npm = request()->input('npm');
                    if ($npm && $value !== $npm . '@student.unsika.ac.id') {
                        $fail("Email harus berformat {$npm}@student.unsika.ac.id.");
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'npm.required' => 'NPM wajib diisi.',
            'npm.unique' => 'NPM sudah terdaftar dalam sistem.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'faculty.required' => 'Fakultas wajib dipilih.',
            'study_program.required' => 'Program studi wajib dipilih.',
            'cohort.required' => 'Angkatan wajib dipilih.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.regex' => 'Email harus menggunakan domain @student.unsika.ac.id.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
        ];
    }
}
