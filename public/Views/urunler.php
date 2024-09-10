<?php
session_start();
    // Gerekli dosyaları ve sınıfları dahil et
require_once 'inc/header.php';
require_once 'inc/sidebar.php';
require_once '../Controller/urunController.php';
require_once '../Model/kategoriModel.php';

$kategoriModel = new kategoriModel($db);
$kategoriler = $kategoriModel->listele();
$urunController = new urunController($db);

$searchTerm = isset($_GET['urunAra']) ? htmlspecialchars($_GET['urunAra']) : '';
$kategori_id = isset($_GET['kategori_id']) ? intval($_GET['kategori_id']) : 0;
$showPasif = isset($_GET['show_pasif']) && $_GET['show_pasif'] == 'on';

    // Arama terimini al
$searchTerm = isset($_POST['urunAra']) ? htmlspecialchars($_POST['urunAra']) : '';
$kategori_id = isset($_POST['kategori_id']) ? intval($_POST['kategori_id']) : 0;
$showPasif = isset($_POST['show_pasif']) && $_POST['show_pasif'] == 'on';

    // Ürünleri filtrele
if (!empty($searchTerm)) {
    $urunler = $urunController->search($searchTerm);
} elseif ($showPasif) {
    $urunler = $urunController->getPasifUrunler();
} else {
    $urunler = $urunController->filterByKategori($kategori_id);
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
        $response = ['success' => false, 'message' => 'İşlem gerçekleştirilemedi.']; // Varsayılan mesaj
        if ($_POST['action'] === 'aktif_yap' && isset($_POST['urun_id'])) {
            $urunId = intval($_POST['urun_id']);
            $result = $urunController->aktifYap($urunId);
            $response['success'] = $result;
            $response['message'] = $result ? 'Ürün aktif hale getirildi.' : 'Ürün aktif hale getirilemedi.';
        } elseif ($_POST['action'] === 'pasif_yap' && isset($_POST['urun_id'])) {
            $urunId = intval($_POST['urun_id']);
            $result = $urunController->pasifYap($urunId);
            $response['success'] = $result;
            $response['message'] = $result ? 'Ürün pasif hale getirildi.' : 'Ürün pasif hale getirilemedi.';
        }
        echo json_encode($response); // JSON yanıtını döndür
        exit;
    }


    // Sepet oturumu başlat
    if (!isset($_SESSION['sepet'])) {
        $_SESSION['sepet'] = [];
    }

    // Ürünü sepete ekle
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['urun_id']) && isset($_POST['miktar'])) {
        $urunId = intval($_POST['urun_id']);
        $miktar = intval($_POST['miktar']);

        if ($miktar <= 0) {
            unset($_SESSION['sepet'][$urunId]);
        } else {
            if (isset($_SESSION['sepet'][$urunId])) {
                $_SESSION['sepet'][$urunId] += $miktar;
            } else {
                $_SESSION['sepet'][$urunId] = $miktar;
            }

            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Ürün Sepete Eklendi!',
                text: 'Ürün sepete başarıyla eklendi.',
                showConfirmButton: true,
                confirmButtonText: 'Tamam'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                    });
                    </script>";
                }
            }
            ?>
            <div class="page-header">
                <div class="page-leftheader">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="fe fe-shopping-cart mr-2 fs-14"></i>Kabaloğlu Kuyumculuk Toptan</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="#">Ürünler</a></li>
                    </ol>
                </div>
            </div>

            <div class="row flex-lg-nowrap">
                <div class="col-12">
                    <div class="row flex-lg-nowrap">
                        <div class="col-12 mb-6">
                            <div class="e-panel card">
                                <div class="card-body">
                                    <div class="row">
                                        <?php if ($_SESSION['rol'] == 'admin'): ?>
                                            <div class="col-3 col-auto">
                                                <form id="pasifForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                                    <label class="custom-switch">
                                                        <input type="checkbox" name="show_pasif" class="custom-switch-input" id="pasif-urunler-checkbox" <?php echo (isset($_POST['show_pasif']) && $_POST['show_pasif'] == 'on') ? 'checked' : ''; ?>>
                                                        <span class="custom-switch-indicator"></span>
                                                        <span class="custom-switch-description">Pasif ürünleri görüntüle</span>
                                                    </label>
                                                </form>
                                            </div>
                                        <?php endif; ?>

                                        <div class="col-5 col-auto">
                                            <form id="kategoriForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                                <div class="input-group mb-2">
                                                    <select class="form-control" name="kategori_id">
                                                        <optgroup label="Kategoriler">
                                                            <option value="0">Kategori Seç </option>
                                                            <?php 
                                                            function kategoriListeleForm($kategoriler, $kategoriModel, $tab = 0, $selectedKategoriId = 0) {
                                                                foreach ($kategoriler as $kategori_id => $kategori) {
                                                                    $selected = $kategori_id == $selectedKategoriId ? 'selected' : '';
                                                                    echo '<option value="' . $kategori_id . '" ' . $selected . '>' . str_repeat('&nbsp;', $tab * 2) . $kategori['kategori_adi'] . '</option>';
                                                                    if (!empty($kategori['alt_kategoriler'])) {
                                                                        kategoriListeleForm($kategori['alt_kategoriler'], $kategoriModel, $tab + 1, $selectedKategoriId);
                                                                    }
                                                                }
                                                            }
                                                            kategoriListeleForm($kategoriler, $kategoriModel, 0, $kategori_id);
                                                            ?>
                                                        </optgroup>
                                                    </select>
                                                    <span class="input-group-append">
                                                        <button class="btn ripple btn-primary" type="submit" name="ara">Ara</button>
                                                    </span>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-3 col-auto">
                                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control" name="urunAra" placeholder="Ürün Ara" value="<?php echo htmlspecialchars($searchTerm); ?>">
                                                    <span class="input-group-append">
                                                        <button class="btn ripple btn-primary" type="submit" name="ara">Ara</button>
                                                    </span>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php
                        $sayfaBasinaGosterilenUrunSayisi = 12;
                        $sayfa = isset($_GET['sayfa']) ? $_GET['sayfa'] : 1;
                        $sayfaBaslangici = ($sayfa - 1) * $sayfaBasinaGosterilenUrunSayisi;
                        $urunlerSayfalik = array_slice($urunler, $sayfaBaslangici, $sayfaBasinaGosterilenUrunSayisi);

                        if (!empty($urunlerSayfalik)):
                            foreach ($urunlerSayfalik as $urun): ?>
                                <div class="col-xl-3">
                                    <div class="card item-card">
                                        <div class="card-body pb-0">
                                            <div class="text-center">

                                                <img  src="<?php echo URL; ?>/app/assets/images/products/<?php echo $urun['foto']; ?>" class="img-fluid w-100 img-hover position-relative"  style="border-radius: 15px;">

                                                <a href="<?php echo URL; ?>/app/assets/images/products/<?php echo $urun['foto']; ?>" download class="btn btn-sm position-relative">
                                                    <button class="btn btn-secondary btn-block mt-2 indir-btn small-btn">
                                                        <i class="fe fe-download mr-1"></i> Resmi İndir <i class="fe fe-download mr-1"></i>
                                                    </button>
                                                </a>
                                            </div>
                                            <div class="card-body px-0">
                                                <div class="cardtitle">
                                                    <a class="shop-title"><?php echo $urun['isim']; ?></a>
                                                    <br>
                                                    Gram: <?php echo $urun['gram']; ?> gr
                                                    <br>
                                                    Stok Kodu: <?php echo $urun['sKodu']; ?>
                                                    <?php if (!empty($urun['aciklama'])): ?>
                                                        <br><br>
                                                        Açıklama: <p><?php echo $urun['aciklama']; ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (!$showPasif): ?>
                                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="event.preventDefault(); sepeteEkle(this);">
                                                <input type="hidden" name="urun_id" value="<?php echo $urun['id']; ?>">
                                                <input type="hidden" name="urunAra" value="<?php echo htmlspecialchars($searchTerm); ?>">
                                                <input type="hidden" name="kategori_id" value="<?php echo htmlspecialchars($kategori_id); ?>">
                                                <div class="text-center pb-6 pl-6 pr-6">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-light border-0 br-0 minus" name="urun_id" id="minus_<?php echo $urun['id']; ?>">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                    </span>
                                                    <input type="text" name="miktar" id="miktar_<?php echo $urun['id']; ?>" class="form-control text-center qty" value="1">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-light border-0 br-0 add" id="plus_<?php echo $urun['id']; ?>">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </span>
                                                    <button type="submit" class="btn btn-secondary btn-block mt-4" id="ekle_<?php echo $urun['id']; ?>">
                                                        <i class="fe fe-shopping-cart mr-1"></i>Sepete Ekle
                                                    </button>
                                                </div>
                                            </form>
                                        <?php endif; ?>
                                        <?php if ($_SESSION['rol'] == 'admin'): ?>
                                            <div class="text-center pb-6 pl-6 pr-6">
                                                <div class="btn-list">
                                                    <?php if (!$showPasif): ?>
                                                        <a href="urunDuzenle.php?id=<?php echo $urun['id']; ?>" class="btn btn-primary notice">Düzenle</a>
                                                        <button type="button" class="btn btn-secondary warning" onclick="pasifYap(<?php echo $urun['id']; ?>)">PASİF YAP</button>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-success" onclick="aktifYap(<?php echo $urun['id']; ?>)">AKTİF YAP</button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach;
                        else: ?>
                            <div class="col-12">
                                <div class="alert alert-warning" role="alert">
                                    Aradığınız kriterlere uygun ürün bulunamadı.
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php
                        $totalPages = ceil(count($urunler) / $sayfaBasinaGosterilenUrunSayisi);
                        ?>
                        <div class="col-12">
                            <div class="pagination-wrapper">
                                <nav aria-label="Sayfa Navigasyonu">
                                    <ul class="pagination">
                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?php echo ($i == $sayfa) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?sayfa=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function sepeteEkle(form) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Ürün Sepete Eklendi!',
                        text: 'Ürün sepete başarıyla eklendi.',
                        showConfirmButton: true,
                        confirmButtonText: 'Tamam'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
                function pasifYap(urunId) {
                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: "Ürünü pasif hale getirmek istiyor musunuz?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Evet, pasif yap!',
                        cancelButtonText: 'İptal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: new URLSearchParams({
                                    'action': 'pasif_yap',
                                    'urun_id': urunId
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Sunucudan geçerli bir yanıt alınamadı.');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Başarılı',
                                        text: 'Ürün pasif hale getirildi.'
                                    }).then(() => {
                                        location.reload(); 
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Hata',
                                        text: data.message || 'Ürün pasif hale getirilemedi.'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Fetch hatası:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata',
                                    text: 'Sunucudan bir hata oluştu, fakat ürün muhtemelen pasif hale getirildi. Sayfa yenilenecek.'
                                }).then(() => {
                                    location.reload();
                                });
                            });
                        }
                    });
                }
                function aktifYap(urunId) {
                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: "Ürünü aktif hale getirmek istiyor musunuz?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Evet, aktif yap!',
                        cancelButtonText: 'İptal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: new URLSearchParams({
                                    'action': 'aktif_yap',
                                    'urun_id': urunId
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Sunucudan geçerli bir yanıt alınamadı.');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Başarılı',
                                        text: 'Ürün aktif hale getirildi.'
                                    }).then(() => {
                                        location.reload(); 
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Hata',
                                        text: data.message || 'Ürün aktif hale getirilemedi.'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Fetch hatası:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Hata',
                                    text: 'Sunucudan bir hata oluştu, fakat ürün muhtemelen aktif hale getirildi. Sayfa yenilenecek.'
                                }).then(() => {
                                    location.reload();
                                });
                            });
                        }
                    });
                }
                function saveScrollPosition() {
                    localStorage.setItem('scrollPosition', window.scrollY);
                }

                function restoreScrollPosition() {
                    const scrollPosition = localStorage.getItem('scrollPosition');
                    if (scrollPosition) {
                        window.scrollTo(0, scrollPosition);
                        localStorage.removeItem('scrollPosition');
                    }
                }

                window.addEventListener('beforeunload', saveScrollPosition);

                document.addEventListener('DOMContentLoaded', restoreScrollPosition);

    // Pasif ürünlerin checkbox'ı ile formu otomatik gönder
                document.getElementById('pasif-urunler-checkbox').addEventListener('change', function() {
                    document.getElementById('pasifForm').submit();
                });

            </script>
            <?php require_once 'inc/footer.php'; ?>
