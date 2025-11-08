<?php
session_start();
include('header.php');
include('connection.php');
include('kawalan-admin.php');
?>

<div class="container">
    <div class="card fade-in">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-users"></i> Senarai Pengguna
            </h2>
        </div>

        <!-- Table Container -->
        <div class="table-container">
            <!-- Table Header with Search and Actions -->
            <div class="table-header">
                <div class="search-box">
                    <form action="" method="POST" style="display: flex; gap: 10px; flex: 1;">
                        <input 
                            type="text" 
                            name="nama" 
                            placeholder="🔍 Cari nama pengguna..."
                            value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>"
                            style="flex: 1; min-width: 200px;"
                        >
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <?php if (isset($_POST['nama']) && $_POST['nama'] != '') { ?>
                            <a href="pengguna-senarai.php" class="btn btn-secondary btn-sm">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        <?php } ?>
                    </form>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <a href="pengguna-upload.php" class="btn btn-success btn-sm">
                        <i class="fas fa-upload"></i> Muat Naik Data
                    </a>
                    <div class="font-controls">
                        <span style="color: var(--text-light); font-size: 0.875rem;">Saiz Teks:</span>
                        <button onclick="ubahsaiz(2)" title="Reset">
                            <i class="fas fa-redo"></i>
                        </button>
                        <button onclick="ubahsaiz(1)" title="Besarkan">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button onclick="ubahsaiz(-1)" title="Kecilkan">
                            <i class="fas fa-minus"></i>
                        </button>
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
                        <th><i class="fas fa-user"></i> Nama</th>
                        <th><i class="fas fa-phone"></i> Nombor Telefon</th>
                        <th><i class="fas fa-key"></i> Katalaluan</th>
                        <th><i class="fas fa-user-tag"></i> Jenis Pengguna</th>
                        <th><i class="fas fa-cog"></i> Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Tambah syarat dalam carian pengguna melalui nama
                    $tambahan = !empty($_POST['nama']) 
                        ? " WHERE pengguna.nama LIKE '%" . mysqli_real_escape_string($condb, $_POST['nama']) . "%'" 
                        : "";

                    // Arahan SQL untuk carian data pengguna
                    $arahan_papar = "SELECT * FROM pengguna $tambahan ORDER BY jenis_pengguna, nama"; 
                    $laksana = mysqli_query($condb, $arahan_papar); 

                    if (mysqli_num_rows($laksana) == 0) {
                        echo "<tr><td colspan='5' class='text-center' style='padding: 30px; color: var(--text-light);'>
                                <i class='fas fa-inbox' style='font-size: 2rem; display: block; margin-bottom: 10px;'></i>
                                Tiada rekod pengguna dijumpai
                              </td></tr>";
                    }

                    // Paparkan setiap rekod pengguna
                    while ($m = mysqli_fetch_array($laksana)) { 
                        $badge_class = $m['jenis_pengguna'] == 'admin' ? 'badge-admin' : 'badge-pengundi';
                    ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($m['nama']); ?></strong>
                            </td>
                            <td><?php echo htmlspecialchars($m['notel']); ?></td>
                            <td>
                                <span style="font-family: monospace; background: var(--bg-light); padding: 4px 8px; border-radius: 4px;">
                                    <?php echo htmlspecialchars($m['katalaluan']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?php echo $badge_class; ?>">
                                    <i class="fas fa-<?php echo $m['jenis_pengguna'] == 'admin' ? 'user-shield' : 'user'; ?>"></i>
                                    <?php echo ucfirst($m['jenis_pengguna']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="pengguna-kemaskini-borang.php?notel=<?php echo urlencode($m['notel']); ?>" 
                                       class="action-edit"
                                       title="Kemaskini">
                                        <i class="fas fa-edit"></i> Kemaskini
                                    </a>
                                    <a href="pengguna-padam.php?notel=<?php echo urlencode($m['notel']); ?>" 
                                       class="action-delete"
                                       onclick="return confirmDelete('Anda pasti ingin memadam pengguna <?php echo htmlspecialchars($m['nama']); ?>?')"
                                       title="Hapus">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
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
                <i class="fas fa-info-circle"></i> Jumlah Pengguna: <strong><?php echo $total; ?></strong>
            </div>
        <?php } ?>
    </div>
</div>

<?php include('footer.php'); ?>