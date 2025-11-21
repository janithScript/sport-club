<?php

namespace App\Filament\Pages\Reports;

use App\Models\Event;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EventParticipationReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Reports';

    protected static string $view = 'filament.pages.reports.event-participation-report';

    protected static ?string $title = 'Event Participation Report';

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return 'Event Participation Reports';
    }

    protected function getTableQuery(): Builder
    {
        // Create a custom query to show event participation statistics
        return Event::query()
            ->withCount(['registrations as total_registrations', 'confirmedRegistrations as confirmed_registrations'])
            ->with(['creator'])
            ->orderBy('start_at', 'desc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('creator.name')
                ->label('Organizer')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('start_at')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('total_registrations')
                ->label('Total Registrations')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('confirmed_registrations')
                ->label('Confirmed Attendees')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('capacity')
                ->label('Capacity')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('available_spots')
                ->label('Available Spots')
                ->state(function (Event $record): ?int {
                    return $record->available_spots;
                })
                ->numeric()
                ->sortable(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('creator')
                ->relationship('creator', 'name')
                ->searchable()
                ->preload(),
            Tables\Filters\Filter::make('date_range')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('event_from'),
                    \Filament\Forms\Components\DatePicker::make('event_until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when($data['event_from'], fn (Builder $query, $date): Builder => $query->whereDate('start_at', '>=', $date))
                        ->when($data['event_until'], fn (Builder $query, $date): Builder => $query->whereDate('start_at', '<=', $date));
                }),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            // No actions needed for reports
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            // No bulk actions needed for reports
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('export_excel')
                ->label('Export to Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    return $this->exportToExcel();
                }),
        ];
    }

    public function exportToExcel(): BinaryFileResponse
    {
        $events = Event::query()
            ->withCount(['registrations as total_registrations', 'confirmedRegistrations as confirmed_registrations'])
            ->with(['creator'])
            ->orderBy('start_at', 'desc')
            ->get();

        $data = $events->map(function ($event) {
            return [
                'Event' => $event->title,
                'Organizer' => $event->creator->name ?? 'N/A',
                'Start Date' => $event->start_at ? $event->start_at->format('Y-m-d H:i:s') : '',
                'Total Registrations' => $event->total_registrations ?? 0,
                'Confirmed Attendees' => $event->confirmed_registrations ?? 0,
                'Capacity' => $event->capacity ?? 0,
                'Available Spots' => $event->available_spots ?? 0,
            ];
        });

        // Create a temporary CSV file
        $filename = 'event-participation-report-' . now()->format('Y-m-d') . '.csv';
        $filepath = storage_path('app/' . $filename);
        
        $file = fopen($filepath, 'w');
        fputcsv($file, ['Event', 'Organizer', 'Start Date', 'Total Registrations', 'Confirmed Attendees', 'Capacity', 'Available Spots']);
        
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        
        fclose($file);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }
}