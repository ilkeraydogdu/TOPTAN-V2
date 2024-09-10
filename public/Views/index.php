<?php
session_start();
require_once 'inc/header.php';
require_once 'inc/sidebar.php';
require_once '../../app/config/DB.php';
require_once '../Controller/urunController.php';
require_once '../Controller/kullaniciController.php';

// Kullanıcı rolünü kontrol ediyoruz
$rol = $_SESSION['rol'];

$kullaniciController = new kullaniciController;

try {
    if ($rol == 'admin') {
        // Admin için genel istatistikler
        $sql = "SELECT SUM(gram) AS toplam_satis FROM siparisler";
        $stmt = $db->query($sql);
        $toplamSatis = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "SELECT COUNT(DISTINCT siparisNumarasi) AS toplam_siparis FROM siparisler";
        $stmt = $db->query($sql);
        $toplamSiparis = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "SELECT COUNT(*) AS toplam_uye FROM kullanici WHERE rol = 'musteri'";
        $stmt = $db->query($sql);
        $toplamUye = $stmt->fetch(PDO::FETCH_ASSOC);

        $enSonSiparisler = $kullaniciController->enSonSiparisleriListele();
    } elseif ($rol == 'musteri') {
        // Müşteri için kendi siparişleri ve istatistikler
        $kullaniciId = $_SESSION['id'];

        $sql = "SELECT SUM(gram) AS toplam_satis FROM siparisler WHERE kullanici_id = :kullanici_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':kullanici_id', $kullaniciId, PDO::PARAM_INT);
        $stmt->execute();
        $toplamSatis = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "SELECT COUNT(DISTINCT siparisNumarasi) AS toplam_siparis FROM siparisler WHERE kullanici_id = :kullanici_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':kullanici_id', $kullaniciId, PDO::PARAM_INT);
        $stmt->execute();
        $toplamSiparis = $stmt->fetch(PDO::FETCH_ASSOC);

        $enSonSiparisler = $kullaniciController->kullaniciSiparisleriListele($kullaniciId);
    }
} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
    exit();
}
?>
<br><br>
<div class="row">
    <div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
        <div class="card overflow-hidden dash1-card border-0">
            <div class="card-body">
                <div class="d-flex align-items-end justify-content-between">
                    <div>
                        <p class=" mb-1 fs-14">Toplam Satış</p>
                        <?php $toplamSatisMiktari = isset($toplamSatis['toplam_satis']) ? $toplamSatis['toplam_satis'] : 0; ?>
                        <h2 class="mb-0 mr-3">
                            <span class="number-font1"><small><?php echo number_format($toplamSatisMiktari, 2, ",", "."); ?> gr</small></span>
                        </h2>
                    </div>
                    <span class="text-secondary fs-35 dash1-iocns bg-secondary-transparent border-secondary"><i class="las la-hand-holding-usd"></i></span>
                </div>
            </div>
            <div id="spark2"></div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
        <div class="card overflow-hidden dash1-card border-0">
            <div class="card-body">
                <div class="d-flex align-items-end justify-content-between">
                    <div>
                        <p class=" mb-1 fs-14">Toplam Sipariş Sayısı</p>
                        <h2 class="mb-0">
                            <span class="number-font1"><small><?php echo $toplamSiparis['toplam_siparis']; ?> Adet</small></span>
                        </h2>
                    </div>
                    <span class="text-info fs-35 bg-info-transparent border-info dash1-iocns"><i class="las la-thumbs-up"></i></span>
                </div>
            </div>
            <div id="spark3"></div>
        </div>
    </div>
    <?php if ($rol == 'admin') { ?>
        <div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden dash1-card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between">
                        <div>
                            <p class=" mb-1 fs-14">Toplam Üye Sayısı</p>
                            <h2 class="mb-0">
                                <span class="number-font1"><?php echo $toplamUye['toplam_uye']; ?></span>
                            </h2>
                        </div>
                        <span class="text-primary fs-35 dash1-iocns bg-primary-transparent border-primary"><i class="las la-users"></i></span>
                    </div>
                </div>
                <div id="spark4"></div>
            </div>
        </div>
    <?php } ?>
</div>

<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo ($rol == 'admin') ? 'En Son Siparişler' : 'Siparişlerim'; ?></h3>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive mt-4">
                                        <?php if (!empty($enSonSiparisler)) { ?>
                                            <table class="table table-bordered border text-nowrap mb-0">
                                                <thead>
                                                    <tr>
                                                        <th class="wd-20p text-center">Sipariş Numarası</th>
                                                        <th class="wd-20p text-center">Sipariş Tarihi</th>
                                                        <th class="wd-20p text-center">İşlemler</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($enSonSiparisler as $siparis) { ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo $siparis["siparisNumarasi"]; ?></td>
                                                            <td class="text-center"><?php echo $siparis["tarih"]; ?></td>
                                                            <td class="text-center">
                                                                <button class="btn btn-sm btn-primary" type="button" onclick="window.location.href='kullanicifatura.php?kullanici_id=<?php echo $siparis["kullanici_id"]; ?>&siparis_numarasi=<?php echo $siparis["siparisNumarasi"]; ?>'">Sipariş Dökümü Görüntüle</button>

                                                                <?php
                                                                switch ($siparis["durum"]) {
                                                                    case 0:
                                                                    echo '<button class="btn btn-sm btn-info" type="button">Onay Bekliyor</button>';
                                                                    break;
                                                                    case 1:
                                                                    echo '<button class="btn btn-sm btn-success" type="button">Onaylandı</button>';
                                                                    break;
                                                                    case 2:
                                                                    echo '<button class="btn btn-sm btn-warning" type="button">Hazırlanıyor</button>';
                                                                    break;
                                                                    case 3:
                                                                    echo '<button class="btn btn-sm btn-secondary" type="button">İptal Edildi</button>';
                                                                    break;
                                                                    default:
                                                                    echo '<button class="btn btn-sm btn-secondary" type="button">Durum Bulunamadı</button>';
                                                                    break;
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        <?php } else {
                                            echo '<div class="alert alert-info" role="alert">Sipariş Yok!</div>';
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
<?php if ($rol == 'admin') { ?>
    <div class="row flex-lg-nowrap">
        <div class="col-12">
            <div class="row flex-lg-nowrap">
                <div class="col-12 mb-3">
                    <div class="e-panel card">
                        <div class="card-header">
                            <h3 class="card-title">Üyeler</h3>
                        </div>
                        <div class="card-body">
                            <div class="e-table">
                                <div class="table-responsive table-lg mt-3">
                                    <table class="table table-bordered border-top text-nowrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Kullanıcı Adı</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center">Durumu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            try {
                                                $sql = "SELECT * FROM kullanici WHERE rol = 'musteri'";
                                                $stmt = $db->query($sql);
                                                $kullanicilar = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($kullanicilar as $kullanici) {
                                                    echo "<tr>";
                                                    echo "<td class='align-middle text-center'><span>" . $kullanici['firma'] . "</span></td>";
                                                    echo "<td class='align-middle text-center'><span>" . $kullanici['email'] . "</span></td>";
                                                    echo "<td class='align-middle text-center'>";
                                                    if ($kullanici['durum'] == "aktif") {
                                                        echo "<a class='btn btn-success'>" . $kullanici['durum'] . "</a>";
                                                    } else {
                                                        echo "<a class='btn btn-secondary'>" . $kullanici['durum'] . "</a>";
                                                    }
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } catch (PDOException $e) {
                                                echo "Bağlantı hatası: " . $e->getMessage();
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }
require_once 'inc/footer.php'; ?>