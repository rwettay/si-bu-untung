<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Barang;

class UpdateRecommendedProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update-recommended 
                            {--min-sold=20 : Minimum sold_count untuk menjadi recommended}
                            {--min-stock=5 : Minimum stok yang harus ada}
                            {--new-days=7 : Produk baru dalam X hari terakhir}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status is_recommended untuk barang berdasarkan kriteria otomatis (sold_count, stok, dan produk baru)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Memulai update status recommended products...');
        
        $minSold = (int) $this->option('min-sold');
        $minStock = (int) $this->option('min-stock');
        $newDays = (int) $this->option('new-days');

        $this->line("📊 Parameter:");
        $this->line("   - Minimum sold_count: {$minSold}");
        $this->line("   - Minimum stok: {$minStock}");
        $this->line("   - Produk baru (hari): {$newDays}");
        $this->newLine();

        try {
            $result = Barang::updateRecommendedStatus($minSold, $minStock, $newDays);

            $this->info('✅ Update selesai!');
            $this->newLine();
            $this->table(
                ['Metrik', 'Nilai'],
                [
                    ['Barang yang di-update', $result['updated']],
                    ['Total Recommended', $result['recommended']],
                    ['Total Unrecommended', $result['unrecommended']],
                    ['Threshold sold_count', $result['threshold_sold_count']],
                ]
            );

            $this->newLine();
            $this->info('💡 Status recommended telah diupdate berdasarkan:');
            $this->line('   1. Top performer (sold_count tinggi) dengan stok mencukupi');
            $this->line('   2. Produk baru (dibuat dalam ' . $newDays . ' hari terakhir) dengan stok mencukupi');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

