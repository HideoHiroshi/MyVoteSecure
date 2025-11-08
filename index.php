<?php
session_start();
include('header.php');
include('connection.php');

// Calculate voting status
$tarikh_tamat = strtotime($tarikh);
$tarikh_sekarang = strtotime(date("Y-m-d H:i:s"));
$voting_active = $tarikh_sekarang <= $tarikh_tamat;
$days_remaining = ceil(($tarikh_tamat - $tarikh_sekarang) / (60 * 60 * 24));
?>

<div class="container">
    <!-- Welcome Banner -->
    <div class="card fade-in" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-align: center; padding: 40px;">
        <h2 style="margin: 0 0 15px 0; font-size: 2rem;">
            <i class="fas fa-vote-yea"></i> Selamat Datang ke MyVoteSecure
        </h2>
        <p style="font-size: 1.1rem; margin: 0;">
            Sistem Undian Online yang Selamat dan Mudah
        </p>
    </div>

    <!-- Voting Status -->
    <div class="card fade-in" style="margin-top: 30px;">
        <?php if ($voting_active) { ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
                <div>
                    <strong>Proses Pengundian Masih Berjalan</strong>
                    <p style="margin: 5px 0 0 0;">
                        Tarikh tamat: <strong><?php echo date("d/m/Y H:i", $tarikh_tamat); ?></strong>
                        <?php if ($days_remaining > 0) { ?>
                            <br>Masa berbaki: <strong><?php echo $days_remaining; ?> hari</strong>
                        <?php } ?>
                    </p>
                </div>
            </div>
        <?php } else { ?>
            <div class="alert alert-danger">
                <i class="fas fa-times-circle" style="font-size: 1.5rem;"></i>
                <div>
                    <strong>Proses Pengundian Telah Tamat</strong>
                    <p style="margin: 5px 0 0 0;">
                        Tarikh tamat: <strong><?php echo date("d/m/Y H:i", $tarikh_tamat); ?></strong>
                    </p>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php if (!empty($_SESSION['jenis_pengguna'])) { ?>
        <!-- User Dashboard -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
            
            <?php if ($_SESSION['jenis_pengguna'] == "admin") { ?>
                <!-- Admin Dashboard Cards -->
                <a href="pengguna-senarai.php" style="text-decoration: none;">
                    <div class="card fade-in" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; text-align: center; cursor: pointer; transition: transform 0.3s;">
                        <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 15px;"></i>
                        <h3 style="margin: 0 0 10px 0;">Senarai Pengguna</h3>
                        <p style="margin: 0; opacity: 0.9;">Urus pengguna sistem</p>
                    </div>
                </a>

                <a href="calon-senarai.php" style="text-decoration: none;">
                    <div class="card fade-in" style="background: linear-gradient(135deg, #f093fb, #f5576c); color: white; text-align: center; cursor: pointer; transition: transform 0.3s;">
                        <i class="fas fa-user-tie" style="font-size: 3rem; margin-bottom: 15px;"></i>
                        <h3 style="margin: 0 0 10px 0;">Senarai Calon</h3>
                        <p style="margin: 0; opacity: 0.9;">Daftar dan urus calon</p>
                    </div>
                </a>

                <a href="laporan.php" style="text-decoration: none;">
                    <div class="card fade-in" style="background: linear-gradient(135deg, #4facfe, #00f2fe); color: white; text-align: center; cursor: pointer; transition: transform 0.3s;">
                        <i class="fas fa-chart-bar" style="font-size: 3rem; margin-bottom: 15px;"></i>
                        <h3 style="margin: 0 0 10px 0;">Laporan</h3>
                        <p style="margin: 0; opacity: 0.9;">Lihat keputusan undian</p>
                    </div>
                </a>

            <?php } elseif ($_SESSION['jenis_pengguna'] == "pengundi") { ?>
                <!-- Voter Dashboard Cards -->
                <a href="undi-calon.php" style="text-decoration: none;">
                    <div class="card fade-in" style="background: linear-gradient(135deg, #11998e, #38ef7d); color: white; text-align: center; cursor: pointer; transition: transform 0.3s;">
                        <i class="fas fa-check-circle" style="font-size: 3rem; margin-bottom: 15px;"></i>
                        <h3 style="margin: 0 0 10px 0;">Undi Calon</h3>
                        <p style="margin: 0; opacity: 0.9;">
                            <?php echo $voting_active ? 'Buat undian anda' : 'Undian telah tamat'; ?>
                        </p>
                    </div>
                </a>

                <?php
                // Check if user has voted
                $notel = $_SESSION['notel'];
                $check_vote = mysqli_query($condb, "SELECT COUNT(*) as voted FROM undian WHERE notel='$notel'");
                $vote_status = mysqli_fetch_array($check_vote);
                ?>

                <div class="card fade-in" style="text-align: center;">
                    <i class="fas fa-info-circle" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 15px;"></i>
                    <h3 style="margin: 0 0 10px 0; color: var(--text-dark);">Status Anda</h3>
                    <?php if ($vote_status['voted'] > 0) { ?>
                        <span class="badge badge-active" style="font-size: 1rem;">
                            <i class="fas fa-check"></i> Telah Mengundi
                        </span>
                    <?php } else { ?>
                        <span class="badge" style="background: #fbbf24; color: #78350f; font-size: 1rem;">
                            <i class="fas fa-hourglass-half"></i> Belum Mengundi
                        </span>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

    <?php } else { ?>
        <!-- Guest View -->
        <div class="card fade-in" style="margin-top: 30px; text-align: center; padding: 50px 30px;">
            <i class="fas fa-vote-yea" style="font-size: 4rem; color: var(--primary-color); margin-bottom: 20px;"></i>
            <h2 style="margin: 0 0 15px 0; color: var(--text-dark);">
                Sistem Undian Online MyVoteSecure
            </h2>
            <p style="color: var(--text-light); font-size: 1.1rem; margin-bottom: 30px;">
                Sila log masuk atau daftar akaun untuk menggunakan sistem ini
            </p>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="login.php" class="btn btn-primary" style="text-decoration: none;">
                    <i class="fas fa-sign-in-alt"></i> Log Masuk
                </a>
                <a href="signup.php" class="btn btn-success" style="text-decoration: none;">
                    <i class="fas fa-user-plus"></i> Daftar Akaun
                </a>
            </div>
        </div>

        <!-- Features Section -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
            <div class="card fade-in" style="text-align: center;">
                <i class="fas fa-shield-alt" style="font-size: 2.5rem; color: var(--success-color); margin-bottom: 15px;"></i>
                <h3 style="color: var(--text-dark);">Selamat</h3>
                <p style="color: var(--text-light);">Undian anda dilindungi dengan sistem keselamatan yang terjamin</p>
            </div>

            <div class="card fade-in" style="text-align: center;">
                <i class="fas fa-mobile-alt" style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 15px;"></i>
                <h3 style="color: var(--text-dark);">Mudah</h3>
                <p style="color: var(--text-light);">Antaramuka yang mudah digunakan di mana-mana sahaja</p>
            </div>

            <div class="card fade-in" style="text-align: center;">
                <i class="fas fa-clock" style="font-size: 2.5rem; color: var(--warning-color); margin-bottom: 15px;"></i>
                <h3 style="color: var(--text-dark);">Pantas</h3>
                <p style="color: var(--text-light);">Proses undian yang cepat dan keputusan segera</p>
            </div>
        </div>
    <?php } ?>
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
</style>

<?php include('footer.php'); ?>