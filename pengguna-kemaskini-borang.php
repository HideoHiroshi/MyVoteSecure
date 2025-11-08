<?php
session_start(); 
include('header.php');             
include('kawalan-admin.php');      
include('connection.php');         

// Semak kewujudan data GET
if (empty($_GET['notel'])) { 
    die("<script>
            alert('Ralat: Tiada data pengguna dipilih');
            window.location.href='pengguna-senarai.php';
        </script>"); 
}

// Dapatkan maklumat pengguna berdasarkan nombor telefon
$notel = mysqli_real_escape_string($condb, $_GET['notel']);
$sql = "SELECT * FROM pengguna WHERE notel = '$notel'";
$laksana = mysqli_query($condb, $sql);

if (mysqli_num_rows($laksana) == 0) {
    die("<script>
            alert('Ralat: Pengguna tidak dijumpai');
            window.location.href='pengguna-senarai.php';
        </script>");
}

$m = mysqli_fetch_array($laksana);
?>

<div class="container-narrow">
    <div class="card fade-in">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-user-edit"></i> Kemaskini Maklumat Pengguna
            </h2>
        </div>

        <form action="pengguna-kemaskini-proses.php?notel_lama=<?php echo urlencode($notel); ?>" method="POST">
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-user"></i> Nama Penuh
                </label>
                <input 
                    type="text" 
                    name="nama" 
                    class="form-control" 
                    value="<?php echo htmlspecialchars($m['nama']); ?>" 
                    required
                    placeholder="Masukkan nama penuh"
                >
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-phone"></i> Nombor Telefon
                </label>
                <input 
                    type="text" 
                    name="notel" 
                    class="form-control" 
                    value="<?php echo htmlspecialchars($m['notel']); ?>" 
                    required
                    pattern="[0-9]{10,13}"
                    title="Nombor telefon mestilah 10-13 digit"
                    placeholder="Contoh: 0123456789"
                >
                <small style="color: var(--text-light); display: block; margin-top: 5px;">
                    * Nombor telefon mestilah antara 10-13 digit
                </small>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-lock"></i> Katalaluan
                </label>
                <input 
                    type="text" 
                    name="katalaluan" 
                    class="form-control" 
                    value="<?php echo htmlspecialchars($m['katalaluan']); ?>" 
                    required
                    placeholder="Masukkan katalaluan"
                >
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-user-tag"></i> Jenis Pengguna
                </label>
                <select name="jenis_pengguna" class="form-control" required>
                    <option value="<?php echo htmlspecialchars($m['jenis_pengguna']); ?>" selected>
                        <?php echo ucfirst($m['jenis_pengguna']); ?> (Semasa)
                    </option>
                    <?php 
                    // Dapatkan senarai jenis pengguna tanpa ulangan
                    $arahan_sql = "SELECT jenis_pengguna FROM pengguna GROUP BY jenis_pengguna ORDER BY jenis_pengguna";
                    $laksana_arahan = mysqli_query($condb, $arahan_sql);

                    while ($n = mysqli_fetch_array($laksana_arahan)) {
                        if ($n['jenis_pengguna'] != $m['jenis_pengguna']) {
                            echo "<option value='" . htmlspecialchars($n['jenis_pengguna']) . "'>" 
                                . ucfirst($n['jenis_pengguna']) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group" style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> Kemaskini
                </button>
                <a href="pengguna-senarai.php" class="btn btn-secondary" style="flex: 1; text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>

    <!-- User Info Card -->
    <div class="card fade-in" style="margin-top: 20px; background: var(--bg-light);">
        <h4 style="margin: 0 0 15px 0; color: var(--text-dark);">
            <i class="fas fa-info-circle"></i> Maklumat Tambahan
        </h4>
        <div style="color: var(--text-light);">
            <p style="margin: 5px 0;">
                <strong>Nombor Telefon Asal:</strong> <?php echo htmlspecialchars($notel); ?>
            </p>
            <p style="margin: 5px 0;">
                <strong>Status Semasa:</strong> 
                <span class="badge <?php echo $m['jenis_pengguna'] == 'admin' ? 'badge-admin' : 'badge-pengundi'; ?>">
                    <?php echo ucfirst($m['jenis_pengguna']); ?>
                </span>
            </p>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>