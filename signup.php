<?php
session_start();
include('header.php');
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama       =   mysqli_real_escape_string($condb, $_POST['nama']);
    $notel      =   mysqli_real_escape_string($condb, $_POST['notel']);
    $katalaluan =   mysqli_real_escape_string($condb, $_POST['katalaluan']);

    # Data validation : had atas
    if(strlen($notel) > 13){
        die("<script>
                alert('No Telefon tidak boleh melebihi 13 digit');
                window.history.back();
            </script>" ); 
    }

    # Data validation : had bawah
    if(strlen($notel) < 10){
        die("<script>
                alert('No Telefon mestilah sekurang-kurangnya 10 digit');
                window.history.back();
            </script>" ); 
    }

     # Semak notel dah wujud atau belum
    $sql_semak  =   "SELECT notel FROM pengguna WHERE notel = '$notel'";
    $laksana_semak  =   mysqli_query($condb, $sql_semak);
    if(mysqli_num_rows($laksana_semak) == 1){
        die("<script>
                alert('No Telefon telah digunakan. Sila gunakan No Telefon yang lain');
                window.history.back();
            </script>"); 
    }

    // Simpan data pengguna baru
    $query = "INSERT INTO pengguna (notel, nama, katalaluan, jenis_pengguna) 
              VALUES ('$notel', '$nama', '$katalaluan', 'pengundi')";
    
    if (mysqli_query($condb, $query)) {
        echo "<script>
                 alert('Pendaftaran Berjaya! Sila log masuk.');
                 window.location.href='login.php';
              </script>";
    } else {
        echo "<script>
                alert('Pendaftaran gagal. Sila cuba lagi.');
                window.history.back();
              </script>";
    }
}
?>

<div class="container-narrow">
    <div class="card fade-in">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-user-plus"></i> Daftar Pengguna Baru
            </h2>
        </div>

        <p class="text-center" style="color: var(--text-light); margin-bottom: 20px;">
            Sila lengkapkan maklumat di bawah untuk mendaftar
        </p>

        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-user"></i> Nama Penuh
                </label>
                <input 
                    type="text" 
                    name="nama" 
                    class="form-control" 
                    placeholder="Masukkan nama penuh anda"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-phone"></i> Nombor Telefon
                </label>
                <input 
                    type="text" 
                    name="notel" 
                    class="form-control" 
                    placeholder="Contoh: 0123456789"
                    required
                    pattern="[0-9]{10,13}"
                    title="Nombor telefon mestilah 10-13 digit"
                >
                <small style="color: var(--text-light); display: block; margin-top: 5px;">
                    * Nombor telefon mestilah antara 10-13 digit
                </small>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-lock"></i> Katalaluan
                </label>
                <input 
                    type="password" 
                    name="katalaluan" 
                    class="form-control" 
                    placeholder="Cipta katalaluan yang kuat"
                    required
                    minlength="6"
                >
                <small style="color: var(--text-light); display: block; margin-top: 5px;">
                    * Katalaluan mestilah sekurang-kurangnya 6 aksara
                </small>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success btn-block">
                    <i class="fas fa-check-circle"></i> Daftar Sekarang
                </button>
            </div>
        </form>

        <div class="text-center mt-3">
            <p style="color: var(--text-light);">
                Sudah mempunyai akaun? 
                <a href="login.php" style="color: var(--primary-color); font-weight: 600;">
                    Log masuk di sini
                </a>
            </p>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>