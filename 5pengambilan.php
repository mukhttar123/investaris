<?php
session_start();
include 'config.php';

// Pastikan hanya admin yang dapat mengakses
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Ambil daftar buku dari database
$bukuResult = $conn->query("SELECT nama_barang, stok FROM barang WHERE stok > 0");

// Proses jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_peminjam = $_POST['nama_peminjam'];
    $alamat_peminjam = $_POST['alamat_peminjam'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $tgl_kembali = $_POST['tgl_kembali'];
    $jumlah_dipinjam = $_POST['jumlah_dipinjam'];

    // Periksa stok buku
    $stmt = $conn->prepare("SELECT stok FROM barang WHERE nama_barang = ?");
    $stmt->bind_param("s", $kode_buku);
    $stmt->execute();
    $result = $stmt->get_result();
    $buku = $result->fetch_assoc();

    if ($buku && $buku['stok'] >= $jumlah_dipinjam) {
        // Kurangi stok buku
        $new_stok = $buku['stok'] - $jumlah_dipinjam;
        $updateStmt = $conn->prepare("UPDATE barang SET stok = ? WHERE nama_barang = ?");
        $updateStmt->bind_param("is", $new_stok, $kode_buku);
        $updateStmt->execute();

        // Masukkan data peminjaman
        $insertStmt = $conn->prepare("INSERT INTO pengambilan (kode_peminjaman, kode_barang, nama_peminjam, alamat_peminjam, tgl_pinjam, tgl_kembali, jumlah_dipinjam) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insertStmt->bind_param("ssssssi", $kode_peminjaman, $kode_buku, $nama_peminjam, $alamat_peminjam, $tgl_pinjam, $tgl_kembali, $jumlah_dipinjam);

        if ($insertStmt->execute()) {
            echo "<p>Transaksi pengambilan berhasil dicatat!</p>";
        } else {
            echo "<p>Terjadi kesalahan: " . $insertStmt->error . "</p>";
        }
    } else {
        echo "<p>Stok buku tidak mencukupi!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengambilan barang</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-body text-white min-h-screen flex flex-col items-center p-8">
    <header class="w-full max-w-6xl flex justify-between items-center mb-6">
        <h1 class="text-3xl mulish font-bold">Form Pengambilan Barang</h1>
        <div>
            <a href="admin.php" class="px-4 py-2 bg-blue-500 text-white rounded-full mulish hover:bg-blue-600 transition">Kembali</a>
        </div>
    </header>

    <div class="w-full max-w-4xl bg-glass p-6 rounded-lg">
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-bold mb-1">Pilih Barang:</label>
                <select name="kode_buku" required class="w-full p-2 rounded bg-gray-800 text-white">
                    <option value="">-- Pilih Barang --</option>
                    <?php while ($row = $bukuResult->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['nama_barang']) ?>">
                            <?= htmlspecialchars($row['nama_barang']) ?> (Stok: <?= htmlspecialchars($row['stok']) ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold mb-1">Tanggal Ambil:</label>
                <input type="date" name="tgl_pinjam" required class="w-full p-2 rounded bg-gray-800 text-white">
            </div>

            <div>
                <label class="block text-sm font-bold mb-1">Jumlah Diambil:</label>
                <input type="number" name="jumlah_dipinjam" required class="w-full p-2 rounded bg-gray-800 text-white">
            </div>

            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600 transition">Catat Pengambilan</button>
        </form>
    </div>

    <div class="w-full max-w-6xl mt-8">
        <h2 class="text-2xl mulish font-bold mb-4">Daftar Barang</h2>
        <table class="table-auto w-full bg-glass rounded-lg overflow-hidden">
            <thead class="bg-gray-800 text-white">
                <tr>
                <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Judul Barang</th>
                    <th class="px-4 py-2">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM barang";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='odd:bg-gray-700 even:bg-gray-600'>";
                        echo "<td class='px-4 py-2'>" . $no++ . "</td>";
                        echo "<td class='px-4 py-2'>" . htmlspecialchars($row['nama_barang']) . "</td>";
                        echo "<td class='px-4 py-2'>" . htmlspecialchars($row['stok']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center py-4'>Tidak ada data barang.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <style>
        .bg-body {
            background-color: rgba(13, 13, 13, 1);
        }

        .mulish {
            font-family: "Mulish", sans-serif;
        }

        .bg-glass {
            background: rgba(255, 255, 255, 0.24);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</body>

</html>



