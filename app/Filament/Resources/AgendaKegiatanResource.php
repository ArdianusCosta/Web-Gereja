<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AgendaKegiatan;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AgendaKegiatanResource\Pages;
use App\Filament\Resources\AgendaKegiatanResource\RelationManagers;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Columns\TextColumn;

class AgendaKegiatanResource extends Resource
{
    protected static ?string $model = AgendaKegiatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $pluralLabel = 'Agenda Kegiatan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('gereja_id')
                            ->label('Lokasi Gereja')
                            ->preload(true)
                            ->relationship('gereja','nama'),
                        TextInput::make('alamat')
                            ->label('Alamat Kegiatan')
                            ->required()
                            ->placeholder('Masukan alamat / Pilih alamat pakai Button Map berwarna hijau dikanan')
                            ->extraAttributes(['id' => 'alamatInput'])
                            ->suffixAction(
                                Action::make('kelolaAlamat')
                                    ->label('Cari Alamat')
                                    ->icon('heroicon-o-map-pin')
                                    ->color('success')
                                    ->url(fn() => \App\Filament\Pages\Alamat::getUrl())
                            ),                        
                        DatePicker::make('tanggal')
                            ->label('Tanggal Kegiatan')
                            ->required()
                            ->default(now()),
                        TimePicker::make('waktu')
                            ->label('Waktu Kegiatan')
                            ->default(waktu()),
                        Textarea::make('kegiatan')
                            ->label('Nama Kegiatan')
                            ->required()
                            ->rows(5)
                            ->placeholder('Masukan nama kegiatan...'),
                        Textarea::make('tempat')
                            ->label('Nama Tempat')
                            ->rows(5)
                            ->placeholder('Masukan nama tempat...'),
                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(5)
                            ->placeholder('Masukan keterangan...')
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('gereja.nama')
                    ->label('Lokasi Gereja')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('alamat')
                    ->label('Alamat Kegiatan')
                    ->searchable(),
                TextColumn::make('tanggal')
                    ->label('Tanggal Kegiatan')
                    ->formatStateUsing(fn($state) => $state ? tanggal($state): '-')
                    ->searchable(),
                TextColumn::make('waktu')
                    ->label('Waktu Kegiatan')
                    ->formatStateUsing(fn($state) => $state ? tanggal($state): '-')
                    ->searchable(),
                TextColumn::make('kegiatan')
                    ->label('Nama Kegiatan')
                    ->searchable(),
                TextColumn::make('tempat')
                    ->searchable()
                    ->label('Nama Tempat'),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->searchable()
                    ->label('created_at')
                    ->toggleable(isToggledHiddenByDefault:true)
                    ->formatStateUsing(fn($state) => $state ? tanggalWaktu($state): '-'),
                TextColumn::make('updated_at')
                    ->searchable()
                    ->label('updated_at')
                    ->toggleable(isToggledHiddenByDefault:true)
                    ->formatStateUsing(fn($state) => $state ? tanggalWaktu($state): '-'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgendaKegiatans::route('/'),
            'create' => Pages\CreateAgendaKegiatan::route('/create'),
            'edit' => Pages\EditAgendaKegiatan::route('/{record}/edit'),
        ];
    }
}
