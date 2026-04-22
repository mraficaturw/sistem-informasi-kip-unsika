<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\EmailNotificationService;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use BackedEnum;

class ManageSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan';
    protected static ?string $title = 'Pengaturan Sistem';
    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.manage-settings';

    public bool $formPendataanActive = false;
    public string $formPendataanPeriod = '';

    public function mount(): void
    {
        $this->formPendataanActive = Setting::get('form_pendataan_active', '0') === '1';
        $this->formPendataanPeriod = Setting::get('form_pendataan_period', '') ?? '';
    }

    public function save(): void
    {
        // Simpan state sebelumnya untuk deteksi perubahan
        $wasActive = Setting::get('form_pendataan_active', '0') === '1';

        Setting::set('form_pendataan_active', $this->formPendataanActive ? '1' : '0');
        Setting::set('form_pendataan_period', $this->formPendataanPeriod);

        // Kirim email notifikasi jika form baru saja diaktifkan (false → true)
        if (!$wasActive && $this->formPendataanActive && $this->formPendataanPeriod) {
            $count = app(EmailNotificationService::class)
                ->notifyNewPeriod($this->formPendataanPeriod);

            Notification::make()
                ->title("Pengaturan disimpan. Email dikirim ke {$count} mahasiswa.")
                ->success()
                ->send();

            return;
        }

        Notification::make()
            ->title('Pengaturan berhasil disimpan.')
            ->success()
            ->send();
    }
}
