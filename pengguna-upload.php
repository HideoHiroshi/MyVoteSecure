<?php
session_start();
include('header.php');
include('kawalan-admin.php');

// Process file upload
$upload_message = '';
$upload_success = false;

if (isset($_POST['upload'])) {
    include('connection.php');

    // Dapatkan maklumat fail yang dimuat naik
    $namafailsementara = $_FILES["data_pengguna"]["tmp_name"];
    $namafail = $_FILES["data_pengguna"]["name"];
    $jenisfail = pathinfo($namafail, PATHINFO_EXTENSION);

    // Semak saiz fail dan jenis fail
    if ($_FILES["data_pengguna"]["size"] > 0 && strtolower($jenisfail) == "txt") {

        // Buka fail txt
        $fail_data_pengguna = fopen($namafailsementara, "r");
        $bil_berjaya = 0;
        $bil_gagal = 0;
        $mesej_ralat = [];

        // Baca setiap baris dalam fail txt
        while (!feof($fail_data_pengguna)) {
            $baris = fgets($fail_data_pengguna);
            $pecahkanbaris = explode("|", $baris);

            // Semak jika baris lengkap dengan 3 nilai
            if (count($pecahkanbaris) < 3) continue;

            // Pecahkan kepada 3 kumpulan: notel, nama, katalaluan
            list($notel, $nama, $katalaluan) = array_map('trim', $pecahkanbaris);

            // Validate phone number
            if (strlen($notel) < 10 || strlen($notel) > 13) {
                $mesej_ralat[] = "Notel $notel tidak sah (mestilah 10-13 digit)";
                $bil_gagal++;
                continue;
            }

            // Semak jika nombor telefon telah wujud dalam sistem
            $semak = mysqli_query($condb, "SELECT * FROM pengguna WHERE notel='$notel'");

            if (mysqli_num_rows($semak) == 1) {
                $mesej_ralat[] = "Notel $notel telah digunakan";
                $bil_gagal++;
            } else {
                // Masukkan ke dalam pangkalan data
                $arahan_sql_simpan = "INSERT INTO pengguna 
                (notel, nama, katalaluan, jenis_pengguna) 
                VALUES ('$notel', '$nama', '$katalaluan', 'pengundi')";
                
                if (mysqli_query($condb, $arahan_sql_simpan)) {
                    $bil_berjaya++;
                } else {
                    $mesej_ralat[] = "Gagal memasukkan data untuk $nama";
                    $bil_gagal++;
                }
            }
        }
        
        fclose($fail_data_pengguna);
        
        $upload_success = $bil_berjaya > 0;
        $upload_message = "Import selesai. Berjaya: $bil_berjaya rekod. Gagal: $bil_gagal rekod.";
        
    } else {
        $upload_message = "Hanya fail berformat TXT sahaja dibenarkan.";
    }
}
?>

<div class="container-narrow">
    <div class="card fade-in">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-upload"></i> Muat Naik Data Pengundi
            </h2>
        </div>

        <?php if ($upload_message != '') { ?>
            <div class="alert <?php echo $upload_success ? 'alert-success' : 'alert-danger'; ?>">
                <i class="fas fa-<?php echo $upload_success ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                <div>
                    <strong><?php echo $upload_message; ?></strong>
                    <?php if (isset($mesej_ralat) && count($mesej_ralat) > 0 && count($mesej_ralat) <= 10) { ?>
                        <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                            <?php foreach ($mesej_ralat as $ralat) { ?>
                                <li><?php echo htmlspecialchars($ralat); ?></li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
            </div>
            
            <?php if ($upload_success) { ?>
                <div class="text-center" style="margin: 20px 0;">
                    <a href="pengguna-senarai.php" class="btn btn-primary">
                        <i class="fas fa-list"></i> Lihat Senarai Pengguna
                    </a>
                </div>
            <?php } ?>
        <?php } ?>

        <!-- Upload Instructions -->
        <div style="background: var(--bg-light); padding: 20px; border-radius: var(--radius-md); margin-bottom: 25px;">
            <h4 style="margin: 0 0 15px 0; color: var(--text-dark);">
                <i class="fas fa-info-circle"></i> Format Fail
            </h4>
            <p style="margin: 0 0 10px 0; color: var(--text-dark);">
                Fail TXT mestilah mengikut format berikut (setiap baris):
            </p>
            <code style="display: block; background: white; padding: 15px; border-radius: 6px; border: 1px solid var(--border-color);">
                notel|nama|katalaluan|
            </code>
            <p style="margin: 15px 0 0 0; color: var(--text-light); font-size: 0.95rem;">
                <strong>Contoh:</strong>
            </p>
            <code style="display: block; background: white; padding: 15px; border-radius: 6px; border: 1px solid var(--border-color); margin-top: 5px;">
                0123456789|Ahmad Ali|password123|<br>
                0198765432|Siti Aminah|mypass456|<br>
                0187654321|Kumar a/l Ravi|secure789|
            </code>
            <div class="alert alert-warning" style="margin-top: 15px;">
                <i class="fas fa-exclamation-triangle"></i>
                <span>
                    <strong>Penting:</strong>
                    <ul style="margin: 5px 0 0 0; padding-left: 20px;">
                        <li>Nombor telefon mestilah antara 10-13 digit</li>
                        <li>Setiap medan dipisahkan dengan simbol "|"</li>
                        <li>Setiap baris mesti diakhiri dengan "|"</li>
                        <li>Nombor telefon yang telah wujud akan diabaikan</li>
                    </ul>
                </span>
            </div>
        </div>

        <!-- Upload Form -->
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-file-alt"></i> Pilih Fail TXT
                </label>
                <div class="image-upload-container">
                    <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 15px;"></i>
                    <input 
                        type="file" 
                        name="data_pengguna" 
                        accept=".txt"
                        required
                        style="display: block; margin: 15px auto;"
                    >
                    <p style="color: var(--text-light); margin: 10px 0 0 0;">
                        Hanya fail .txt dibenarkan
                    </p>
                </div>
            </div>

            <div class="form-group" style="display: flex; gap: 10px;">
                <button type="submit" name="upload" class="btn btn-success" style="flex: 1;">
                    <i class="fas fa-upload"></i> Muat Naik
                </button>
                <a href="pengguna-senarai.php" class="btn btn-secondary" style="flex: 1; text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?php include('footer.php'); ?>