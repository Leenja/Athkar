<?php

namespace App\Filament\Resources\DhikrResource\Pages;

use App\Filament\Resources\DhikrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDhikrs extends ListRecords
{
    protected static string $resource = DhikrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
