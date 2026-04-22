<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KhsSubmissionResource\Pages;
use App\Models\KhsSubmission;
use Filament\Forms\Components as FormComponents;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\KhsVerifiedMail;
use App\Mail\KhsRejectedMail;
use BackedEnum;
use UnitEnum;

class KhsSubmissionResource extends Resource
{
    protected static ?string $model = KhsSubmission::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static string|UnitEnum|null $navigationGroup = 'Akademik';
    protected static string|null $navigationLabel = 'Pendataan KHS';
    protected static string|null $modelLabel = 'Pendataan KHS';
    protected static string|null $pluralModelLabel = 'Pendataan KHS';
    protected static ?int $navigationSort = 1;

    /**
     * Gunakan eager loading relasi 'user' untuk menghindari N+1 query
     * saat tabel menampilkan banyak baris dengan kolom user.name, user.faculty, dsb.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with('user');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            SchemaComponents\Section::make('Detail KHS')->schema([
                // Relasi ke mahasiswa, bisa dicari berdasarkan nama
                FormComponents\Select::make('user_id')
                    ->label('Mahasiswa')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                FormComponents\TextInput::make('semester')
                    ->label('Semester')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(14),
                FormComponents\TextInput::make('ips')
                    ->label('IPS')
                    ->numeric()
                    ->required()
                    ->step(0.01)
                    ->minValue(0)
                    ->maxValue(4),
                FormComponents\TextInput::make('ipk')
                    ->label('IPK Terakhir')
                    ->numeric()
                    ->required()
                    ->step(0.01)
                    ->minValue(0)
                    ->maxValue(4),
                FormComponents\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending'  => 'Menunggu',
                        'verified' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->required(),
                // Kolom catatan diisi admin jika menolak pengajuan
                FormComponents\Textarea::make('admin_notes')
                    ->label('Catatan / Alasan Penolakan')
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.faculty')
                    ->label('Fakultas')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('user.study_program')
                    ->label('Prodi')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('user.cohort')
                    ->label('Angkatan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ips')
                    ->label('IPS')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ipk')
                    ->label('IPK')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'verified',
                        'danger'  => 'rejected',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'  => 'Menunggu',
                        'verified' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default    => $state,
                    }),
                Tables\Columns\TextColumn::make('form_period')
                    ->label('Periode')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('Diajukan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'  => 'Menunggu',
                        'verified' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                // Tombol untuk membuka file PDF KHS di tab baru
                Actions\Action::make('viewFile')
                    ->label('Lihat KHS')
                    ->icon('heroicon-o-eye')
                    ->url(fn (KhsSubmission $record) => \Illuminate\Support\Facades\Storage::disk('supabase')->temporaryUrl($record->khs_file, now()->addMinutes(30)))
                    ->openUrlInNewTab(),

                // Tombol setujui: hanya tampil jika status masih pending
                Actions\Action::make('verify')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (KhsSubmission $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (KhsSubmission $record) {
                        // Ubah status KHS menjadi verified
                        $record->update(['status' => 'verified']);

                        // Reset counter penolakan dan cooldown mahasiswa setelah disetujui
                        $record->user()->update([
                            'khs_rejection_count'  => 0,
                            'khs_next_resubmit_at' => null,
                        ]);

                        // Kirim email notifikasi jika mahasiswa opt-in
                        if ($record->user->email_opt_in) {
                            Mail::to($record->user->email)->queue(new KhsVerifiedMail($record));
                        }

                        Notification::make()->title('KHS disetujui.')->success()->send();
                    }),

                // Tombol tolak: hanya tampil jika status masih pending
                Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (KhsSubmission $record) => $record->status === 'pending')
                    ->form([
                        // Admin wajib mengisi alasan penolakan
                        FormComponents\Textarea::make('admin_notes')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function (KhsSubmission $record, array $data) {
                        // Simpan status ditolak beserta catatan alasan dari admin
                        $record->update([
                            'status'      => 'rejected',
                            'admin_notes' => $data['admin_notes'],
                        ]);

                        // Terapkan cooldown 15 menit agar mahasiswa tidak langsung resubmit
                        $record->user->update([
                            'khs_next_resubmit_at' => now()->addMinutes(15),
                        ]);

                        // Kirim email notifikasi penolakan jika mahasiswa opt-in
                        if ($record->user->email_opt_in) {
                            Mail::to($record->user->email)->queue(new KhsRejectedMail($record));
                        }

                        Notification::make()->title('KHS ditolak.')->warning()->send();
                    }),

                Actions\EditAction::make(),
            ])
            ->headerActions([
                // Ekspor data KHS yang disetujui ke format Excel
                Actions\Action::make('exportApproved')
                    ->label('Export Disetujui (Excel)')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        return \Maatwebsite\Excel\Facades\Excel::download(
                            new \App\Exports\KhsApprovedExport(),
                            'khs_disetujui_' . now()->format('Y-m-d') . '.xlsx'
                        );
                    }),
            ])
            ->defaultSort('submitted_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKhsSubmissions::route('/'),
            'edit'  => Pages\EditKhsSubmission::route('/{record}/edit'),
        ];
    }
}
