<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Models\Barang;
use Filament\Forms;
use Filament\Resources\Form;      // ✅ v2: Resources\Form
use Filament\Resources\Resource;
use Filament\Resources\Table;     // ✅ v2: Resources\Table
use Filament\Tables;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    /** Sembunyikan dari sidebar default; kita akan pakai menu kustom */
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    /** FORM (Create/Edit) — v2 pakai Resources\Form */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('id_barang')
                ->label('ID Barang')
                ->required()
                ->maxLength(20)
                // v2: unique('nama_tabel','kolom')
                ->unique('barang', 'id_barang')
                // jangan bisa diedit kalau record sudah ada
                ->disabled(fn (?Barang $record) => filled($record)),

            Forms\Components\TextInput::make('nama_barang')
                ->label('Nama Barang')
                ->required()
                ->columnSpanFull(),

            Forms\Components\TextInput::make('stok_barang')
                ->label('Stok')
                ->numeric()
                ->minValue(0)
                ->required(),

            Forms\Components\TextInput::make('harga_satuan')
                ->label('Harga Satuan')
                ->numeric()
                ->prefix('Rp')
                ->required(),

            Forms\Components\DatePicker::make('tanggal_kedaluwarsa')
                ->label('Tanggal Kedaluwarsa'),

            Forms\Components\TextInput::make('gambar')
                ->label('URL Gambar')
                ->columnSpanFull(),
        ])->columns(2);
    }

    /** TABEL (List) — v2 pakai Resources\Table */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_barang')
                    ->label('ID')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_barang')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_kedaluwarsa')
                    ->label('Kedaluwarsa')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('stok_barang')
                    ->label('Stok')
                    ->sortable(),

                Tables\Columns\TextColumn::make('harga_satuan')
                    ->label('Harga')
                    ->money('idr', true)   // cocok di v2.17 (paket money sudah terpasang)
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit'   => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
