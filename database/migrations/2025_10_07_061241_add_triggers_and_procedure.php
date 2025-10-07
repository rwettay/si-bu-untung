<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
  public function up(): void {
    // BEFORE INSERT detail_transaksi -> hitung subtotal dari harga_satuan barang
    DB::unprepared("
      CREATE TRIGGER trg_before_insert_detail
      BEFORE INSERT ON detail_transaksi FOR EACH ROW
      BEGIN
        DECLARE harga DECIMAL(10,2);
        SELECT harga_satuan INTO harga FROM barang WHERE id_barang = NEW.id_barang;
        SET NEW.subtotal = NEW.jumlah_pesanan * harga;
      END
    ");

    // BEFORE UPDATE detail_transaksi
    DB::unprepared("
      CREATE TRIGGER trg_before_update_detail
      BEFORE UPDATE ON detail_transaksi FOR EACH ROW
      BEGIN
        DECLARE harga DECIMAL(10,2);
        SELECT harga_satuan INTO harga FROM barang WHERE id_barang = NEW.id_barang;
        SET NEW.subtotal = NEW.jumlah_pesanan * harga;
      END
    ");

    // AFTER INSERT/UPDATE/DELETE -> update total_transaksi di transaksi
    DB::unprepared("
      CREATE TRIGGER trg_after_insert_detail
      AFTER INSERT ON detail_transaksi FOR EACH ROW
      BEGIN
        UPDATE transaksi
        SET total_transaksi = (SELECT COALESCE(SUM(subtotal),0) FROM detail_transaksi WHERE id_transaksi = NEW.id_transaksi)
        WHERE id_transaksi = NEW.id_transaksi;
      END
    ");
    DB::unprepared("
      CREATE TRIGGER trg_after_update_detail
      AFTER UPDATE ON detail_transaksi FOR EACH ROW
      BEGIN
        UPDATE transaksi
        SET total_transaksi = (SELECT COALESCE(SUM(subtotal),0) FROM detail_transaksi WHERE id_transaksi = NEW.id_transaksi)
        WHERE id_transaksi = NEW.id_transaksi;
      END
    ");
    DB::unprepared("
      CREATE TRIGGER trg_after_delete_detail
      AFTER DELETE ON detail_transaksi FOR EACH ROW
      BEGIN
        UPDATE transaksi
        SET total_transaksi = (SELECT COALESCE(SUM(subtotal),0) FROM detail_transaksi WHERE id_transaksi = OLD.id_transaksi)
        WHERE id_transaksi = OLD.id_transaksi;
      END
    ");

    // Stored Procedure: sp_invoice_terakhir_pelanggan(IN p_id_pelanggan VARCHAR(20))
    DB::unprepared("
      DROP PROCEDURE IF EXISTS sp_invoice_terakhir_pelanggan;
    ");
    DB::unprepared("
      CREATE PROCEDURE sp_invoice_terakhir_pelanggan (IN p_id_pelanggan VARCHAR(20))
      BEGIN
        DECLARE v_id_transaksi VARCHAR(20);

        SELECT t.id_transaksi INTO v_id_transaksi
        FROM transaksi t
        WHERE t.id_pelanggan = p_id_pelanggan
        ORDER BY t.tanggal_transaksi DESC, t.id_transaksi DESC
        LIMIT 1;

        IF v_id_transaksi IS NULL THEN
          SELECT 'NOT_FOUND' AS status, 'Belum ada transaksi' AS message;
        ELSE
          SELECT
            t.id_transaksi,
            t.tanggal_transaksi,
            t.status_transaksi,
            p.id_pelanggan,
            p.nama_pelanggan,
            p.alamat,
            p.no_hp,
            t.total_transaksi AS grand_total
          FROM transaksi t
          JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan
          WHERE t.id_transaksi = v_id_transaksi;

          SELECT
            b.id_barang,
            b.nama_barang,
            dt.jumlah_pesanan,
            (dt.subtotal / NULLIF(dt.jumlah_pesanan,0)) AS harga_satuan,
            dt.subtotal
          FROM detail_transaksi dt
          JOIN barang b ON b.id_barang = dt.id_barang
          WHERE dt.id_transaksi = v_id_transaksi
          ORDER BY b.nama_barang;
        END IF;
      END
    ");
  }

  public function down(): void {
    DB::unprepared("DROP TRIGGER IF EXISTS trg_before_insert_detail;");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_before_update_detail;");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_after_insert_detail;");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_after_update_detail;");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_after_delete_detail;");
    DB::unprepared("DROP PROCEDURE IF EXISTS sp_invoice_terakhir_pelanggan;");
  }
};
