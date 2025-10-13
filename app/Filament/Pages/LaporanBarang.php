<?php

namespace App\Filament\Pages;

use App\Models\Barang;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;

class LaporanBarang extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Laporan Barang';
    protected static ?string $navigationIcon  = 'heroicon-o-squares-2x2';
    protected static string $view = 'filament.pages.simple-table-shell';

    public function table(Table $table): Table
    {
        $today = Carbon::today();

        return $table
            ->query(Barang::query())
            ->columns([
                Tables\Columns\TextColumn::make('id_barang')->label('Id Barang')->searchable(),
                Tables\Columns\TextColumn::make('nama_barang')->label('Nama Barang')->searchable(),
                Tables\Columns\TextColumn::make('tanggal_kedaluwarsa')->date('d/m/Y')->label('Tanggal Kadaluwarsa'),
                Tables\Columns\TextColumn::make('stok_barang')->label('Stok Barang')->sortable(),

                Tables\Columns\BadgeColumn::make('status')->label('Status')
                    ->getStateUsing(function (Barang $b) use ($today) {
                        $exp = $b->tanggal_kedaluwarsa ? Carbon::parse($b->tanggal_kedaluwarsa) : null;
                        if ($exp && $exp->lt($today)) return 'Kadaluwarsa';
                        if ($exp && $exp->between($today, $today->copy()->addDays(30))) return 'Hampir Kadaluarsa';
                        if (!is_null($b->stok_barang) && $b->stok_barang <= 10) return 'Hampir Habis';
                        return 'Aman';
                    })
                    ->colors([
                        'danger'  => 'Kadaluwarsa',
                        'warning' => 'Hampir Kadaluarsa',
                        'info'    => 'Hampir Habis',
                        'success' => 'Aman',
                    ]),
            ])
            ->paginated([10,25,50])
            ->defaultPaginationPageOption(10);
    }
}
