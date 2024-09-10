<?php
session_start();
require_once '../../app/config/DB.php';
$kullaniciId = $_SESSION['id'] ?? null;

if ($kullaniciId) {
	header("Location: index.php");
	exit;
}
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


?>
<!DOCTYPE html>
<html>

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<title>Kabaloğlu Kuyumculuk Toptan | Pofuduk Dijital</title>
	<link rel="icon" href="<?php echo URL ?>/app/assets/images/brand/favicon.ico" type="image/x-icon"/>
	<link href="<?php echo URL ?>/app/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo URL ?>/app/assets/css/style.css" rel="stylesheet" />
	<link href="<?php echo URL ?>/app/assets/css/dark.css" rel="stylesheet" />
	<link href="<?php echo URL ?>/app/assets/css/skin-modes.css" rel="stylesheet" />
	<link href="<?php echo URL ?>/app/assets/css/animated.css" rel="stylesheet" />
	<link href="<?php echo URL ?>/app/assets/css/icons.css" rel="stylesheet" />
	<link id="theme" href="<?php echo URL ?>/app/assets/colors/color1.css" rel="stylesheet" type="text/css"/>
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
												<h1 class="mb-2">Kayıt Ol</h1>
												<br><br>
												<?php if (!empty($hataMesaji)) : ?>
													<ul class="nav1 bg-danger mt-4 br-7">
														<li class="nav-item1">
															<a class="nav-link text-white active"><?php echo $hataMesaji; ?></a>
														</li>
													</ul>
												<?php endif; ?>
											</div>
											<form method="POST" action="../Controller/kayitController.php" >
												<input type="hidden" name="referer" value="<?php echo basename($_SERVER['PHP_SELF']); ?>">
												<div class="input-group mb-4">
													<div class="input-group-prepend">
														<div class="input-group-text">
															<i class="fe fe-user"></i>
														</div>
													</div>
													<input type="text" name="firma" class="form-control" placeholder="Firma Adı">
												</div>
												<div class="input-group mb-4">
													<div class="input-group-prepend">
														<div class="input-group-text">
															<i class="fe fe-user"></i>
														</div>
													</div>
													<input type="email" name="email" class="form-control" placeholder="Email Adresiniz">
												</div>
												<div class="input-group mb-4">
													<div class="input-group-prepend">
														<div class="input-group-text">
															<i class="fe fe-user"></i>
														</div>
													</div>
													<input type="text" name="tel" class="form-control" placeholder="Telefon Numaranız "value="+90">
												</div>
												<div class="input-group mb-4">
													<div class="input-group-prepend">
														<div class="input-group-text">
															<i class="fe fe-user"></i>
														</div>
													</div>
													<input type="hidden" name="adres">
													<textarea name="adres" rows="5" class="form-control" placeholder="Adres"></textarea>
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

												<div class="row">
													<div class="col-12">
														<button type="submit" class="btn btn-primary btn-block px-4" name="kayitOl">Kayıt Ol</button>
													</div>
												</div>
												<div class="text-center pt-4">
													<div class="font-weight-normal fs-16">Zaten Bir Hesabın Varmı? <a class="btn-link font-weight-normal" href="<?php echo URL; ?>/public/Views/giris.php"> Giriş Yap</a></div>
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
											<img src="<?php echo URL ?>/app/assets/images/png/login.png" alt="img">
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
	<script src="<?php echo URL ?>/app/assets/js/jquery-3.5.1.min.js"></script>
	<script src="<?php echo URL ?>/app/assets/plugins/bootstrap/popper.min.js"></script>
	<script src="<?php echo URL ?>/app/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo URL ?>/app/assets/plugins/othercharts/jquery.sparkline.min.js"></script>
	<script src="<?php echo URL ?>/app/assets/js/circle-progress.min.js"></script>
	<script src="<?php echo URL ?>/app/assets/plugins/rating/jquery.rating-stars.js"></script>
	<script src="<?php echo URL ?>/app/assets/js/custom.js"></script>	
</body>
</html>