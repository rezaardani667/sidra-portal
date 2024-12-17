<?php

namespace App\Filament\Widgets;

use App\Models\Route;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class RoutesChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $data = collect(range(0, 59))->map(function ($minute) {
            $time = now()->setTimezone('Asia/Jakarta')->subMinutes(59 - $minute)->format('H.i');
            
            return (object) [
            'date' => $time,
            'http200' => rand(0, 30), // Simulated HTTP 200 responses
            'http401' => rand(0, 20), // Simulated HTTP 401 responses
            'http404' => rand(0, 20),  // Simulated HTTP 404 responses
            'http500' => rand(0, 20),  // Simulated HTTP 500 responses
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'HTTP 200',
                    'data' => $data->pluck('http200'),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                ],
                [
                    'label' => 'HTTP 401',
                    'data' => $data->pluck('http401'),
                    'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
                    'borderColor' => 'rgba(255, 206, 86, 1)',
                ],
                [
                    'label' => 'HTTP 404',
                    'data' => $data->pluck('http404'),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ],
                [
                    'label' => 'HTTP 500',
                    'data' => $data->pluck('http500'),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                ],
            ],
            'labels' => $data->pluck('date'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
