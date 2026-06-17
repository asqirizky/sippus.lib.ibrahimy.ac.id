<?php 
echo "tes";

$host = "ols-docker-env-mysql-1";
$db   = "webperpus";
$user = "root";
$pass = "lib_ibrahimy2025";
$port = 3306;

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);

    // Set error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Koneksi ke database BERHASIL!";
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>
?>