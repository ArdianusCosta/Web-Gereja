<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\WartaJemaat;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\WartaJemaatResource\Pages;
use App\Filament\Resources\WartaJemaatResource\RelationManagers;
use Filament\Tables\Columns\ColumnGroup;

class WartaJemaatResource extends Resource
{
    protected static ?string $model = WartaJemaat::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected static ?string $pluralLabel = 'Berita / Warta Jemaat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(false)
                    ->schema([
                        TextInput::make('judul_warta')
                            ->label('Judul Warta')
                            ->placeholder('Masukan judul warta...')
                            ->required(),
                        DatePicker::make('tanggal_warta')
                            ->label('Tanggal Warta')
                            ->default(now()),
                        FileUpload::make('lampiran_pdf')
                            ->label('Lampiran Pdf / Foto Terkait')
                            ->columnSpanFull()
                            ->disk('public')
                            ->directory('pdf-warta'),
                        Textarea::make('isi_warta')
                            ->label('Isi Warta')
                            ->columnSpanFull()
                            ->rows(5)
                            ->placeholder('Masukan isi warta...')
                            ->required()
                    ])->columns(2),
                Section::make(false)
                        ->schema([
                            Repeater::make('kontent')
                                ->schema([
                                    TextInput::make('sub_judul')
                                        ->label('Sub Judul')
                                        ->placeholder('Masukan sub judul...'),
                                ])
                        ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul_warta')
                    ->label('Judul Warta')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_warta')
                    ->label('Tanggal Warta')
                    ->searchable()
                    ->date()
                    ->formatStateUsing(fn($state) => tanggal($state)),
                IconColumn::make('lampiran_pdf')
                    ->label('Lampiran PDF')
                    ->boolean() 
                    ->trueIcon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => $record->lampiran_pdf ? asset('storage/' . $record->lampiran_pdf) : null)
                    ->openUrlInNewTab()
                    ->tooltip('Unduh / Lihat PDF'),
                ColumnGroup::make('kontent', [
                    TextColumn::make('sub_judul')
                        ->label('Sub Judul')
                        ->limit(50)
                        ->html()
                        ->toggleable()
                        ->searchable()
                        ->getStateUsing(function ($record) {
                            return collect($record->kontent ?? [])->pluck('sub_judul')->join(', ');
                        }),
                ]),
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
            'index' => Pages\ListWartaJemaats::route('/'),
            'create' => Pages\CreateWartaJemaat::route('/create'),
            'edit' => Pages\EditWartaJemaat::route('/{record}/edit'),
        ];
    }
}
