<?php

namespace App\Filament\Pages;

use Filament\Navigation\NavigationItem;

class Dashboard extends \Filament\Pages\Dashboard
{
    // ...
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;
    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make('Dashboard')
            ->icon('heroicon-o-home')
            ->url(Dashboard::getUrl())
            ->visible(auth()->user()->role == "admin")
            ->isActiveWhen(fn (): bool => request()->url() == Dashboard::getUrl())
        ];
    }
    public function getColumns(): int | string | array
    {
        return 1;
    }
}
