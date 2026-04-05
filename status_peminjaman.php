<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Status Peminjaman Anggota</h1>

        <?php
        // 1. Data Anggota
        $nama_anggota = "Budi Santoso";
        $total_pinjaman = 2;
        $buku_terlambat = 1;
        $hari_keterlambatan = 5;

        // 2. Aturan Business Logic (IF-ELSEIF-ELSE)
        $bisa_pinjam = true;
        $pesan_peringatan = "";
        $total_denda = 0;

        if ($buku_terlambat > 0) {
            $total_denda = $buku_terlambat * $hari_keterlambatan * 1000;
            if ($total_denda > 50000) {
                $total_denda = 50000;
            }
        }

        if ($buku_terlambat > 0) {
            $bisa_pinjam = false;
            // Pake tag <strong> bawaan HTML biar rapi pas di-render Bootstrap
            $pesan_peringatan = "Anda memiliki buku yang terlambat dikembalikan! Harap lunasi denda sebesar <strong>Rp " . number_format($total_denda, 0, ',', '.') . "</strong>";
        } elseif ($total_pinjaman >= 3) {
            $bisa_pinjam = false;
            $pesan_peringatan = "Anda sudah mencapai batas maksimal peminjaman (3 buku).";
        }

        // 3. Tentukan Level Member (SWITCH)
        $level_member = "";
        $badge_class = ""; // Tambahan variabel buat class warna badge UI
        switch (true) {
            case ($total_pinjaman >= 0 && $total_pinjaman <= 5):
                $level_member = "Bronze";
                $badge_class = "text-bg-secondary"; 
                break;
            case ($total_pinjaman >= 6 && $total_pinjaman <= 15):
                $level_member = "Silver";
                $badge_class = "text-bg-info";
                break;
            case ($total_pinjaman > 15):
                $level_member = "Gold";
                $badge_class = "text-bg-warning";
                break;
            default:
                $level_member = "Unranked";
                $badge_class = "text-bg-dark";
        }
        ?>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Anggota</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td width="200"><strong>Nama Anggota</strong></td>
                        <td width="20">:</td>
                        <td><?php echo $nama_anggota; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Level Member</strong></td>
                        <td>:</td>
                        <td><span class="badge <?php echo $badge_class; ?> fs-6"><?php echo $level_member; ?></span></td>
                    </tr>
                    <tr>
                        <td><strong>Total Pinjaman Saat Ini</strong></td>
                        <td>:</td>
                        <td><?php echo $total_pinjaman; ?> buku</td>
                    </tr>
                </table>
            </div>
        </div>

        <h4 class="mb-3">Status Saat Ini</h4>
        
        <?php 
        // Logic nampilin alert beda warna berdasarkan status
        if ($bisa_pinjam) { 
        ?>
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <div>
                    <strong>Aman Bro!</strong> Member diperbolehkan meminjam buku.
                </div>
            </div>
        <?php 
        } else { 
        ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <div>
                    <h5 class="alert-heading fw-bold">Peringatan: Tidak Boleh Pinjam!</h5>
                    <hr>
                    <p class="mb-0"><?php echo $pesan_peringatan; ?></p>
                </div>
            </div>
        <?php 
        } 
        ?>

    </div>
</body>
</html>