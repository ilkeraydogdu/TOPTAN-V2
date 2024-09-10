<?php
session_start();
require_once '../../app/config/DB.php';
require_once '../Controller/kullaniciController.php';

$kullaniciId = $_SESSION['id'] ?? null;

if ($kullaniciId) {
	header("Location: index.php");
	exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$email = $_POST['email'];
	$controller = new kullaniciController();
	$sonuc = $controller->sifreSifirlamaIstegi($email);
	if ($sonuc) {
		$_SESSION['sifre_sifirlama_mesaj'] = 'Şifre sıfırlama bağlantısı e-posta adresinize gönderildi.';
	} else {
		$_SESSION['sifre_sifirlama_mesaj'] = 'Bu e-posta adresi ile kayıtlı bir kullanıcı bulunamadı.';
	}
	header("Location: sifreSifirlama.php");
	exit;
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
												<h1 class="mb-2">Şifremi Unuttum</h1>
												<br><br>
												<?php
												if (isset($_SESSION['sifre_sifirlama_mesaj'])) {
													echo '<ul class="nav1 bg-info mt-4 br-7">
													<li class="nav-item1">
													<a class="nav-link text-white active">' . $_SESSION['sifre_sifirlama_mesaj'] . '</a>
													</li>
													</ul>';
													unset($_SESSION['sifre_sifirlama_mesaj']);
												}
												?>
											</div>
											<form method="POST" action="">
												<div class="input-group mb-4">
													<div class="input-group-prepend">
														<div class="input-group-text">
															<i class="fe fe-user"></i>
														</div>
													</div>
													<input type="email" name="email" class="form-control" required placeholder="Email Adresiniz">
												</div>
												<div class="row">
													<div class="col-12">
														<button type="submit" name="sifreSifirla" class="btn btn-primary btn-block px-4">Şifremi Sıfırla</button>
													</div>
												</div>
												<div class="col-12 text-center">
													<a href="<?php echo URL; ?>/public/Views/giris.php" class="btn btn-link box-shadow-0 px-0">Giriş Yap</a>
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
	<script src="<?php echo URL; ?>/app/assets/js/jquery-3.5.1.min.js"></script>
	<script src="<?php echo URL; ?>/app/assets/plugins/bootstrap/popper.min.js"></script>
	<script src="<?php echo URL; ?>/app/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo URL; ?>/app/assets/plugins/othercharts/jquery.sparkline.min.js"></script>
	<script src="<?php echo URL; ?>/app/assets/js/circle-progress.min.js"></script>
	<script src="<?php echo URL; ?>/app/assets/plugins/rating/jquery.rating-stars.js"></script>
	<script src="<?php echo URL; ?>/app/assets/js/custom.js"></script>
</body>
</html>
