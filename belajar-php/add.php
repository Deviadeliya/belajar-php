<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - TokoKu</title>
    <meta name="description" content="Form tambah produk baru ke sistem manajemen TokoKu.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink-50:#fdf2f8;--pink-100:#fce7f3;--pink-200:#fbcfe8;--pink-300:#f9a8d4;
            --pink-400:#f472b6;--pink-500:#ec4899;--pink-600:#db2777;--pink-700:#be185d;
            --pink-800:#9d174d;--rose-400:#fb7185;--rose-500:#f43f5e;
            --bg:#fff5f9;--card:#ffffff;--text:#1e1b2e;--muted:#7c6e7f;--border:#f9c0d8;
            --shadow:0 4px 24px rgba(236,72,153,0.08);--radius:16px;--radius-sm:10px;
            --transition:all 0.25s cubic-bezier(0.4,0,0.2,1);
        }
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;}
        .navbar{background:linear-gradient(135deg,var(--pink-600),var(--rose-500));padding:0 2rem;height:64px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 4px 20px rgba(219,39,119,0.3);position:sticky;top:0;z-index:100;}
        .navbar-brand{font-size:1.4rem;font-weight:800;color:#fff;text-decoration:none;display:flex;align-items:center;gap:10px;letter-spacing:-0.5px;}
        .navbar-brand .icon{font-size:1.6rem;}
        .navbar-links{display:flex;gap:8px;list-style:none;}
        .navbar-links a{color:rgba(255,255,255,0.85);text-decoration:none;padding:8px 16px;border-radius:8px;font-weight:500;font-size:0.9rem;transition:var(--transition);}
        .navbar-links a:hover,.navbar-links a.active{background:rgba(255,255,255,0.2);color:#fff;}
        .main-container{max-width:1100px;margin:0 auto;padding:2.5rem 1.5rem;}
        .page-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;}
        .page-title{font-size:1.8rem;font-weight:800;background:linear-gradient(135deg,var(--pink-600),var(--rose-500));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;letter-spacing:-0.5px;}
        .page-subtitle{color:var(--muted);font-size:0.9rem;margin-top:4px;}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:var(--radius-sm);font-family:'Plus Jakarta Sans',sans-serif;font-size:0.875rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:var(--transition);white-space:nowrap;}
        .btn-primary{background:linear-gradient(135deg,var(--pink-500),var(--rose-500));color:#fff;box-shadow:0 4px 14px rgba(236,72,153,0.35);}
        .btn-primary:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(236,72,153,0.5);}
        .btn-secondary{background:var(--pink-100);color:var(--pink-600);border:1.5px solid var(--pink-200);}
        .btn-secondary:hover{background:var(--pink-200);transform:translateY(-1px);}
        .card{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);border:1.5px solid var(--border);overflow:hidden;}
        .form-card{max-width:540px;margin:0 auto;}
        .form-group{margin-bottom:1.3rem;}
        .form-label{display:block;font-size:0.85rem;font-weight:600;color:var(--pink-700);margin-bottom:6px;}
        .form-control{width:100%;padding:11px 16px;border:1.5px solid var(--border);border-radius:var(--radius-sm);font-family:'Plus Jakarta Sans',sans-serif;font-size:0.9rem;color:var(--text);background:var(--bg);transition:var(--transition);outline:none;}
        .form-control:focus{border-color:var(--pink-400);background:#fff;box-shadow:0 0 0 3px rgba(236,72,153,0.12);}
        .form-control::placeholder{color:#c4b5c8;}
        .form-actions{display:flex;gap:10px;margin-top:1.5rem;}
        .alert{padding:12px 18px;border-radius:var(--radius-sm);font-size:0.88rem;font-weight:500;margin-bottom:1.2rem;display:flex;align-items:center;gap:8px;}
        .alert-danger{background:#fff0f3;color:#be123c;border:1.5px solid #fecdd3;}
        @media(max-width:600px){.navbar{padding:0 1rem;}.main-container{padding:1.5rem 1rem;}.page-header{flex-direction:column;align-items:flex-start;}.form-actions{flex-direction:column;}}
    </style>
</head>
<body>

<nav class="navbar">
    <a class="navbar-brand" href="index.php">
        <span class="icon">🛍️</span> TokoKu
    </a>
    <ul class="navbar-links">
        <li><a href="index.php">Dashboard</a></li>
        <li><a href="add.php" class="active">Tambah Produk</a></li>
    </ul>
</nav>

<div class="main-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Tambah Produk Baru</h1>
            <p class="page-subtitle">Isi detail produk yang ingin ditambahkan</p>
        </div>
        <a href="index.php" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card form-card">
        <div style="padding: 2rem;">
            <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">⚠️ <?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <form action="process_add.php" method="POST">
                <div class="form-group">
                    <label class="form-label" for="name">Nama Produk</label>
                    <input type="text" id="name" name="name" class="form-control"
                           placeholder="Contoh: Baju Batik Premium" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="price">Harga (IDR)</label>
                    <input type="number" id="price" name="price" class="form-control"
                           placeholder="Contoh: 150000" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="stok">Jumlah Stok</label>
                    <input type="number" id="stok" name="stok" class="form-control"
                           placeholder="Contoh: 50" min="0" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">➕ Tambah Produk</button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>