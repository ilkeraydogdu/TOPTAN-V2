<?php
error_reporting(0); 
session_start();
$kullaniciId = $_SESSION['id'] ?? null;

if (!$kullaniciId) {
	header('Location: giris.php');
	exit;
}
require_once '../../app/config/DB.php';

?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<meta name="robots" content="noindex">
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>	
	<title>Kabaloğlu Kuyumculuk | Toptan Yönetim Paneli</title>
	<link rel="icon" href="<?php echo URL; ?>/app/assets/images/brand/favicon.ico" type="image/x-icon"/>
	<link href="<?php echo URL; ?>/app/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo URL; ?>/app/assets/css/style.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>/app/assets/css/dark.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>/app/assets/css/skin-modes.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>/app/assets/css/animated.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>/app/assets/css/sidemenu.css" rel="stylesheet">
	<link href="<?php echo URL; ?>/app/assets/plugins/p-scrollbar/p-scrollbar.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>/app/assets/css/icons.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>/app/assets/plugins/datatable/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
	<link href="<?php echo URL; ?>/app/assets/plugins/datatable/css/buttons.bootstrap4.min.css"  rel="stylesheet">
	<link href="<?php echo URL; ?>/app/assets/plugins/datatable/responsive.bootstrap4.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo URL; ?>/app/assets/plugins/simplebar/css/simplebar.css">
	<link id="theme" href="<?php echo URL; ?>/app/assets/colors/color1.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="<?php echo URL; ?>/app/assets/switcher/css/switcher.css">
	<link rel="stylesheet" href="<?php echo URL; ?>/app/assets/switcher/demo.css">
</head>
<body class="app sidebar-mini dark-mode">	
	<div class="page">
		<div class="page-main">
			<aside class="app-sidebar">
				<div class="app-sidebar__logo">
					<a class="header-brand" href="<?php echo URL; ?>/public/Views/index.php">
						<img src="<?php echo URL; ?>/app/assets/images/brand/logo.png" class="header-brand-img desktop-lgo" alt="Admintro logo">
						<img src="<?php echo URL; ?>/app/assets/images/brand/logo1.png" class="header-brand-img dark-logo" alt="Admintro logo">
						<img src="<?php echo URL; ?>/app/assets/images/brand/favicon.png" class="header-brand-img mobile-logo" alt="Admintro logo">
						<img src="<?php echo URL; ?>/app/assets/images/brand/favicon1.png" class="header-brand-img darkmobile-logo" alt="Admintro logo">
					</a>
				</div>
				<div class="app-sidebar__user">
					<ul class="side-menu app-sidebar3">
						
						<li class="slide">
							<a class="side-menu__item"  href="<?php echo URL; ?>/public/Views/index.php">
								<svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
									<path d="M0 0h24v24H0V0z" fill="none"/>
									<path d="M19 5v2h-4V5h4M9 5v6H5V5h4m10 8v6h-4v-6h4M9 17v2H5v-2h4M21 3h-8v6h8V3zM11 3H3v10h8V3zm10 8h-8v10h8V11zm-10 4H3v6h8v-6z"/>
								</svg>
								<span class="side-menu__label">Gösterge Paneli</span>
							</a>
						</li>

						<li class="side-item side-item-category">Ürün İşlemlerİ</li>
						<li class="slide">
							<a class="side-menu__item" data-toggle="slide" href="#">
								<svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
									<path d="M0 0h24v24H0V0z" fill="none"/>
									<path d="M19 3H5c-1.1 0-2 .9-2 2v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 6h-4c0 1.62-1.38 3-3 3s-3-1.38-3-3H5V5h14v4zm-4 7h6v3c0 1.1-.9 2-2 2H5c-1.1 0-2-.9-2-2v-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3z">
									</path>
								</svg>
								<span class="side-menu__label">Ürünler</span>
								<i class="angle fa fa-angle-right"></i>
							</a>
							<ul class="slide-menu ">
								<li><a class="slide-item" href="<?php echo URL; ?>/public/Views/urunler.php">Ürünler</a></li>
								<li><a class="slide-item" href="<?php echo URL; ?>/public/Views/siparislerim.php">Siparişlerim</a></li>
								<li><a class="slide-item" href="<?php echo URL; ?>/public/Views/sepet.php">Sepetim</a></li>
								<?php if ($_SESSION['rol'] == 'admin'): ?>
									<li><a class="slide-item" href="<?php echo URL; ?>/public/Views/urunekle.php">Ürün Ekle</a></li>
									<li><a class="slide-item" href="<?php echo URL; ?>/public/Views/kategoriler.php">Ürün Kategoriler</a></li>
								<?php endif; ?>
							</ul>
						</li>
						<?php if ($_SESSION['rol'] == 'admin'): ?>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
									<svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M22 9h-4.79l-4.38-6.56c-.19-.28-.51-.42-.83-.42s-.64.14-.83.43L6.79 9H2c-.55 0-1 .45-1 1 0 .09.01.18.04.27l2.54 9.27c.23.84 1 1.46 1.92 1.46h13c.92 0 1.69-.62 1.93-1.46l2.54-9.27L23 10c0-.55-.45-1-1-1zM12 4.8L14.8 9H9.2L12 4.8zM18.5 19l-12.99.01L3.31 11H20.7l-2.2 8zM12 13c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path></svg>
									<span class="side-menu__label">Tüm Siparişler</span>
									<i class="angle fa fa-angle-right"></i>
								</a>
								<ul class="slide-menu ">
									<li><a class="slide-item" href="<?php echo URL; ?>/public/Views/siparistum.php">Tüm Siparişler</a></li>
									<li><a class="slide-item" href="<?php echo URL; ?>/public/Views/onaybekleyensiparislerim.php">Onay Bekleyen Siparişler</a></li>
									<li><a class="slide-item" href="<?php echo URL; ?>/public/Views/hazirlaniyorsiparislerim.php">Hazırlanan Siparişler</a></li>
									<li><a class="slide-item" href="<?php echo URL; ?>/public/Views/onaylanansiparislerim.php">Onaylanan Siparişler</a></li>
									<li><a class="slide-item" href="<?php echo URL; ?>/public/Views/iptalsiparislerim.php">İptal Siparişler</a></li>

								</ul>
							</li>
						<?php endif; ?>

						<?php if ($_SESSION['rol'] == 'admin'): ?>
							<li class="side-item side-item-category">Üye İşlemleri</li>
							<li class="slide">
								<a class="side-menu__item" data-toggle="slide" href="#">
									<svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
										<path d="M0 0h24v24H0V0z" fill="none"/>
										<path d="M20 6v2h-2v2h-2V8h-2V6h2V4h2v2h2zm-8 4H8V8h4v2zm6 0h-4V8h4v2zm2-6H6v14h12V4zm-2 12h-8v-2h8v2z"/>
									</svg>
									<span class="side-menu__label">Üyeler</span>
									<i class="angle fa fa-angle-right"></i>
								</a>
								<ul class="slide-menu ">
									<li><a class="slide-item" href="<?php echo URL; ?>/public/Views/kullanici.php">Üyeler</a></li>
									<li><a class="slide-item" href="<?php echo URL; ?>/public/Views/adminler.php">Yöneticiler</a></li>
									<li><a class="slide-item" href="<?php echo URL; ?>/public/Views/onay.php">Onay Bekleyenler</a></li>
									
								</ul>
							</li>
						<?php endif; ?>
					</ul>
				</nav>
			</div>
		</aside>
		<!-- END: Side Menu -->