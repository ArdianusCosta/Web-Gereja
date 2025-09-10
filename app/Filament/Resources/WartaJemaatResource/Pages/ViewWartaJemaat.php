<?php

namespace App\Filament\Resources\WartaJemaatResource\Pages;

use App\Filament\Resources\WartaJemaatResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWartaJemaat extends ViewRecord
{
    protected static string $resource = WartaJemaatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
