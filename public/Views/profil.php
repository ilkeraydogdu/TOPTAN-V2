<?php 
require_once 'inc/header.php';
require_once 'inc/sidebar.php';
require_once '../Controller/kullaniciDuzenleController.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect to login page if not logged in
	header('Location: login.php');
	exit;
}

$userId = $_SESSION['id'];

$kullaniciDuzenleController = new kullaniciDuzenleController();

// Retrieve user information for the logged-in user
$kullanici = $kullaniciDuzenleController->kullaniciBilgisiGetir($userId);

// Handle form submissions for updating user information and changing password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST['bilgileriGuncelle'])) {
		$firma = $_POST['firma'];
		$email = $_POST['email'];
		$tel = $_POST['tel'];
		$adres = $_POST['adres'];
		$durum = $_POST['durum'];
		$sonuc = $kullaniciDuzenleController->kullaniciBilgileriGuncelle($userId, $firma, $email, $tel, $adres, $durum);
		if ($sonuc) {
			echo "<script>alert('Bilgiler başarıyla güncellendi.'); window.location.href = 'profil.php';</script>";
		} else {
			echo "<script>alert('Bilgiler güncellenirken hata oluştu.');</script>";
		}
	} elseif (isset($_POST['sifreDegistir'])) {
		$sifre = $_POST['sifre'];
		$sifre2 = $_POST['sifre2'];
		if ($sifre === $sifre2) {
			$hashedPassword = password_hash($sifre, PASSWORD_DEFAULT);
			$sonuc = $kullaniciDuzenleController->sifreGuncelle($userId, $hashedPassword);
			if ($sonuc) {
				echo "<script>alert('Şifre başarıyla güncellendi.'); window.location.href = 'profil.php';</script>";
			} else {
				echo "<script>alert('Şifre güncellenirken hata oluştu.');</script>";
			}
		} else {
			echo "<script>alert('Şifreler eşleşmiyor.');</script>";
		}
	}
}
?>
<!-- Your HTML content goes here -->

<div class="page-header">
	<div class="page-leftheader">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#"><i class="fe fe-shopping-cart mr-2 fs-14"></i>Kabaloğlu Kuyumculuk Toptan</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#">Profilim</a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xl-3 col-lg-4">
		<form method="POST">
			<div class="card">
				<div class="card-header">
					<div class="card-title">Şifre Güncelle</div>
				</div>
				<div class="card-body">
					<div class="text-center mb-5">
						<div class="card-title"><?php echo htmlspecialchars($kullanici['firma']); ?></div>
						<label class="form-label"><?php echo $kullanici['rol'] == "musteri" ? "MÜŞTERİ" : ""; ?></label>
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
								<input type="text" class="form-control"  name="firma" value="<?php echo htmlspecialchars($kullanici['firma']); ?>">
							</div>
						</div>
					</div>
					<div class="card-title font-weight-bold mt-5">Gerekli Bilgiler</div>
					<div class="row">
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label class="form-label">Tel Numarası</label>
								<input type="text" class="form-control" name="tel" value="<?php echo htmlspecialchars($kullanici['tel']); ?>">
							</div>
						</div>
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label class="form-label">E-mail Adresi</label>
								<input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($kullanici['email']); ?>">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="form-label">Adres</label>
								<textarea rows="2" class="form-control" name="adres"><?php echo htmlspecialchars($kullanici['adres']); ?></textarea>
							</div>
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
