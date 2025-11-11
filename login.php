<?php
session_start();
include('header.php');
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data daripada Borang
    $notel      =   mysqli_real_escape_string($condb, $_POST['notel']);
    $katalaluan =   mysqli_real_escape_string($condb, $_POST['katalaluan']);

    // Proses login pengguna
    $query = "SELECT notel, nama, jenis_pengguna 
    FROM pengguna 
    WHERE notel = '$notel' AND katalaluan = '$katalaluan'";
    $result = mysqli_query($condb, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $_SESSION['notel']          = $row['notel'];
        $_SESSION['nama']          = $row['nama'];
        $_SESSION['jenis_pengguna'] = $row['jenis_pengguna'];

        echo "<script> 
                alert('Login berjaya! Selamat datang.'); 
                window.location.href='index.php';
              </script>";
        exit;
    } else {
        $err = "<div class='alert alert-danger'>
                    <i class='fas fa-exclamation-circle'></i>
                    <span><strong>Login Gagal!</strong><br>Sila semak No Telefon dan Katalaluan anda.</span>
                </div>";
    }
}
?>

<div class="container-narrow">
    <div class="card fade-in">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-sign-in-alt"></i> Daftar Masuk Pengguna
            </h2>
        </div>

        <?php if (!empty($err)) echo $err; ?>

        <form action="" method="POST">
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
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-lock"></i> Katalaluan
                </label>
                <div style="position: relative;">
                    <input 
                        type="password" 
                        name="katalaluan" 
                        id="password-login"
                        class="form-control" 
                        placeholder="Masukkan katalaluan anda"
                        required
                        style="padding-right: 45px;"
                    >
                    <button 
                        type="button" 
                        onclick="togglePassword('password-login', this)"
                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-light); font-size: 1.2rem; padding: 5px;"
                        title="Tunjuk/Sembunyi Katalaluan"
                    >
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Daftar Masuk
                </button>
            </div>
        </form>

        <div class="text-center mt-3">
            <p style="color: var(--text-light);">
                Belum mempunyai akaun? 
                <a href="signup.php" style="color: var(--primary-dark); font-weight: 600; transition: color 0.3s ease;" onmouseover="this.style.color='#1e3a8a'" onmouseout="this.style.color='var(--primary-dark)'">
                    Daftar di sini
                </a>
            </p>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>