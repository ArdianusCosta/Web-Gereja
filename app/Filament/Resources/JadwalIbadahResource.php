<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\JadwalIbadah;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\JadwalIbadahResource\Pages;
use App\Filament\Resources\JadwalIbadahResource\RelationManagers;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Columns\TextColumn;

class JadwalIbadahResource extends Resource
{
    protected static ?string $model = JadwalIbadah::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';
    protected static ?string $pluralLabel = 'Jadwal Ibadah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    Select::make('gereja_id')
                        ->label('Gereja')
                        ->relationship('gereja', 'nama')
                        ->required()
                        ->native(false)
                        ->searchable()
                        ->preload(true)
                        ->placeholder('Pilih Gereja...'),
                    TextInput::make('nama_ibadah')
                        ->label('Nama Ibadah')
                        ->required()
                        ->placeholder('Masukkan Nama Ibadah...'),
                    Select::make('hari')
                        ->label('Hari')
                        ->options([
                            'minggu' => 'Minggu',
                            'senin' => 'Senin',
                            'selasa' => 'Selasa',
                            'rabu' => 'Rabu',
                            'kamis' => 'Kamis',
                            'jumat' => 'Jumat',
                            'sabtu' => 'Sabtu',
                        ])
                        ->required()
                        ->placeholder('Pilih Hari Ibadah'),
                    DatePicker::make('tanggal')
                        ->label('Tanggal')
                        ->date()
                        ->default(now()),
                    TimePicker::make('waktu')
                        ->label('Waktu')
                        ->time()
                        ->required()
                        ->default(waktu())
                        ->placeholder('Masukkan Waktu Ibadah...'),
                    TextInput::make('lokasi')
                        ->label('Lokasi')
                        ->required()
                        ->placeholder('Masukkan Lokasi Ibadah...'),
                    RichEditor::make('keterangan')
                        ->label('Keterangan')
                        ->columnSpanFull()
                        ->nullable()
                        ->placeholder('Masukkan Keterangan Tambahan...')
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('gereja.nama')
                    ->label('Nama Gereja')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nama_ibadah')
                    ->label('Nama Ibadah')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('hari')
                    ->label('Hari')
                    ->searchable(),
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->searchable()
                    ->formatStateUsing(fn($state) => tanggal($state)),
                TextColumn::make('waktu')
                    ->label('Waktu')
                    ->time()
                    ->searchable(),
                TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->searchable(),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(50)
                    ->html()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('created_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn($state) => $state ? tanggalWaktu($state): '-'),
                TextColumn::make('updated_at')
                    ->label('updated_at')
                    ->toggleable(isToggledHiddenByDefault: true)
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
            'index' => Pages\ListJadwalIbadahs::route('/'),
            'create' => Pages\CreateJadwalIbadah::route('/create'),
            'edit' => Pages\EditJadwalIbadah::route('/{record}/edit'),
        ];
    }
}
