<?php
//hanya membenarkan admin sahaja yang boleh akses fail
if(empty($_SESSION['jenis_pengguna']) || $_SESSION['jenis_pengguna'] != "admin"){
    die("<script>alert('sila login'); 
    window.location.href='logout.php';</script>");
}
?>