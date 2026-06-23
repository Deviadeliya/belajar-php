<?php
$host = 'localhost';
$db   = 'belajar_php';
$user = 'root';
$pass = '';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

function getPDO() {
    global $dsn, $user, $pass;
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        // Auto-create table jika belum ada
        $pdo->exec("CREATE TABLE IF NOT EXISTS products (
            id    INT AUTO_INCREMENT PRIMARY KEY,
            name  VARCHAR(255) NOT NULL,
            price DECIMAL(15,0) NOT NULL DEFAULT 0,
            stok  INT NOT NULL DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Tambah kolom price jika belum ada
        $cols = $pdo->query("DESCRIBE products")->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array('price', $cols)) {
            $pdo->exec("ALTER TABLE products ADD COLUMN price DECIMAL(15,0) NOT NULL DEFAULT 0");
        }
        if (!in_array('stok', $cols)) {
            $pdo->exec("ALTER TABLE products ADD COLUMN stok INT NOT NULL DEFAULT 0");
        }
    }
    return $pdo;
}