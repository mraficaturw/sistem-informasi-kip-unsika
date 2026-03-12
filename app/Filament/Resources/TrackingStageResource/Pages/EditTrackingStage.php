<?php

namespace App\Filament\Resources\TrackingStageResource\Pages;

use App\Filament\Resources\TrackingStageResource;
use Filament\Resources\Pages\EditRecord;

class EditTrackingStage extends EditRecord
{
    protected static string $resource = TrackingStageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
