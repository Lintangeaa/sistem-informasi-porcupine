<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    // ...
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;
    public function getColumns(): int | string | array
    {
        return 1;
    }

}
