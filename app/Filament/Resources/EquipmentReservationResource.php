<?php

namespace App\Filament\Resources;

use App\Models\EquipmentReservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EquipmentReservationResource extends Resource
{
    protected static ?string $model = EquipmentReservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Equipment Management';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('equipment_id')
                    ->relationship('equipment', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Forms\Components\DateTimePicker::make('reserved_from')
                    ->required(),
                Forms\Components\DateTimePicker::make('reserved_to')
                    ->required(),
                Forms\Components\DateTimePicker::make('borrowed_at'),
                Forms\Components\DateTimePicker::make('due_at'),
                Forms\Components\DateTimePicker::make('returned_at'),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'reserved' => 'Reserved',
                        'borrowed' => 'Borrowed',
                        'returned' => 'Returned',
                        'cancelled' => 'Cancelled',
                    ]),
                Forms\Components\Textarea::make('admin_note')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('equipment.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reserved_from')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reserved_to')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('equipment')
                    ->relationship('equipment', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'reserved' => 'Reserved',
                        'borrowed' => 'Borrowed',
                        'returned' => 'Returned',
                        'cancelled' => 'Cancelled',
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
            'index' => \App\Filament\Resources\EquipmentReservationResource\Pages\ListEquipmentReservations::route('/'),
            'create' => \App\Filament\Resources\EquipmentReservationResource\Pages\CreateEquipmentReservation::route('/create'),
            'edit' => \App\Filament\Resources\EquipmentReservationResource\Pages\EditEquipmentReservation::route('/{record}/edit'),
        ];
    }
}