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
                                        if (isset($_GET['kullanici_id'])) {
                                            $kullanici_id = $_GET['kullanici_id'];

                                            try {
                                                $sql = "SELECT DISTINCT DATE_FORMAT(tarih, '%Y-%m-%d') AS siparis_tarihi
                                                FROM siparisler
                                                WHERE kullanici_id = :kullanici_id
                                                ORDER BY tarih DESC";
                                                $stmt = $db->prepare($sql);
                                                $stmt->bindParam(':kullanici_id', $kullanici_id);
                                                $stmt->execute();
                                                $siparis_tarihleri = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                if ($siparis_tarihleri) { ?>
                                                    <table class="table table-bordered border text-nowrap mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="wd-20p text-center">Sipariş Tarihi</th>
                                                                <th class="tx-right text-center">Görüntüle</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($siparis_tarihleri as $siparis_tarihi) {
                                                                $tarih = $siparis_tarihi['siparis_tarihi'];
                                                                ?>
                                                                <tr>
                                                                    <td class="text-center"><?php echo $tarih; ?></td>
                                                                    <td class="text-center">
                                                                        <button class="btn btn-sm btn-success" type="button" onclick="window.location.href='tumsiparisdetay.php?kullanici_id=<?php echo $kullanici_id; ?>&siparis_tarihi=<?php echo $tarih; ?>'">Görüntüle</button>
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
                                            echo '<div class="alert alert-info" role="alert">Kullanıcı ID eksik!</div>';
                                        }  ?>
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
require_once 'inc/footer.php';
?>
