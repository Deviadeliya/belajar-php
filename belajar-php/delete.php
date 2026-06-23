<?php
include 'config.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$pdo  = getPDO();
$stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
$stmt->execute([':id' => $id]);

header('Location: index.php?deleted=1');
exit;
