<?php
session_start();

require_once '../../app/config/DB.php';
require_once '../Controller/kullaniciController.php';

$controller = new kullaniciController();
$kullaniciId = $_SESSION['id'] ?? null;

// Kullanıcı giriş yapmışsa ana sayfaya yönlendir
if ($kullaniciId) {
	header("Location: index.php");
	exit;
}

// Token'i URL'den al
$token = $_GET['token'] ?? null;

// Şifre sıfırlama formu gönderildiğinde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$yeniSifre = $_POST['sifre'];
	$sifreTekrar = $_POST['sifre_tekrar'];
	$token = $_POST['token'];

    // Şifrelerin eşleşip eşleşmediğini kontrol et
	if ($yeniSifre !== $sifreTekrar) {
		$_SESSION['mesaj'] = 'Şifreler eşleşmiyor. Lütfen tekrar deneyin.';
	} else {
		$sonuc = $controller->sifreSifirla($token, $yeniSifre);
		if ($sonuc) {
			$_SESSION['mesaj'] = 'Şifreniz başarıyla güncellendi.';
			header("Location: giris.php");
			exit;
		} else {
			$_SESSION['mesaj'] = 'Şifre sıfırlama başarısız. Geçersiz veya süresi dolmuş bağlantı.';
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<title>Kabaloğlu Kuyumculuk Toptan | Pofuduk Dijital</title>
	<link rel="icon" href="<?php echo URL; ?>/app/assets/images/brand/favicon.ico" type="image/x-icon"/>
	<link href="<?php echo URL; ?>/app/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo URL; ?>/app/assets/css/style.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>/app/assets/css/dark.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>/app/assets/css/skin-modes.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>/app/assets/css/animated.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>/app/assets/css/icons.css" rel="stylesheet" />
	<link id="theme" href="<?php echo URL; ?>/app/assets/colors/color1.css" rel="stylesheet" type="text/css"/>
</head>
<body class="h-100vh page-style1 dark-mode">
	<div class="page">
		<div class="page-single">
			<div class="p-5">
				<div class="row">
					<div class="col mx-auto">
						<div class="row justify-content-center">
							<div class="col-lg-9 col-xl-8">
								<div class="card-group mb-0">
									<div class="card p-4">
										<div class="card-body">
											<div class="text-center title-style mb-6">
												<h1 class="mb-2">Yeni Şifre </h1>
												<br><br>
												<?php
												if (isset($_SESSION['mesaj'])) {
													echo '<p>' . $_SESSION['mesaj'] . '</p>';
													unset($_SESSION['mesaj']);
												}
												?>
											</div>
											<form method="POST" action="">
												<input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
												<div class="input-group mb-4">
													<div class="input-group-prepend">
														<div class="input-group-text">
															<i class="fe fe-eye" id="togglePassword"></i>
														</div>
													</div>
													<input type="password" name="sifre" class="form-control" id="password" placeholder="Şifreniz" required>
												</div>
												<div class="input-group mb-4">
													<div class="input-group-prepend">
														<div class="input-group-text">
															<i class="fe fe-eye" id="toggleConfirmPassword"></i>
														</div>
													</div>
													<input type="password" name="sifre_tekrar" class="form-control" id="confirmPassword" placeholder="Tekrar Şifreniz" required>
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
												<div class="row">
													<div class="col-12">
														<button type="submit" name="sifreYenile" class="btn btn-primary btn-block px-4">Şifremi Yenile</button>
													</div>
												</div>
											</form>
										</div>
									</div>
									<div class="card text-white bg-primary py-5 d-md-down-none page-content mt-0">
										<div class="text-center justify-content-center page-single-content">
											<div class="box">
												<div></div>
												<div></div>
												<div></div>
												<div></div>
												<div></div>
												<div></div>
												<div></div>
												<div></div>
												<div></div>
												<div></div>
											</div>
											<img src="<?php echo URL; ?>/app/assets/images/png/login.png">
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
	<script src
