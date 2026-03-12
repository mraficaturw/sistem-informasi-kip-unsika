<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\User;

class ComplaintService
{
    /**
     * Create a new complaint from a student.
     */
    public function createComplaint(User $user, array $data): Complaint
    {
        return Complaint::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'message' => $data['message'],
            'status' => 'open',
        ]);
    }

    /**
     * Admin replies to a complaint.
     */
    public function reply(Complaint $complaint, string $reply): Complaint
    {
        $complaint->update([
            'admin_reply' => $reply,
            'status' => 'replied',
            'replied_at' => now(),
        ]);

        return $complaint->fresh();
    }

    /**
     * Close a complaint.
     */
    public function close(Complaint $complaint): Complaint
    {
        $complaint->update(['status' => 'closed']);

        return $complaint->fresh();
    }
}
