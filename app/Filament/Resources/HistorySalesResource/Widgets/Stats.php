<?php
namespace App\Filament\Resources\HistorySalesResource\Widgets;

use App\Models\HistorySales;
use App\Models\Production;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\Select;
use DateTime;
use DateInterval;
use DatePeriod;

class Stats extends ChartWidget
{

    public function __construct()
    {
        $this->filter = now()->month;  // Assign nilai di konstruktor
    }
    protected static bool $isLazy = true;
    protected static ?string $heading = "Perbandingan Produksi Dan Penjualan";
    public ?string $filter;

    protected function getFilters(): ?array
    {
        return array_combine(range(1, 12), ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Okt', 'Nov', 'Des']);
    }

    protected function getData(): array
    {
        $year = now()->year;
        $month = $this->filter;

        $start = new DateTime("$year-$month-01");
        $start->setTime(0, 0, 0);

        $interval = new DateInterval('P1W');
        $recurrences = 5;  // Maksimal 5 minggu untuk 1 bulan
        $weeks = [];
        foreach (new DatePeriod($start, $interval, $recurrences) as $date) {
            $weeks[] = $date->format('W');
        }

        $historyTotals = $this->getHistorySales($weeks, $year, $month);
        $productions = $this->getProductions($weeks, $year, $month);

        return [
            'datasets' => [
                [
                    'label' => 'History Penjualan (/Kg)',
                    'data' => $historyTotals,
                ],
                [
                    'label' => 'Produksi (/Kg)',
                    'data' => $productions,
                ],
            ],
            'labels' => $weeks,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getHistorySales($weeks, $year, $month)
    {
        $historyTotals = [];
        foreach ($weeks as $i => $week) {
            $totalSales = HistorySales::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereRaw('WEEK(created_at) = ' . $week)
                ->where('status', 'accepted')
                ->sum('accepted_weight');
            $historyTotals[] = $totalSales;
        }
        return $historyTotals;
    }

    public function getProductions($weeks, $year, $month)
    {
        $productions = [];
        foreach ($weeks as $i => $week) {
            $totalProductions = Production::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereRaw('WEEK(created_at) = ' . $week)
                ->sum('raw_weight');
            $productions[] = $totalProductions;
        }
        return $productions;
    }
}

