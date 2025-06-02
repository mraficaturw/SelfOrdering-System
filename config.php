<?php
$host = 'localhost';
$db   = 'ayam_geprek_db'; // Nama database yang dibuat
$user = 'root';           // User default XAMPP
$pass = '';               // Password default XAMPP (kosong)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Tampilkan pesan error lebih ramah
    die("Koneksi database gagal: Pastikan MySQL berjalan dan konfigurasi benar. Error: " . $e->getMessage());
}
?>