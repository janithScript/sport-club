<?php

namespace App\Filament\Pages\Reports;

use App\Models\EventRegistration;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AttendanceReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Reports';

    protected static string $view = 'filament.pages.reports.attendance-report';

    protected static ?string $title = 'Attendance Report';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'Attendance Reports';
    }

    protected function getTableQuery(): Builder
    {
        return EventRegistration::query()
            ->with(['event', 'user'])
            ->where('status', 'confirmed')
            ->orderBy('registered_at', 'desc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('event.title')
                ->searchable()
                ->sortable()
                ->label('Event'),
            Tables\Columns\TextColumn::make('user.name')
                ->searchable()
                ->sortable()
                ->label('Member'),
            Tables\Columns\TextColumn::make('registered_at')
                ->dateTime()
                ->sortable()
                ->label('Registration Date'),
            Tables\Columns\TextColumn::make('event.start_at')
                ->dateTime()
                ->sortable()
                ->label('Event Date'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('event')
                ->relationship('event', 'title')
                ->searchable()
                ->preload(),
            Tables\Filters\SelectFilter::make('user')
                ->relationship('user', 'name')
                ->searchable()
                ->preload(),
            Tables\Filters\Filter::make('date_range')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('registered_from'),
                    \Filament\Forms\Components\DatePicker::make('registered_until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when($data['registered_from'], fn (Builder $query, $date): Builder => $query->whereDate('registered_at', '>=', $date))
                        ->when($data['registered_until'], fn (Builder $query, $date): Builder => $query->whereDate('registered_at', '<=', $date));
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
        $registrations = EventRegistration::query()
            ->with(['event', 'user'])
            ->where('status', 'confirmed')
            ->orderBy('registered_at', 'desc')
            ->get();

        $data = $registrations->map(function ($registration) {
            return [
                'Event' => $registration->event->title ?? 'N/A',
                'Member' => $registration->user->name ?? 'N/A',
                'Registration Date' => $registration->registered_at ? $registration->registered_at->format('Y-m-d H:i:s') : '',
                'Event Date' => $registration->event->start_at ? $registration->event->start_at->format('Y-m-d H:i:s') : '',
            ];
        });

        // Create a temporary CSV file
        $filename = 'attendance-report-' . now()->format('Y-m-d') . '.csv';
        $filepath = storage_path('app/' . $filename);
        
        $file = fopen($filepath, 'w');
        fputcsv($file, ['Event', 'Member', 'Registration Date', 'Event Date']);
        
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        
        fclose($file);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }
}