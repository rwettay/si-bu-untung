<?php

namespace App\Filament\Pages;

use App\Models\Transaksi;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Builder;

class LaporanPenjualan extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Laporan Penjualan';
    protected static ?string $navigationIcon  = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.pages.simple-table-shell';

    public function table(Table $table): Table
    {
        return $table
            ->query(Transaksi::query()->with('pelanggan'))
            ->columns([
                Tables\Columns\TextColumn::make('id_transaksi')
                    ->label('ID')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_transaksi')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pelanggan.nama_pelanggan')
                    ->label('Pelanggan')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status_transaksi')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'dibayar',
                        'info'    => 'dikirim',
                    ]),

                Tables\Columns\TextColumn::make('total_transaksi')
                    ->label('Total')
                    ->money('idr', true)
                    ->sortable()
                    ->summarize(
                        Sum::make()->money('idr', true)  // ← ini yang benar
                    ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_transaksi')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'dibayar' => 'Dibayar',
                        'dikirim' => 'Dikirim',
                    ]),

                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari'),
                        Forms\Components\DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('tanggal_transaksi', '>=', $d))
                            ->when($data['until'] ?? null, fn ($q, $d) => $q->whereDate('tanggal_transaksi', '<=', $d));
                    }),
            ])
            ->defaultSort('tanggal_transaksi', 'desc')
            ->paginated([10, 25, 50]);
    }
}
