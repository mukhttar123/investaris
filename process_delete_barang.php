<?php
session_start();
include 'config.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if (isset($_GET['nama_barang'])) {
    $nama_barang = $_GET['nama_barang'];

    // Siapkan dan jalankan query untuk menghapus barang
    $stmt = $conn->prepare("DELETE FROM barang WHERE nama_barang = ?");
    $stmt->bind_param("s", $nama_barang);

    if ($stmt->execute()) {
        // Redirect kembali ke halaman admin setelah berhasil menghapus
        header('Location: admin.php?message=Barang berhasil dihapus');
    } else {
        // Redirect kembali dengan pesan error
        header('Location: admin.php?error=Gagal menghapus barang');
    }

    $stmt->close();
}
$conn->close();
?>