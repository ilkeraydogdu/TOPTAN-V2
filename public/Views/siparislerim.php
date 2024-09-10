<?php
session_start();
require_once 'inc/header.php';
require_once 'inc/sidebar.php';
require_once '../../app/config/DB.php';

// Kullanıcının oturum açmış olup olmadığını kontrol et
if(isset($_SESSION['id'])) {
    // Kullanıcı oturum açmış ise, kullanıcı kimliğini al
    $kullanici_id = $_SESSION['id'];

    try {
        // Kullanıcının tüm siparişlerini veritabanından al
        $sql = "SELECT DISTINCT siparisNumarasi, DATE_FORMAT(tarih, '%Y-%m-%d') as siparis_tarihi, durum
        FROM siparisler
        WHERE kullanici_id = :kullanici_id
        ORDER BY siparis_tarihi DESC, siparisNumarasi"; // Sipariş tarihine ve numarasına göre sırala
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':kullanici_id', $kullanici_id);
        $stmt->execute();
        $siparisler = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <br><br>
        <div class="row flex-lg-nowrap">
            <div class="col-12">
                <div class="e-panel card">
                    <div class="card-header">
                        <h3 class="card-title">Siparişler</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive mt-4">
                            <?php 
                            if ($siparisler){ 
                                ?>
                                <table class="table table-bordered border text-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th class="wd-20p text-center">Sipariş Numarası</th>
                                            <th class="wd-20p text-center">Sipariş Tarihi</th>
                                            <th class="wd-20p text-center">Durum</th>
                                            <th class="wd-20p text-center">Sipariş Dökümü</th>
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
                                                    <?php
                                                    switch ($siparis['durum']) {
                                                        case 0:
                                                        echo '<button class="btn btn-sm btn-info" type="button">Sipariş Onay Bekliyor</button>';
                                                        break;
                                                        case 1:
                                                        echo '<button class="btn btn-sm btn-success" type="button">Sipariş Hazırlandı</button>';
                                                        break;
                                                        case 2:
                                                        echo '<button class="btn btn-sm btn-warning" type="button">Sipariş Hazırlanıyor</button>';
                                                        break;
                                                        case 3:
                                                        echo '<button class="btn btn-sm btn-secondary" type="button">Sipariş İptal Edildi</button>';
                                                        break;
                                                        default:
                                                        echo 'Bilinmeyen Durum';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-primary" type="button" onclick="window.location.href='kullanicifatura.php?kullanici_id=<?php echo $kullanici_id; ?>&siparis_numarasi=<?php echo $siparisNumarasi; ?>'">Görüntüle</button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php } else {
                                echo '<div class="alert alert-info col-lg-8" >
                                <small>Sipariş bulunamadı!</small>
                                </div>';
                            } 
                            ?>
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
} else {
    echo "Kullanıcı oturum açmamış!";
}
require_once 'inc/footer.php';
?>
