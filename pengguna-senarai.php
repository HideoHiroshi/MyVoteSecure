<?php
session_start();
include('header.php');
include('connection.php');
include('kawalan-admin.php');
?>

<h3 align='center'>Senarai Pengguna</h3>
<table align='center' width='100%' border='1' id='saiz'> 
    <tr bgcolor='cyan'>
        <td colspan='1'>
            <!-- Borang carian pengguna -->
            <form action='' method='POST' style="margin:0; padding:0;">
                <input type='text' name='nama' placeholder='Carian Nama Pengguna'>
                <input type='submit' value='Cari'>
            </form>
        </td>
        <td colspan='4' align='right'>
            | <a href='pengguna-upload.php'>Muat Naik Data Pengundi</a> |
            <?php include('butang-saiz.php'); ?>
        </td>
    </tr>
    <tr bgcolor='yellow'> 
        <td width='35%'>Nama</td> 
        <td width='15%'>Nombor Telefon</td> 
        <td width='10%'>Katalaluan</td> 
        <td width='10%'>Jenis Pengguna</td> 
        <td width='20%'>Tindakan</td>
    </tr> 

<?php 
// Tambah syarat dalam carian pengguna melalui nama
$tambahan = !empty($_POST['nama']) 
    ? " WHERE pengguna.nama LIKE '%" . $_POST['nama'] . "%'" : "";

// Arahan SQL untuk carian data pengguna
$arahan_papar = "SELECT * FROM pengguna $tambahan ORDER BY jenis_pengguna"; 
$laksana = mysqli_query($condb, $arahan_papar); 

// Paparkan setiap rekod pengguna
while ($m = mysqli_fetch_array($laksana)) { 
    echo "<tr> 
        <td>".$m['nama']."</td> 
        <td>".$m['notel']."</td> 
        <td>".$m['katalaluan']."</td>
        <td>".$m['jenis_pengguna']."</td>
        <td>

| <a href='pengguna-kemaskini-borang.php?notel=".$m['notel']."'> Kemaskini </a>

| <a href='pengguna-padam.php?notel=".$m['notel']."' 
     onClick=\"return confirm('Anda pasti anda ingin memadam data ini.')\">Hapus</a> |

        </td>
    </tr>"; 
}
?> 
</table>
<?php include('footer.php'); ?>