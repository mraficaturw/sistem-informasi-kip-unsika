<?php
namespace App\Filament\Resources\AnnouncementResource\Pages;
use App\Filament\Resources\AnnouncementResource;
use App\Services\EmailNotificationService;
use Filament\Resources\Pages\CreateRecord;

class CreateAnnouncement extends CreateRecord
{
    protected static string $resource = AnnouncementResource::class;

    // Setelah berhasil buat, kembali ke halaman daftar
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Setelah berita berhasil dibuat, kirim email notifikasi ke mahasiswa opt-in
     * jika berita dipublikasikan langsung.
     */
    protected function afterCreate(): void
    {
        $record = $this->record;

        if ($record->is_published) {
            app(EmailNotificationService::class)->notifyNewAnnouncement($record);
        }
    }
}
