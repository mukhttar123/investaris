<?php
include 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $stok =$_POST['stok'];
    $satuan = $_POST['satuan'];

    $stmt= $conn->prepare("INSERT INTO barang (kode_barang, nama_barang, stok, satuan, created_at) VALUES (?,?,?,?, NOW())");
    $stmt->bind_param("ssis",$kode_barang,$nama_barang,$stok,$satuan);

    if($stmt->execute()){
        header("Location: admin.php?success=1");
    } else {
        echo "terjadi kesalahan".$stmt->error;
    }

    $stmt->close();
}
?>