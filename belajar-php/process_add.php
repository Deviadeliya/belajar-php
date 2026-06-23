<?php
include 'config.php';

$name  = trim($_POST['name']  ?? '');
$price = (int)($_POST['price'] ?? 0);
$stok  = (int)($_POST['stok']  ?? 0);

if ($name === '') {
    header('Location: add.php?error=Nama+produk+tidak+boleh+kosong');
    exit;
}

$pdo  = getPDO();
$stmt = $pdo->prepare("INSERT INTO products (name, price, stok) VALUES (:name, :price, :stok)");
$stmt->execute([':name' => $name, ':price' => $price, ':stok' => $stok]);

header('Location: index.php?added=1');
exit;
