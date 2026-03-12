<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrackingStageResource\Pages;
use App\Models\TrackingStage;
use Filament\Forms\Components as FormComponents;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use BackedEnum;
use UnitEnum;

class TrackingStageResource extends Resource
{
    protected static ?string $model = TrackingStage::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-signal';
    protected static string|UnitEnum|null $navigationGroup = 'Konten';
    protected static string|null $navigationLabel = 'Tracking Pencairan';
    protected static string|null $modelLabel = 'Tahap Tracking';
    protected static string|null $pluralModelLabel = 'Tracking Pencairan';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            SchemaComponents\Section::make('Detail Tahap')->schema([
                FormComponents\TextInput::make('title')
                    ->label('Judul Tahap')
                    ->required()
                    ->maxLength(255),
                FormComponents\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(2),
                FormComponents\DatePicker::make('date')
                    ->label('Tanggal'),
                FormComponents\Select::make('status')
                    ->label('Status')
                    ->options([
                        'completed' => 'Selesai',
                        'active' => 'Sedang Berjalan',
                        'upcoming' => 'Akan Datang',
                    ])
                    ->required()
                    ->default('upcoming'),
                FormComponents\TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->required()
                    ->default(0),
                FormComponents\Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(2),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->label('Status')
                    ->colors([
                        'success' => 'completed',
                        'warning' => 'active',
                        'gray' => 'upcoming',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'completed' => 'Selesai',
                        'active' => 'Berjalan',
                        'upcoming' => 'Akan Datang',
                        default => $state,
                    }),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->defaultSort('sort_order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrackingStages::route('/'),
            'create' => Pages\CreateTrackingStage::route('/create'),
            'edit' => Pages\EditTrackingStage::route('/{record}/edit'),
        ];
    }
}
