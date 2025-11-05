<?php
session_start();
include('connection.php');
include('kawalan-admin.php');

// Memastikan data POST tidak kosong sebelum proses pendaftaran
if (!empty($_POST)) {

    // Mengambil data dari borang
    $nama_calon = mysqli_real_escape_string($condb, $_POST['nama_calon_baru']);
    $jawatan_calon = $_POST['jawatan_calon_baru'];

    // Tetapan folder untuk simpan gambar
    $target_dir = "gambar/";
    $image_file_type = strtolower(pathinfo($_FILES['gambar_calon']['name'],    
                       PATHINFO_EXTENSION));

    // Validasi: Benarkan hanya jenis fail gambar tertentu
    if (!in_array($image_file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo "<script>alert('Hanya fail gambar (JPG, JPEG, PNG, GIF) dibenarkan'); 
        window.history.back();</script>";
        exit;
    }

    // Jana nama fail baru berdasarkan tarikh dan masa untuk elak konflik nama
    $nama_fail_baru = date("Ymd_His") . '.' . $image_file_type;
    $target_file = $target_dir . $nama_fail_baru;

    // Memuat naik imej yang dimuat naik ke folder 'gambar'
    if (move_uploaded_file($_FILES['gambar_calon']['tmp_name'], $target_file)) {

        // Arahan SQL untuk simpan data calon ke dalam pangkalan data
        $arahan_daftar = "INSERT INTO calon (nama_calon, kod_jawatan, gambar) 
                          VALUES 
                          ('$nama_calon', '$jawatan_calon', '$nama_fail_baru')";

        // Melaksanakan arahan SQL            
        if (mysqli_query($condb, $arahan_daftar)) {
            echo "<script>alert('Calon berjaya didaftarkan'); 
                  window.location.href='calon-senarai.php';</script>";
        } else {
            echo "<script>alert('Pendaftaran calon gagal');
                  window.location.href='calon-senarai.php';</script>";
        }
    } else {
        echo "<script>alert('Gagal memuat naik gambar');
               window.history.back();</script>";
    }
} else {
    echo "<script>alert('Sila isi semua maklumat');
           window.location.href='calon-senarai.php';</script>";
}
?>