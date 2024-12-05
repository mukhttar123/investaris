<?php
session_start();
include 'config.php';

// Pastikan hanya admin yang dapat mengakses
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Ambil data peminjaman dari database
$query = " SELECT peminjaman.kode_peminjaman, peminjaman.nama_peminjam, peminjaman.alamat_peminjam, barang.nama_barang, peminjaman.tgl_pinjam, peminjaman.tgl_kembali, peminjaman.jumlah_dipinjam, peminjaman.status
            FROM peminjaman
            JOIN barang ON peminjaman.kode_barang = barang.kode_barang
            ORDER BY peminjaman.tgl_pinjam DESC;

";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Laporan Peminjaman Barang</h1>

    <table>
        <thead>
            <tr>
                <th>Kode Peminjaman</th>
                <th>Nama Peminjam</th>
                <th>Alamat</th>
                <th>Judul Barangy</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Jumlah Dipinjam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['kode_peminjaman']) ?></td>
                        <td><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                        <td><?= htmlspecialchars($row['alamat_peminjam']) ?></td>
                        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                        <td><?= htmlspecialchars($row['tgl_pinjam']) ?></td>
                        <td><?= htmlspecialchars($row['tgl_kembali']) ?></td>
                        <td><?= htmlspecialchars($row['jumlah_dipinjam']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">Tidak ada data peminjaman.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="admin.php">Kembali ke Halaman Admin</a>
</body>
</html>
