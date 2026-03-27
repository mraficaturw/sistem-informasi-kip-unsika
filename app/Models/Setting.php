<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Durasi cache untuk pengaturan (dalam detik).
     * Pengaturan jarang berubah, sehingga cache 10 menit sangat efisien.
     */
    protected const CACHE_TTL = 600;

    /**
     * Prefix key untuk cache, menghindari konflik dengan cache lain.
     */
    protected const CACHE_PREFIX = 'setting:';

    /**
     * Ambil nilai pengaturan berdasarkan key.
     * Menggunakan cache untuk menghindari query berulang ke database.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        // Coba ambil dari cache terlebih dahulu; jika tidak ada, query DB dan simpan ke cache
        $value = Cache::remember(
            self::CACHE_PREFIX . $key,
            self::CACHE_TTL,
            fn () => static::where('key', $key)->value('value')
        );

        // Kembalikan nilai dari cache/DB, atau gunakan default jika null
        return $value ?? $default;
    }

    /**
     * Simpan atau perbarui nilai pengaturan berdasarkan key.
     * Cache dihapus agar data berikutnya selalu segar (fresh).
     */
    public static function set(string $key, mixed $value): void
    {
        // Perbarui atau buat entri di database
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Hapus cache lama agar nilai baru langsung terbaca
        Cache::forget(self::CACHE_PREFIX . $key);
    }

    /**
     * Hapus seluruh cache pengaturan (berguna saat reset massal).
     */
    public static function clearCache(string $key): void
    {
        Cache::forget(self::CACHE_PREFIX . $key);
    }
}
