<?php
session_start();
include 'config.php';

// Pastikan hanya admin yang dapat mengakses
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Ambil data pengambilan dari database
$query = " SELECT barang.nama_barang, pengambilan.tgl_ambil,pengambilan.jumlah_ambil,barang.status
            FROM pengambilan
            JOIN barang ON barang.id
            ORDER BY pengambilan.tgl_ambil DESC;

";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan pengambilan</title>
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
    <h1>Laporan pengambilan Barang</h1>

    <table>
        <thead>
            <tr>
                <th>Judul Barang</th>
                <th>Tanggal Ambil</th>
                <th>Jumlah Diambil</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                        <td><?= htmlspecialchars($row['tgl_ambil']) ?></td>
                        <td><?= htmlspecialchars($row['jumlah_ambil']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">Tidak ada data laporan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="admin.php">Kembali ke Halaman Admin</a>
</body>
</html>
