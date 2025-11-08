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
                <i class="fas fa-chart-bar"></i> Laporan Keputusan Undian
            </h2>
        </div>

        <!-- Position Selection Form -->
        <form method="POST" style="max-width: 500px; margin: 0 auto;">
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-filter"></i> Pilih Jawatan untuk Lihat Keputusan
                </label>
                <div style="display: flex; gap: 10px;">
                    <select name="kod_jawatan" class="form-control" required>
                        <option value="" disabled selected>Pilih Jawatan</option>
                        <option value="semua" <?php echo (isset($_POST['kod_jawatan']) && $_POST['kod_jawatan'] == 'semua') ? 'selected' : ''; ?>>
                            📊 KEPUTUSAN KESELURUHAN
                        </option>
                        <?php
                        // Paparkan semua jawatan dalam dropdown
                        $arahan_jawatan = "SELECT * FROM jawatan ORDER BY kod_jawatan";
                        $laksana_jawatan = mysqli_query($condb, $arahan_jawatan);
                        while ($jawatan = mysqli_fetch_array($laksana_jawatan)) {
                            $selected = (isset($_POST['kod_jawatan']) && $_POST['kod_jawatan'] == $jawatan['kod_jawatan']) ? 'selected' : '';
                            echo "<option value='" . $jawatan['kod_jawatan'] . "' $selected>"
                                . htmlspecialchars($jawatan['nama_jawatan']) . "</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Papar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <?php
    // Proses apabila borang dihantar
    if (!empty($_POST['kod_jawatan'])) {
        $kod_jawatan = mysqli_real_escape_string($condb, $_POST['kod_jawatan']); 

        // Jika paparan untuk semua jawatan
        if ($kod_jawatan === 'semua') {
    ?>
            <div class="results-container fade-in">
                <h3 style="text-align: center; color: var(--text-dark); margin-bottom: 30px;">
                    <i class="fas fa-trophy"></i> Pemenang Undian Jawatankuasa Kelab
                </h3>

                <?php
                $sql_jawatan = "SELECT * FROM jawatan ORDER BY kod_jawatan";
                $laksana_jawatan = mysqli_query($condb, $sql_jawatan);
                $position_count = 0;

                while ($jawatan = mysqli_fetch_array($laksana_jawatan)) {
                    $position_count++;
                ?>
                    <div class="card fade-in" style="margin-bottom: 30px;">
                        <h4 style="text-align: center; color: var(--primary-color); margin-bottom: 20px; padding: 15px; background: var(--bg-light); border-radius: var(--radius-md);">
                            <i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($jawatan['nama_jawatan']); ?>
                        </h4>

                        <?php
                        // Paparkan calon dan bilangan undi
                        $arahan_calon = "
                            SELECT calon.nama_calon, calon.gambar,
                                    (SELECT COUNT(*) FROM undian 
                                    WHERE undian.id_calon = calon.id_calon) AS bilangan_undi 
                            FROM calon 
                            WHERE calon.kod_jawatan = '" . $jawatan['kod_jawatan'] . "' 
                            ORDER BY bilangan_undi DESC";          
                        $laksana_calon = mysqli_query($condb, $arahan_calon);
                        
                        // Tentukan calon tertinggi
                        $calon_tertinggi = [];
                        $undian_tertinggi = 0;
                        $all_candidates = [];

                        while ($calon = mysqli_fetch_array($laksana_calon)) {
                            $all_candidates[] = $calon;
                            if ($calon['bilangan_undi'] > $undian_tertinggi) {
                                $calon_tertinggi = [$calon];
                                $undian_tertinggi = $calon['bilangan_undi'];
                            } elseif ($calon['bilangan_undi'] == $undian_tertinggi) {
                                $calon_tertinggi[] = $calon;
                            }
                        }

                        // Display winners
                        if (count($calon_tertinggi) > 0 && $undian_tertinggi > 0) {
                        ?>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
                                <?php foreach ($calon_tertinggi as $pemenang) { ?>
                                    <div class="winner-card">
                                        <i class="fas fa-trophy" style="font-size: 2rem; color: #f59e0b; margin-bottom: 10px;"></i>
                                        <?php if (!empty($pemenang['gambar'])) { ?>
                                            <img 
                                                src="gambar/<?php echo htmlspecialchars($pemenang['gambar']); ?>" 
                                                alt="<?php echo htmlspecialchars($pemenang['nama_calon']); ?>"
                                                style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 4px solid #f59e0b; margin: 10px auto; display: block;"
                                                onerror="this.src='https://via.placeholder.com/100?text=Winner'"
                                            >
                                        <?php } ?>
                                        <div class="candidate-name" style="font-size: 1.2rem;">
                                            <?php echo htmlspecialchars($pemenang['nama_calon']); ?>
                                        </div>
                                        <div class="vote-count">
                                            <i class="fas fa-vote-yea"></i> <?php echo $undian_tertinggi; ?> Undi
                                        </div>
                                        <?php if (count($calon_tertinggi) > 1) { ?>
                                            <small style="display: block; margin-top: 10px; color: var(--warning-color); font-weight: 600;">
                                                (Seri)
                                            </small>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <!-- All candidates results -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kedudukan</th>
                                    <th>Calon</th>
                                    <th>Bilangan Undian</th>
                                    <th>Peratusan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_votes = array_sum(array_column($all_candidates, 'bilangan_undi'));
                                $rank = 0;
                                $prev_votes = -1;
                                
                                foreach ($all_candidates as $index => $calon) {
                                    if ($calon['bilangan_undi'] != $prev_votes) {
                                        $rank = $index + 1;
                                    }
                                    $prev_votes = $calon['bilangan_undi'];
                                    $percentage = $total_votes > 0 ? round(($calon['bilangan_undi'] / $total_votes) * 100, 1) : 0;
                                    $is_winner = $calon['bilangan_undi'] == $undian_tertinggi && $undian_tertinggi > 0;
                                ?>
                                    <tr style="<?php echo $is_winner ? 'background: #fef3c7; font-weight: 600;' : ''; ?>">
                                        <td>
                                            <?php if ($is_winner) { ?>
                                                <i class="fas fa-trophy" style="color: #f59e0b;"></i>
                                            <?php } ?>
                                            #<?php echo $rank; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($calon['nama_calon']); ?></td>
                                        <td><?php echo $calon['bilangan_undi']; ?></td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div style="flex: 1; background: var(--bg-light); height: 20px; border-radius: 10px; overflow: hidden;">
                                                    <div style="width: <?php echo $percentage; ?>%; height: 100%; background: <?php echo $is_winner ? '#f59e0b' : 'var(--primary-color)'; ?>; transition: width 0.5s;"></div>
                                                </div>
                                                <span style="min-width: 50px; font-weight: 600;"><?php echo $percentage; ?>%</span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
    <?php
        // Jika paparan hanya untuk satu jawatan
        } else {
            // Dapatkan maklumat jawatan
            $arahan_jawatan = "SELECT * FROM jawatan WHERE kod_jawatan = '$kod_jawatan'";
            $jawatan = mysqli_fetch_array(mysqli_query($condb, $arahan_jawatan));
    ?>
            <div class="results-container fade-in">
                <h3 style="text-align: center; color: var(--text-dark); margin-bottom: 30px;">
                    <i class="fas fa-poll"></i> Keputusan Undian: <?php echo htmlspecialchars($jawatan['nama_jawatan']); ?>
                </h3>

                <?php
                // Dapatkan senarai calon dan bilangan undian
                $arahan_calon = "
                    SELECT calon.nama_calon, calon.gambar,
                        (SELECT COUNT(*) FROM undian 
                        WHERE undian.id_calon = calon.id_calon) AS bilangan_undi 
                    FROM calon 
                    WHERE calon.kod_jawatan = '$kod_jawatan' 
                    ORDER BY bilangan_undi DESC ";

                $laksana_calon = mysqli_query($condb, $arahan_calon);
                $all_candidates = [];
                $total_votes = 0;
                $max_votes = 0;

                while ($calon = mysqli_fetch_array($laksana_calon)) {
                    $all_candidates[] = $calon;
                    $total_votes += $calon['bilangan_undi'];
                    if ($calon['bilangan_undi'] > $max_votes) {
                        $max_votes = $calon['bilangan_undi'];
                    }
                }

                // Display winner(s)
                $winners = array_filter($all_candidates, function($c) use ($max_votes) {
                    return $c['bilangan_undi'] == $max_votes && $max_votes > 0;
                });

                if (count($winners) > 0) {
                ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
                        <?php foreach ($winners as $pemenang) { ?>
                            <div class="winner-card">
                                <i class="fas fa-trophy" style="font-size: 2.5rem; color: #f59e0b; margin-bottom: 15px;"></i>
                                <?php if (!empty($pemenang['gambar'])) { ?>
                                    <img 
                                        src="gambar/<?php echo htmlspecialchars($pemenang['gambar']); ?>" 
                                        alt="<?php echo htmlspecialchars($pemenang['nama_calon']); ?>"
                                        style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 5px solid #f59e0b; margin: 15px auto; display: block;"
                                        onerror="this.src='https://via.placeholder.com/120?text=Winner'"
                                    >
                                <?php } ?>
                                <div class="candidate-name" style="font-size: 1.3rem;">
                                    <?php echo htmlspecialchars($pemenang['nama_calon']); ?>
                                </div>
                                <div class="vote-count" style="font-size: 1.1rem;">
                                    <i class="fas fa-vote-yea"></i> <?php echo $pemenang['bilangan_undi']; ?> Undi
                                </div>
                                <?php if (count($winners) > 1) { ?>
                                    <small style="display: block; margin-top: 10px; color: var(--warning-color); font-weight: 600;">
                                        (Seri)
                                    </small>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>

                <!-- Detailed Results Table -->
                <div class="card">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kedudukan</th>
                                <th>Calon</th>
                                <th>Bilangan Undian</th>
                                <th>Peratusan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $rank = 0;
                            $prev_votes = -1;
                            
                            foreach ($all_candidates as $index => $calon) {
                                if ($calon['bilangan_undi'] != $prev_votes) {
                                    $rank = $index + 1;
                                }
                                $prev_votes = $calon['bilangan_undi'];
                                $percentage = $total_votes > 0 ? round(($calon['bilangan_undi'] / $total_votes) * 100, 1) : 0;
                                $is_winner = $calon['bilangan_undi'] == $max_votes && $max_votes > 0;
                            ?>
                                <tr style="<?php echo $is_winner ? 'background: #fef3c7; font-weight: 600;' : ''; ?>">
                                    <td>
                                        <?php if ($is_winner) { ?>
                                            <i class="fas fa-trophy" style="color: #f59e0b;"></i>
                                        <?php } ?>
                                        #<?php echo $rank; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($calon['nama_calon']); ?></td>
                                    <td><?php echo $calon['bilangan_undi']; ?></td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div style="flex: 1; background: var(--bg-light); height: 24px; border-radius: 12px; overflow: hidden; border: 1px solid var(--border-color);">
                                                <div style="width: <?php echo $percentage; ?>%; height: 100%; background: <?php echo $is_winner ? 'linear-gradient(90deg, #f59e0b, #fbbf24)' : 'linear-gradient(90deg, var(--primary-color), var(--primary-light))'; ?>; transition: width 0.5s;"></div>
                                            </div>
                                            <span style="min-width: 60px; font-weight: 600; font-size: 1.1rem;"><?php echo $percentage; ?>%</span>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr style="background: var(--bg-light); font-weight: 600;">
                                <td colspan="2" class="text-center">JUMLAH KESELURUHAN</td>
                                <td><?php echo $total_votes; ?></td>
                                <td>100%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
    <?php
        }
    }
    ?>
</div>

<?php include('footer.php'); ?>