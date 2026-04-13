<?php
function hitung_total_anggota($list) { return count($list); }

function hitung_anggota_aktif($list) {
    $c = 0; foreach ($list as $a) { if ($a['status'] == 'Aktif') $c++; } return $c;
}

function hitung_rata_rata_pinjaman($list) {
    if (count($list) == 0) return 0;
    $t = 0; foreach ($list as $a) { $t += $a['total_pinjaman']; } return $t / count($list);
}

function cari_anggota_by_id($list, $id) {
    foreach ($list as $a) { if ($a['id'] == $id) return $a; } return null;
}

function cari_anggota_teraktif($list) {
    if (empty($list)) return null;
    $t = $list[0]; foreach ($list as $a) { if ($a['total_pinjaman'] > $t['total_pinjaman']) $t = $a; } return $t;
}

function filter_by_status($list, $status) {
    $res = []; foreach ($list as $a) { if ($a['status'] == $status) $res[] = $a; } return $res;
}

function validasi_email($email) { return filter_var($email, FILTER_VALIDATE_EMAIL) !== false; }

function format_tanggal_indo($tgl) {
    $bln = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $p = explode('-', $tgl); return $p[2] . ' ' . $bln[(int)$p[1]] . ' ' . $p[0];
}

// BONUS
function sort_by_nama($list) {
    usort($list, fn($a, $b) => strcmp($a['nama'], $b['nama'])); return $list;
}

function search_by_nama($list, $kw) {
    if (empty($kw)) return $list;
    return array_filter($list, fn($a) => stripos($a['nama'], $kw) !== false);
}
?>