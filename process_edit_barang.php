<?php
session_start();
include 'config.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id_barang = $_POST['id_barang'];
    $nama_barang = $_POST['nama_barang'];
    $stok = $_POST['stok'];
    $satuan = $_POST['satuan'];

    // Validasi input
    if (empty($id_barang) || empty($nama_barang) || empty($stok) || empty($satuan)) {
        header('Location: admin.php?error=Semua field harus diisi');
        exit;
    }

    // Pastikan stok adalah angka
    if (!is_numeric($stok) || $stok < 0) {
        header('Location: admin.php?error=Stok harus berupa angka positif');
        exit;
    }

    // Siapkan dan jalankan query untuk memperbarui data barang
    $stmt = $conn->prepare("UPDATE barang SET nama_barang=?, stok=?, satuan=? WHERE id=?");
    $stmt->bind_param("sisi", $nama_barang, $stok, $satuan, $id_barang); // Perbaiki tipe parameter

    if ($stmt->execute()) {
        // Jika berhasil, redirect dengan pesan sukses
        header('Location: admin.php?message=Barang berhasil diperbarui');
    } else {
        // Jika gagal, redirect dengan pesan error
        header('Location: admin.php?error=Gagal memperbarui barang');
    }

    $stmt->close();
} else {
    // Jika bukan POST, redirect ke halaman admin
    header('Location: admin.php');
}
?>