<?php
session_start();
include('kawalan-admin.php');
include('connection.php');

// Semak kewujudan data POST
if (!empty($_POST)) {
// Mengambil dan menapis data POST
$nama       = mysqli_real_escape_string($condb, $_POST['nama']);
$notel      = mysqli_real_escape_string($condb, $_POST['notel']);
$katalaluan = mysqli_real_escape_string($condb, $_POST['katalaluan']);
$notel_lama = mysqli_real_escape_string($condb, $_GET['notel_lama']);
$jenis_pengguna = $_POST['jenis_pengguna'];
     
// had atas had bawah nombor telefon: mestilah antara 10 hingga 13 digit
    if (strlen($notel) < 10 || strlen($notel) > 13) {
        die("<script>
                alert('Ralat: Nombor telefon mesti antara 10 hingga 13 digit');
                window.history.back();
            </script>");
    }
    // Arahan SQL untuk kemaskini pengguna
    $arahan = "UPDATE pengguna SET
        nama = '$nama',
        notel = '$notel',
        katalaluan = '$katalaluan',
        jenis_pengguna = '$jenis_pengguna'
        WHERE notel = '$notel_lama'";

    if (mysqli_query($condb, $arahan)) {
        echo "<script>
                alert('Kemaskini Berjaya');
                window.location.href='pengguna-senarai.php';
              </script>";
    } else {
        echo "<script>
                alert('Kemaskini Gagal');
                window.history.back();
              </script>";
    }

} else {
    die("<script>
            alert('Sila lengkapkan data');
            window.location.href='pengguna-senarai.php';
        </script>");
}
?>