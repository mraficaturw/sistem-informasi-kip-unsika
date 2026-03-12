<x-filament-panels::page>
    <form wire:submit="save">
        <div class="space-y-6">
            {{-- Form Pendataan Settings --}}
            <x-filament::section heading="Form Pendataan Semester" description="Kelola status form pendataan KHS mahasiswa">
                <div class="space-y-4">
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="formPendataanActive" class="fi-checkbox-input rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                Form Pendataan Aktif
                            </span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Jika diaktifkan, mahasiswa dapat mengisi form pendataan semester.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                            Label Periode (opsional)
                        </label>
                        <input type="text" wire:model="formPendataanPeriod" placeholder="Contoh: Genap 2025/2026"
                            class="fi-input block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <p class="mt-1 text-sm text-gray-500">Label ini akan ditampilkan kepada mahasiswa.</p>
                    </div>
                </div>
            </x-filament::section>

            <div class="flex justify-end">
                <x-filament::button type="submit" icon="heroicon-o-check">
                    Simpan Pengaturan
                </x-filament::button>
            </div>
        </div>
    </form>
</x-filament-panels::page>
