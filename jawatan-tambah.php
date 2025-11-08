<?php
session_start();
include('header.php');
include('connection.php');
include('kawalan-admin.php');

// Proses Tambah Jawatan Baharu
if (!empty($_POST['nama_jawatan_baru'])) {
   
$nama_jawatan_baru = mysqli_real_escape_string($condb, $_POST['nama_jawatan_baru']);

    // Proses tambah ke jadual jawatan
    $arahan_tambah = "INSERT INTO jawatan (nama_jawatan) 
                      VALUES ('$nama_jawatan_baru')";
    if (mysqli_query($condb, $arahan_tambah)) {
        echo "<script>
                alert('Jawatan berjaya ditambah'); 
                window.location.href='jawatan-tambah.php';
              </script>";
    } else {
        echo $arahan_tambah . mysqli_error($condb);
        echo "<script>alert('Tambah jawatan gagal');</script>";
    }
}

// Proses Padam Jawatan
if (!empty($_GET['kod_jawatan'])) {
    $kod_jawatan = mysqli_real_escape_string($condb, $_GET['kod_jawatan']);
    // Proses padam berdasarkan kod_jawatan
    $arahan_padam = "DELETE FROM jawatan WHERE kod_jawatan='$kod_jawatan'";

    // Laksanakan padam
    if (mysqli_query($condb, $arahan_padam)) {
        echo "<script>
                alert('Jawatan berjaya dipadam'); 
                window.location.href='jawatan-tambah.php';
              </script>";
    } else {
        echo "<script>
                alert('Padam jawatan gagal'); 
                window.location.href='jawatan-tambah.php';
              </script>";
    }
}
?>

<!-- Borang Tambah Jawatan -->
<h3 align='center'>Senarai Jawatan</h3>

<table align='center' width='50%' border='1'>
    <caption>
        <form action='jawatan-tambah.php' method='POST' align='center'>
<input type='text' name='nama_jawatan_baru' placeholder='Nama Jawatan Baru' required>
            <input type='submit' value='Tambah'>
        </form>
    </caption>
    <tr bgcolor='cyan'>
        <td>Nama Jawatan</td>
        <td>Tindakan</td>
    </tr>
    <?php
    // Paparkan semua jawatan
    $arahan_papar = "SELECT * FROM jawatan ORDER BY kod_jawatan";
    $laksana = mysqli_query($condb, $arahan_papar);
    while ($jawatan = mysqli_fetch_array($laksana)) {
        echo "<tr>
                <td>" . $jawatan['nama_jawatan'] . "</td>
                <td>

               <a href='jawatan-tambah.php?kod_jawatan=" . $jawatan['kod_jawatan'] . "'
               onClick=\"return confirm('Anda pasti ingin memadam jawatan ini?')\">
               Hapus</a>

                </td></tr>";
    } ?>
</table>
<?php include('footer.php'); ?>