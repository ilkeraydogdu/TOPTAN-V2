<?php
session_start(); 
$rol = $_SESSION['rol']; 
if ($rol !== 'admin') {
	header('Location: index.php'); 
	exit; 
}
require_once 'inc/header.php';
require_once 'inc/sidebar.php';

$hataMesaji = "";
if (!empty($_GET['durum'])) {
	switch ($_GET['durum']) {
		case 'sifreHatali':
		$hataMesaji = "GİRİLEN ŞİFRELER UYUŞMUYOR!";
		break;
		case 'domainHatali':
		$hataMesaji = "GİRİLEN MAİL ADRESİ GEÇERSİZ!";
		break;
		case 'basarisiz':
		if (!empty($_GET['mesaj'])) {
			$hataMesaji = htmlspecialchars($_GET['mesaj']);
		} else {
			$hataMesaji = "KAYIT OLUNURKEN HATA OLUŞTU!";
		}
		break;
		default:
		$hataMesaji = "";
	}
}

$isAdmin = $_SESSION['rol'] === 'admin'; // Kullanıcının admin olup olmadığını kontrol et

?>

<div class="page-header">
	<div class="page-leftheader">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#"><i class="fe fe-shopping-cart mr-2 fs-14"></i>Kabaloğlu Kuyumculuk Toptan</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#">Kullanıcı Ekle</a></li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-6 col-md-6">
		<?php if ($hataMesaji) { ?>
			<div class="card">
				<div class="card-header">
					<h3 class="card-title" style="color:orange;"><?php echo $hataMesaji; ?></h3> 
				</div>
			</div>
		<?php } ?>
	</div>
</div>

<div class="row">
	<div class="col-lg-6 col-md-6">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Kullanıcı Ekle</h3>
			</div>
			<div class="card-body">
				<form method="POST" action="../Controller/kayitController.php">
					<input type="hidden" name="referer" value="<?php echo basename($_SERVER['PHP_SELF']); ?>">
					<div class="form-group">
						<label class="form-label">Firma Adı</label>
						<input class="form-control" type="text" name="firma" placeholder="Yağmur Kuyumculuk">
					</div>
					<div class="form-group">
						<label class="form-label">Email Adresi</label>
						<input class="form-control" type="email" name="email" placeholder="admin@test.com">
					</div>
					<div class="form-group">
						<label class="form-label">Telefon Numarası</label>
						<input class="form-control" type="text" name="tel" placeholder="0551xxxxxxx">
					</div>
					<div class="form-group">
						<label class="form-label">Adres</label>
						<input type="hidden" name="adres">
						<textarea name="adres" rows="3" class="form-control" placeholder="Açık Adresiniz"></textarea>
					</div>
					<div class="form-group">
						<label class="form-label">Şifre</label>
						<input class="form-control" type="password" name="sifre">
					</div>
					<div class="form-group">
						<label class="form-label">Şifre Tekrar</label>
						<input class="form-control" type="password" name="sifre2">
					</div>
					<?php if ($isAdmin) { ?>
						<div class="col-md-5">
							<div class="form-group">
								<label class="form-label">Rol</label>
								<select class="form-control" name="rol">
									<optgroup label="ROL">
										<option data-select2-id="5">--SEÇ--</option>
										<option value="admin">ADMİN</option>
										<option value="musteri">MÜŞTERİ</option>
									</optgroup>
								</select>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label class="form-label">Durum</label>
								<select class="form-control" name="durum">
									<optgroup label="DURUM">
										<option data-select2-id="5">--SEÇ--</option>
										<option value="aktif">AKTİF</option>
										<option value="pasif">PASİF</option>
									</optgroup>
								</select>
							</div>
						</div>
					<?php } ?>
					<div class="form-group mb-0 mt-4 row justify-content-end">
						<div class="col-md-12">
							<button type="submit"  class="btn btn-primary">Ekle</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php require_once 'inc/footer.php'; ?>
