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
    <title>Admin - PerBarangan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
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

        .hidden {
            display: none;
        }
    </style>
</head>

<body class="min-h-screen bg-body text-white flex flex-col items-center p-8">
    <header class="flex justify-between items-center w-full max-w-6xl mb-8">
        <h1 class="text-4xl mulish font-bold">PerBarangan Digital</h1>
        <div>
            <p class="text-white mulish">Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
            <a href="logout.php" class="px-6 py-2 bg-red-500 text-white rounded-full mulish hover:bg-red-600 transition">Logout</a>
        </div>
    </header>

    <nav class="w-full max-w-6xl mb-8">
        <ul class="flex gap-4 justify-center">
            <li>
                <button class="px-4 py-2 bg-blue-500 text-white rounded-full mulish hover:bg-blue-600 transition" onclick="toggleModal()">Tambah Barang</button>
            </li>
            <li><a href="daftar_buku.php" class="px-4 py-2 bg-green-500 text-white rounded-full mulish hover:bg-green-600 transition">Daftar Barang</a></li>
            <li><a href="peminjaman.php" class="px-4 py-2 bg-yellow-500 text-white rounded-full mulish hover:bg-yellow-600 transition">Pengambilan Barang</a></li>
            <li><a href="pengembalian.php" class="px-4 py-2 bg-purple-500 text-white rounded-full mulish hover:bg-purple-600 transition">Pengembalian Barang</a></li>
            <li><a href="laporan.php" class="px-4 py-2 bg-pink-500 text-white rounded-full mulish hover:bg-pink-600 transition">Laporan</a></li>
        </ul>
    </nav>

    <div class="w-full max-w-6xl">
        <h2 class="text-3xl mulish font-bold mb-4">Daftar Barang</h2>
        <table class="table-auto w-full bg-glass rounded-lg overflow-hidden">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Kode Barang</th>
                    <th class="px-4 py-2">Nama Barang</th>
                    <th class="px-4 py-2">Stok</th>
                    <th class="px-4 py-2">Satuan</th>
                    <th class="px-4 py-2">Waktu Ditambahkan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT kode_barang, nama_barang, stok, satuan, created_at FROM barang ORDER BY stok ASC";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='odd:bg-gray-700 even:bg-gray-600'>";
                        echo "<td class='px-4 py-2'>" . $no++ . "</td>";
                        echo "<td class='px-4 py-2'>" . htmlspecialchars($row['kode_barang']) . "</td>";
                        echo "<td class='px-4 py-2'>" . htmlspecialchars($row['nama_barang']) . "</td>";
                        echo "<td class='px-4 py-2'>" . htmlspecialchars($row['stok']) . "</td>";
                        echo "<td class='px-4 py-2'>" . htmlspecialchars($row['satuan']) . "</td>";
                        echo "<td class='px-4 py-2'>" . htmlspecialchars($row['created_at']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center py-4'>Tidak ada data barang.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl mb-4 mulish font-bold text-gray-800">Tambah Barang Baru</h2>
            <form method="POST" action="process_input_buku.php" class="text-black">
                <div class="mb-4">
                    <label for="kode_barang" class="block text-gray-700">Kode Barang:</label>
                    <input type="text" id="kode_barang" name="kode_barang" class="w-full border-gray-300 rounded-md focus:ring focus:ring-blue-200 p-2" required>
                </div>
                <div class="mb-4">
                    <label for="nama_barang" class="block text-gray-700">Nama Barang:</label>
                    <input type="text" id="nama_barang" name="nama_barang" class="w-full border-gray-300 rounded-md focus:ring focus:ring-blue-200 p-2" required>
                </div>
                <div class="mb-4">
                    <label for="stok" class="block text-gray-700">Stok:</label>
                    <input type="number" id="stok" name="stok" class="w-full border-gray-300 rounded-md focus:ring focus:ring-blue-200 p-2" required>
                </div>
                <div class="mb-4">
                    <label for="satuan" class="block text-gray-700">Satuan:</label>
                    <select id="satuan" name="satuan" class="w-full border-gray-300 rounded-md focus:ring focus:ring-blue-200 p-2">
                        <option value="pcs">Pcs</option>
                        <option value="lusin">Lusin</option>
                        <option value="rim">Rim</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-full mr-2" onclick="toggleModal()">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-full">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal() {
            const modal = document.getElementById('modal');
            modal.classList.toggle('hidden');
        }
    </script>
</body>

</html>
