<?php
session_start();
include('header.php');
include('connection.php');
include('kawalan-pengguna.php');

// Semak jika tarikh hari semasa telah melebihi tarikh tamat mengundi
if (strtotime(date("Y-m-d H:i:s")) > strtotime($tarikh)) {
    echo "<div style='color:red; font-weight:bold; text-align:center;'>
            Proses mengundi telah tamat
          </div>";
} else {
    echo "<div style='color:green; font-weight:bold; text-align:center;'>
            Proses mengundi masih berjalan
          </div>";
}

// umpuk nilai nombor telefon pengguna dari sesi login
$notel = $_SESSION['notel'];

// Semak sama ada pengguna telah membuat undian
$arahan_semak = "
    "
    SELECT *
    FROM undian
    JOIN calon ON undian.id_calon = calon.id_calon
    JOIN jawatan ON calon.kod_jawatan = jawatan.kod_jawatan
    WHERE undian.notel = '$notel'
";
$laksana_semak = mysqli_query($condb, $arahan_semak);

// Papar calon yang dipilih oleh pengguna
if (mysqli_num_rows($laksana_semak) > 0) {
    echo "<h3 align='center'>Anda Telah Mengundi</h3>
          <table align='center' border='1' width='50%'>
              <tr bgcolor='cyan'>
                  <th>Jawatan</th>
                  <th>Calon Dipilih</th>
              </tr>";

    while ($pilihan = mysqli_fetch_array($laksana_semak)) {
        echo "<tr>
                  <td>{$pilihan['nama_jawatan']}</td>
                  <td>{$pilihan['nama_calon']}</td>
              </tr>";
    }

    echo "</table>";
} else {
    // Papar borang untuk memilih calon jika belum mengundi
    echo "<h3 align='center'>Pilih Calon Yang Layak</h3>
          <form action='undi-proses.php' method='POST'>";

    // Ambil semua jawatan daripada pangkalan data
    $arahan_jawatan = "SELECT * FROM jawatan ORDER BY kod_jawatan";
    $laksana_jawatan = mysqli_query($condb, $arahan_jawatan);

    while ($jawatan = mysqli_fetch_array($laksana_jawatan)) {
        echo "<h3 align='center'>{$jawatan['nama_jawatan']}</h3>
        <div style='display: flex; flex-wrap: wrap; justify-content: center;'>";

// Ambil calon yang bertanding bagi jawatan tersebut
        $arahan_calon = "
            SELECT * FROM calon 
            WHERE kod_jawatan = '{$jawatan['kod_jawatan']}'
            ORDER BY nama_calon
        ";
        $laksana_calon = mysqli_query($condb, $arahan_calon);

        while ($calon = mysqli_fetch_array($laksana_calon)) {
            echo "<div style='margin: 10px; text-align: center;'>
                      <img src='gambar/{$calon['gambar']}' 
                           style =' width: 150px; 
                                    height: 150px; 
                                    border-radius: 10px;' >

                      <p>{$calon['nama_calon']}</p>

                      <label>
                          <input type='radio' name='undi_{$jawatan['kod_jawatan']}'
                          value='{$calon['id_calon']}' required> Pilih </label>
                  </div>";
        }
        echo "</div>"; 
    }
    
// Butang hantar undian
echo "<div align='center'>
         <input type='submit' value='Saya Undi'>
      </div>
</form>";
}
?>
<?php include('footer.php'); ?>