<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use Filament\Forms\Components as FormComponents;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use BackedEnum;
use UnitEnum;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';
    protected static string|UnitEnum|null $navigationGroup = 'Konten';
    protected static string|null $navigationLabel = 'Berita';
    protected static string|null $modelLabel = 'Berita';
    protected static string|null $pluralModelLabel = 'Berita';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            SchemaComponents\Section::make('Detail Berita')->schema([
                FormComponents\FileUpload::make('cover_image')
                    ->label('Cover Image')
                    ->image()
                    ->disk('public')
                    ->directory('announcements/covers')
                    ->maxSize(2048)
                    ->columnSpanFull(),
                FormComponents\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),
                FormComponents\RichEditor::make('content')
                    ->label('Isi Berita')
                    ->required()
                    ->columnSpanFull(),
                FormComponents\Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'pencairan' => 'Pencairan',
                        'administrasi' => 'Administrasi',
                        'kebijakan' => 'Kebijakan',
                        'internal' => 'Internal',
                        'lainnya' => 'Lainnya',
                    ]),
                FormComponents\DatePicker::make('publish_date')
                    ->label('Tanggal')
                    ->required()
                    ->default(now()),
                FormComponents\Toggle::make('is_published')
                    ->label('Dipublikasikan')
                    ->default(true),
                FormComponents\Hidden::make('created_by')
                    ->default(fn () => auth()->id()),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://placehold.co/40x40/e84545/white?text=N'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->label('Kategori')
                    ->colors([
                        'primary' => 'pencairan',
                        'warning' => 'administrasi',
                        'info' => 'kebijakan',
                        'gray' => 'internal',
                    ]),
                Tables\Columns\TextColumn::make('publish_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'pencairan' => 'Pencairan',
                        'administrasi' => 'Administrasi',
                        'kebijakan' => 'Kebijakan',
                        'internal' => 'Internal',
                        'lainnya' => 'Lainnya',
                    ]),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->defaultSort('publish_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
