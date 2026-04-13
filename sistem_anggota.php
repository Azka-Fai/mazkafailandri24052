<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Anggota Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <?php
    require_once 'functions_anggota.php';

    $anggota_list = [
        ["id" => "AGT-001", "nama" => "Budi Gaming", "email" => "budi@email.com", "telepon" => "081234567890", "alamat" => "Jakarta", "tanggal_daftar" => "2024-01-15", "status" => "Aktif", "total_pinjaman" => 5],
        ["id" => "AGT-002", "nama" => "Sigit Waluyo", "email" => "sigit@email.com", "telepon" => "082345678901", "alamat" => "Bandung", "tanggal_daftar" => "2024-02-20", "status" => "Aktif", "total_pinjaman" => 15],
        ["id" => "AGT-003", "nama" => "Andika Wijaya", "email" => "andika@email.com", "telepon" => "083456789012", "alamat" => "Surabaya", "tanggal_daftar" => "2023-11-10", "status" => "Non-Aktif", "total_pinjaman" => 2],
        ["id" => "AGT-004", "nama" => "Roro Jonggrang", "email" => "jonggrang_salah", "telepon" => "084567890123", "alamat" => "Semarang", "tanggal_daftar" => "2024-03-05", "status" => "Aktif", "total_pinjaman" => 8],
        ["id" => "AGT-005", "nama" => "Eko Show", "email" => "ekos@email.com", "telepon" => "085678901234", "alamat" => "Yogya", "tanggal_daftar" => "2023-08-17", "status" => "Non-Aktif", "total_pinjaman" => 0]
    ];

    $keyword = $_GET['search'] ?? '';
    $data_tampil = $anggota_list;
    
    if ($keyword) $data_tampil = search_by_nama($data_tampil, $keyword);
    $data_tampil = sort_by_nama($data_tampil);

    $total = hitung_total_anggota($anggota_list);
    $aktif = hitung_anggota_aktif($anggota_list);
    $teraktif = cari_anggota_teraktif($anggota_list);
    ?>

    <div class="container">
        <h2 class="mb-4">Sistem Perpustakaan (Tugas 2)</h2>

        <form class="mb-4 d-flex gap-2">
            <input type="text" name="search" class="form-control w-25" placeholder="Cari nama..." value="<?= htmlspecialchars($keyword) ?>">
            <button class="btn btn-primary" type="submit">Cari</button>
        </form>

        <div class="row mb-4 text-center text-white">
            <div class="col-md-3"><div class="card bg-primary p-3">Total: <?= $total ?></div></div>
            <div class="col-md-3"><div class="card bg-success p-3">Aktif: <?= ($aktif/$total)*100 ?>%</div></div>
            <div class="col-md-3"><div class="card bg-info text-dark p-3">Rata-rata: <?= hitung_rata_rata_pinjaman($anggota_list) ?></div></div>
        </div>

        <div class="card mb-4 border-warning">
            <div class="card-header bg-warning"><strong>Anggota Teraktif</strong></div>
            <div class="card-body">
                <h5><?= $teraktif['nama'] ?></h5>
                <p>Pinjaman: <?= $teraktif['total_pinjaman'] ?> | Daftar: <?= format_tanggal_indo($teraktif['tanggal_daftar']) ?></p>
            </div>
        </div>

        <table class="table table-striped table-bordered mb-4">
            <thead class="table-dark"><tr><th>ID</th><th>Nama</th><th>Email</th><th>Daftar</th><th>Status</th><th>Pinjam</th></tr></thead>
            <tbody>
                <?php foreach($data_tampil as $a): ?>
                    <tr>
                        <td><?= $a['id'] ?></td><td><?= $a['nama'] ?></td>
                        <td><?= validasi_email($a['email']) ? $a['email'] : '<span class="text-danger">Invalid</span>' ?></td>
                        <td><?= format_tanggal_indo($a['tanggal_daftar']) ?></td><td><?= $a['status'] ?></td><td><?= $a['total_pinjaman'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="row">
            <div class="col-md-6">
                <div class="card"><div class="card-header bg-success text-white">List Aktif</div>
                    <ul class="list-group list-group-flush">
                        <?php foreach(filter_by_status($anggota_list, 'Aktif') as $a) echo "<li class='list-group-item'>{$a['nama']}</li>"; ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card"><div class="card-header bg-secondary text-white">List Non-Aktif</div>
                    <ul class="list-group list-group-flush">
                        <?php foreach(filter_by_status($anggota_list, 'Non-Aktif') as $a) echo "<li class='list-group-item'>{$a['nama']}</li>"; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>