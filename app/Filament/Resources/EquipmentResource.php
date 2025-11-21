<?php

namespace App\Filament\Resources;

use App\Models\Equipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Equipment Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('category')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_quantity')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                Forms\Components\TextInput::make('available_quantity')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                Forms\Components\Select::make('condition')
                    ->required()
                    ->options([
                        'new' => 'New',
                        'good' => 'Good',
                        'fair' => 'Fair',
                        'poor' => 'Poor',
                    ]),
                Forms\Components\TextInput::make('asset_tag')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('available_quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('condition')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asset_tag')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('condition')
                    ->options([
                        'new' => 'New',
                        'good' => 'Good',
                        'fair' => 'Fair',
                        'poor' => 'Poor',
                    ]),
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
            'index' => \App\Filament\Resources\EquipmentResource\Pages\ListEquipment::route('/'),
            'create' => \App\Filament\Resources\EquipmentResource\Pages\CreateEquipment::route('/create'),
            'edit' => \App\Filament\Resources\EquipmentResource\Pages\EditEquipment::route('/{record}/edit'),
        ];
    }
}