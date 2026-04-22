<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KhsUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'ips' => ['required', 'numeric', 'min:0', 'max:4.00'],
            'khs_file' => ['required', 'file', 'mimes:pdf', 'max:2048'], // 2MB (sesuai limit bucket Supabase)
        ];
    }

    public function messages(): array
    {
        return [
            'semester.required' => 'Semester wajib diisi.',
            'semester.integer' => 'Semester harus berupa angka.',
            'semester.min' => 'Semester minimal 1.',
            'semester.max' => 'Semester maksimal 14.',
            'ips.required' => 'IPS wajib diisi.',
            'ips.numeric' => 'IPS harus berupa angka.',
            'ips.min' => 'IPS minimal 0.',
            'ips.max' => 'IPS maksimal 4.00.',
            'khs_file.required' => 'File KHS wajib diupload.',
            'khs_file.mimes' => 'File KHS harus berformat PDF.',
            'khs_file.max' => 'Ukuran file KHS maksimal 2MB.',
        ];
    }
}
