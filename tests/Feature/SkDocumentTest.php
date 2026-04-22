<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Black-box & White-box Testing: SK Document per Angkatan
 *
 * Menguji fitur unduh SK penerima KIP yang dibagi per angkatan.
 */
class SkDocumentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        // Minimal settings agar halaman home bisa dirender
        Setting::set('form_pendataan_active', '0');
        Setting::set('form_pendataan_period', '');
    }

    // ─── Helper ────────────────────────────────────────────────────

    private function createApprovedStudent(): User
    {
        $student = User::factory()->create([
            'role'   => 'student',
            'status' => 'approved',
        ]);
        $student->assignRole('student');

        return $student;
    }

    private function createUnapprovedStudent(): User
    {
        $student = User::factory()->create([
            'role'   => 'student',
            'status' => 'pending',
        ]);
        $student->assignRole('student');

        return $student;
    }

    private function seedSkDocuments(): void
    {
        Document::create(['name' => 'SK Angkatan 2021', 'file' => 'documents/sk-2021.pdf', 'angkatan' => '2021']);
        Document::create(['name' => 'SK Angkatan 2022', 'file' => 'documents/sk-2022.pdf', 'angkatan' => '2022']);
        Document::create(['name' => 'SK Angkatan 2023', 'file' => 'documents/sk-2023.pdf', 'angkatan' => '2023']);
        Document::create(['name' => 'SK Angkatan 2024', 'file' => 'documents/sk-2024.pdf', 'angkatan' => '2024']);
    }

    // ─── SK-01: Homepage menampilkan tombol "Unduh SK" saat ada dokumen ─

    /**
     * SK-01: Jika ada dokumen SK di DB, tombol "Unduh SK" harus tampil.
     */
    public function test_homepage_shows_unduh_sk_button_when_documents_exist(): void
    {
        $this->seedSkDocuments();
        $student = $this->createApprovedStudent();

        $response = $this->actingAs($student)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Unduh SK');
        $response->assertSee('modalPilihSK');
    }

    // ─── SK-02: Homepage menampilkan "Belum Ada Dokumen" saat kosong ───

    /**
     * SK-02: Jika tidak ada dokumen SK, tombol disabled harus tampil.
     */
    public function test_homepage_shows_no_document_when_table_empty(): void
    {
        $student = $this->createApprovedStudent();

        $response = $this->actingAs($student)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Belum Ada Dokumen');
    }

    // ─── SK-03: Guest melihat tombol "Login untuk Unduh" ──────────

    /**
     * SK-03: User guest harus melihat tombol login, bukan tombol download.
     */
    public function test_guest_sees_login_button_on_sk_section(): void
    {
        $this->seedSkDocuments();

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Login untuk Unduh');
        $response->assertDontSee('modalPilihSK');
    }

    // ─── SK-04: Approved student melihat tombol unduh (trigger modal) ─

    /**
     * SK-04: Student approved harus bisa buka modal pilih SK.
     */
    public function test_approved_student_sees_download_button(): void
    {
        $this->seedSkDocuments();
        $student = $this->createApprovedStudent();

        $response = $this->actingAs($student)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Unduh SK');
        $response->assertSee('Pilih SK Penerima KIP');
        $response->assertSee('Angkatan 2021');
        $response->assertSee('Angkatan 2024');
    }

    // ─── SK-05: Unapproved student melihat tombol disabled ────────

    /**
     * SK-05: Student yang belum diapprove melihat tombol disabled.
     */
    public function test_unapproved_student_sees_disabled_button(): void
    {
        $this->seedSkDocuments();
        $student = $this->createUnapprovedStudent();

        $response = $this->actingAs($student)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Akun Belum Disetujui');
        $response->assertDontSee('modalPilihSK');
    }

    // ─── SK-06: Document model menerima field angkatan ────────────

    /**
     * SK-06 (White-box): Model Document harus bisa mass-assign angkatan.
     */
    public function test_document_model_accepts_angkatan_field(): void
    {
        $doc = Document::create([
            'name'     => 'SK Test',
            'file'     => 'documents/test.pdf',
            'angkatan' => '2023',
        ]);

        $this->assertDatabaseHas('documents', [
            'id'       => $doc->id,
            'angkatan' => '2023',
        ]);
    }

    // ─── SK-07: Seeder membuat 4 dokumen SK per angkatan ──────────

    /**
     * SK-07 (White-box): Seeder harus membuat 4 dokumen SK dengan angkatan berbeda.
     */
    public function test_seeder_creates_four_sk_documents(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $skDocs = Document::whereNotNull('angkatan')->get();

        $this->assertCount(4, $skDocs);
        $this->assertEquals(
            ['2021', '2022', '2023', '2024'],
            $skDocs->pluck('angkatan')->sort()->values()->toArray()
        );
    }
}
