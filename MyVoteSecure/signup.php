<?php
include('header.php');
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama       =   mysqli_real_escape_string($condb, $_POST['nama']);
    $notel      =   mysqli_real_escape_string($condb, $_POST['notel']);
    $katalaluan =   mysqli_real_escape_string($condb, $_POST['katalaluan']);

    # Data validation : had atas
    if(strlen($notel) > 12){
        die("<script>
                alert('No Telefon Lebih 12 Digit');
                location.href='signup.php';
            </script>" ); }

    # Data validation : had bawah
    if(strlen($notel) < 10){
        die("<script>
                alert('No Telefon Kurang 10 Digit');
                location.href='signup.php';
            </script>" ); }

     # Semak notel dah wujud atau belum
    $sql_semak  =   "select notel from pengguna where notel = '$notel' ";
    $laksana_semak  =   mysqli_query($condb,$sql_semak);
    if(mysqli_num_rows($laksana_semak)==1){
        die("<script>
                alert('No Telefon telah digunakan. Sila tukar No Telefon yang lain');
                location.href='signup.php';
            </script>"); }

// Simpan data pengguna baru
    $query = "INSERT INTO pengguna
    (notel, nama, katalaluan, jenis_pengguna) 
     VALUES ('$notel', '$nama', '$katalaluan', 'pengundi')";
   if (mysqli_query($condb, $query)) {
        echo "<script>
                 alert('Pendaftaran Berjaya');
                  location.href='login.php';
              </script>";
        } else {
            echo "<script>alert('Pendaftaran gagal. Sila cuba lagi.');</script>";
            echo $sql_simpan.mysqli_error($condb);
        }
}
?>

<!-- Bahagian Borang Login -->
<h3>Daftar Pengguna Baru (Sign Up)</h3>
<p>Sila Lengkapkan Maklumat di bawah</p>
<form method='POST' action=''>
    <table border='0'>
        <tr>
            <td>No Telefon:</td>
            <td><input type='text' name='notel' required></td>
        </tr>
        <tr>
            <td>Nama:</td>
            <td><input type='text' name='nama' required></td>
        </tr>
        <tr>
            <td>Katalaluan:</td>
            <td><input type='password' name='katalaluan' required></td>
        </tr>
        <tr>
            <td colspan='2' align='center'>
                <button type='submit'>Daftar</button>
            </td>
        </tr>
    </table>
</form>
<?php include('footer.php'); ?>