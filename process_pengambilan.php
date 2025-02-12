<?php
session_start();
include 'config.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Periksa apakah data telah dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_barang = trim($_POST['nama_barang']);
    $jumlah_ambil = intval($_POST['jumlah']); // Pastikan tipe data integer

    // Validasi input
    if (empty($nama_barang) || $jumlah_ambil <= 0) {
        header('Location: admin.php?error=Nama barang dan jumlah tidak boleh kosong.');
        exit;
    }

    // Cek apakah barang ada di database
    $query = "SELECT id, stok FROM barang WHERE nama_barang = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nama_barang);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header('Location: admin.php?error=Barang tidak ditemukan.');
        exit;
    }

    $row = $result->fetch_assoc();
    $barang_id = $row['id'];
    $stok_sekarang = $row['stok'];

    // Cek apakah stok cukup
    if ($stok_sekarang < $jumlah_ambil) {
        header('Location: admin.php?error=Stok tidak cukup untuk pengambilan.');
        exit;
    }

    // Kurangi stok barang
    $new_stok = $stok_sekarang - $jumlah_ambil;
    $update_query = "UPDATE barang SET stok = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ii", $new_stok, $barang_id);
    $update_stmt->execute();

    // Simpan data pengambilan ke tabel pengambilan
    $tgl_ambil = date('Y-m-d H:i:s');
    $insert_query = "INSERT INTO pengambilan (tgl_ambil, jumlah_ambil, id_barang) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("sii", $tgl_ambil, $jumlah_ambil, $barang_id);
    $insert_stmt->execute();

    // Menambahkan riwayat ke tabel history_barang
    $query_history = "INSERT INTO history_barang (id_barang, stok, satuan, status) 
                      SELECT id, ?, satuan, 'keluar' FROM barang WHERE id = ?";
    $stmt_history = $conn->prepare($query_history);
    $stmt_history->bind_param("ii", $jumlah_ambil, $barang_id);
    $stmt_history->execute();

    // Redirect atau tampilkan pesan sukses
    $_SESSION['message'] = "Pengambilan barang berhasil.";
    header('Location: admin.php');
    exit;
} else {
    header('Location: admin.php?error=Akses tidak valid.');
}
?>