<?php

namespace App\Filament\Resources\TrackingStageResource\Pages;

use App\Filament\Resources\TrackingStageResource;
use Filament\Resources\Pages\ListRecords;

class ListTrackingStages extends ListRecords
{
    protected static string $resource = TrackingStageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
