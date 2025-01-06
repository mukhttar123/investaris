<?php
session_start();
include 'config.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang - PerBarangan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-body {
            background-color: rgba(13, 13, 13, 1);
        }
        .text-white {
            color: white;
        }
    </style>
</head>
<body class="bg-body text-white">
    <div class="min-h-screen p-8">
        <header class="mb-8">
            <h1 class="text-4xl font-bold">Laporan Barang</h1>
        </header>

        <h2 class="text-3xl mb-4">Barang Masuk</h2>
        <table class="table-auto w-full bg-gray-800 rounded-lg overflow-hidden mb-8">
            <thead>
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Nama Barang</th>
                    <th class="px-4 py-2">Stok</th>
                    <th class="px-4 py-2">Satuan</th>
                    <th class="px-4 py-2">Waktu Ditambahkan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query_masuk = "SELECT * FROM barang WHERE status='masuk' ORDER BY created_at DESC";                
                    $result_masuk = $conn->query($query_masuk);
                    if ($result_masuk->num_rows > 0) {
                        $no = 1;
                        while ($row = $result_masuk->fetch_assoc()) {
                            echo "<tr class='odd:bg-gray-700 even:bg-gray-600'>";
                            echo "<td class='px-4 py-2'>" . $no++ . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['nama_barang']) . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['stok']) . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['satuan']) . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['created_at']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-4'>Tidak ada data barang masuk.</td></tr>";
                    }
                ?>
            </tbody>
        </table>

        <h2 class="text-3xl mb-4">Barang Keluar</h2>
        <table class="table-auto w-full bg-gray-800 rounded-lg overflow-hidden">
            <thead>
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Nama Barang</th>
                    <th class="px-4 py-2">Jumlah Diambil</th>
                    <th class="px-4 py-2">Tanggal Ambil</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query_keluar = "SELECT p.jumlah_ambil, p.tgl_ambil, b.nama_barang FROM pengambilan p JOIN barang b ON p.id_barang = b.id ORDER BY p.tgl_ambil DESC";               
                    $result_keluar = $conn->query($query_keluar);
                    if ($result_keluar->num_rows > 0) {
                        $no = 1;
                        while ($row = $result_keluar->fetch_assoc()) {
                            echo "<tr class='odd:bg-gray-700 even:bg-gray-600'>";
                            echo "<td class='px-4 py-2'>" . $no++ . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row ['nama_barang']) . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['jumlah_ambil']) . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['tgl_ambil']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center py-4'>Tidak ada riwayat pengambilan.</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>