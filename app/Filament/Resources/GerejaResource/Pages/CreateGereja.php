<?php

namespace App\Filament\Resources\GerejaResource\Pages;

use App\Filament\Resources\GerejaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGereja extends CreateRecord
{
    protected static string $resource = GerejaResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl();
    }

}
