<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Daftar Transaksi Peminjaman</h1>

        <?php
        // TODO: Hitung statistik dengan loop
        $total_transaksi = 0;
        $total_dipinjam = 0;
        $total_dikembalikan = 0;

        // Loop pertama untuk hitung statistik yg HANYA DITAMPILKAN
        for ($i = 1; $i <= 10; $i++) {
            // STOP di transaksi ke-8
            if ($i == 8) {
                break;
            }
            // SKIP transaksi genap
            if ($i % 2 == 0) {
                continue; 
            }

            // Kalo lolos filter di atas, hitung stat-nya
            $status = ($i % 3 == 0) ? "Dikembalikan" : "Dipinjam";
            
            $total_transaksi++;
            if ($status == "Dipinjam") {
                $total_dipinjam++;
            } else {
                $total_dikembalikan++;
            }
        }
        ?>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Transaksi</h5>
                        <p class="card-text fs-3"><?php echo $total_transaksi; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Masih Dipinjam</h5>
                        <p class="card-text fs-3"><?php echo $total_dipinjam; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Sudah Dikembalikan</h5>
                        <p class="card-text fs-3"><?php echo $total_dikembalikan; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>ID Transaksi</th>
                    <th>Peminjam</th>
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Hari (Sejak Pinjam)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // TODO: Loop untuk tampilkan data
                $no_urut = 1; // Untuk nomor urut di tabel yg rapi
                for ($i = 1; $i <= 10; $i++) {
                    
                    // Gunakan break untuk stop di transaksi 8
                    if ($i == 8) break;
                    
                    // Gunakan continue untuk skip genap
                    if ($i % 2 == 0) continue;

                    // Generate data transaksi
                    $id_transaksi = "TRX-" . str_pad($i, 4, "0", STR_PAD_LEFT);
                    $nama_peminjam = "Anggota " . $i;
                    $judul_buku = "Buku Teknologi Vol. " . $i;
                    $tanggal_pinjam = date('Y-m-d', strtotime("-$i days"));
                    $tanggal_kembali = date('Y-m-d', strtotime("+7 days", strtotime($tanggal_pinjam)));
                    $status = ($i % 3 == 0) ? "Dikembalikan" : "Dipinjam";
                    
                    // Hitung jumlah hari sejak pinjam (Logic: Hari ini dikurang tgl pinjam = $i hari)
                    $hari_sejak_pinjam = $i; 

                    // Gunakan warna berbeda untuk status (Bootstrap badges)
                    $badge_class = ($status == "Dikembalikan") ? "bg-success" : "bg-warning text-dark";

                    echo "<tr>";
                    echo "<td>" . $no_urut++ . "</td>";
                    echo "<td>" . $id_transaksi . "</td>";
                    echo "<td>" . $nama_peminjam . "</td>";
                    echo "<td>" . $judul_buku . "</td>";
                    echo "<td>" . $tanggal_pinjam . "</td>";
                    echo "<td>" . $tanggal_kembali . "</td>";
                    echo "<td>" . $hari_sejak_pinjam . " hari</td>";
                    echo "<td><span class='badge " . $badge_class . "'>" . $status . "</span></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>