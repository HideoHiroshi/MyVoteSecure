<?php
session_start();
include('header.php');
include('connection.php');
include('kawalan-admin.php');
?>

<div class="container">
    <!-- Candidate Registration Form -->
    <div class="card fade-in">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-user-plus"></i> Pendaftaran Calon Baru
            </h2>
        </div>

        <form action="calon-daftar-proses.php" method="POST" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-user"></i> Nama Calon
                    </label>
                    <input 
                        type="text" 
                        name="nama_calon_baru" 
                        class="form-control" 
                        placeholder="Masukkan nama calon"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-briefcase"></i> Jawatan
                    </label>
                    <div style="display: flex; gap: 10px;">
                        <select name="jawatan_calon_baru" class="form-control" required style="flex: 1;">
                            <option value="" disabled selected>Pilih Jawatan</option>
                            <?php
                            // Papar senarai jawatan daripada jadual 'jawatan'
                            $jawatan_query = "SELECT * FROM jawatan ORDER BY kod_jawatan";
                            $jawatan_result = mysqli_query($condb, $jawatan_query);
                            while ($jawatan = mysqli_fetch_array($jawatan_result)) {
                                echo "<option value='" . $jawatan['kod_jawatan'] . "'>"
                                    . htmlspecialchars($jawatan['nama_jawatan']) . "</option>";
                            }
                            ?>
                        </select>
                        <a href="jawatan-tambah.php" class="btn btn-primary btn-sm" style="text-decoration: none;" title="Tambah jawatan baru">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-image"></i> Gambar Calon
                    </label>
                    <input 
                        type="file" 
                        name="gambar_calon" 
                        class="form-control" 
                        accept="image/*" 
                        required
                    >
                    <small style="color: var(--text-light); display: block; margin-top: 5px;">
                        * Format: JPG, JPEG, PNG, GIF
                    </small>
                </div>
            </div>

            <div class="form-group text-center">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check-circle"></i> Daftar Calon
                </button>
            </div>
        </form>
    </div>

    <!-- Candidates List -->
    <div class="card fade-in" style="margin-top: 30px;">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-list"></i> Senarai Calon
            </h2>
        </div>

        <div class="table-container">
            <!-- Search and Controls -->
            <div class="table-header">
                <div class="search-box">
                    <form action="" method="POST" style="display: flex; gap: 10px; flex: 1;">
                        <input 
                            type="text" 
                            name="nama_calon" 
                            placeholder="🔍 Cari nama calon..."
                            value="<?php echo isset($_POST['nama_calon']) ? htmlspecialchars($_POST['nama_calon']) : ''; ?>"
                            style="flex: 1; min-width: 200px;"
                        >
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <?php if (isset($_POST['nama_calon']) && $_POST['nama_calon'] != '') { ?>
                            <a href="calon-senarai.php" class="btn btn-secondary btn-sm">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        <?php } ?>
                    </form>
                </div>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <div class="font-controls">
                        <span style="color: var(--text-light); font-size: 0.875rem;">Saiz:</span>
                        <button onclick="ubahsaiz(2)"><i class="fas fa-redo"></i></button>
                        <button onclick="ubahsaiz(1)"><i class="fas fa-plus"></i></button>
                        <button onclick="ubahsaiz(-1)"><i class="fas fa-minus"></i></button>
                    </div>
                    <button onclick="window.print()" class="btn btn-secondary btn-sm">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                </div>
            </div>

            <!-- Table -->
            <table class="table">
                <thead>
                    <tr>
                        <th><i class="fas fa-image"></i> Gambar</th>
                        <th><i class="fas fa-user"></i> Nama Calon</th>
                        <th><i class="fas fa-briefcase"></i> Jawatan</th>
                        <th><i class="fas fa-cog"></i> Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Tambahan untuk carian nama calon
                    $tambahan = !empty($_POST['nama_calon']) 
                        ? " AND calon.nama_calon LIKE '%" . mysqli_real_escape_string($condb, $_POST['nama_calon']) . "%'" 
                        : "";

                    // Papar calon berserta jawatan mereka
                    $arahan_papar = "SELECT calon.*, jawatan.nama_jawatan 
                                     FROM calon 
                                     JOIN jawatan ON calon.kod_jawatan = jawatan.kod_jawatan 
                                     WHERE 1=1 $tambahan 
                                     ORDER BY jawatan.kod_jawatan, calon.nama_calon"; 
                    $laksana = mysqli_query($condb, $arahan_papar);

                    if (mysqli_num_rows($laksana) == 0) {
                        echo "<tr><td colspan='4' class='text-center' style='padding: 30px; color: var(--text-light);'>
                                <i class='fas fa-inbox' style='font-size: 2rem; display: block; margin-bottom: 10px;'></i>
                                Tiada calon dijumpai
                              </td></tr>";
                    }

                    while ($m = mysqli_fetch_array($laksana)) { 
                    ?>
                        <tr>
                            <td>
                                <img 
                                    src="gambar/<?php echo htmlspecialchars($m['gambar']); ?>" 
                                    alt="<?php echo htmlspecialchars($m['nama_calon']); ?>"
                                    style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 3px solid var(--border-color);"
                                    onerror="this.src='https://via.placeholder.com/80?text=No+Image'"
                                >
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($m['nama_calon']); ?></strong>
                            </td>
                            <td>
                                <span class="badge badge-active">
                                    <i class="fas fa-briefcase"></i>
                                    <?php echo htmlspecialchars($m['nama_jawatan']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="calon-padam.php?id_calon=<?php echo $m['id_calon']; ?>" 
                                   class="action-delete"
                                   onclick="return confirmDelete('Anda pasti ingin memadam calon <?php echo htmlspecialchars($m['nama_calon']); ?>?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <?php
        $total = mysqli_num_rows($laksana);
        if ($total > 0) {
        ?>
            <div style="padding: 15px; background: var(--bg-light); border-top: 2px solid var(--border-color); text-align: center; color: var(--text-dark); font-weight: 500;">
                <i class="fas fa-info-circle"></i> Jumlah Calon: <strong><?php echo $total; ?></strong>
            </div>
        <?php } ?>
    </div>
</div>

<?php include('footer.php'); ?>