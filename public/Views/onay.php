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
require_once '../Controller/kullaniciDuzenleController.php';

$kullaniciController = new kullaniciController();
$kullanicilar = $kullaniciController->listele();

$onay = new kullaniciDuzenleController();
if (isset($_POST['onayla'])) {
	$id = $_POST['id'];
	$success = $onay->kullaniciOnayla($id);
	if ($success) {
		echo "<script>alert('Kullanıcı Onaylandı.'); window.location.href = 'kullanici.php';</script>";
        exit; // Ekleme: Yönlendirme yapıldıktan sonra betik çalışmasını sonlandırın.
    } else {
    	echo "<script>alert('Onaylama işlemi başarısız.');</script>";
    }
}

?>

<div class="page-header">
	<div class="page-leftheader">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#"><i class="fe fe-shopping-cart mr-2 fs-14"></i>Kabaloğlu Kuyumculuk Toptan</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#">Onay Bekleyen Üyeler</a></li>
		</ol>
	</div>
</div>

<div class="row flex-lg-nowrap">
	<div class="col-12">
		<div class="row flex-lg-nowrap">
			<div class="col-12 mb-3">
				<div class="e-panel card">
					<div class="card-header">
						<h3 class="card-title">Onay Bekleyen Üyeler</h3>
					</div>
					<div class="card-body">
						<?php 
						$bekleyenKullanicilarVar = false;
						foreach ($kullanicilar as $kullanici) {
							if ($kullanici['durum'] == "pasif") {
								$bekleyenKullanicilarVar = true;
								break;
							}
						}
						?>

						<?php if ($bekleyenKullanicilarVar) { ?>
							<div class="row">
								<!-- Kullanıcıları Listele -->
								<?php foreach ($kullanicilar as $kullanici) {
									if ($kullanici['durum'] == "pasif") { ?>
										<div class="col-lg-6">
											<div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
												<div class="wrapper ml-sm-3  mt-4 mt-sm-0">
													<h4 class="mb-0 mt-1 text-dark font-weight-semibold"><?php echo $kullanici['firma']; ?> </h4>
													<span><?php echo ($kullanici['rol'] == "musteri") ? "MÜŞTERİ" : ""; ?></span>
													<p class="text-muted"><?php if ($kullanici['durum'] == "pasif") {
														echo "Onay bekliyor...";
													} ?></p>
												</div>
												<div class="float-sm-right ml-auto mt-4 mt-sm-0">
													<form method="POST">
														<input type="hidden" name="id" value="<?php echo $kullanici['id']; ?>">
														<button type="submit" name="onayla" class="btn btn-success">Onayla</button>
													</form>
												</div>
											</div>
										</div>
									<?php }
								} ?>
							</div>
						<?php } else {
							echo '<div class="alert alert-info" role="alert">Onay Bekleyen Kullanıcı bulunamadı!</div>';
						} ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once 'inc/footer.php'; ?>
