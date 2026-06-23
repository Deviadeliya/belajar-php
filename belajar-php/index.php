<?php
include 'config.php';

$pdo      = getPDO();
$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();

// Hitung statistik
$totalProduk = count($products);
$totalStok   = array_sum(array_column($products, 'stok'));
$totalNilai  = array_sum(array_map(fn($p) => $p['price'] * $p['stok'], $products));

$alert = '';
if (isset($_GET['added']))   $alert = ['type' => 'success', 'msg' => '✅ Produk berhasil ditambahkan!'];
if (isset($_GET['edited']))  $alert = ['type' => 'success', 'msg' => '✅ Produk berhasil diperbarui!'];
if (isset($_GET['deleted'])) $alert = ['type' => 'success', 'msg' => '🗑️ Produk berhasil dihapus!'];
if (isset($_GET['stok']))    $alert = ['type' => 'success', 'msg' => '📦 Stok berhasil diperbarui!'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - TokoKu</title>
    <meta name="description" content="Sistem manajemen produk modern dengan tampilan pink elegan.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink-50: #fdf2f8;
            --pink-100: #fce7f3;
            --pink-200: #fbcfe8;
            --pink-300: #f9a8d4;
            --pink-400: #f472b6;
            --pink-500: #ec4899;
            --pink-600: #db2777;
            --pink-700: #be185d;
            --pink-800: #9d174d;
            --rose-400: #fb7185;
            --rose-500: #f43f5e;
            --bg: #fff5f9;
            --card: #ffffff;
            --text: #1e1b2e;
            --muted: #7c6e7f;
            --border: #f9c0d8;
            --shadow: 0 4px 24px rgba(236, 72, 153, 0.08);
            --shadow-lg: 0 8px 40px rgba(236, 72, 153, 0.15);
            --radius: 16px;
            --radius-sm: 10px;
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }
        /* NAVBAR */
        .navbar {
            background: linear-gradient(135deg, var(--pink-600) 0%, var(--rose-500) 100%);
            padding: 0 2rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(219, 39, 119, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar-brand {
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.5px;
        }
        .navbar-brand .icon { font-size: 1.6rem; }
        .navbar-links {
            display: flex;
            gap: 8px;
            list-style: none;
        }
        .navbar-links a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: var(--transition);
        }
        .navbar-links a:hover,
        .navbar-links a.active {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }
        /* CONTAINER */
        .main-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }
        /* PAGE HEADER */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .page-title {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--pink-600), var(--rose-500));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }
        .page-subtitle {
            color: var(--muted);
            font-size: 0.9rem;
            margin-top: 4px;
        }
        /* BUTTONS */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: var(--transition);
            white-space: nowrap;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--pink-500), var(--rose-500));
            color: #fff;
            box-shadow: 0 4px 14px rgba(236, 72, 153, 0.35);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.5);
        }
        .btn-secondary {
            background: var(--pink-100);
            color: var(--pink-600);
            border: 1.5px solid var(--pink-200);
        }
        .btn-secondary:hover {
            background: var(--pink-200);
            transform: translateY(-1px);
        }
        .btn-danger {
            background: #fff0f3;
            color: #e11d48;
            border: 1.5px solid #fecdd3;
        }
        .btn-danger:hover {
            background: #ffe4e6;
            transform: translateY(-1px);
        }
        .btn-warning {
            background: #fff7ed;
            color: #c2410c;
            border: 1.5px solid #fed7aa;
        }
        .btn-warning:hover {
            background: #ffedd5;
            transform: translateY(-1px);
        }
        .btn-sm {
            padding: 6px 14px;
            font-size: 0.8rem;
            border-radius: 8px;
        }
        /* CARD */
        .card {
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1.5px solid var(--border);
            overflow: hidden;
        }
        /* TABLE */
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead {
            background: linear-gradient(135deg, var(--pink-500) 0%, var(--rose-500) 100%);
        }
        thead th {
            color: #fff;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 14px 20px;
            text-align: left;
            white-space: nowrap;
        }
        tbody tr {
            border-bottom: 1px solid var(--pink-100);
            transition: var(--transition);
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--pink-50); }
        tbody td {
            padding: 14px 20px;
            font-size: 0.9rem;
            color: var(--text);
            vertical-align: middle;
        }
        .td-actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
        }
        .badge-pink { background: var(--pink-100); color: var(--pink-600); }
        .badge-green { background: #f0fdf4; color: #16a34a; }
        .badge-red { background: #fff0f3; color: #e11d48; }
        /* ALERTS */
        .alert {
            padding: 12px 18px;
            border-radius: var(--radius-sm);
            font-size: 0.88rem;
            font-weight: 500;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-success {
            background: #f0fdf4;
            color: #15803d;
            border: 1.5px solid #bbf7d0;
        }
        .alert-danger {
            background: #fff0f3;
            color: #be123c;
            border: 1.5px solid #fecdd3;
        }
        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--muted);
        }
        .empty-state .emoji { font-size: 3rem; margin-bottom: 1rem; }
        .empty-state p { font-size: 1rem; margin-bottom: 1.5rem; }
        /* STATS */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 1.3rem 1.5rem;
            border: 1.5px solid var(--border);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .stat-icon-pink { background: var(--pink-100); }
        .stat-icon-rose { background: #fff0f3; }
        .stat-icon-purple { background: #f5f3ff; }
        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
        }
        .stat-label {
            font-size: 0.78rem;
            color: var(--muted);
            font-weight: 500;
            margin-top: 4px;
        }
        /* FORM */
        .form-card { max-width: 540px; margin: 0 auto; }
        .form-group { margin-bottom: 1.3rem; }
        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--pink-700);
            margin-bottom: 6px;
        }
        .form-control {
            width: 100%;
            padding: 11px 16px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9rem;
            color: var(--text);
            background: var(--bg);
            transition: var(--transition);
            outline: none;
        }
        .form-control:focus {
            border-color: var(--pink-400);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.12);
        }
        .form-control::placeholder { color: #c4b5c8; }
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 1.5rem;
        }
        /* RESPONSIVE */
        @media (max-width: 600px) {
            .navbar { padding: 0 1rem; }
            .main-container { padding: 1.5rem 1rem; }
            .page-header { flex-direction: column; align-items: flex-start; }
            .form-actions { flex-direction: column; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <a class="navbar-brand" href="index.php">
        <span class="icon">🛍️</span> TokoKu
    </a>
    <ul class="navbar-links">
        <li><a href="index.php" class="active">Dashboard</a></li>
        <li><a href="add.php">Tambah Produk</a></li>
    </ul>
</nav>

<div class="main-container">

    <?php if ($alert): ?>
    <div class="alert alert-<?= $alert['type'] ?>">
        <?= $alert['msg'] ?>
    </div>
    <?php endif; ?>

    <!-- Statistik -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon stat-icon-pink">🛍️</div>
            <div>
                <div class="stat-value"><?= $totalProduk ?></div>
                <div class="stat-label">Total Produk</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-rose">📦</div>
            <div>
                <div class="stat-value"><?= number_format($totalStok) ?></div>
                <div class="stat-label">Total Stok</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-purple">💰</div>
            <div>
                <div class="stat-value">Rp <?= number_format($totalNilai, 0, ',', '.') ?></div>
                <div class="stat-label">Nilai Inventori</div>
            </div>
        </div>
    </div>

    <!-- Header & Button -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Daftar Produk</h1>
            <p class="page-subtitle">Kelola semua produk Anda di satu tempat</p>
        </div>
        <a href="add.php" class="btn btn-primary">
            <span>➕</span> Tambah Produk
        </a>
    </div>

    <!-- Tabel -->
    <div class="card">
        <?php if (empty($products)): ?>
        <div class="empty-state">
            <div class="emoji">🛒</div>
            <p>Belum ada produk. Yuk tambahkan produk pertamamu!</p>
            <a href="add.php" class="btn btn-primary">Tambah Produk</a>
        </div>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Produk</th>
                        <th>Harga (IDR)</th>
                        <th>Stok</th>
                        <th>Total Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $i => $product): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <strong><?= htmlspecialchars($product['name']) ?></strong>
                        </td>
                        <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                        <td>
                            <?php
                            $stok = (int)$product['stok'];
                            $badgeClass = $stok <= 0 ? 'badge-red' : ($stok <= 10 ? 'badge-pink' : 'badge-green');
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= $stok ?></span>
                        </td>
                        <td>Rp <?= number_format($product['price'] * $product['stok'], 0, ',', '.') ?></td>
                        <td>
                            <div class="td-actions">
                                <a href="edit.php?id=<?= $product['id'] ?>" class="btn btn-secondary btn-sm">✏️ Edit</a>
                                <a href="updatestok.php?id=<?= $product['id'] ?>" class="btn btn-warning btn-sm">📦 Stok</a>
                                <a href="delete.php?id=<?= $product['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus produk ini?')">🗑️ Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

</div>

</body>
</html>