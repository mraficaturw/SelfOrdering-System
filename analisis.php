<?php
session_start();
require_once 'OrderManager.php';

$orderManager = new OrderManager();
$sambalData = $orderManager->getAnalisisSambal();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis - Ayam Geprek UMKM</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <div class="container">
            <h1><i class="fas fa-chart-bar"></i> ANALISIS SAMBAL</h1>
            <nav>
                <a href="dapur.php"><i class="fas fa-home"></i> Dapur</a>
                <a href="analisis.php"><i class="fas fa-chart-bar"></i> Analisis</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="section">
            <h2 class="section-title"><i class="fas fa-chart-pie"></i> Statistik Sambal Paling Laku</h2>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div>
                    <canvas id="sambalChart"></canvas>
                </div>
                <div>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: var(--primary); color: white;">
                                <th style="padding: 12px; text-align: left;">Jenis Sambal</th>
                                <th style="padding: 12px; text-align: center;">Jumlah Pesanan</th>
                                <th style="padding: 12px; text-align: center;">Terakhir Dipesan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sambalData as $index => $sambal): ?>
                                <tr style="border-bottom: 1px solid #eee; <?= $index % 2 == 0 ? 'background: #f9f9f9;' : '' ?>">
                                    <td style="padding: 12px;"><?= $sambal['jenis_bumbu'] ?></td>
                                    <td style="padding: 12px; text-align: center; font-weight: bold;"><?= $sambal['jumlah_pesanan'] ?></td>
                                    <td style="padding: 12px; text-align: center;"><?= date('d M Y H:i', strtotime($sambal['terakhir_dipesan'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 UMKM Ayam Geprek. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Data for chart
        const sambalData = {
            labels: [<?= implode(',', array_map(function($s) { return "'" . $s['jenis_bumbu'] . "'"; }, $sambalData)) ?>],
            datasets: [{
                label: 'Jumlah Pesanan',
                data: [<?= implode(',', array_column($sambalData, 'jumlah_pesanan')) ?>],
                backgroundColor: [
                    '#FF9A8B', '#FAD6A5', '#BAD7FF', '#DEEDFF', '#A5D6A7',
                    '#FFCC80', '#90CAF9', '#F48FB1', '#80CBC4', '#FFAB91'
                ],
                borderWidth: 1
            }]
        };

        // Create chart
        window.onload = function() {
            const ctx = document.getElementById('sambalChart').getContext('2d');
            const sambalChart = new Chart(ctx, {
                type: 'pie',
                data: sambalData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        title: {
                            display: true,
                            text: 'Distribusi Sambal Paling Laku'
                        }
                    }
                }
            });
        };
    </script>
</body>
</html>