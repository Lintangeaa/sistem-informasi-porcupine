<?php

namespace App\Filament\Resources\HistorySalesResource\Widgets;

use App\Models\HistorySales;
use App\Models\Production;
use Filament\Widgets\ChartWidget;
use DateTime;
use DateInterval;
use DatePeriod;

class Stats extends ChartWidget
{

    protected function getData(): array
    {
        $year = now()->year;

        $start = new DateTime("$year-01-01");
        $start->setTime(0, 0, 0);  // Normalize time to midnight

        $interval = new DateInterval('P1M');
        $recurrences = 11;
        $months = [];
        foreach (new DatePeriod($start, $interval, $recurrences) as $date) {
            $months[] = substr($date->format('F'), 0, 3);
        }

        $historyTotals = $this->getHistorySales($months, $year);
        $productions = $this->getProductions($months, $year);
        return  [
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
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getHistorySales($months, $year)
    {
        $historyTotals = [];
        foreach ($months as $i => $month) {
            $totalSales = HistorySales::whereYear('created_at', $year)
                ->whereMonth('created_at', $i + 1)
                ->sum('accepted_weight');
            $historyTotals[] = $totalSales;
        }
        return $historyTotals;
    }

    public function getProductions($months, $year)
    {
        $productions = [];
        foreach ($months as $i => $month) {
            $totalProductions = Production::whereYear('created_at', $year)
                ->whereMonth('created_at', $i + 1)
                ->sum('weight');
            $productions[] = $totalProductions;
        }
        return $productions;
    }
}
