<?php
$tarikh = "2025-12-31 23:59:59"; // tarikh akhir mengundi 
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyVoteSecure - Sistem Undian Online</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="site-header">      
        <h1>                      
            <img src="images/logo-sekolah.png" alt="Logo" style="height:80px;vertical-align:middle;margin-right:10px">
            <i class="fas fa-vote-yea"></i> MyVoteSecure - Undian Online
        </h1>
        
        <!-- Navigation -->
        <nav class="nav-bar">
            <ul class="nav-links">
                <?php if (!empty($_SESSION['jenis_pengguna'])) { ?>  
                    <!-- Menu for logged in users -->
                    <?php if ($_SESSION['jenis_pengguna'] == "admin") { ?>
                        <li><a href='index.php'><i class="fas fa-home"></i> Laman Utama</a></li>
                        <li><a href='pengguna-senarai.php'><i class="fas fa-users"></i> Senarai Pengguna</a></li>
                        <li><a href='calon-senarai.php'><i class="fas fa-user-tie"></i> Senarai Calon</a></li>
                        <li><a href='laporan.php'><i class="fas fa-chart-bar"></i> Laporan Pengundian</a></li>
                    <?php } elseif ($_SESSION['jenis_pengguna'] == "pengundi") { ?>
                        <li><a href='index.php'><i class="fas fa-home"></i> Laman Utama</a></li>
                        <li><a href='undi-calon.php'><i class="fas fa-check-circle"></i> Undi Calon</a></li>
                    <?php } ?>
                    <li><a href='logout.php' class="btn-danger" style="color: white; padding: 8px 16px; border-radius: 6px;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php } else { ?>
                    <!-- Menu for guests -->
                    <li><a href='index.php'><i class="fas fa-home"></i> Laman Utama</a></li>
                    <li><a href='login.php'><i class="fas fa-sign-in-alt"></i> Log Masuk</a></li>
                    <li><a href='signup.php'><i class="fas fa-user-plus"></i> Daftar Akaun</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>

    <!-- Main Content Wrapper -->
    <main class="container" id="saiz">