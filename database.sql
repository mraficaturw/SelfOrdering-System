CREATE DATABASE IF NOT EXISTS ayam_geprek_db;
USE ayam_geprek_db;

CREATE TABLE pelanggan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    waktu_pesan TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pelanggan_id INT NOT NULL,
    bagian_ayam VARCHAR(50),
    jenis_bumbu VARCHAR(50) NOT NULL,
    pakai_nasi BOOLEAN DEFAULT 1,
    digeprek BOOLEAN DEFAULT 1,
    FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id) ON DELETE CASCADE
);

CREATE TABLE history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pelanggan_nama VARCHAR(100) NOT NULL,
    waktu_pesan TIMESTAMP,
    waktu_selesai TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel baru untuk analisis sambal
CREATE TABLE analisis_sambal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jenis_bumbu VARCHAR(50) NOT NULL,
    jumlah_pesanan INT DEFAULT 0,
    terakhir_dipesan TIMESTAMP
);