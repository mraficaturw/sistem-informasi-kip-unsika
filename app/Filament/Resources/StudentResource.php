<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Mail\AccountApprovedMail;
use App\Mail\AccountRejectedMail;
use App\Models\User;
use Filament\Forms\Components as FormComponents;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;
use BackedEnum;
use UnitEnum;

class StudentResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';
    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Mahasiswa';
    protected static string|null $navigationLabel = 'Mahasiswa';
    protected static string|null $modelLabel = 'Mahasiswa';
    protected static string|null $pluralModelLabel = 'Mahasiswa';
    protected static ?int $navigationSort = 1;

    /**
     * Filter query agar resource ini hanya menampilkan user dengan role 'student',
     * bukan admin. Ini mencegah admin muncul di tabel mahasiswa.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('role', 'student');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make('Informasi Mahasiswa')
                    ->schema([
                        FormComponents\TextInput::make('npm')
                            ->label('NPM')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20),
                        FormComponents\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        FormComponents\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        FormComponents\TextInput::make('faculty')
                            ->label('Fakultas')
                            ->maxLength(255),
                        FormComponents\TextInput::make('study_program')
                            ->label('Program Studi')
                            ->maxLength(255),
                        FormComponents\TextInput::make('cohort')
                            ->label('Angkatan')
                            ->maxLength(4),
                        // Dropdown status: menentukan apakah mahasiswa bisa login
                        FormComponents\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending'  => 'Menunggu Verifikasi',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                            ])
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('npm')
                    ->label('NPM')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('faculty')
                    ->label('Fakultas')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('study_program')
                    ->label('Prodi')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('cohort')
                    ->label('Angkatan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger'  => 'rejected',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'  => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default    => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'  => 'Menunggu Verifikasi',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
                // Filter berdasarkan angkatan, opsi diambil dari data yang sudah ada di DB
                Tables\Filters\SelectFilter::make('cohort')
                    ->label('Angkatan')
                    ->options(
                        fn () => User::where('role', 'student')
                            ->distinct()
                            ->orderBy('cohort')
                            ->pluck('cohort', 'cohort')
                            ->filter()
                            ->toArray()
                    ),
            ])
            ->recordActions([
                // Tombol setujui: hanya tampil jika status belum approved
                Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (User $record) => $record->status !== 'approved')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Mahasiswa?')
                    ->modalDescription('Mahasiswa akan bisa login setelah disetujui.')
                    ->action(function (User $record) {
                        // Ubah status menjadi approved dan kirim email notifikasi
                        $record->update(['status' => 'approved']);

                        // Email dikirim via queue agar tidak memperlambat respons admin
                        Mail::to($record->email)->queue(new AccountApprovedMail($record));

                        Notification::make()
                            ->title('Mahasiswa berhasil disetujui.')
                            ->success()
                            ->send();
                    }),

                // Tombol tolak: hanya tampil jika status belum rejected
                Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (User $record) => $record->status !== 'rejected')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Mahasiswa?')
                    ->action(function (User $record) {
                        $record->update(['status' => 'rejected']);

                        // Kirim email notifikasi penolakan jika mahasiswa opt-in
                        if ($record->email_opt_in) {
                            Mail::to($record->email)->queue(new AccountRejectedMail($record));
                        }

                        Notification::make()
                            ->title('Mahasiswa ditolak.')
                            ->warning()
                            ->send();
                    }),

                Actions\EditAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit'   => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
