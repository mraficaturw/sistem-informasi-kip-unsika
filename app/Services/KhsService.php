<?php

namespace App\Services;

use App\Models\KhsSubmission;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class KhsService
{
    /**
     * Submit KHS for a semester.
     *
     * @throws ValidationException
     */
    public function submit(User $user, int $semester, float $ips, UploadedFile $file): KhsSubmission
    {
        // Check for duplicate semester submission
        $exists = KhsSubmission::where('user_id', $user->id)
            ->where('semester', $semester)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'semester' => ['Anda sudah mengupload KHS untuk semester ini.'],
            ]);
        }

        // Store the PDF file
        $path = $file->store('khs/' . $user->npm, 'public');

        return KhsSubmission::create([
            'user_id' => $user->id,
            'semester' => $semester,
            'ips' => $ips,
            'khs_file' => $path,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);
    }
}
