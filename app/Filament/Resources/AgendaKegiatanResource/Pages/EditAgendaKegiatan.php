<?php

namespace App\Filament\Resources\AgendaKegiatanResource\Pages;

use App\Filament\Resources\AgendaKegiatanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgendaKegiatan extends EditRecord
{
    protected static string $resource = AgendaKegiatanResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl();
    }
}
