<?php
// Inisialisasi variabel kosong biar gak error 'undefined' pas form baru diload
$nama = $email = $telepon = $alamat = $jk = $tgl_lahir = $pekerjaan = "";
$errors = [];
$is_submitted = false;

// Cek apakah form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $is_submitted = true;

    // Ambil data dari form dan bersihkan spasi berlebih
    $nama = trim($_POST["nama"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telepon = trim($_POST["telepon"] ?? "");
    $alamat = trim($_POST["alamat"] ?? "");
    $jk = $_POST["jk"] ?? "";
    $tgl_lahir = $_POST["tgl_lahir"] ?? "";
    $pekerjaan = $_POST["pekerjaan"] ?? "";

    // 1. Validasi Nama (Required, min 3 karakter)
    if (empty($nama)) {
        $errors['nama'] = "Nama lengkap wajib diisi.";
    } elseif (strlen($nama) < 3) {
        $errors['nama'] = "Nama minimal 3 karakter.";
    }

    // 2. Validasi Email (Required, format email valid)
    if (empty($email)) {
        $errors['email'] = "Email wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Format email tidak valid.";
    }

    // 3. Validasi Telepon (Required, format 08..., 10-13 digit)
    if (empty($telepon)) {
        $errors['telepon'] = "Nomor telepon wajib diisi.";
    } elseif (!preg_match("/^08[0-9]{8,11}$/", $telepon)) {
        $errors['telepon'] = "Format harus diawali 08 dan berjumlah 10-13 digit.";
    }

    // 4. Validasi Alamat (Required, min 10 karakter)
    if (empty($alamat)) {
        $errors['alamat'] = "Alamat wajib diisi.";
    } elseif (strlen($alamat) < 10) {
        $errors['alamat'] = "Alamat minimal 10 karakter.";
    }

    // 5. Validasi Jenis Kelamin (Required)
    if (empty($jk)) {
        $errors['jk'] = "Jenis kelamin wajib dipilih.";
    }

    // 6. Validasi Tanggal Lahir (Required, min umur 10 tahun)
    if (empty($tgl_lahir)) {
        $errors['tgl_lahir'] = "Tanggal lahir wajib diisi.";
    } else {
        // Hitung umur pakai class DateTime bawaan PHP
        $birthDate = new DateTime($tgl_lahir);
        $today = new DateTime('today');
        $age = $birthDate->diff($today)->y;
        
        if ($age < 10) {
            $errors['tgl_lahir'] = "Umur minimal pendaftar adalah 10 tahun.";
        }
    }

    // 7. Validasi Pekerjaan (Required)
    if (empty($pekerjaan)) {
        $errors['pekerjaan'] = "Pekerjaan wajib dipilih.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <h3 class="mb-4 text-center">Registrasi Anggota Perpustakaan</h3>

            <?php if ($is_submitted && empty($errors)): ?>
                <div class="alert alert-success">
                    Registrasi berhasil dilakukan!
                </div>
                <div class="card mb-4 border-success">
                    <div class="card-header bg-success text-white">
                        Data Anggota Baru
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr><th width="30%">Nama Lengkap</th><td>: <?= htmlspecialchars($nama) ?></td></tr>
                            <tr><th>Email</th><td>: <?= htmlspecialchars($email) ?></td></tr>
                            <tr><th>Telepon</th><td>: <?= htmlspecialchars($telepon) ?></td></tr>
                            <tr><th>Alamat</th><td>: <?= nl2br(htmlspecialchars($alamat)) ?></td></tr>
                            <tr><th>Jenis Kelamin</th><td>: <?= $jk == 'L' ? 'Laki-laki' : 'Perempuan' ?></td></tr>
                            <tr><th>Tanggal Lahir</th><td>: <?= htmlspecialchars($tgl_lahir) ?></td></tr>
                            <tr><th>Pekerjaan</th><td>: <?= htmlspecialchars($pekerjaan) ?></td></tr>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="" method="POST" novalidate>
                        
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>" id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>" placeholder="Masukkan nama lengkap">
                            <div class="invalid-feedback">
                                <?= $errors['nama'] ?? '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="contoh@email.com">
                            <div class="invalid-feedback">
                                <?= $errors['email'] ?? '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="telepon" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control <?= isset($errors['telepon']) ? 'is-invalid' : '' ?>" id="telepon" name="telepon" value="<?= htmlspecialchars($telepon) ?>" placeholder="08xxxxxxxxxx">
                            <div class="invalid-feedback">
                                <?= $errors['telepon'] ?? '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control <?= isset($errors['alamat']) ? 'is-invalid' : '' ?>" id="alamat" name="alamat" rows="3"><?= htmlspecialchars($alamat) ?></textarea>
                            <div class="invalid-feedback">
                                <?= $errors['alamat'] ?? '' ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Jenis Kelamin</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= isset($errors['jk']) ? 'is-invalid' : '' ?>" type="radio" name="jk" id="jk_l" value="L" <?= $jk == 'L' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="jk_l">Laki-laki</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= isset($errors['jk']) ? 'is-invalid' : '' ?>" type="radio" name="jk" id="jk_p" value="P" <?= $jk == 'P' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="jk_p">Perempuan</label>
                            </div>
                            <?php if(isset($errors['jk'])): ?>
                                <div class="invalid-feedback d-block"><?= $errors['jk'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control <?= isset($errors['tgl_lahir']) ? 'is-invalid' : '' ?>" id="tgl_lahir" name="tgl_lahir" value="<?= htmlspecialchars($tgl_lahir) ?>">
                            <div class="invalid-feedback">
                                <?= $errors['tgl_lahir'] ?? '' ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="pekerjaan" class="form-label">Pekerjaan</label>
                            <select class="form-select <?= isset($errors['pekerjaan']) ? 'is-invalid' : '' ?>" id="pekerjaan" name="pekerjaan">
                                <option value="">-- Pilih Pekerjaan --</option>
                                <option value="Pelajar" <?= $pekerjaan == 'Pelajar' ? 'selected' : '' ?>>Pelajar</option>
                                <option value="Mahasiswa" <?= $pekerjaan == 'Mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                                <option value="Pegawai" <?= $pekerjaan == 'Pegawai' ? 'selected' : '' ?>>Pegawai</option>
                                <option value="Lainnya" <?= $pekerjaan == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                            <div class="invalid-feedback">
                                <?= $errors['pekerjaan'] ?? '' ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Daftar Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>