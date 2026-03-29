<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perhitungan Diskon - Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Sistem Perhitungan Diskon Bertingkat</h1>

        <?php
        // TODO: Isi data pembeli dan buku di sini
        $nama_pembeli = "Budi Santoso";
        $judul_buku = "Laravel Advanced";
        $harga_satuan = 150000;
        $jumlah_beli = 4;
        $is_member = true; // true atau false

        // TODO: Hitung subtotal
        $subtotal = $harga_satuan * $jumlah_beli;

        // TODO: Tentukan persentase diskon berdasarkan jumlah
        $persentase_diskon = 0;
        if ($jumlah_beli >= 3 && $jumlah_beli <= 5) {
            $persentase_diskon = 0.10; // 10%
        } elseif ($jumlah_beli >= 6 && $jumlah_beli <= 10) {
            $persentase_diskon = 0.15; // 15%
        } elseif ($jumlah_beli > 10) {
            $persentase_diskon = 0.20; // 20%
        }

        // TODO: Hitung diskon
        $diskon = $subtotal * $persentase_diskon;

        // TODO: Total setelah diskon pertama
        $total_setelah_diskon1 = $subtotal - $diskon;

        // TODO: Hitung diskon member jika member
        $diskon_member = 0;
        if ($is_member) {
            // Diskon member dihitung dari total SETELAH diskon pertama
            $diskon_member = $total_setelah_diskon1 * 0.05; 
        }

        // TODO: Total setelah semua diskon
        $total_setelah_diskon = $total_setelah_diskon1 - $diskon_member;

        // TODO: Hitung PPN
        $ppn = $total_setelah_diskon * 0.11; // 11%

        // TODO: Total akhir
        $total_akhir = $total_setelah_diskon + $ppn;

        // TODO: Total penghematan
        $total_hemat = $diskon + $diskon_member;

        // Helper function biar nampilin Rupiah rapi dan code gak DRY (Don't Repeat Yourself)
        function rp($angka) {
            return "Rp " . number_format($angka, 0, ',', '.');
        }
        ?>

        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">Invoice Pembelian</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                            <div>
                                <h5 class="mb-1 fw-bold"><?= $nama_pembeli ?></h5>
                            </div>
                            <div>
                                <?php if($is_member): ?>
                                    <span class="badge bg-success px-3 py-2">Member Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary px-3 py-2">Non-Member</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <table class="table table-borderless table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Judul Buku</td>
                                    <td class="text-end fw-bold"><?= $judul_buku ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Harga Satuan</td>
                                    <td class="text-end"><?= rp($harga_satuan) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jumlah Beli</td>
                                    <td class="text-end"><?= $jumlah_beli ?> pcs</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="pt-2"><strong>Subtotal</strong></td>
                                    <td class="text-end pt-2"><strong><?= rp($subtotal) ?></strong></td>
                                </tr>
                                
                                <?php if($diskon > 0): ?>
                                <tr>
                                    <td class="text-danger">Diskon (<?= $persentase_diskon * 100 ?>%)</td>
                                    <td class="text-end text-danger">- <?= rp($diskon) ?></td>
                                </tr>
                                <?php endif; ?>

                                <?php if($is_member): ?>
                                <tr>
                                    <td class="text-danger">Diskon Member (5%)</td>
                                    <td class="text-end text-danger">- <?= rp($diskon_member) ?></td>
                                </tr>
                                <?php endif; ?>

                                <tr class="border-top">
                                    <td class="pt-2">Total Setelah Diskon</td>
                                    <td class="text-end pt-2"><?= rp($total_setelah_diskon) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">PPN (11%)</td>
                                    <td class="text-end text-muted">+ <?= rp($ppn) ?></td>
                                </tr>
                            </tbody>
                            <tfoot class="border-top border-2">
                                <tr>
                                    <td class="pt-3"><h5 class="mb-0">Total Akhir</h5></td>
                                    <td class="text-end pt-3"><h5 class="mb-0 text-primary fw-bold"><?= rp($total_akhir) ?></h5></td>
                                </tr>
                                <tr>
                                    <td class="text-success pt-2"><small>Total Hemat</small></td>
                                    <td class="text-end text-success fw-bold pt-2"><small><?= rp($total_hemat) ?></small></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>