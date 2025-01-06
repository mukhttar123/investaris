<?php
session_start();
include 'config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_barang = $_POST['judul_buku'];
    $stok = $_POST['stok'];
    $satuan = $_POST['satuan'];

    // Validasi input
    if ( empty($nama_barnag) || empty($stok) || empty($satuan)) {
        $error = "Semua field harus diisi.";
    } else {
        // Insert data ke tabel buku
        $stmt = $conn->prepare("INSERT INTO barang ( nama_barang, stok, satuan, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssis", $nama_barang, $stok, $satuan);

        if ($stmt->execute()) {
            $success = "Barang berhasil ditambahkan.";
        } else {
            $error = "Terjadi kesalahan: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Barang Baru</title>
</head>
<body>
    <h1>Tambah Barang Baru</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Judul Barang:</label><br>
        <input type="text" name="nama_barang" required><br><br>

        <label>Stok:</label><br>
        <input type="number" name="stok" required><br><br>

        <label>Satuan:</label><br>
        <select name="satuan" required>
            <option value="Pcs">Pcs</option>
            <option value="Lusin">Lusin</option>
            <option value="Rim">Rim</option>
        </select><br><br>

        <button type="submit">Simpan</button>
    </form>

    <br>
    <a href="daftar_buku.php">Lihat Daftar Barang</a> |
    <a href="admin.php">Kembali ke Admin</a> |
    <a href="logout.php">Logout</a>
</body>
</html>
