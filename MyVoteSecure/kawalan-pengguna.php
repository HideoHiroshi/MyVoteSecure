<?php
//hanya membenarkan pengguna berdaftar sahaja yang boleh akses fail
if(empty($_SESSION['jenis_pengguna']) || $_SESSION['jenis_pengguna'] != "pengundi"){
    die("<script>alert('sila login'); 
    window.location.href='logout.php';</script>");
}
?>