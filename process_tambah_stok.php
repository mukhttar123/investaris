<?php
session_start();
include 'config.php';

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Periksa apakah data telah dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_barang = trim($_POST['nama_barang']);
    $stok_tambah = intval($_POST['stok_tambah']); // Pastikan tipe data integer

    // Validasi input
    if (empty($nama_barang) || $stok_tambah <= 0) {
        die('Nama barang atau jumlah stok tidak valid.');
    }

    // Update stok di database
    $stmt = $conn->prepare("UPDATE barang SET stok = stok + ? WHERE nama_barang = ?");
    $stmt->bind_param("is", $stok_tambah, $nama_barang);

    if ($stmt->execute()) {
        header('Location: admin.php?success=stok_updated');
    } else {
        // Gunakan mysqli_error() untuk debug jika terjadi kesalahan
        echo 'Terjadi kesalahan saat memperbarui stok: ' . $conn->error;
    }

    $stmt->close();
} else {
    echo 'Akses tidak valid.';
}
?>
