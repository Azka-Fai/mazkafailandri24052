<?php
session_start(); // Wajib di paling atas buat nyimpen recent searches

// Data buku (Sengaja gue bikin 12 biar pagination 10 item per page-nya kelihatan jalan)
$buku_list = [
    ['kode' => 'B001', 'judul' => 'Belajar PHP & MySQL', 'kategori' => 'Teknologi', 'pengarang' => 'Budi Raharjo', 'penerbit' => 'Informatika', 'tahun' => 2022, 'harga' => 85000, 'stok' => 15],
    ['kode' => 'B002', 'judul' => 'Mahir Framework Laravel', 'kategori' => 'Teknologi', 'pengarang' => 'Ahmad Fikri', 'penerbit' => 'Andi Publisher', 'tahun' => 2023, 'harga' => 120000, 'stok' => 0],
    ['kode' => 'B003', 'judul' => 'Seni Berpikir Positif', 'kategori' => 'Pengembangan Diri', 'pengarang' => 'Ibrahim El Fiky', 'penerbit' => 'Zaman', 'tahun' => 2020, 'harga' => 65000, 'stok' => 5],
    ['kode' => 'B004', 'judul' => 'Pemrograman Python Dasar', 'kategori' => 'Teknologi', 'pengarang' => 'Eko Kurniawan', 'penerbit' => 'Programmer Zaman Now', 'tahun' => 2023, 'harga' => 95000, 'stok' => 20],
    ['kode' => 'B005', 'judul' => 'Sejarah Dunia yang Disembunyikan', 'kategori' => 'Sejarah', 'pengarang' => 'Jonathan Black', 'penerbit' => 'Pustaka Alvabet', 'tahun' => 2015, 'harga' => 150000, 'stok' => 2],
    ['kode' => 'B006', 'judul' => 'Atomic Habits', 'kategori' => 'Pengembangan Diri', 'pengarang' => 'James Clear', 'penerbit' => 'Gramedia', 'tahun' => 2019, 'harga' => 108000, 'stok' => 50],
    ['kode' => 'B007', 'judul' => 'Mastering JavaScript', 'kategori' => 'Teknologi', 'pengarang' => 'Sandhika Galih', 'penerbit' => 'WPU', 'tahun' => 2024, 'harga' => 110000, 'stok' => 10],
    ['kode' => 'B008', 'judul' => 'Bumi Manusia', 'kategori' => 'Sastra', 'pengarang' => 'Pramoedya Ananta Toer', 'penerbit' => 'Lentera Dipantara', 'tahun' => 2005, 'harga' => 90000, 'stok' => 0],
    ['kode' => 'B009', 'judul' => 'UI/UX Design for Beginner', 'kategori' => 'Desain', 'pengarang' => 'Rangga Kurni', 'penerbit' => 'Informatika', 'tahun' => 2021, 'harga' => 80000, 'stok' => 8],
    ['kode' => 'B010', 'judul' => 'Filosofi Teras', 'kategori' => 'Pengembangan Diri', 'pengarang' => 'Henry Manampiring', 'penerbit' => 'Kompas', 'tahun' => 2018, 'harga' => 98000, 'stok' => 12],
    ['kode' => 'B011', 'judul' => 'Sapiens: Riwayat Singkat Umat Manusia', 'kategori' => 'Sejarah', 'pengarang' => 'Yuval Noah Harari', 'penerbit' => 'KPG', 'tahun' => 2014, 'harga' => 135000, 'stok' => 4],
    ['kode' => 'B012', 'judul' => 'Struktur Data & Algoritma', 'kategori' => 'Teknologi', 'pengarang' => 'Rinaldi Munir', 'penerbit' => 'Informatika', 'tahun' => 2016, 'harga' => 100000, 'stok' => 7]
];

// Ambil array kategori unik buat dropdown otomatis
$kategori_list = array_unique(array_column($buku_list, 'kategori'));

// Ambil parameter GET
$keyword = $_GET['keyword'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$min_harga = $_GET['min_harga'] ?? '';
$max_harga = $_GET['max_harga'] ?? '';
$tahun = $_GET['tahun'] ?? '';
$status = $_GET['status'] ?? 'semua';
$sort = $_GET['sort'] ?? 'judul';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

// Save pencarian ke session (recent searches)
if (!empty($keyword) && !isset($_GET['export'])) {
    if (!isset($_SESSION['recent_searches'])) {
        $_SESSION['recent_searches'] = [];
    }
    // Masukkin keyword baru di awal array, pastiin unik, batesin 5 history aja
    if (!in_array($keyword, $_SESSION['recent_searches'])) {
        array_unshift($_SESSION['recent_searches'], $keyword);
        $_SESSION['recent_searches'] = array_slice($_SESSION['recent_searches'], 0, 5);
    }
}

// Validasi
$errors = [];
$tahun_sekarang = date('Y');

if (!empty($min_harga) && !empty($max_harga)) {
    if ($min_harga > $max_harga) {
        $errors[] = "Harga minimum tidak boleh lebih besar dari harga maksimum.";
    }
}

if (!empty($tahun)) {
    if ($tahun < 1900 || $tahun > $tahun_sekarang) {
        $errors[] = "Tahun harus valid (1900 - $tahun_sekarang).";
    }
}

// Filter data
$hasil = [];
if (empty($errors)) {
    foreach ($buku_list as $buku) {
        $match = true;

        // Pencarian Keyword (Judul / Pengarang)
        if (!empty($keyword)) {
            $keyword_lower = strtolower($keyword);
            $judul_lower = strtolower($buku['judul']);
            $pengarang_lower = strtolower($buku['pengarang']);
            if (!str_contains($judul_lower, $keyword_lower) && !str_contains($pengarang_lower, $keyword_lower)) {
                $match = false;
            }
        }

        // Filter Kategori
        if (!empty($kategori) && $buku['kategori'] !== $kategori) {
            $match = false;
        }

        // Filter Harga
        if (!empty($min_harga) && $buku['harga'] < $min_harga) $match = false;
        if (!empty($max_harga) && $buku['harga'] > $max_harga) $match = false;

        // Filter Tahun
        if (!empty($tahun) && $buku['tahun'] != $tahun) $match = false;

        // Filter Status
        if ($status === 'tersedia' && $buku['stok'] <= 0) $match = false;
        if ($status === 'habis' && $buku['stok'] > 0) $match = false;

        if ($match) {
            $hasil[] = $buku;
        }
    }

    // Sorting Data
    usort($hasil, function($a, $b) use ($sort) {
        if ($sort === 'harga') return $a['harga'] <=> $b['harga'];
        if ($sort === 'tahun') return $b['tahun'] <=> $a['tahun']; // Tahun terbaru di atas
        return strcmp($a['judul'], $b['judul']); // Default: Judul (A-Z)
    });
}

// Export to CSV
if (isset($_GET['export']) && $_GET['export'] == 'csv' && empty($errors)) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=data_buku.csv');
    $output = fopen('php://output', 'w');
    // Header CSV
    fputcsv($output, ['Kode', 'Judul', 'Kategori', 'Pengarang', 'Penerbit', 'Tahun', 'Harga', 'Stok']);
    foreach ($hasil as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit(); // Stop render HTML
}

// Pagination logic
$limit = 10;
$total_data = count($hasil);
$total_pages = ceil($total_data / $limit);
$offset = ($page - 1) * $limit;
$hasil_paged = array_slice($hasil, $offset, $limit);

// Helper function untuk Highlight Keyword
function highlight_word($text, $word) {
    if (empty($word)) return htmlspecialchars($text);
    $escaped_word = preg_quote($word, '/');
    $highlighted = preg_replace("/($escaped_word)/i", "<mark class='bg-warning p-0'>$1</mark>", htmlspecialchars($text));
    return $highlighted;
}

// Helper untuk bikin URL query string (buat pagination tetep bawa parameter)
function build_url($params) {
    $current = $_GET;
    unset($current['export']); // Pastiin export gak kebawa di pagination
    return '?' . http_build_query(array_merge($current, $params));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pencarian Buku Lanjutan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light pb-5">

<div class="container mt-4">
    <h2 class="mb-4">Sistem Pencarian Buku Lanjutan</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?= $err ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="" method="GET">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pencarian</label>
                            <input type="text" class="form-control" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Judul / Pengarang">
                        </div>

                        <?php if (isset($_SESSION['recent_searches']) && !empty($_SESSION['recent_searches'])): ?>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Riwayat Pencarian:</small>
                                <?php foreach ($_SESSION['recent_searches'] as $rs): ?>
                                    <a href="?keyword=<?= urlencode($rs) ?>" class="badge bg-secondary text-decoration-none"><?= htmlspecialchars($rs) ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($kategori_list as $kat): ?>
                                    <option value="<?= $kat ?>" <?= $kategori == $kat ? 'selected' : '' ?>><?= $kat ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Range Harga</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" name="min_harga" placeholder="Min" value="<?= htmlspecialchars($min_harga) ?>">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" name="max_harga" placeholder="Max" value="<?= htmlspecialchars($max_harga) ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tahun Terbit</label>
                            <input type="number" class="form-control" name="tahun" value="<?= htmlspecialchars($tahun) ?>" placeholder="Cth: 2020">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ketersediaan</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="semua" id="stat1" <?= $status == 'semua' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="stat1">Semua</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="tersedia" id="stat2" <?= $status == 'tersedia' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="stat2">Tersedia</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="habis" id="stat3" <?= $status == 'habis' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="stat3">Habis</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Urutkan Berdasarkan</label>
                            <select name="sort" class="form-select">
                                <option value="judul" <?= $sort == 'judul' ? 'selected' : '' ?>>Judul Buku (A-Z)</option>
                                <option value="harga" <?= $sort == 'harga' ? 'selected' : '' ?>>Harga Termurah</option>
                                <option value="tahun" <?= $sort == 'tahun' ? 'selected' : '' ?>>Tahun Terbaru</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                            <a href="search_advanced.php" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Hasil Pencarian: <span class="badge bg-primary"><?= empty($errors) ? $total_data : 0 ?> Ditemukan</span></h5>
                <?php if (empty($errors) && $total_data > 0): ?>
                    <a href="<?= build_url(['export' => 'csv']) ?>" class="btn btn-success btn-sm">Download CSV</a>
                <?php endif; ?>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Pengarang</th>
                                <th>Tahun</th>
                                <th>Harga</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($errors)): ?>
                                <tr><td colspan="7" class="text-center text-danger py-3">Perbaiki error pada form filter.</td></tr>
                            <?php elseif (empty($hasil_paged)): ?>
                                <tr><td colspan="7" class="text-center py-4">Data buku tidak ditemukan berdasarkan filter tersebut.</td></tr>
                            <?php else: ?>
                                <?php foreach ($hasil_paged as $buku): ?>
                                    <tr>
                                        <td><?= $buku['kode'] ?></td>
                                        <td><?= highlight_word($buku['judul'], $keyword) ?></td>
                                        <td><?= $buku['kategori'] ?></td>
                                        <td><?= highlight_word($buku['pengarang'], $keyword) ?></td>
                                        <td><?= $buku['tahun'] ?></td>
                                        <td>Rp <?= number_format($buku['harga'], 0, ',', '.') ?></td>
                                        <td>
                                            <?php if ($buku['stok'] > 0): ?>
                                                <span class="badge bg-success"><?= $buku['stok'] ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Habis</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if ($total_pages > 1 && empty($errors)): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= build_url(['page' => $page - 1]) ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                <a class="page-link" href="<?= build_url(['page' => $i]) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= build_url(['page' => $page + 1]) ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>