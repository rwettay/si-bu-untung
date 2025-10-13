<?php

namespace App\Providers;

use App\Filament\Pages\LaporanBarang;
use App\Filament\Pages\LaporanPenjualan;
use App\Filament\Resources\BarangResource;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerNavigationItems([
                NavigationGroup::make('Kelola Barang')->items([
                    NavigationItem::make('Tambah Barang')
                        ->icon('heroicon-o-plus-circle')
                        ->url(BarangResource::getUrl('create')),
                    NavigationItem::make('Edit Barang')
                        ->icon('heroicon-o-pencil-square')
                        ->url(BarangResource::getUrl()),
                    NavigationItem::make('Hapus Barang')
                        ->icon('heroicon-o-trash')
                        ->url(BarangResource::getUrl()), // hapus dari tabel index
                ]),

                NavigationItem::make('Laporan Barang')
                    ->icon('heroicon-o-squares-2x2')
                    ->url(LaporanBarang::getUrl()),

                NavigationItem::make('Laporan Penjualan')
                    ->icon('heroicon-o-chart-bar')
                    ->url(LaporanPenjualan::getUrl()),
            ]);
        });
    }
}
