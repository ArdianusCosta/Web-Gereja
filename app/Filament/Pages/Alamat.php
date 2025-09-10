<?php

namespace App\Filament\Pages;

use App\Models\Alamat as AlamatModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;

class Alamat extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static string $view = 'filament.pages.alamat';
    protected static ?string $title = 'Alamat & Maps';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('alamat')
                            ->label('Alamat Lengkap')
                            ->extraAttributes(['id' => 'alamat-input', 'list' => 'alamat-list'])
                            ->columnSpanFull(),

                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->extraAttributes(['id' => 'latitude-input'])
                            ->placeholder('Latitude otomatis terisi...'),

                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->extraAttributes(['id' => 'longitude-input'])
                            ->placeholder('Longitude otomatis terisi...'),
                    ])->columns(2),
            ])
            ->statePath('data')
            ->model(AlamatModel::class);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        if (($state['latitude'] && !$state['longitude']) || (!$state['latitude'] && $state['longitude'])) {
            Notification::make()
                ->title('Gagal menyimpan')
                ->body('Latitude dan Longitude harus sepasang.')
                ->danger()
                ->send();
            return;
        }

        AlamatModel::create($state);
        $this->form->fill();

        Notification::make()
            ->title('Berhasil!')
            ->body('Data alamat berhasil disimpan.')
            ->success()
            ->send();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(AlamatModel::query())
            ->columns([
                TextColumn::make('alamat')->label('Alamat'),
                TextColumn::make('latitude')->label('Lat'),
                TextColumn::make('longitude')->label('Lon'),
            ])
            ->actions([
                DeleteAction::make(),
            ]);
    }
}
