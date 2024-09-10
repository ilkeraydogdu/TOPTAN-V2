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
                                        try {
                                            $sql = "SELECT DISTINCT kullanici_id FROM siparisler";
                                            $stmt = $db->prepare($sql);
                                            $stmt->execute();
                                            $kullanici_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
                                            if ($kullanici_ids) { ?>
                                                <table class="table table-bordered border text-nowrap mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th class="wd-20p text-center">Firma Adı</th>
                                                            <th class="wd-20p text-center">İşlem</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        foreach ($kullanici_ids as $kullanici_id) { 
                                                            // Kullanıcı tablosundan kullanıcının firma adını al
                                                            $sql_firma = "SELECT firma FROM kullanici WHERE id = :kullanici_id LIMIT 1";
                                                            $stmt_firma = $db->prepare($sql_firma);
                                                            $stmt_firma->bindParam(':kullanici_id', $kullanici_id);
                                                            $stmt_firma->execute();
                                                            $firma = $stmt_firma->fetch(PDO::FETCH_COLUMN); 
                                                            ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $firma; ?></td>
                                                                <td class="text-center">
                                                                    <button class="btn btn-sm btn-success" type="button" onclick="window.location.href='kullanicisiparisdetay.php?kullanici_id=<?php echo $kullanici_id; ?>'">Siparişleri Görüntüle</button>

                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            <?php } else {
                                                echo '<div class="alert alert-info" role="alert">Sipariş bulunamadı!</div>';
                                            }
                                        } catch (PDOException $e) {
                                            echo "Bağlantı hatası: " . $e->getMessage();
                                            exit();
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
