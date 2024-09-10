<?php 
session_start(); 

require_once 'inc/header.php';
require_once 'inc/sidebar.php';
require_once '../Controller/kullaniciDuzenleController.php';

$kullaniciDuzenleController = new kullaniciDuzenleController();

// Check if user ID is provided in the URL
if (!isset($_GET['id'])) {
    // Redirect if user ID is not provided
	header('Location: kullanici.php');
	exit;
}


$id = $_GET['id'];

// Retrieve user information for the given ID
$kullanici = $kullaniciDuzenleController->kullaniciBilgisiGetir($id);

// Handle form submissions for updating user information and changing password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST['bilgileriGuncelle'])) {
		$firma = $_POST['firma'];
		$email = $_POST['email'];
		$tel = $_POST['tel'];
		$adres = $_POST['adres'];
		$durum = $_POST['durum'];
		$sonuc = $kullaniciDuzenleController->kullaniciBilgileriGuncelle($id, $firma, $email, $tel, $adres, $durum);
		
		if ($sonuc === true) {
			echo "<script>alert('Bilgiler başarıyla güncellendi.'); window.location.href = 'kullanici.php';</script>";
		} elseif (is_string($sonuc)) {
            // Display the specific duplicate error messages
			echo "<script>alert('$sonuc');</script>";
		} else {
			echo "<script>alert('Bilgiler güncellenirken hata oluştu.');</script>";
		}
	}
}


?>
<!-- Your HTML content goes here -->

<div class="page-header">
	<div class="page-leftheader">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#"><i class="fe fe-shopping-cart mr-2 fs-14"></i>Kabaloğlu Kuyumculuk Toptan</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#">Kullanıcı Düzenle</a></li>
		</ol>
	</div>
</div>
<div class="row">
	
	<div class="col-xl-3 col-lg-4">
		<form method="POST">
			<div class="card">
				<div class="card-header">
					<div class="card-title">Şifre Güncelle</div>
					<?php
					if (isset($_GET['durum']) && $_GET['durum'] == "sifreDegisti") {
						echo "Şifre değişikliği gerçekleşti";
					}
					if (isset($_GET['durum']) && $_GET['durum'] == "hata") {
						echo "Şifre değiştirirken hata oluştu";
					}
					if (isset($_GET['durum']) && $_GET['durum'] == "hata2") {
						echo "Şifre değişikliği olmadı";
					}
					?>
				</div>
				<div class="card-body">
					<div class="text-center mb-5">
						<div class="card-title"><?php echo $kullanici['firma']; ?></div>
						<label class="form-label"><?php if ($kullanici['rol']=="musteri") { echo "MÜŞTERİ"; } ?></label>
					</div>
					<!-- Şifre alanını göz simgesi ile kontrol etme -->
					<div class="input-group mb-4">
						<div class="input-group-prepend">
							<div class="input-group-text">
								<i class="fe fe-eye" id="togglePassword"></i>
							</div>
						</div>
						<input type="password" name="sifre" class="form-control" id="password" placeholder="Şifreniz">
					</div>
					<div class="input-group mb-4">
						<div class="input-group-prepend">
							<div class="input-group-text">
								<i class="fe fe-eye" id="toggleConfirmPassword"></i>
							</div>
						</div>
						<input type="password" name="sifre2" class="form-control" id="confirmPassword" placeholder="Tekrar Şifreniz">
					</div>
					<script>
						const togglePassword = document.querySelector('#togglePassword');
						const password = document.querySelector('#password');

						togglePassword.addEventListener('click', function (e) {
							const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
							password.setAttribute('type', type);
							this.classList.toggle('fe-eye-off');
						});

						const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
						const confirmPassword = document.querySelector('#confirmPassword');

						toggleConfirmPassword.addEventListener('click', function (e) {
							const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
							confirmPassword.setAttribute('type', type);
							this.classList.toggle('fe-eye-off');
						});
					</script>

				</div>
				<div class="card-footer text-right">
					<button type="submit" name="sifreDegistir" class="btn btn-primary">Şifre Değiştir</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-xl-9 col-lg-8">
		<div class="card">
			<div class="card-header">
				<div class="card-title">Bilgileri Düzenle</div>
			</div>
			<form method="POST">
				<div class="card-body">
					<div class="card-title font-weight-bold">Kişisel Bilgiler:</div>
					<div class="row">
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label class="form-label">Adı Soyadı:</label>
								<input type="text" class="form-control"  name="firma" value="<?php echo $kullanici['firma']; ?>" >
							</div>
						</div>
					</div>
					<div class="card-title font-weight-bold mt-5">Gerekli Bilgiler</div>
					<div class="row">
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label class="form-label">tel Numarası</label>
								<input type="text" class="form-control" name="tel" value="<?php echo $kullanici['tel']; ?>">
							</div>
						</div>
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label class="form-label">E-mail Adresi</label>
								<input type="email" class="form-control" name="email" value="<?php echo $kullanici['email']; ?>">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="form-label">Adres</label>
								<textarea rows="2" class="form-control" name="adres"><?php echo $kullanici['adres']; ?></textarea>
							</div>
						</div>
						<div class="col-md-6">
							<label class="form-label">Durum:</label>
							<select name="durum" class="form-control">
								<option value="aktif" <?php if ($kullanici['durum'] == 'aktif') echo 'selected'; ?>>Aktif</option>
								<option value="pasif" <?php if ($kullanici['durum'] == 'pasif') echo 'selected'; ?>>Pasif</option>
							</select>
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
