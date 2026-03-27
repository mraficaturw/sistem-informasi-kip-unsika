<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Models\Document;
use Filament\Forms\Components as FormComponents;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use BackedEnum;
use UnitEnum;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-folder-open';
    protected static string|UnitEnum|null $navigationGroup = 'Konten';
    protected static string|null $navigationLabel = 'Dokumen SK';
    protected static string|null $modelLabel = 'Dokumen SK';
    protected static string|null $pluralModelLabel = 'Dokumen SK';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            SchemaComponents\Section::make('Upload Dokumen SK')->schema([
                FormComponents\TextInput::make('name')
                    ->label('Nama Dokumen')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: SK Penerima KIP Semester Genap 2025'),
                FormComponents\FileUpload::make('file')
                    ->label('File Dokumen (PDF)')
                    ->required()
                    ->disk('public')
                    ->directory('documents')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(2048),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Dokumen')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Upload')->dateTime('d M Y')->sortable(),
            ])
            ->recordActions([
                Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Document $record) => asset('storage/' . $record->file))
                    ->openUrlInNewTab(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
