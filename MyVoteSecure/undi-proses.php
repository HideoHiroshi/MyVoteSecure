<?php
session_start();
include('connection.php');
include('kawalan-pengguna.php');

// Semak kewujudan data POST untuk proses undian
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script>
            alert('Sila buat undian terlebih dahulu');
            window.location.href='undi-calon.php';
          </script>";
    exit;
}

// Proses menyimpan data undian ke dalam pangkalan data

foreach ($_POST as $jawatan => $id_calon) {
    $id_calon = mysqli_real_escape_string($condb, $id_calon);

    //  arahan SQL untuk simpan undian
    $arahan_undi = "
        INSERT INTO undian (notel, id_calon)
        VALUES ('{$_SESSION['notel']}', '$id_calon') ";
    
    // Jika gagal, papar mesej dan kembali ke halaman undian
    if (!mysqli_query($condb, $arahan_undi)) {
        echo "<script>
                alert('Proses undian gagal');
                window.location.href='undi-calon.php';
              </script>";
        exit;
    }
}

// Jika berjaya, papar mesej berjaya dan kembali ke halaman undian
echo "<script>
        alert('Undian berjaya direkodkan');
        window.location.href='undi-calon.php';
      </script>";
?>