<?php
session_start(); 
include('header.php');             
include('kawalan-admin.php');      
include('connection.php');         

// Semak kewujudan data GET
if (empty($_GET)) { 
    die("<script>window.location.href='pengguna-senarai.php';</script>"); 
}
// Dapatkan maklumat pengguna berdasarkan nombor telefon
$notel = mysqli_real_escape_string($condb, $_GET['notel']);
$sql = "SELECT * FROM pengguna WHERE notel = '$notel'";
$laksana = mysqli_query($condb, $sql);
$m = mysqli_fetch_array($laksana);
?>
<h3>Kemaskini Maklumat Pengguna</h3>
<!-- Borang dalam bentuk jadual -->
<form action='pengguna-kemaskini-proses.php?notel_lama=<?= $notel ?>' method='POST'>
    <table>
        <tr>
            <td><label>Nama:</label></td>
            <td><input type='text' name='nama' value='<?= $m['nama'] ?>' required></td>
        </tr>
        <tr>
            <td><label>No. Telefon:</label></td>
<td><input type='text' name='notel' value='<?= $m['notel'] ?>' required></td>
        </tr>
        <tr>
            <td><label>Katalaluan:</label></td>
 <td><input type='text' name='katalaluan' value='<?= $m['katalaluan'] ?>' required></td>
        </tr>
        <tr>
            <td><label>Jenis Pengguna:</label></td>
            <td>
                <select name='jenis_pengguna' required>
                        <option value='<?= $m['jenis_pengguna'] ?>'> 
                        <?= $m['jenis_pengguna'] ?></option>

<?php 
// Dapatkan senarai jenis pengguna tanpa ulangan
$arahan_sql = "SELECT jenis_pengguna FROM pengguna GROUP BY jenis_pengguna ORDER BY jenis_pengguna";
$laksana_arahan = mysqli_query($condb, $arahan_sql);

while ($n = mysqli_fetch_array($laksana_arahan)) {
    if ($n['jenis_pengguna'] != $m['jenis_pengguna']) {
       echo "<option value='" . $n['jenis_pengguna'] . "'>
            " . $n['jenis_pengguna'] . "</option>";
    }
}
?>
</select>
</td>
</tr>
<tr>
    <td colspan="2" align="center">
        <input type='submit' value='Kemaskini'>
    </td>
</tr>
</table>

</form>
<?php include('footer.php'); ?>