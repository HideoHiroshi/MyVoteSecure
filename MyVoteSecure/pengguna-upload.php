<?php
session_start();
include('header.php');
include('kawalan-admin.php');
?>
<!-- Borang muat naik fail pengguna -->
<h3 align="center">Muat Naik Data Pengundi (*.txt)</h3>
<form action='' method='POST' enctype='multipart/form-data' style="text-align:center">
    <p><b>Sila Pilih Fail TXT yang ingin dimuat naik</b></p>
    <input type='file' name='data_pengguna' required><br><br>
    <button type='submit' name='upload'>Muat Naik</button>
</form>
<?php include('footer.php'); ?>

<?php
// Menyemak kewujudan data POST
if (isset($_POST['upload'])) {
    include('connection.php');

    // Dapatkan maklumat fail yang dimuat naik
    $namafailsementara = $_FILES["data_pengguna"]["tmp_name"];
    $namafail = $_FILES["data_pengguna"]["name"];
    $jenisfail = pathinfo($namafail, PATHINFO_EXTENSION);

    // Semak saiz fail dan jenis fail
    if ($_FILES["data_pengguna"]["size"] > 0 && $jenisfail == "txt") {

        // Buka fail txt
        $fail_data_pengguna = fopen($namafailsementara, "r");
        $bil = 0;

        // Baca setiap baris dalam fail txt
        while (!feof($fail_data_pengguna)) {
            $baris = fgets($fail_data_pengguna);
            $pecahkanbaris = explode("|", $baris);

            // Semak jika baris lengkap dengan 3 nilai
            if (count($pecahkanbaris) < 3) continue;

// Pecahkan kepada 3 kumpulan: notel, nama, katalaluan
list($notel, $nama, $katalaluan) = array_map('trim', $pecahkanbaris);

// Semak jika nombor telefon telah wujud dalam sistem
    $semak = mysqli_query($condb, "SELECT * FROM pengguna 
    WHERE notel='$notel'");

    if (mysqli_num_rows($semak) == 1) {
        echo "<script>alert('Notel $notel telah digunakan. Sila ubah di fail TXT.');
              </script>";
    } else {
        // Masukkan ke dalam pangkalan data
        $arahan_sql_simpan = "INSERT INTO pengguna 
        (notel, nama, katalaluan, jenis_pengguna) 
        VALUES ('$notel', '$nama', '$katalaluan', 'pengundi')";
        mysqli_query($condb, $arahan_sql_simpan);
        $bil++;
    }
}
    fclose($fail_data_pengguna);
    echo "<script>
            alert('Import data selesai. Sebanyak $bil rekod berjaya disimpan.');
            window.location.href='pengguna-senarai.php';
            </script>";
} else {
    echo "<script>alert('Hanya fail berformat TXT sahaja dibenarkan.');</script>";
}
}
?>