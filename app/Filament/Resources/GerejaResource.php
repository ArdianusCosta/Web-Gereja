<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Gereja;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\GerejaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GerejaResource\RelationManagers;

class GerejaResource extends Resource
{
    protected static ?string $model = Gereja::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $pluralLabel = 'Daftar Gereja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    TextInput::make('nama')
                        ->label('Nama Gereja')
                        ->required()
                        ->placeholder('Masukkan Nama Gereja...'),
                    TextInput::make('telepon')
                        ->label('No Telepon Gereja')
                        ->tel()
                        ->numeric()
                        ->nullable()
                        ->placeholder('Masukkan Nomor Telp Gereja...'),
                    FileUpload::make('foto_gereja')
                        ->label('Foto Gereja')
                        ->image()
                        ->disk('public')
                        ->directory('gereja')
                        ->visibility('public')
                        ->nullable()
                        ->columnSpanFull(),
                    FileUpload::make('gambar_qris')
                        ->label('QRIS Gereja')
                        ->image()
                        ->disk('public')
                        ->columnSpanFull()
                        ->directory('qris-gereja')
                        ->visibility('public')
                        ->nullable(),
                    RichEditor::make('alamat')
                        ->label('Alamat Gereja')
                        ->nullable()
                        ->placeholder('Masukkan Alamat Gereja...')
                        ->columnSpanFull(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Gereja')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('foto_gereja')
                    ->label('Foto Gereja')
                    ->disk('public'),
                TextColumn::make('telepon')
                    ->label('No Telepon Gereja')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('gambar_qris')
                    ->label('QRIS Gereja')
                    ->disk('public')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('alamat')
                    ->label('Alamat Gereja')
                    ->limit(50)
                    ->html(),
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
            'index' => Pages\ListGerejas::route('/'),
            'create' => Pages\CreateGereja::route('/create'),
            'edit' => Pages\EditGereja::route('/{record}/edit'),
        ];
    }
}
