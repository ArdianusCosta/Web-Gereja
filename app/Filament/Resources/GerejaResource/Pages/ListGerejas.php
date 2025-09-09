<?php

namespace App\Filament\Resources\GerejaResource\Pages;

use App\Filament\Resources\GerejaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGerejas extends ListRecords
{
    protected static string $resource = GerejaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
