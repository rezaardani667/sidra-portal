<?php

namespace App\Filament\Widgets;

use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TableRoutes extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Route::query()
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->weight(FontWeight::Bold),
                TextColumn::make('gatewayService.protocol')
                    ->label('Protocols')
                    ->badge()
                    ->color('gray')
                    ->separator(','),
                TextColumn::make('gatewayService.host')
                    ->label('Host')
                    ->badge()
                    ->color('gray')
                    ->separator(','),
                TextColumn::make('methods')
                    ->label('Methods')
                    ->badge()
                    ->separator(','),
                TextColumn::make('paths')
                    ->label('Paths')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('expression')
                    ->label('Expression'),
                TextColumn::make('tags')
                    ->label('Tags')
                    ->badge()
                    ->separator(','),
                TextColumn::make('updated_at')
                    ->label('Last Modified')
                    ->dateTime('M d, Y, h:i A')
            ]);
    }
}
