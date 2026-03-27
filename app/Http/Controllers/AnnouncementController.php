<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        // ── Bangun query dasar: hanya berita yang sudah dipublikasikan ──────
        $query = Announcement::published()->latest('publish_date');

        // ── Filter pencarian berdasarkan judul atau isi berita ─────────────
        // Hanya dijalankan jika parameter 'search' diisi oleh pengguna
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // ── Filter berdasarkan kategori berita (jika dipilih) ──────────────
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // ── Paginasi hasil: 9 berita per halaman, pertahankan query string ─
        $announcements = $query->paginate(9)->withQueryString();

        return view('announcements.index', compact('announcements'));
    }

    /**
     * Tampilkan detail satu berita (route model binding otomatis).
     */
    public function show(Announcement $announcement): View
    {
        return view('announcements.show', compact('announcement'));
    }
}
