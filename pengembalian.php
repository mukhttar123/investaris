<?php
session_start();
include 'config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_peminjaman = $_POST['kode_peminjaman'];

    // Periksa apakah kode peminjaman ada
    $stmt = $conn->prepare("SELECT * FROM peminjaman WHERE id = ?");
    $stmt->bind_param("i", $kode_peminjaman);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $peminjaman = $result->fetch_assoc();

        if ($peminjaman['status'] === 'Dikembalikan') {
            echo "<p>Buku ini sudah dikembalikan sebelumnya.</p>";
        } else {
            // Update status peminjaman menjadi 'Dikembalikan'
            $stmt = $conn->prepare("UPDATE peminjaman SET status = 'Dikembalikan' WHERE id = ?");
            $stmt->bind_param("i", $kode_peminjaman);

            if ($stmt->execute()) {
                // Tambahkan stok buku
                $stmt = $conn->prepare("
                    UPDATE buku 
                    SET stok = stok + ? 
                    WHERE kode_barang = ?
                ");
                $stmt->bind_param("is", $peminjaman['jumlah_dipinjam'], $peminjaman['kode_barang']);

                if ($stmt->execute()) {
                    echo "<p>Pengembalian berhasil dicatat dan stok buku diperbarui.</p>";
                } else {
                    echo "<p>Terjadi kesalahan saat memperbarui stok buku: " . $stmt->error . "</p>";
                }
            } else {
                echo "<p>Terjadi kesalahan saat memperbarui status peminjaman: " . $stmt->error . "</p>";
            }
        }
    } else {
        echo "<p>Kode peminjaman tidak ditemukan.</p>";
    }
}
?>

<h1>Form Pengembalian Buku</h1>
<form method="POST">
    <label>Kode Peminjaman:</label>
    <input type="number" name="kode_peminjaman" required><br>
    <button type="submit">Catat Pengembalian</button>
</form>
<a href='admin.php'>Kembali ke Admin</a>
