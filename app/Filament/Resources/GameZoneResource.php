<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameZoneResource\Pages;
use App\Models\GameZone;
use Filament\Forms;
use Filament\Forms\{Form};
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\{Group, Section, TextInput, DatePicker, FileUpload, Select, Textarea};

class GameZoneResource extends Resource
{
    protected static ?string $model = GameZone::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Group::make()->schema([
                    Section::make()->schema([
                        Forms\Components\TextInput::make('gameName')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('gameWebLink')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('rewardCoins')
                            ->required()
                            ->numeric()
                            ->integer(),
                            Group::make()->schema([
                        Forms\Components\Select::make('category')
                            ->required()
                            ->options([
                                'Featured' => 'Featured',
                                'Puzzle' => 'Puzzle',
                                'Arcade' => 'Arcade',
                                'Simulation' => 'Simulation',
                            ]),
                        Forms\Components\Select::make('visibility')
                            ->required()
                            ->options([
                                'hidden' => 'Hidden',
                                'show' => 'Show',
                            ])
                            ])->columns(2)
                    ])
                ]),
                Group::make()->schema([
                    Section::make('Thumbnail Preview')->schema([
                        Forms\Components\FileUpload::make('thumbnail')
                            ->image()
                            ->directory('games/thumbnail')
                            ->nullable(),
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gameId'),
                Tables\Columns\ImageColumn::make('thumbnail'),

                Tables\Columns\TextColumn::make('gameName'),
                // Tables\Columns\TextColumn::make('gameWebLink'),

                Tables\Columns\TextColumn::make('rewardCoins')->suffix(' MST'),
                Tables\Columns\TextColumn::make('category'),
                Tables\Columns\BadgeColumn::make('visibility')
                    ->colors([
                        'danger' => 'hidden',
                        'success' => 'show',
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListGameZones::route('/'),
            'create' => Pages\CreateGameZone::route('/create'),
            'edit' => Pages\EditGameZone::route('/{record}/edit'),
        ];
    }
}
