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

?>
<br><br>
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div class="card-header">
                        <h3 class="card-title">Siparişler</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive mt-4">
                                        <?php 

                                        if (isset($_GET['kullanici_id']) && isset($_GET['siparis_tarihi'])) {
                                            $kullanici_id = $_GET['kullanici_id'];
                                            $siparis_tarihi = $_GET['siparis_tarihi'];

                                            try {
                                                $sql = "SELECT DISTINCT siparisNumarasi
                                                FROM siparisler
                                                WHERE kullanici_id = :kullanici_id AND DATE_FORMAT(tarih, '%Y-%m-%d') = :siparis_tarihi
                                                ORDER BY siparisNumarasi"; 
                                                $stmt = $db->prepare($sql);
                                                $stmt->bindParam(':kullanici_id', $kullanici_id);
                                                $stmt->bindParam(':siparis_tarihi', $siparis_tarihi);
                                                $stmt->execute();
                                                $siparis_numaralari = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                if ($siparis_numaralari) {
                                                 ?>
                                                 <table class="table table-bordered border text-nowrap mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th class="wd-20p text-center">Sipariş Numarası</th>
                                                            <th class="wd-20p text-center">Sipariş Tarihi</th>
                                                            <th class="tx-right text-center">Görüntüle</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($siparis_numaralari as $siparis) {
                                                            $siparisNumarasi = $siparis['siparisNumarasi'];
                                                            ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $siparisNumarasi; ?></td>
                                                                <td class="text-center"><?php echo $siparis_tarihi; ?></td>
                                                                <td class="text-center">
                                                                    <button class="btn btn-sm btn-success" type="button" onclick="window.location.href='kullanicifatura.php?kullanici_id=<?php echo $kullanici_id; ?>&siparis_numarasi=<?php echo $siparisNumarasi; ?>'">Görüntüle</button>

                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            <?php } else {
                                                echo '<div class="alert alert-info" role="alert">Sipariş bulunamadı!</div>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<div class="alert alert-info" role="alert">Bağlantı hatası:</div>' . $e->getMessage();
                                            exit();
                                        }
                                    } else {
                                        echo '<div class="alert alert-info" role="alert">Kullanıcı ID veya sipariş tarihi eksik!</div>';
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
<?php require_once 'inc/footer.php'; ?>