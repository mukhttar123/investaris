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
        header('Location: admin.php?error=Nama barang atau jumlah stok tidak valid.');
        exit;
    }

    // Update stok di database
    $stmt = $conn->prepare("UPDATE barang SET stok = stok + ? WHERE nama_barang = ?");
    $stmt->bind_param("is", $stok_tambah, $nama_barang);

    if ($stmt->execute()) {
        // Menambahkan riwayat ke tabel history_barang
        $query_history = "INSERT INTO history_barang (id_barang, stok, satuan, status) 
                          SELECT id, ?, satuan, 'masuk' FROM barang WHERE nama_barang = ?";
        $stmt_history = $conn->prepare($query_history);
        $stmt_history->bind_param("is", $stok_tambah, $nama_barang);
        
        if ($stmt_history->execute()) {
            // Redirect dengan pesan sukses
            header('Location: admin.php?message=Stok berhasil diperbarui');
        } else {
            // Jika terjadi kesalahan saat menambahkan riwayat
            header('Location: admin.php?error=Terjadi kesalahan saat menambahkan riwayat: ' . $stmt_history->error);
        }
        
        $stmt_history->close();
    } else {
        // Jika terjadi kesalahan saat memperbarui stok
        header('Location: admin.php?error=Terjadi kesalahan saat memperbarui stok: ' . $stmt->error);
    }

    $stmt->close();
} else {
    header('Location: admin.php?error=Akses tidak valid.');
}
?>