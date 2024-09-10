<?php
session_start(); 
$rol = $_SESSION['rol']; 
if ($rol !== 'admin') {
    header('Location: index.php'); 
    exit; 
}
require_once 'inc/header.php';
require_once 'inc/sidebar.php';
require_once '../Controller/urunDuzenleController.php';

$db = getDbConnection();
$urunDuzenleController = new urunDuzenleController($db);

if (!isset($_GET['id'])) {
    header('Location: urunler.php');
    exit;
}

$id = $_GET['id'];
$urun = $urunDuzenleController->urunBilgisiGetir($id);
$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['bilgileriGuncelle'])) {
        $kategori_id = $_POST['kategori_id'];
        $isim = $_POST['isim'];
        $gram = $_POST['gram'];
        $sKodu = $_POST['sKodu'];
        $aciklama = $_POST['aciklama'];

        $guncellemeSonucu = $urunDuzenleController->urunBilgileriGuncelle($id, $kategori_id, $sKodu, $aciklama, $isim, $gram);
        
        if ($guncellemeSonucu['sonuc']) {
            echo "<script>alert('Ürün bilgileri başarıyla güncellendi.'); window.location.href = 'urunler.php';</script>";
        } else {
            $hataMesaji = $guncellemeSonucu['hataMesaji'];
        }
    } elseif (isset($_POST['gorselGuncelle'])) {
        if(isset($_FILES['foto'])) {
            $uploadDir = '../../app/assets/images/products/';
            $fotoDosya = $_FILES['foto'];
            $fotoDosyaAdi = basename($fotoDosya['name']);
            $fotoYol = $uploadDir . $fotoDosyaAdi;
            $fotoDosyaTipi = strtolower(pathinfo($fotoYol, PATHINFO_EXTENSION));
            
            if($fotoDosyaTipi == "jpg" || $fotoDosyaTipi == "png" || $fotoDosyaTipi == "jpeg" || $fotoDosyaTipi == "gif" ) {
                if (move_uploaded_file($fotoDosya['tmp_name'], $fotoYol)) {
                    $eskiGorselAdi = $urun['foto'];
                    if ($eskiGorselAdi && file_exists($uploadDir . $eskiGorselAdi)) {
                        unlink($uploadDir . $eskiGorselAdi);
                    }
                    $sonuc = $urunDuzenleController->urunGorselGuncelle($id, $fotoDosyaAdi);
                    if($sonuc) {
                        echo "<script>alert('Ürün görseli başarıyla güncellendi.'); window.location.href = 'urunler.php';</script>";
                    } else {
                        echo "<script>alert('Ürün görseli güncellenirken hata oluştu.');</script>";
                    }
                } else {
                    echo "<script>alert('Dosya yükleme hatası.');</script>";
                }
            } else {
                echo "<script>alert('Yalnızca JPG, JPEG, PNG & GIF dosya türleri izin verilir.');</script>";
            }
        } else {
            echo "<script>alert('Dosya seçilmedi.');</script>";
        }
    }
}
?>

<div class="page-header">
    <div class="page-leftheader">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-shopping-cart mr-2 fs-14"></i>Kabaloğlu Kuyumculuk Toptan</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">Ürün Düzenle</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-lg-4">
        <form method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Ürün Görsel Güncelle</div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-5">
                        <img src="<?php echo URL; ?>/app/assets/images/products/<?php echo $urun['foto']; ?>" alt="Ürün Görseli">
                    </div>
                    <div class="custom-file mb-4">
                        <input type="file" class="custom-file-input" id="foto" name="foto">
                        <label class="custom-file-label" for="foto">Yeni Görsel Seç</label>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" name="gorselGuncelle" class="btn btn-primary">Görsel Değiştir</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-xl-9 col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Ürün Bilgilerini Düzenle</div>
            </div>
            <form method="POST">
                <div class="card-body">
                    <div class="card-title font-weight-bold">Ürün Bilgileri:</div>
                    <?php if (!empty($hataMesaji)) : ?>
                        <div class="text-center title-style mb-6">
                            <br><br>
                            <ul class="nav1 bg-info mt-4 br-7">
                                <li class="nav-item1">
                                    <a class="nav-link text-white active"><?php echo $hataMesaji; ?></a>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="form-group row">
                        <label for="kategori_id" class="col-sm-3 col-form-label">Kategori:</label>
                        <div class="col-sm-9">
                            <select name="kategori_id" id="kategori_id" class="form-control">
                                <option value="0">--Kategori Seç--</option>
                                <?php 
                                echo $urunDuzenleController->listCategories($urun['kategori_id']);
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="isim" class="col-sm-3 col-form-label">Ürün Adı:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="isim" name="isim" value="<?php echo $urun['isim']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="gram" class="col-sm-3 col-form-label">Gram:</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="gram" name="gram" value="<?php echo $urun['gram']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="sKodu" class="col-sm-3 col-form-label">Stok Kodu:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="sKodu" name="sKodu" value="<?php echo $urun['sKodu']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="aciklama" class="col-sm-3 col-form-label">Açıklama:</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="aciklama" name="aciklama" rows="3" required><?php echo $urun['aciklama']; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" name="bilgileriGuncelle" class="btn btn-primary">Bilgileri Güncelle</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'inc/footer.php'; ?>
