<?php
session_start();
include('kawalan-admin.php');

if (!empty($_GET)) {
    include('connection.php');
    $id_calon = mysqli_real_escape_string($condb, $_GET['id_calon']);

    // proses Padam data calon
    $arahan = "delete from calon where id_calon='$id_calon'";
    
    if (mysqli_query($condb, $arahan)) {
        echo "<script>alert('Padam data Berjaya');
              window.location.href='calon-senarai.php';</script>";
    } else {
        echo "<script>alert('Padam data gagal');
              window.location.href='calon-senarai.php';</script>";
    }
} else {
    die("<script>alert('Ralat! akses secara terus'); 
          window.location.href='calon-senarai.php';</script>");
}
?>