<?php
session_start();
include('header.php');
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Mengambil data daripada Borang di bawah
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
        $_SESSION['jenis_pengguna'] = $row['jenis_pengguna'];

        echo "<script> alert('Login berjaya!'); </script>";
        header("Location: index.php");
        exit;

    } else {

        $err = "<p style='color:red;'>Login Gagal<br>
                Semak No Telefon dan Katalaluan</p>";
    }
}
?>

<!-- Bahagian Borang Login -->
<h3>Daftar Masuk Pengguna (LOGIN)</h3>
<p>Sila Lengkapkan Maklumat di bawah</p>
<form action = '' method = 'POST'>
    <table border='0'>
    <tr>
        <td>Notel</td>
        <td><input type='text' name='notel'></td>
    </tr>
    <tr>
        <td>Katalaluan</td>
        <td><input type='password' name='katalaluan'></td>
    </tr>
    <tr>
        <td colspan='2' align='center'>
            <input type='submit' value='Daftar Masuk'>
        </td>
    </tr>
</table>       
<?php if (!empty($err)) echo $err; ?>
</form>
<?php include('footer.php'); ?>