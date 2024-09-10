<?php
session_start(); 
$rol = $_SESSION['rol']; 
if ($rol !== 'admin') {
	header('Location: index.php'); 
	exit; 
}
require_once 'inc/header.php';
require_once 'inc/sidebar.php';
require_once '../Controller/urunekleController.php';

$controller = new urunekleController($db);

$hataMesaji = ''; // Hata mesajını saklamak için bir değişken

// Ürün ekleme formu gönderildiğinde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$kategori_id = $_POST['kategori_id'];
	$isim = $_POST['isim'];
	$sKodu = $_POST['sKodu'];
	$aciklama = $_POST['aciklama'];
	$gram = $_POST['gram'];
	$foto = $_FILES['foto'];

	$hataMesaji = $controller->urunEkle($kategori_id, $isim, $sKodu, $aciklama, $gram, $foto);
}

$model = new urunekleModel($controller->db);
$kategoriler = $model->getKategoriler();

function kategoriListe($kategoriler, $ust_id = 0, $depth = 0) {
    // Verilen üst kategori ID'sine sahip kategorileri bul
	$altKategoriler = array_filter($kategoriler, function ($kategori) use ($ust_id) {
		return $kategori['ust_kategori'] == $ust_id;
	});

    // Eğer alt kategoriler varsa, liste oluştur
	if (!empty($altKategoriler)) {
        // İç içe geçmiş listeleme için gerekli boşlukları ekle
		$indent = str_repeat('&nbsp;&nbsp;', $depth);

        // Her bir alt kategori için işlem yap
		foreach ($altKategoriler as $kategori) {
			echo '<option value="' . $kategori['id'] . '">' . $indent . $kategori['kategori_adi'] . '</option>';

            // Alt kategorileri recursive olarak çağırarak iç içe geçmiş listeleme sağla
			kategoriListe($kategoriler, $kategori['id'], $depth + 1);
		}
	}
}
?>

<!-- Ürün ekleme formu -->
<br><br>
<div class="row">
	<div class="col-lg-6 col-md-6">
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
		
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Ürün Ekle</h3>
			</div>
			<div class="card-body">
				<form method="POST" action="" enctype="multipart/form-data">
					<div class="form-group">
						<label for="kategori_id">Kategori:</label>
						<select class="form-control" id="kategori_id" name="kategori_id">
							<?php kategoriListe($kategoriler); ?>
						</select>
					</div>
					<div class="form-group">
						<label for="isim">Ürün İsmi:</label>
						<input type="text" class="form-control" id="isim" name="isim">
					</div>
					<div class="form-group">
						<label for="sKodu">Ürün Stok Kodu:</label>
						<input type="text" class="form-control" id="sKodu" name="sKodu">
					</div>

					<div class="form-group">
						<label for="aciklama">Ürün Açıklaması:</label>
						<textarea class="form-control" id="aciklama" name="aciklama" rows="3"></textarea>
					</div>

					<div class="form-group">
						<label for="gram">Ürün Gramı:</label>
						<input type="text" class="form-control" id="gram" name="gram">
					</div>
					<div class="form-group">
						<label for="foto">Ürün Fotoğrafı:</label>
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="foto" name="foto">
							<label class="custom-file-label">Yeni Ürün Fotoğrafı Seç</label>
						</div>
					</div>
					<button type="submit" class="btn btn-primary">Ürünü Ekle</button>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
require_once 'inc/footer.php';
?>
