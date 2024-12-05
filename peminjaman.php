<?php
session_start();
include 'config.php';

// Pastikan hanya admin yang dapat mengakses
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Ambil daftar buku dari database
$bukuResult = $conn->query("SELECT kode_barang, nama_barang, stok FROM barang WHERE stok > 0");

// Proses jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_peminjaman = $_POST['kode_peminjaman'];
    $kode_buku = $_POST['kode_buku'];
    $nama_peminjam = $_POST['nama_peminjam'];
    $alamat_peminjam = $_POST['alamat_peminjam'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $tgl_kembali = $_POST['tgl_kembali'];
    $jumlah_dipinjam = $_POST['jumlah_dipinjam'];

    // Periksa stok buku
    $stmt = $conn->prepare("SELECT stok FROM barang WHERE kode_barang = ?");
    $stmt->bind_param("s", $kode_buku);
    $stmt->execute();
    $result = $stmt->get_result();
    $buku = $result->fetch_assoc();

    if ($buku && $buku['stok'] >= $jumlah_dipinjam) {
        // Kurangi stok buku
        $new_stok = $buku['stok'] - $jumlah_dipinjam;
        $updateStmt = $conn->prepare("UPDATE barang SET stok = ? WHERE kode_barang = ?");
        $updateStmt->bind_param("is", $new_stok, $kode_buku);
        $updateStmt->execute();

        // Masukkan data peminjaman
        $insertStmt = $conn->prepare("INSERT INTO peminjaman (kode_peminjaman, kode_barang, nama_peminjam, alamat_peminjam, tgl_pinjam, tgl_kembali, jumlah_dipinjam) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insertStmt->bind_param("ssssssi", $kode_peminjaman, $kode_buku, $nama_peminjam, $alamat_peminjam, $tgl_pinjam, $tgl_kembali, $jumlah_dipinjam);

        if ($insertStmt->execute()) {
            echo "<p>Transaksi peminjaman berhasil dicatat!</p>";
        } else {
            echo "<p>Terjadi kesalahan: " . $insertStmt->error . "</p>";
        }
    } else {
        echo "<p>Stok buku tidak mencukupi!</p>";
    }
}
?>

<h1>Form Peminjaman Buku</h1>
<form method="POST">
    <label>Kode Peminjaman:</label>
    <input type="text" name="kode_peminjaman" required><br>

    <label>Pilih Buku:</label>
    <select name="kode_buku" required>
        <option value="">-- Pilih Barang --</option>
        <?php while ($row = $bukuResult->fetch_assoc()): ?>
            <option value="<?= $row['kode_barang'] ?>"><?= $row['nama_barang'] ?> (Stok: <?= $row['stok'] ?>)</option>
        <?php endwhile; ?>
    </select><br>

    <label>Nama Peminjam:</label>
    <input type="text" name="nama_peminjam" required><br>
    <label>Alamat Peminjam:</label>
    <input type="text" name="alamat_peminjam" required><br>

    <label>Tanggal Pinjam:</label>
    <input type="date" name="tgl_pinjam" required><br>
    <label>Tanggal Kembali:</label>
    <input type="date" name="tgl_kembali" required><br>

    <label>Jumlah Dipinjam:</label>
    <input type="number" name="jumlah_dipinjam" required><br>

    <button type="submit">Catat Peminjaman</button>
</form>

<a href='admin.php'>Kembali ke Halaman Admin</a>

<?php
include 'config.php';

echo "<h1>Daftar Buku</h1>";

// Ambil data buku dari database
$sql = "SELECT * FROM barang";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>No</th>
                <th>Kode Buku</th>
                <th>Judul Buku</th>
                <th>Jumlah</th>
            </tr>";
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $no++ . "</td>
                <td>" . $row['kode_barang'] . "</td>
                <td>" . $row['nama_barang'] . "</td>
                <td>" . $row['stok'] . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Belum ada buku yang tersedia.</p>";
}

echo "<br><a href='admin.php'>Kembali ke Beranda</a>";
?>

