<?php
session_start();
require_once 'OrderManager.php';

$orderManager = new OrderManager();
$notification = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_order'])) {
    $pelanggan_id = (int)$_POST['pelanggan_id'];
    if ($orderManager->selesaikanPesanan($pelanggan_id)) {
        $notification = "Pesanan berhasil diselesaikan!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_history'])) {
    if ($orderManager->clearHistory()) {
        $notification = "History berhasil dihapus!";
    }
}

$pesanan_aktif = $orderManager->getPesananAktif();
$history = $orderManager->getHistory();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dapur - Ayam Geprek UMKM</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php if ($notification): ?>
        <div class="notification" id="notification">
            <i class="fas fa-check-circle"></i> <?= $notification ?>
        </div>
    <?php endif; ?>

    <header>
        <div class="container">
            <h1><i class="fas fa-utensils"></i> DAPUR AYAM GEPREK UMKM</h1>
            <nav>
                <a href="dapur.php"><i class="fas fa-list"></i> Pesanan Aktif</a>
                <a href="analisis.php"><i class="fas fa-chart-bar"></i> Analisis Sambal</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="section">
            <h2 class="section-title"><i class="fas fa-utensils"></i> Pesanan Aktif</h2>
            <div class="dapur-container">
                <?php if (empty($pesanan_aktif)): ?>
                    <div class="dapur-item" style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                        <h3 style="color: var(--primary);">Tidak Ada Pesanan</h3>
                        <p>Belum ada pesanan yang masuk</p>
                        <i class="fas fa-utensils" style="font-size: 5rem; color: var(--secondary); margin: 30px 0;"></i>
                    </div>
                <?php else: ?>
                    <?php foreach ($pesanan_aktif as $pesanan): ?>
                        <?php $detailPesanan = $orderManager->getDetailPesanan($pesanan['id']); ?>
                        <div class="dapur-item">
                            <h3 style="color: var(--primary);">Pesanan: <?= $pesanan['nama'] ?></h3>
                            <p><i class="fas fa-clock"></i> <?= date('d M Y H:i', strtotime($pesanan['waktu_pesan'])) ?></p>
                            <p><i class="fas fa-box"></i> Jumlah Item: <?= $pesanan['jumlah_item'] ?></p>
                            
                            <div style="margin-top: 20px; background: rgba(215, 237, 226, 0.3); border-radius: 12px; padding: 15px;">
                                <h4 style="margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid var(--secondary);">
                                    <i class="fas fa-list"></i> Detail Pesanan:
                                </h4>
                                <?php foreach ($detailPesanan as $index => $item): ?>
                                    <div style="margin-bottom: 18px; padding: 15px; background: white; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                        <p><strong>Item <?= $index + 1 ?></strong></p>
                                        <p><i class="fas fa-drumstick-bite"></i> Bagian: <?= $item['bagian_ayam'] ?></p>
                                        <p><i class="fas fa-mortar-pestle"></i> Bumbu: <?= $item['jenis_bumbu'] ?></p>
                                        <p><i class="fas fa-rice"></i> Nasi: <?= $item['pakai_nasi'] ? 'Ya' : 'Tidak' ?></p>
                                        <p><i class="fas fa-hammer"></i> Digeprek: <?= $item['digeprek'] ? 'Ya' : 'Tidak' ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <form method="POST" style="margin-top: 25px; text-align: center;">
                                <input type="hidden" name="pelanggan_id" value="<?= $pesanan['id'] ?>">
                                <button type="submit" name="complete_order" class="btn">
                                    <i class="fas fa-check"></i> Tandai Selesai
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h2 class="section-title"><i class="fas fa-history"></i> History Pesanan</h2>
                <form method="POST">
                    <button type="submit" name="clear_history" class="btn btn-secondary">
                        <i class="fas fa-trash"></i> Hapus History
                    </button>
                </form>
            </div>
            
            <div id="history-list">
                <?php if (empty($history)): ?>
                    <div class="order-item" style="text-align: center; padding: 30px;">
                        <i class="fas fa-history" style="font-size: 3rem; color: var(--secondary); margin-bottom: 20px;"></i>
                        <p>Belum ada history pesanan</p>
                    </div>
                <?php else: ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                        <?php foreach ($history as $item): ?>
                            <div class="order-item">
                                <div class="order-info">
                                    <h3><?= $item['pelanggan_nama'] ?></h3>
                                    <p><i class="fas fa-clock"></i> Dipesan: <?= date('d M Y H:i', strtotime($item['waktu_pesan'])) ?></p>
                                    <p><i class="fas fa-check-circle"></i> Selesai: <?= date('d M Y H:i', strtotime($item['waktu_selesai'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 UMKM Ayam Geprek. All rights reserved.</p>
            <p>Akses Dapur</p>
        </div>
    </footer>

    <script>
        <?php if ($notification): ?>
            const notification = document.getElementById('notification');
            if (notification) {
                notification.classList.add('show');
                
                setTimeout(() => {
                    notification.classList.remove('show');
                }, 5000);
            }
        <?php endif; ?>
    </script>
</body>
</html>