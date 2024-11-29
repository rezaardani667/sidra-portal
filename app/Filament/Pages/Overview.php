<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Overview extends Page
{
    protected static ?int $navigationSort =  1;

    protected static ?string $navigationGroup = 'Gateway Manager';
    protected static ?string $navigationIcon = 'heroicon-o-eye';
    protected static ?string $Heading = 'Overview';

    protected static string $view = 'filament.pages.overview';
}
