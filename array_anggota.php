<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas 1 - Array Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <?php
    $anggota_list = [
        ["id" => "AGT-001", "nama" => "Alessandro", "email" => "sandro@email.com", "telepon" => "081234567890", "alamat" => "Jakarta", "tanggal_daftar" => "2024-01-15", "status" => "Aktif", "total_pinjaman" => 5],
        ["id" => "AGT-002", "nama" => "John Cena", "email" => "john@email.com", "telepon" => "082345678901", "alamat" => "Bandung", "tanggal_daftar" => "2024-02-20", "status" => "Aktif", "total_pinjaman" => 15],
        ["id" => "AGT-003", "nama" => "Lakatompessy", "email" => "lakatompessy@email.com", "telepon" => "083456789012", "alamat" => "Surabaya", "tanggal_daftar" => "2023-11-10", "status" => "Non-Aktif", "total_pinjaman" => 2],
        ["id" => "AGT-004", "nama" => "Erpan1140", "email" => "erpan1140@email.com", "telepon" => "084567890123", "alamat" => "Semarang", "tanggal_daftar" => "2024-03-05", "status" => "Aktif", "total_pinjaman" => 8],
        ["id" => "AGT-005", "nama" => "Nextjack", "email" => "nextjack@email.com", "telepon" => "085678901234", "alamat" => "Yogyakarta", "tanggal_daftar" => "2023-08-17", "status" => "Non-Aktif", "total_pinjaman" => 3]
    ];

    $total = count($anggota_list);
    $aktif = 0; $non_aktif = 0; $total_pinjam = 0; $teraktif = $anggota_list[0];

    foreach ($anggota_list as $agt) {
        if ($agt['status'] == 'Aktif') $aktif++; else $non_aktif++;
        $total_pinjam += $agt['total_pinjaman'];
        if ($agt['total_pinjaman'] > $teraktif['total_pinjaman']) $teraktif = $agt;
    }

    $persen_aktif = ($total > 0) ? ($aktif / $total) * 100 : 0;
    $persen_non = ($total > 0) ? ($non_aktif / $total) * 100 : 0;
    $rata_rata = ($total > 0) ? ($total_pinjam / $total) : 0;

    $filter = isset($_GET['status']) ? $_GET['status'] : 'Semua';
    $data_tampil = ($filter != 'Semua') ? array_filter($anggota_list, fn($i) => $i['status'] == $filter) : $anggota_list;
    ?>

    <div class="container">
        <h2 class="mb-4">Dashboard Anggota (Tugas 1)</h2>
        
        <div class="row mb-4 g-3 text-white text-center">
            <div class="col-md-3"><div class="card bg-primary p-3"><h5>Total: <?= $total ?></h5></div></div>
            <div class="col-md-3"><div class="card bg-success p-3"><h5>Aktif: <?= $persen_aktif ?>%</h5></div></div>
            <div class="col-md-3"><div class="card bg-secondary p-3"><h5>Non-Aktif: <?= $persen_non ?>%</h5></div></div>
            <div class="col-md-3"><div class="card bg-info text-dark p-3"><h5>Rata-rata: <?= $rata_rata ?></h5></div></div>
        </div>

        <div class="alert alert-warning"><strong>Teraktif:</strong> <?= $teraktif['nama'] ?> (<?= $teraktif['total_pinjaman'] ?> pinjaman)</div>

        <div class="mb-3">
            <a href="?status=Semua" class="btn btn-sm btn-dark">Semua</a>
            <a href="?status=Aktif" class="btn btn-sm btn-success">Aktif</a>
            <a href="?status=Non-Aktif" class="btn btn-sm btn-secondary">Non-Aktif</a>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark"><tr><th>ID</th><th>Nama</th><th>Email</th><th>Status</th><th>Pinjaman</th></tr></thead>
            <tbody>
                <?php foreach ($data_tampil as $a): ?>
                    <tr><td><?= $a['id'] ?></td><td><?= $a['nama'] ?></td><td><?= $a['email'] ?></td><td><?= $a['status'] ?></td><td><?= $a['total_pinjaman'] ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>