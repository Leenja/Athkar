<?php

namespace App\Filament\Resources\DhikrResource\Pages;

use App\Filament\Resources\DhikrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDhikr extends EditRecord
{
    protected static string $resource = DhikrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
