<?php

namespace App\Filament\Resources\DetailTransaksiResource\Pages;

use App\Filament\Resources\DetailTransaksiResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDetailTransaksi extends EditRecord
{
    protected static string $resource = DetailTransaksiResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
