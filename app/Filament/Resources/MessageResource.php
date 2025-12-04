<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Models\Message;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Communication';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sender_id')
                    ->label('Sender')
                    ->relationship('sender', 'name')
                    ->required(),
                Forms\Components\Select::make('receiver_id')
                    ->label('Receiver')
                    ->relationship('receiver', 'name')
                    ->required(),
                Forms\Components\TextInput::make('subject')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('read_at')
                    ->label('Read At'),
                Forms\Components\DateTimePicker::make('created_at')
                    ->label('Sent At')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sender.name')
                    ->label('From')
                    ->sortable(),
                Tables\Columns\TextColumn::make('receiver.name')
                    ->label('To')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('body')
                    ->searchable()
                    ->limit(100)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('isRead')
                    ->label('Read')
                    ->boolean()
                    ->getStateUsing(fn (Message $record): bool => $record->isRead()),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Sent At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('unread')
                    ->query(fn (Builder $query): Builder => $query->whereNull('read_at')),
                Tables\Filters\SelectFilter::make('sender')
                    ->relationship('sender', 'name'),
                Tables\Filters\SelectFilter::make('receiver')
                    ->relationship('receiver', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('reply')
                    ->label('Reply')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->form([
                        Forms\Components\Hidden::make('receiver_id')
                            ->default(fn ($record) => $record->sender_id),
                        Forms\Components\TextInput::make('subject')
                            ->default(fn ($record) => $record->subject ? 'Re: ' . $record->subject : '')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('body')
                            ->required()
                            ->placeholder('Write your reply here...')
                            ->autosize(),
                    ])
                    ->action(function (Message $record, array $data): void {
                        Message::create([
                            'sender_id' => auth()->id(),
                            'receiver_id' => $data['receiver_id'],
                            'subject' => $data['subject'],
                            'body' => $data['body'],
                        ]);
                    })
                    ->visible(fn (): bool => auth()->user()->is_admin),
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
            'index' => Pages\ListMessages::route('/'),
            'create' => Pages\CreateMessage::route('/create'),
            'view' => Pages\ViewMessage::route('/{record}'),
            'edit' => Pages\EditMessage::route('/{record}/edit'),
        ];
    }
}