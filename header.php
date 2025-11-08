<?php
$tarikh = "2025-10-10 23:59:59"; // tarikh akhir mengundi 
?>

<!-- Tajuk sistem. Akan dipaparkan atas antaramuka -->
 <h1 align='center'>MyVoteSecure - Undian Online</h1>
 <hr>

<?php if (!empty($_SESSION['jenis_pengguna'])) { ?>  
<!-- Menu navigasi untuk pengguna yang telah log masuk -->
<?php if ($_SESSION['jenis_pengguna'] == "admin") { ?>
    | <a href='index.php'>Laman Utama</a>
    | <a href='pengguna-senarai.php'>Senarai Pengguna</a>
    | <a href='calon-senarai.php'>Senarai Calon</a>
    | <a href='laporan.php'>Laporan Pengundian</a>
<?php } elseif ($_SESSION['jenis_pengguna'] == "pengundi") { ?>
    | <a href='index.php'>Laman Utama</a>
    | <a href='undi-calon.php'>Undi Calon</a>
<?php } ?>
    | <a href='logout.php'>Logout</a>
<?php } else { ?>
<!-- Menu navigasi untuk pengguna yang belum log masuk -->
    | <a href='index.php'>Laman Utama</a>
    | <a href='login.php'>Log Masuk</a>
    | <a href='signup.php'>Daftar Sini</a>
<?php } ?>
<hr>