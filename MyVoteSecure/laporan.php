<?php
session_start();
include('header.php');
include('connection.php');
?>

<!-- Tajuk -->
<h3 align='center'>Laporan Keputusan Undian</h3>

<!-- Borang pilihan jawatan -->
<form method='POST' align='center'>
    <select name='kod_jawatan' required>
        <option value='' disabled selected>Pilih Jawatan</option>
        <option value='semua'>KEPUTUSAN UNDIAN</option>
        <?php
        // Paparkan semua jawatan dalam dropdown
        $arahan_jawatan = "SELECT * FROM jawatan ORDER BY kod_jawatan";
        $laksana_jawatan = mysqli_query($condb, $arahan_jawatan);
        while ($jawatan = mysqli_fetch_array($laksana_jawatan)) {
            echo "<option value='".$jawatan['kod_jawatan']."'>
                  ".$jawatan['nama_jawatan']."</option>";
        }
        ?>
    </select>
    <input type='submit' value='Papar'>
</form>

<?php
// Proses apabila borang dihantar
if (!empty($_POST['kod_jawatan'])) {

    // Penapisan asas input
    $kod_jawatan = mysqli_real_escape_string($condb, $_POST['kod_jawatan']); 

    // Tambah kod paparan keputusan di sini jika perlu
}
?>
<?php
// Jika paparan untuk semua jawatan
    if ($kod_jawatan === 'semua') {
        echo "<div align='center'><h3>Pemenang Undian Jawatankuasa Kelab Komputer</h3>";

$sql_jawatan = "SELECT * FROM jawatan ORDER BY kod_jawatan";
$laksana_jawatan = mysqli_query($condb, $sql_jawatan);

        while ($jawatan = mysqli_fetch_array($laksana_jawatan)) {
echo "<h3 align='center'>".$jawatan['nama_jawatan']."</h3>";
echo "<table border='1' align='center' width='50%'>";
echo "<tr><th width='70%'>Calon</th>
                        <th width='30%'>Bilangan Undian</th></tr>";



// Paparkan calon dan bilangan undi
$arahan_calon = "
    SELECT calon.nama_calon, 
            (SELECT COUNT(*) FROM undian 
            WHERE undian.id_calon = calon.id_calon) AS bilangan_undi 
    FROM calon 
    WHERE calon.kod_jawatan = '".$jawatan['kod_jawatan']."' 
    ORDER BY bilangan_undi DESC";          
$laksana_calon = mysqli_query($condb, $arahan_calon);
// Tentukan calon tertinggi
$calon_tertinggi = [];
$undian_tertinggi = 0;

while ($calon = mysqli_fetch_array($laksana_calon)) {
    if ($calon['bilangan_undi'] > $undian_tertinggi) {
        $calon_tertinggi = [$calon['nama_calon']];
        $undian_tertinggi = $calon['bilangan_undi'];
    } elseif ($calon['bilangan_undi'] == $undian_tertinggi) {
        $calon_tertinggi[] = $calon['nama_calon'];
    }
}

// Paparkan calon yang menang
foreach ($calon_tertinggi as $nama_calon) {
    echo "<tr><td>".$nama_calon."</td>
            <td align='center'>".$undian_tertinggi."</td></tr>";
}
echo "</table></div>";
        }
// Jika paparan hanya untuk satu jawatan
} else {
    // Dapatkan maklumat jawatan
$arahan_jawatan = "SELECT * FROM jawatan 
                   WHERE kod_jawatan = '$kod_jawatan'";
    $jawatan = mysqli_fetch_array(mysqli_query($condb, $arahan_jawatan));

    echo "<div align='center'><h3>Keputusan Undian : ".$jawatan['nama_jawatan']."</h3>";
    echo "<table border='1' width='50%' align='center'>";
    echo "<tr><th width='70%'>Calon</th>
            <th width='30%'>Bilangan Undian</th></tr>";

// Dapatkan senarai calon dan bilangan undian
$arahan_calon = "
    SELECT calon.nama_calon, 
        (SELECT COUNT(*) FROM undian 
        WHERE undian.id_calon = calon.id_calon) AS bilangan_undi 
    FROM calon 
    WHERE calon.kod_jawatan = '$kod_jawatan' 
    ORDER BY bilangan_undi DESC ";

$laksana_calon = mysqli_query($condb, $arahan_calon);
while ($calon = mysqli_fetch_array($laksana_calon)) {
    echo "<tr><td>".$calon['nama_calon']."</td>
    <td align='center'>".$calon['bilangan_undi']."</td></tr>";
}
echo "</table></div>";
}
}
include('footer.php');
?>