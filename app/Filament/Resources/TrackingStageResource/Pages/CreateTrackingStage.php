<?php

namespace App\Filament\Resources\TrackingStageResource\Pages;

use App\Filament\Resources\TrackingStageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTrackingStage extends CreateRecord
{
    protected static string $resource = TrackingStageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
