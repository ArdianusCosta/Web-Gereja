<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Media;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\MediaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MediaResource\RelationManagers;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $pluralLabel = 'Media Sosial & Link';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Media')
                    ->tabs([
                        Tab::make('Gereja')
                            ->schema([
                                Placeholder::make('gereja_note')
                                    ->content("Di tab ini Anda dapat memilih Gereja mana yang akan Anda inputkan.\n\n📖 \"Sebab di mana dua atau tiga orang berkumpul dalam nama-Ku, di situ Aku ada di tengah-tengah mereka.\" (Matius 18:20)"),
                                Forms\Components\Select::make('gereja_id')
                                    ->label('Gereja')
                                    ->relationship('gereja', 'nama')
                                    ->preload(true)
                                    ->required()
                                    ->native(false)
                                    ->searchable()
                                    ->placeholder('Pilih Gereja...'),
                            ]),

                        Tab::make('Link YouTube')
                            ->schema([
                                Placeholder::make("youtube_note")
                                    ->content("Di tab ini Anda dapat memasukkan link YouTube Gereja Anda.\n\n📖 \"Pergilah ke seluruh dunia, beritakanlah Injil kepada segala makhluk.\" (Markus 16:15)"),
                                TextInput::make('link_youtube')
                                    ->label('Link YouTube')
                                    ->url()
                                    ->nullable()
                                    ->placeholder('Masukkan Link YouTube...'),
                            ]),

                        Tab::make('Link Instagram')
                            ->schema([
                                Placeholder::make("instagram_note")
                                    ->content("Di tab ini Anda dapat memasukkan link Instagram Gereja Anda.\n\n📖 \"Aku akan memberitakan nama-Mu kepada saudara-saudara-Ku, di tengah-tengah jemaat Aku akan memuji Engkau.\" (Ibrani 2:12)"),
                                TextInput::make('link_instagram')
                                    ->label('Link Instagram')
                                    ->url()
                                    ->nullable()
                                    ->placeholder('Masukkan Link Instagram...'),
                            ]),

                        Tab::make('Link Facebook')
                            ->schema([
                                Placeholder::make("facebook_note")
                                    ->content("Di tab ini Anda dapat memasukkan link Facebook Gereja Anda.\n\n📖 \"Sebab itu pergilah, jadikanlah semua bangsa murid-Ku, baptislah mereka dalam nama Bapa dan Anak dan Roh Kudus.\" (Matius 28:19)"),
                                TextInput::make('link_facebook')
                                    ->label('Link Facebook')
                                    ->url()
                                    ->nullable()
                                    ->placeholder('Masukkan Link Facebook...'),
                            ]),
                    ]) ->columnSpanFull()
                    ->contained(false),
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
                ImageColumn::make('link_youtube')
                    ->label('Link YouTube')
                    ->getStateUsing(fn($record) =>
                        ($id = getYoutubeId($record->link_youtube))
                            ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg"
                            : null
                    )
                    ->url(fn($record) => $record->link_youtube)
                    ->openUrlInNewTab()
                    ->size(120),                                                           
                TextColumn::make('link_instagram')
                    ->label('Link Instagram')
                    ->searchable(),
                TextColumn::make('link_facebook')   
                    ->label('Link Facebook')
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
            'index' => Pages\ListMedia::route('/'),
            'create' => Pages\CreateMedia::route('/create'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
        ];
    }
}
