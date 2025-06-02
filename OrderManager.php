<?php
require_once 'config.php';

class OrderManager {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function tambahPesanan($pesanan, $nama_pemesan) {
        try {
            $this->pdo->beginTransaction();
            
            // Tambahkan pelanggan
            $stmt = $this->pdo->prepare("INSERT INTO pelanggan (nama) VALUES (?)");
            $stmt->execute([$nama_pemesan]);
            $pelanggan_id = $this->pdo->lastInsertId();
            
            // Tambahkan pesanan
            $stmt = $this->pdo->prepare("INSERT INTO pesanan (pelanggan_id, bagian_ayam, jenis_bumbu, pakai_nasi, digeprek) 
                                        VALUES (?, ?, ?, ?, ?)");
            
            foreach ($pesanan as $item) {
                $stmt->execute([
                    $pelanggan_id,
                    $item->getBagianAyam(),
                    $item->getJenisBumbu(),
                    $item->getPakaiNasi() == 'Ya' ? 1 : 0,
                    $item->getDigeprek() == 'Ya' ? 1 : 0
                ]);
                
                // Update analisis sambal
                $this->updateAnalisisSambal($item->getJenisBumbu());
            }
            
            $this->pdo->commit();
            return $pelanggan_id;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function updateAnalisisSambal($jenis_bumbu) {
        // Cek apakah sambal sudah ada
        $stmt = $this->pdo->prepare("SELECT * FROM analisis_sambal WHERE jenis_bumbu = ?");
        $stmt->execute([$jenis_bumbu]);
        $sambal = $stmt->fetch();
        
        if ($sambal) {
            // Update existing
            $stmt = $this->pdo->prepare("UPDATE analisis_sambal 
                                        SET jumlah_pesanan = jumlah_pesanan + 1, 
                                            terakhir_dipesan = NOW() 
                                        WHERE jenis_bumbu = ?");
            $stmt->execute([$jenis_bumbu]);
        } else {
            // Insert new
            $stmt = $this->pdo->prepare("INSERT INTO analisis_sambal (jenis_bumbu, jumlah_pesanan, terakhir_dipesan) 
                                        VALUES (?, 1, NOW())");
            $stmt->execute([$jenis_bumbu]);
        }
    }

    public function selesaikanPesanan($pelanggan_id) {
        try {
            $stmt = $this->pdo->prepare("SELECT nama, waktu_pesan 
                                        FROM pelanggan 
                                        WHERE id = ?");
            $stmt->execute([$pelanggan_id]);
            $pelanggan = $stmt->fetch();
            
            if ($pelanggan) {
                // Pindahkan ke history
                $stmt = $this->pdo->prepare("INSERT INTO history (pelanggan_nama, waktu_pesan) 
                                            VALUES (?, ?)");
                $stmt->execute([
                    $pelanggan['nama'],
                    $pelanggan['waktu_pesan']
                ]);
                
                // Hapus pesanan dan pelanggan
                $stmt = $this->pdo->prepare("DELETE FROM pelanggan WHERE id = ?");
                $stmt->execute([$pelanggan_id]);
                
                return true;
            }
            return false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getPesananAktif() {
        $stmt = $this->pdo->query("SELECT pelanggan.id, pelanggan.nama, pelanggan.waktu_pesan, 
                                  COUNT(pesanan.id) as jumlah_item
                                  FROM pelanggan
                                  JOIN pesanan ON pelanggan.id = pesanan.pelanggan_id
                                  GROUP BY pelanggan.id
                                  ORDER BY pelanggan.waktu_pesan ASC");
        return $stmt->fetchAll();
    }

    public function getDetailPesanan($pelanggan_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM pesanan WHERE pelanggan_id = ?");
        $stmt->execute([$pelanggan_id]);
        return $stmt->fetchAll();
    }

    public function getHistory() {
        $stmt = $this->pdo->query("SELECT * FROM history ORDER BY waktu_selesai DESC");
        return $stmt->fetchAll();
    }
    
    public function clearHistory() {
        $this->pdo->exec("DELETE FROM history");
        return true;
    }
    
    public function getAnalisisSambal() {
        $stmt = $this->pdo->query("SELECT * FROM analisis_sambal ORDER BY jumlah_pesanan DESC");
        return $stmt->fetchAll();
    }
}