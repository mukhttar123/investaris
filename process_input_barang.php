<?php
session_start();
include 'config.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_barang = $_POST['nama_barang'];
    $stok = $_POST['stok'];
    $satuan = $_POST['satuan'];

    // Menambahkan barang ke tabel barang dengan status 'masuk'
    $query_barang = "INSERT INTO barang (nama_barang, stok, satuan, status) VALUES (?, ?, ?, 'masuk')";
    $stmt_barang = $conn->prepare($query_barang);
    $stmt_barang->bind_param("sis", $nama_barang, $stok, $satuan);
    $stmt_barang->execute();

    // Mendapatkan ID barang yang baru ditambahkan
    $id_barang = $stmt_barang->insert_id;

    // Menambahkan riwayat ke tabel history_barang
    $query_history = "INSERT INTO history_barang (id_barang, stok, satuan, status) VALUES (?, ?, ?, 'masuk')";
    $stmt_history = $conn->prepare($query_history);
    $stmt_history->bind_param("iis", $id_barang, $stok, $satuan);
    $stmt_history->execute();

    // Redirect atau tampilkan pesan sukses
    header('Location: admin.php?message=Barang berhasil ditambahkan');
    exit;
}
?>