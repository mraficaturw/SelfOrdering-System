<?php
session_start();
require_once 'AyamGeprekBuilder.php';
require_once 'OrderManager.php';

$orderManager = new OrderManager();
$notification = '';

// Proses form pemesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_order'])) {
    $jumlah_pesanan = (int)$_POST['jumlah_pesanan'];
    $nama_pemesan = htmlspecialchars($_POST['nama_pemesan']);
    $pesanan = [];

    for ($i = 0; $i < $jumlah_pesanan; $i++) {
        $builder = new AyamGeprekBuilder();
        
        $pakai_nasi = isset($_POST['pakai_nasi'][$i]) && $_POST['pakai_nasi'][$i] == '1';
        $digeprek = isset($_POST['digeprek'][$i]) && $_POST['digeprek'][$i] == '1';

        $ayamGeprek = $builder
            ->setBagianAyam($_POST['bagian_ayam'][$i] ?? '')
            ->setPakaiNasi($pakai_nasi)
            ->setJenisBumbu($_POST['jenis_bumbu'][$i] ?? '')
            ->setDigeprek($digeprek)
            ->build();

        $pesanan[] = $ayamGeprek;
    }

    $pelanggan_id = $orderManager->tambahPesanan($pesanan, $nama_pemesan);
    
    if ($pelanggan_id) {
        $notification = "Pesanan $nama_pemesan berhasil dikirim ke dapur!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Ayam Geprek</title>
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
            <h1><i class="fas fa-utensils"></i> AYAM GEPREK UMKM</h1>
        </div>
    </header>

    <main class="container">
        <section id="order" class="section">
            <h2 class="section-title"><i class="fas fa-pencil-alt"></i> Pemesanan Ayam Geprek</h2>
            <form method="POST" id="order-form">
                <div class="form-group">
                    <label for="nama_pemesan"><i class="fas fa-user"></i> Nama Pemesan:</label>
                    <input type="text" id="nama_pemesan" name="nama_pemesan" placeholder="Masukkan nama Anda" required>
                </div>

                <div class="form-group">
                    <label for="jumlah_pesanan"><i class="fas fa-list-ol"></i> Jumlah Pesanan:</label>
                    <input type="number" id="jumlah_pesanan" name="jumlah_pesanan" min="1" value="1" required>
                </div>

                <div id="items-container">
                    <!-- Item forms will be generated here -->
                </div>

                <button type="submit" name="submit_order" class="btn">
                    <i class="fas fa-paper-plane"></i> Kirim Pesanan ke Dapur
                </button>
            </form>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 UMKM Ayam Geprek. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.getElementById('jumlah_pesanan').addEventListener('input', function() {
            const jumlah = parseInt(this.value) || 0;
            const container = document.getElementById('items-container');
            container.innerHTML = '';
            
            for (let i = 0; i < jumlah; i++) {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'item-container';
                itemDiv.innerHTML = `
                    <h3><i class="fas fa-hamburger"></i> Pesanan ${i + 1}</h3>
                    </br>
                    <div class="form-group">
                        <label for="bagian_ayam_${i}"></r><i class="fas fa-drumstick-bite"></i> Bagian Ayam:</label>
                        <select name="bagian_ayam[]" id="bagian_ayam_${i}" required>
                            <option value="">Pilih Bagian</option>
                            <option value="Dada">Dada</option>
                            <option value="Paha Atas">Paha Atas</option>
                            <option value="Paha Bawah">Paha Bawah</option>
                            <option value="Sayap">Sayap</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="jenis_bumbu_${i}"><i class="fas fa-mortar-pestle"></i> Jenis Bumbu:</label>
                        <select name="jenis_bumbu[]" id="jenis_bumbu_${i}" required>
                            <option value="">Pilih Bumbu</option>
                            <option value="Sambal Geprek">Sambal Geprek</option>
                            <option value="Sambal Matah">Sambal Matah</option>
                            <option value="Sambal Hijau">Sambal Hijau</option>
                            <option value="Black Paper">Black Paper</option>
                            <option value="Barbeque">Barbeque</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="display: flex; gap: 20px;">
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="pakai_nasi[]" value="1" checked>
                            <i class="fas fa-rice"></i> Pakai Nasi
                        </label>
                        
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="digeprek[]" value="1" checked>
                            <i class="fas fa-hammer"></i> Digeprek
                        </label>
                    </div>
                `;
                container.appendChild(itemDiv);
            }
        });

        // Show notification
        <?php if ($notification): ?>
            const notification = document.getElementById('notification');
            if (notification) {
                notification.classList.add('show');
                
                setTimeout(() => {
                    notification.classList.remove('show');
                }, 5000);
            }
        <?php endif; ?>

        window.onload = function() {
            document.getElementById('jumlah_pesanan').dispatchEvent(new Event('input'));
        };
    </script>
</body>
</html>