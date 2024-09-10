<?php
session_start(); 
$rol = $_SESSION['rol']; 
if ($rol !== 'admin') {
    header('Location: index.php'); 
    exit; 
}
require_once 'inc/header.php';
require_once 'inc/sidebar.php';
require_once '../../app/config/DB.php';

try {
    // Tüm onay bekleyen siparişleri veritabanından al
    $sql = "SELECT DISTINCT siparisler.siparisNumarasi, DATE_FORMAT(siparisler.tarih, '%Y-%m-%d') as siparis_tarihi, siparisler.durum, siparisler.kullanici_id
    FROM siparisler
    WHERE siparisler.durum = 3
    ORDER BY siparis_tarihi DESC, siparisler.siparisNumarasi";

    $stmt = $db->prepare($sql);
    $stmt->execute();
    $siparisler = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <br><br>
    <div class="row flex-lg-nowrap">
        <div class="col-12">
            <div class="row flex-lg-nowrap">
                <div class="col-12 mb-3">
                    <div class="e-panel card">
                        <div class="card-header">
                            <h3 class="card-title">İptal Olan Siparişler</h3>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive mt-4">
                                            <?php if ($siparisler) { ?>
                                                <table class="table table-bordered border text-nowrap mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th class="wd-20p text-center">Sipariş Numarası</th>
                                                            <th class="wd-20p text-center">Sipariş Tarihi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($siparisler as $siparis) {
                                                            $siparisNumarasi = $siparis['siparisNumarasi'];
                                                            ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $siparisNumarasi; ?></td>
                                                                <td class="text-center"><?php echo $siparis['siparis_tarihi']; ?></td>
                                                                
                                                                <td class="text-center">
                                                                    <button class="btn btn-sm btn-primary" type="button" onclick="window.location.href='kullanicifatura.php?kullanici_id=<?php echo $siparis['kullanici_id']; ?>&siparis_numarasi=<?php echo $siparisNumarasi; ?>'">Sipariş Dökümü Görüntüle</button>
                                                                    <button class="btn btn-sm btn-secondary" type="button">İptal Edildi</button>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            <?php } else {
                                                echo '<tr><td colspan="4"><div class="alert alert-info" role="alert">İptal Olan Sipariş bulunamadı!</div></td></tr>';
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
    exit();
}

require_once 'inc/footer.php';
?>
