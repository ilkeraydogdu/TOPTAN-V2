<?php
session_start();
require_once '../Controller/urunController.php';
require_once '../Controller/siparisController.php';

$urunController = new urunController();
$siparisController = new siparisController();

if (!empty($_SESSION['sepet'])) {
    $urunlerSepet = [];
    $toplamGram = 0;

    foreach ($_SESSION['sepet'] as $urunId => $miktar) {
        $urunBilgileri = $urunController->urunBilgileriniCek($urunId);
        if ($urunBilgileri) {
            $urunlerSepet[] = [
                'id' => $urunId,
                'isim' => $urunBilgileri['isim'],
                'foto' => $urunBilgileri['foto'],
                'gram' => $urunBilgileri['gram'],
                'miktar' => $miktar,
                'toplamGram' => $urunBilgileri['gram'] * $miktar
            ];
            $toplamGram += $urunBilgileri['gram'] * $miktar;
        }
    }

    if (isset($_GET['sil'])) {
        $silId = $_GET['sil'];
        unset($_SESSION['sepet'][$silId]);
        header("Location: sepet.php");
        exit;
    }

    if (isset($_POST['siparis_ver'])) {
        $kullaniciId = $_SESSION['id']; 
        $urunIdler = $_POST['urun_id'];
        $adetler = $_POST['adet']; 
        $gramlar = $_POST['gram']; 
        $note = $_POST['note'];
        $aciklamalar = $_POST['aciklama'];

        $siparisController->siparisOlustur($kullaniciId, $urunIdler, $adetler, $gramlar, $note, $aciklamalar);
        
        header("Location: siparislerim.php");
        exit;
    }

    require_once 'inc/header.php';
    require_once 'inc/sidebar.php';
    ?>
    
    <div class="side-app">
        <div class="page-header">
            <div class="page-leftheader">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="fe fe-shopping-cart mr-2 fs-14"></i>Kabaloğlu Kuyumculuk Toptan</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Sepetim</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Sepetim</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form action="sepet.php" method="POST">
                                <div class="alert alert-info col-lg-8" role="alert">
                                    <small>Verilen siparişler 1 gün içinde iptal edilebilir aksi taktirde yapımına başlanacak olup iptal edilmeyecektir.</small>
                                </div>
                                <table class="table table-bordered text-nowrap border-top">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#  </th>
                                            <th class="text-center">Ürün Adı</th>
                                            <th class="text-center">Açıklama</th>
                                            <th class="text-center">Ürün Adeti</th>
                                            <th class="text-center">Toplam Gram</th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($urunlerSepet as $index => $urun) { ?>
                                           <input type="hidden" name="urun_id[]" value="<?php echo $urun['id']; ?>">
                                           <input type="hidden" name="adet[]" value="<?php echo $urun['miktar']; ?>">
                                           <input type="hidden" name="gram[]" value="<?php echo $urun['toplamGram']; ?>">
                                           <tr>
                                            <td class="text-center"><img src="<?php echo URL; ?>/app/assets/images/products/<?php echo $urun['foto']; ?>" width="100" height="100"></td>


                                            <td class="text-center"><?php echo $urun['isim']." (".$urun['gram']."gr)"; ?></td>
                                            
                                            <td class="text-center">
                                                <textarea name="aciklama[]" placeholder="Açıklama"></textarea>
                                            </td>

                                            <td class="text-center"><?php echo $urun['miktar']; ?> Adet</td>
                                            <td class="text-center"><?php echo $urun['toplamGram']; ?> Gram</td>
                                            <td class="text-center">
                                                <a href="?sil=<?php echo $urun['id']; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Sil">
                                                    <i class="fe fe-trash-2"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div class="col-lg mg-t-10">
                                <textarea name="note" class="form-control mb-4" placeholder="NOT"></textarea>
                            </div>
                            <div class="float-right">
                                <!-- Diğer gerekli form alanları -->
                                <button type="submit" name="siparis_ver" class="btn btn-secondary mt-2">Sipariş Ver <i class="fe fe-arrow-right"></i></button>

                            </div>
                        </form>
                        <div class="float-left mt-2">
                            <h5>Toplam Gram: <?php echo number_format($toplamGram, 2, ",", "."); ?> Gram</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
} else {
    header("Location:urunler.php");
}
require_once 'inc/footer.php';
?>