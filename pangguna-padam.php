<?php
session_start();
include('kawalan-admin.php');

if (!empty($_GET)) {
    include('connection.php');
    // Proses padam pengguna
    $arahan = "delete from pengguna where notel='" . $_GET['notel'] . "'";
    if (mysqli_query($condb, $arahan)) {
        echo "<script>alert('Padam data Berjaya'); 
        window.location.href='pengguna-senarai.php';</script>";
    } else {
        echo "<script>alert('Padam data gagal'); 
        window.location.href='pengguna-senarai.php';</script>";
    }
} else {
    die("<script>alert('Ralat! akses secara terus'); 
    window.location.href='pengguna-senarai.php';</script>");
}
?>