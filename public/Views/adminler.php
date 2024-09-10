<?php
session_start(); 
$rol = $_SESSION['rol']; 
if ($rol !== 'admin') {
	header('Location: index.php'); 
	exit; 
}
require_once 'inc/header.php';
require_once 'inc/sidebar.php'; 
require_once '../Controller/kullaniciController.php';
$kullaniciController = new kullaniciController;
if (isset($_POST['ara'])) {
	$arananKelime = trim($_POST['arananKelime']);
	$kullanicilar = $kullaniciController->listele($arananKelime);
} else {
	$kullanicilar = $kullaniciController->listele();
}

if (isset($_POST['sil'])) {
	$id = $_POST['sil_id'];
	$kullaniciController->sil($id);
    // Redirect back to the same page after deletion
	header("Location: ".$_SERVER['PHP_SELF']);
	exit;
}

?>

<div class="page-header">
	<div class="page-leftheader">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#"><i class="fe fe-shopping-cart mr-2 fs-14"></i>Kabaloğlu Kuyumculuk Toptan</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#">Yöneticiler</a></li>
		</ol>
	</div>
</div>

<div class="row flex-lg-nowrap">
	<div class="col-12">
		<div class="row flex-lg-nowrap">
			<div class="col-12 mb-3">
				<div class="e-panel card">
					<div class="card-body">
						<div class="row">
							<div class="col-6 mb-4">
								<a href="kullaniciEkle.php" class="btn btn-primary"><i class="fe fe-plus"></i>Yeni Üye Ekle</a>
							</div>
							<div class="col-6 col-auto">
								<form method="POST">
									<div class="input-group mb-2">
										<input type="text" class="form-control" name="arananKelime" placeholder="Yönetici Ara" value="<?php echo isset($arananKelime) ? $arananKelime : ''; ?>">
										<span class="input-group-append">
											<button class="btn ripple btn-primary" type="submit" name="ara">Ara</button>
										</span>
									</div>
									
								</form>
							</div>
						</div>
						<div class="row">
							<?php foreach ($kullanicilar as $kullanici) : ?>
								<?php if ($kullanici['rol'] == "admin" && $kullanici['durum'] == "aktif") : ?>
									<div class="col-lg-6">
										<div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
											<div class="wrapper ml-sm-3 mt-4 mt-sm-0">
												<h4 class="mb-0 mt-1 text-dark font-weight-semibold"><?php echo $kullanici['firma']; ?></h4>
												<p class="text-muted"><?php echo $kullanici['rol']; ?></p>
											</div>
											<div class="float-sm-right ml-auto mt-4 mt-sm-0">
												<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
													<input type="hidden" name="sil_id" value="<?php echo $kullanici['id']; ?>">
													<button type="submit" name="sil" class="btn btn-outline-secondary" onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')">Sil</button>
													<a href="kullaniciDuzenle.php?id=<?php echo $kullanici['id']; ?>" class="btn btn-primary">Düzenle</a>
												</form>
											</div>
										</div>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once 'inc/footer.php'; ?>
