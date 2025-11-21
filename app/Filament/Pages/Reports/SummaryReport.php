<?php

namespace App\Filament\Pages\Reports;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Equipment;
use App\Models\EquipmentReservation;
use App\Models\User;
use Filament\Pages\Page;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SummaryReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationGroup = 'Reports';

    protected static string $view = 'filament.pages.reports.summary-report';

    protected static ?string $title = 'Summary Report';

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return 'Summary Reports';
    }

    // Method to get statistics for the summary report
    public function getStatistics(): array
    {
        return [
            'total_events' => Event::count(),
            'upcoming_events' => Event::where('start_at', '>', now())->count(),
            'total_equipment' => Equipment::count(),
            'total_members' => User::where('is_admin', false)->count(),
            'total_registrations' => EventRegistration::count(),
            'confirmed_registrations' => EventRegistration::where('status', 'confirmed')->count(),
            'active_reservations' => EquipmentReservation::whereIn('status', ['reserved', 'borrowed'])->count(),
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
        // Get statistics data
        $stats = $this->getStatistics();
        
        // Create summary data
        $data = [
            ['Metric', 'Value'],
            ['Total Events', $stats['total_events']],
            ['Upcoming Events', $stats['upcoming_events']],
            ['Total Equipment', $stats['total_equipment']],
            ['Total Members', $stats['total_members']],
            ['Total Registrations', $stats['total_registrations']],
            ['Confirmed Registrations', $stats['confirmed_registrations']],
            ['Active Reservations', $stats['active_reservations']],
        ];

        // Create a temporary CSV file
        $filename = 'summary-report-' . now()->format('Y-m-d') . '.csv';
        $filepath = storage_path('app/' . $filename);
        
        $file = fopen($filepath, 'w');
        
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        
        fclose($file);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }
}