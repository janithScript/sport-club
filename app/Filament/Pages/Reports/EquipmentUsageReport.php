<?php

namespace App\Filament\Pages\Reports;

use App\Models\EquipmentReservation;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EquipmentUsageReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationGroup = 'Reports';

    protected static string $view = 'filament.pages.reports.equipment-usage-report';

    protected static ?string $title = 'Equipment Usage Report';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return 'Equipment Usage Reports';
    }

    protected function getTableQuery(): Builder
    {
        return EquipmentReservation::query()
            ->with(['equipment', 'user'])
            ->whereIn('status', ['borrowed', 'returned'])
            ->orderBy('created_at', 'desc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('equipment.name')
                ->searchable()
                ->sortable()
                ->label('Equipment'),
            Tables\Columns\TextColumn::make('user.name')
                ->searchable()
                ->sortable()
                ->label('Member'),
            Tables\Columns\TextColumn::make('quantity')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('reserved_from')
                ->dateTime()
                ->sortable()
                ->label('Reservation Start'),
            Tables\Columns\TextColumn::make('reserved_to')
                ->dateTime()
                ->sortable()
                ->label('Reservation End'),
            Tables\Columns\TextColumn::make('borrowed_at')
                ->dateTime()
                ->sortable()
                ->label('Borrowed Date'),
            Tables\Columns\TextColumn::make('returned_at')
                ->dateTime()
                ->sortable()
                ->label('Returned Date'),
            Tables\Columns\TextColumn::make('status')
                ->searchable()
                ->sortable(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
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
                    'borrowed' => 'Borrowed',
                    'returned' => 'Returned',
                ]),
            Tables\Filters\Filter::make('date_range')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('reserved_from'),
                    \Filament\Forms\Components\DatePicker::make('reserved_until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when($data['reserved_from'], fn (Builder $query, $date): Builder => $query->whereDate('reserved_from', '>=', $date))
                        ->when($data['reserved_until'], fn (Builder $query, $date): Builder => $query->whereDate('reserved_to', '<=', $date));
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
        $reservations = EquipmentReservation::query()
            ->with(['equipment', 'user'])
            ->whereIn('status', ['borrowed', 'returned'])
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $reservations->map(function ($reservation) {
            return [
                'Equipment' => $reservation->equipment->name ?? 'N/A',
                'Member' => $reservation->user->name ?? 'N/A',
                'Quantity' => $reservation->quantity ?? 0,
                'Reservation Start' => $reservation->reserved_from ? $reservation->reserved_from->format('Y-m-d H:i:s') : '',
                'Reservation End' => $reservation->reserved_to ? $reservation->reserved_to->format('Y-m-d H:i:s') : '',
                'Borrowed Date' => $reservation->borrowed_at ? $reservation->borrowed_at->format('Y-m-d H:i:s') : '',
                'Returned Date' => $reservation->returned_at ? $reservation->returned_at->format('Y-m-d H:i:s') : '',
                'Status' => $reservation->status ?? 'N/A',
            ];
        });

        // Create a temporary CSV file
        $filename = 'equipment-usage-report-' . now()->format('Y-m-d') . '.csv';
        $filepath = storage_path('app/' . $filename);
        
        $file = fopen($filepath, 'w');
        fputcsv($file, ['Equipment', 'Member', 'Quantity', 'Reservation Start', 'Reservation End', 'Borrowed Date', 'Returned Date', 'Status']);
        
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        
        fclose($file);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }
}