<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Announcement::published()->latest('publish_date');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        $announcements = $query->paginate(9)->withQueryString();

        return view('announcements.index', compact('announcements'));
    }

    public function show(Announcement $announcement): View
    {
        return view('announcements.show', compact('announcement'));
    }
}
