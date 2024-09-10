<?php 
session_start(); 
$rol = $_SESSION['rol']; 
if ($rol !== 'admin') {
    header('Location: index.php'); 
    exit; 
}
require_once 'inc/header.php';
require_once 'inc/sidebar.php';
require_once '../Controller/KullaniciFaturaController.php';

$controller = new KullaniciFaturaController();
$kullanici_id = isset($_GET['kullanici_id']) ? htmlspecialchars($_GET['kullanici_id']) : null; 
$siparis_numarasi = isset($_GET['siparis_numarasi']) ? htmlspecialchars($_GET['siparis_numarasi']) : null;
$siparisler = $controller->getSiparisDetay($kullanici_id, $siparis_numarasi);


if (!empty($siparisler)) {
    // İlk siparişi alalım
    $ilk_siparis = reset($siparisler);
    if (isset($ilk_siparis['kullanici_adi'], $ilk_siparis['adres'], $ilk_siparis['tel'], $ilk_siparis['email'], $ilk_siparis['tarih'])) {
        $siparis_numarasi = $ilk_siparis['siparisNumarasi'];
        $kullanici_adi = $ilk_siparis['kullanici_adi'];
        $adres = $ilk_siparis['adres'];
        $tel = $ilk_siparis['tel'];
        $email = $ilk_siparis['email'];
        $tarih = $ilk_siparis['tarih'];

        ?>


        <br><br>
        <div class="row flex-lg-nowrap">
            <div class="col-12">
                <div class="row flex-lg-nowrap">
                    <div class="col-12 mb-3">
                        <div class="e-panel card">
                            <div class="card-header">
                                <h3 class="card-title">Sipariş Dökümü</h3>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-body">
                                        <h1 class="invoice-title font-weight-bold text-uppercase mb-1">#<?php echo $siparis_numarasi; ?></h1>
                                        <div class="row mt-4">
                                            <div class="col-md">
                                                <label class="font-weight-bold"><?php echo $kullanici_adi; ?></label><br>
                                                <div class="billed-to">
                                                    <p>Adres: <?php echo $adres; ?></p>
                                                    <p>Tel: <?php echo $tel; ?></p>
                                                    <p>Email: <?php echo $email; ?></p>
                                                    <p><b>Sipariş Tarihi: <?php echo $tarih; ?></b></p>
                                                </div>
                                            </div>
                                            <div class="col-md">
                                                <div class="billed-from text-md-right">
                                                    <label class="font-weight-bold">Kabaloğlu Kuyumculuk</label>
                                                    <p>Adres: Yenibosna Merkez, Kuyumcular sk Vizyonpark Atölyeler Bloğu <br>C2 Blok 3.Kat No:305, <br>34197 Küçükçekmece/İstanbul<br>
                                                    Tel:  0531 597 89 48</p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (!empty($ilk_siparis["note"])) {?>
                                            <div class="float-left mb-5 col-lg-7">
                                                <textarea class="form-control mb-4 mt-4" disabled  rows="3"><?php echo $ilk_siparis["note"]; ?></textarea>
                                            </div>
                                        <?php } ?>
                                        <div class="float-right mb-5">
                                            <?php if ($_SESSION['rol'] == 'admin'): ?>
                                                <button type="button" class="btn btn-secondary mt-4" id="cancelOrderBtn"><i class="fe fe-x-circle"></i>İptal Et</button>
                                                <button type="button" class="btn btn-warning mt-4" id="prepareOrderBtn"><i class="fe fe-more-horizontal"></i>Hazırlanıyor</button>
                                                <button type="button" class="btn btn-success mt-4" id="approveOrderBtn"><i class="fe fe-check-circle"></i>Onayla</button>
                                                
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-info mt-4" onClick="printPage()"><i class="si si-printer"></i> Yazdır</button>
                                            <div id="notificationContainer"></div>
                                            <script>
                                                function printPage() {
                                                    var style = document.createElement('style');
                                                    style.innerHTML = `
                                                    @media print {
                                                        header, footer {
                                                            display: none;
                                                        }
                                                        .btn {
                                                            display: none;
                                                        }
                                                    }
                                                    `;
                                                    document.head.appendChild(style);
                                                    window.print();
                                                    document.head.removeChild(style);
                                                }

                                                document.addEventListener('DOMContentLoaded', function () {
                                                    var siparisNumarasi = "<?php echo $siparis_numarasi; ?>";

                                                    document.getElementById('cancelOrderBtn').addEventListener('click', function () {
                                                        updateOrderStatus(3);
                                                    });

                                                    document.getElementById('prepareOrderBtn').addEventListener('click', function () {
                                                        updateOrderStatus(2);
                                                    });

                                                    document.getElementById('approveOrderBtn').addEventListener('click', function () {
                                                        updateOrderStatus(1);
                                                    });

                                                    document.getElementById('deleteOrderBtn').addEventListener('click', function () {
                                                        deleteOrder();
                                                    });

                                                    function updateOrderStatus(status) {
                                                        var xmlhttp = new XMLHttpRequest();
                                                        var url = "faturaonay.php";
                                                        xmlhttp.onreadystatechange = function () {
                                                            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                                                console.log(xmlhttp.responseText);
                                                                var message = "Fatura durumu güncellendi!";
                                                                showNotification(message, 'success');
                                                                if (status == 1) {
                                                                    window.location.href = 'onaylanansiparislerim.php';
                                                                } else if (status == 2) {
                                                                    window.location.href = 'hazirlaniyorsiparislerim.php';
                                                                } else if (status == 3) {
                                                                    window.location.href = 'iptalsiparislerim.php';
                                                                }
                                                            }
                                                        };
                                                        var params = "siparis_numarasi=" + siparisNumarasi + "&durum=" + status;
                                                        xmlhttp.open("POST", url, true);
                                                        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                                        xmlhttp.send(params);
                                                    }

                                                    function showNotification(message, type) {
                                                        var notificationContainer = document.getElementById('notificationContainer');

                                                        var notification = document.createElement('div');
                                                        notification.classList.add('notification', 'notification-' + type);
                                                        notification.innerHTML = message;

                                                        notificationContainer.appendChild(notification);

                                                        setTimeout(function () {
                                                            notification.remove();
                                                        }, 3000);
                                                    }
                                                });
                                            </script>
                                        </div>
                                        <div class="table-responsive mt-4">
                                            <table class="table table-bordered border text-nowrap mb-0">
                                                <thead>
                                                    <tr>
                                                        <th class="wd-20p text-center">Ürün Görseli</th>
                                                        <th class="wd-20p text-center">Açıklama</th>
                                                        <th class="wd-20p text-center">Üst Kategori</th>
                                                        <th class="wd-20p text-center">Ürün Adı</th>
                                                        <th class="tx-right text-center">Ürün Gramı</th>
                                                        <th class="tx-right text-center">Satın Alınan Adet</th>
                                                        <th class="tx-right text-center">Satın Alınan Gram</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $toplam_gram = 0;
                                                    foreach ($siparisler as $urun):
                                                        if (isset($urun['urun_gram'], $urun['foto'], $urun['ust_kategori'], $urun['urun_adi'], $urun['adet'])) {
                                                            $toplam_gram += $urun['adet'] * $urun['urun_gram'];
                                                            ?>
                                                            <tr>
                                                                <td colspan="1"><img width="190" height="200" src="<?php echo URL; ?>/app/assets/images/products/<?php echo $urun['foto']; ?>"></td>
                                                                <td class="text-center"><?php echo $urun['aciklama']; ?></td>
                                                                <td class="text-center"><?php echo $urun['ust_kategori']; ?></td>
                                                                <td colspan="1"><?php echo $urun['urun_adi']; ?></td>
                                                                <td class="tx-right font-weight-bold text-center"><?php echo $urun['urun_gram']; ?> gr.</td>
                                                                <td class="tx-right text-center font-weight-bold"><?php echo $urun['adet']; ?> Adet</td>
                                                                <td class="tx-right text-center font-weight-bold"><?php echo $urun['urun_gram'] * $urun['adet']; ?> gr.</td>
                                                            </tr>
                                                            <?php 
                                                        }
                                                    endforeach; ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>

                                                        <th colspan="6">Toplam:</th>
                                                        <th class="tx-right font-weight-bold text-center"><?php echo $toplam_gram; ?> gr.</th>
                                                        <th class="wd-15p"></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
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
} else {
    echo "<p>Bir hata oluştu: Sipariş bilgileri eksik veya tanımsız.</p>";
}
} else {
    echo "<p>Sipariş bulunamadı.</p>";
}
require_once 'inc/footer.php';
?>

