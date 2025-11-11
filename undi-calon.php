<?php
session_start();
include('header.php');
include('connection.php');
include('kawalan-pengguna.php');
?>

<div class="container">
<?php
// Semak jika tarikh hari semasa telah melebihi tarikh tamat mengundi
if (strtotime(date("Y-m-d H:i:s")) > strtotime($tarikh)) {
    echo "<div class='alert alert-danger' style='margin-bottom: 20px;'>
            <i class='fas fa-times-circle'></i>
            <div>
                <strong>Proses Mengundi Telah Tamat</strong>
            </div>
          </div>";
} else {
    echo "<div class='alert alert-success' style='margin-bottom: 20px;'>
            <i class='fas fa-check-circle'></i>
            <div>
                <strong>Proses Mengundi Masih Berjalan</strong>
            </div>
          </div>";
}

// umpuk nilai nombor telefon pengguna dari sesi login
$notel = $_SESSION['notel'];

// Semak sama ada pengguna telah membuat undian
$arahan_semak = "
    SELECT *
    FROM undian
    JOIN calon ON undian.id_calon = calon.id_calon
    JOIN jawatan ON calon.kod_jawatan = jawatan.kod_jawatan
    WHERE undian.notel = '$notel'
";
$laksana_semak = mysqli_query($condb, $arahan_semak);

// Papar calon yang dipilih oleh pengguna
if (mysqli_num_rows($laksana_semak) > 0) {
    echo "<div class='card fade-in'>
            <div class='card-header'>
                <h2 class='card-title'>
                    <i class='fas fa-check-circle'></i> Anda Telah Mengundi
                </h2>
            </div>
            <div class='table-container'>
                <table class='table'>
                    <thead>
                        <tr>
                            <th><i class='fas fa-briefcase'></i> Jawatan</th>
                            <th><i class='fas fa-user'></i> Calon Dipilih</th>
                        </tr>
                    </thead>
                    <tbody>";

    while ($pilihan = mysqli_fetch_array($laksana_semak)) {
        echo "<tr>
                  <td><strong>{$pilihan['nama_jawatan']}</strong></td>
                  <td>{$pilihan['nama_calon']}</td>
              </tr>";
    }

    echo "      </tbody>
                </table>
            </div>
          </div>";
} else {
    // Papar borang untuk memilih calon jika belum mengundi
    echo "<div class='card fade-in'>
            <div class='card-header'>
                <h2 class='card-title'>
                    <i class='fas fa-vote-yea'></i> Pilih Calon Yang Layak
                </h2>
            </div>
            <form action='undi-proses.php' method='POST' style='padding: 0;'>";

    // Ambil semua jawatan daripada pangkalan data
    $arahan_jawatan = "SELECT * FROM jawatan ORDER BY kod_jawatan";
    $laksana_jawatan = mysqli_query($condb, $arahan_jawatan);

    while ($jawatan = mysqli_fetch_array($laksana_jawatan)) {
        echo "<div style='padding: 20px; border-bottom: 2px solid var(--border-color);'>
                <h3 style='text-align: center; color: var(--primary-color); margin-bottom: 20px;'>
                    <i class='fas fa-briefcase'></i> {$jawatan['nama_jawatan']}
                </h3>
                <div style='display: flex; flex-wrap: wrap; justify-content: center; gap: 20px;'>";

        // Ambil calon yang bertanding bagi jawatan tersebut
        $arahan_calon = "
            SELECT * FROM calon 
            WHERE kod_jawatan = '{$jawatan['kod_jawatan']}'
            ORDER BY nama_calon
        ";
        $laksana_calon = mysqli_query($condb, $arahan_calon);

        while ($calon = mysqli_fetch_array($laksana_calon)) {
            echo "<div class='candidate-card' style='margin: 0;'>
                      <img src='gambar/{$calon['gambar']}' 
                           alt='{$calon['nama_calon']}'
                           style='width: 150px; 
                                  height: 150px; 
                                  border-radius: 10px;
                                  object-fit: cover;
                                  border: 3px solid var(--border-color);'>
                      <p style='margin: 10px 0; font-weight: 600;'>{$calon['nama_calon']}</p>
                      <label style='cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 5px;'>
                          <input type='radio' name='undi_{$jawatan['kod_jawatan']}'
                                 value='{$calon['id_calon']}' required>
                          <span>Pilih</span>
                      </label>
                  </div>";
        }
        echo "</div></div>"; 
    }
    
    // Butang hantar undian
    echo "<div style='text-align: center; padding: 25px;'>
             <button type='submit' class='btn btn-success' style='font-size: 1.1rem; padding: 12px 40px;'>
                <i class='fas fa-check-circle'></i> Saya Undi
             </button>
          </div>
        </form>
      </div>";
}
?>
</div> <!-- Close container -->
<?php include('footer.php'); ?>