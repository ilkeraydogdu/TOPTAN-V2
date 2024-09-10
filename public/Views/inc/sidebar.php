
<div class="app-content main-content">
	<div class="side-app">
		<!--app header-->
		<div class="app-header header">
			<div class="container-fluid">
				<div class="d-flex">
					<div class="app-sidebar__toggle" data-toggle="sidebar">
						<a class="open-toggle" href="index.html#">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-align-left header-icon mt-1"><line x1="17" y1="10" x2="3" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="17" y1="18" x2="3" y2="18"></line></svg>
						</a>
					</div>
					<a class="header-brand" href="<?php echo URL ?>/index.php">
						<img src="<?php echo URL; ?>/app/assets/images/brand/logo.png" class="header-brand-img desktop-lgo">
						<img src="<?php echo URL; ?>/app/assets/images/brand/logo1.png" class="header-brand-img dark-logo">
						<img src="<?php echo URL; ?>/app/assets/images/brand/favicon.png" class="header-brand-img mobile-logo">
						<img src="<?php echo URL; ?>/app/assets/images/brand/favicon1.png" class="header-brand-img darkmobile-logo">
					</a>
					<div class="d-flex order-lg-2 ml-auto">
						
						<div class="dropdown   header-fullscreen" >
							<a  class="nav-link icon full-screen-link p-0"  id="fullscreen-button">
								<svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M10 4L8 4 8 8 4 8 4 10 10 10zM8 20L10 20 10 14 4 14 4 16 8 16zM20 14L14 14 14 20 16 20 16 16 20 16zM20 8L16 8 16 4 14 4 14 10 20 10z"/></svg>
							</a>
						</div>
						<div class="dropdown profile-dropdown">
							<a class="nav-link icon" data-toggle="dropdown" aria-expanded="false">
								<svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24">
									<path d="M4 4v4l4-4zm12 0l4 4V4zm4 16v-4l-4 4zM4 20h4l-4-4zm15-8c0-3.87-3.13-7-7-7s-7 3.13-7 7 3.13 7 7 7 7-3.13 7-7zm-7 5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"></path>
								</svg>
							</a>
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow animated">
								<div class="switch_section">
									<div class="switch-toggle d-flex">
										<h6 class="mr-auto">Gündüz Mod</h6>
										<div class="onoffswitch2">
											<input type="radio" name="onoffswitch2" id="myonoffswitch3" class="onoffswitch2-checkbox">
											<label for="myonoffswitch3" class="onoffswitch2-label"></label>
										</div>
									</div>
									<div class="switch-toggle d-flex">
										<h6 class="mr-auto">Karanlık Mod</h6>
										<div class="onoffswitch2">
											<input type="radio" name="onoffswitch2" id="myonoffswitch14" class="onoffswitch2-checkbox">
											<label for="myonoffswitch14" class="onoffswitch2-label"></label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<script>
							document.addEventListener('DOMContentLoaded', function () {
								const dayModeRadio = document.getElementById('myonoffswitch3');
								const nightModeRadio = document.getElementById('myonoffswitch14');

								const theme = localStorage.getItem('theme');
								if (theme === 'dark') {
									document.body.classList.add('dark-mode');
									nightModeRadio.checked = true;
								} else {
									document.body.classList.remove('dark-mode');
									dayModeRadio.checked = true;
								}

								dayModeRadio.addEventListener('change', function () {
									if (this.checked) {
										document.body.classList.remove('dark-mode');
										localStorage.setItem('theme', 'light');
									}
								});

								nightModeRadio.addEventListener('change', function () {
									if (this.checked) {
										document.body.classList.add('dark-mode');
										localStorage.setItem('theme', 'dark');
									}
								});
							});
						</script>


						<div class="dropdown header-notify">
							<a class="nav-link icon" data-toggle="dropdown">
								<svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M11 9h2V6h3V4h-3V1h-2v3H8v2h3v3zm-4 9c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2zm-8.9-5h7.45c.75 0 1.41-.41 1.75-1.03l3.86-7.01L19.42 4l-3.87 7H8.53L4.27 2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2z"></path></svg>
								<span class="pulse "></span>
							</a>
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow  animated">
								<div class="dropdown-header">
									<h6 class="mb-0">Sepet</h6>
								</div>
								<div class="notify-menu"  id="cart-content">
									<?php
									require_once '../Controller/urunController.php';
									require_once '../Model/urunModel.php';
									$urunController = new urunController();
									if (isset($_SESSION['sepet']) && !empty($_SESSION['sepet'])) {
										foreach ($_SESSION['sepet'] as $urunId => $miktar) {
											$urunBilgileri = $urunController->detay($urunId);
											if ($urunBilgileri) {
												$foto = $urunBilgileri['foto'];
												$urunAdi = $urunBilgileri['isim'];
												$urunGram = $urunBilgileri['gram'];
												?>
												<a href="index.html#" class="dropdown-item border-bottom d-flex pl-4 sepet-urun" data-urun-id="<?php echo $urunId; ?>">
													<div class="notifyimg bg-primary-transparent text-primary"> <img src="<?php echo URL; ?>/app/assets/images/products/<?php echo $foto ?>" alt="img" class="avatar avatar-md brround"></i> </div>
													<div>
														<div class="font-weight-normal1"><?php echo $urunAdi . ' (' . $urunGram . ' gr)'; ?></div>
														<div class="small text-muted"><?php echo $miktar; ?> Adet</div>
													</div>
												</a>
												<?php
											}
										}
									} else {
										echo '<div class="dropdown-item text-center">Sepetinizde ürün bulunmamaktadır.</div>';
									}
									?>
								</div>
								<script type="text/javascript">
									document.querySelectorAll('.btn-secondary').forEach(function(button) {
										button.addEventListener('click', function(event) {
											event.preventDefault(); 	
											var urunId = this.form.querySelector('input[name="urun_id"]').value;
											var miktar = this.form.querySelector('input[name="miktar"]').value;
											var xhr = new XMLHttpRequest();
											xhr.open('POST', 'sepet.php');
											xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
											xhr.onload = function() {
												if (xhr.status === 200) {
													var sepetIcerigi = JSON.parse(xhr.responseText);
													updateSidebarSepet(sepetIcerigi);
													showSepetEklemeBildirimi(urunId, miktar);
												} else {
													console.error('Hata:', xhr.statusText);
												}
											};
											xhr.send('urun_id=' + urunId + '&miktar=' + miktar);
										});
									});
									function updateSidebarSepet(sepetIcerigi) {
										document.getElementById('cart-content').innerHTML = '';
										for (var urunId in sepetIcerigi) {
											var urun = sepetIcerigi[urunId];
											var html = `
											<a href="#" class="dropdown-item border-bottom d-flex pl-4 sepet-urun" data-urun-id="${urunId}">
											<div class="notifyimg bg-primary-transparent text-primary"> 
											<img src="<?php echo URL; ?>/app/assets/images/products/${urun.foto}" alt="img" class="avatar avatar-md brround">
											</div>
											<div>
											<div class="font-weight-normal1">${urun.isim} (${urun.gram} gr)</div>
											<div class="small text-muted">${urun.miktar} Adet</div>
											</div>
											</a>
											`;
											document.getElementById('cart-content').innerHTML += html;
										}
									}
									function showSepetEklemeBildirimi(urunId, miktar) {
										var bildirim = document.createElement('div');
										bildirim.classList.add('sepet-bildirim');
										bildirim.textContent = `${miktar} adet "${urunBilgileri['isim']}" ürün sepete eklendi.`;
										document.body.appendChild(bildirim);

										setTimeout(function() {
											bildirim.style.opacity = '1';
										}, 100);

										setTimeout(function() {
											bildirim.style.opacity = '0';
											setTimeout(function() {
												bildirim.remove();
											}, 500);
										}, 5000);
									}
								</script>



								<div class=" text-center p-2 border-top">
									<a href="<?php echo URL ?>/public/Views/sepet.php" class="">Alışverişi Tamamla</a>
								</div>

							</div>
						</div>

						
						<div class="dropdown profile-dropdown">
							<a class="nav-link icon" data-toggle="dropdown">
								<svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24">
									<path d="M12 2C9.24 2 7 4.24 7 7c0 1.86.78 3.52 2.03 4.72C7.27 12.92 2 14.76 2 17v3h20v-3c0-2.24-5.27-4.08-7.03-5.28C16.22 10.52 17 8.86 17 7c0-2.76-2.24-5-5-5zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm9 9h-2v-3h-3v-2h3v-3h2v3h3v2h-3v3z"/>
								</svg>
							</a>
							<?php 
							if ($_SESSION['id']) {
								$id=$_SESSION['id'];
							}
							if ($_SESSION['firma']) {
								$isim=$_SESSION['firma'];
							}
							if ($_SESSION['rol']) {
								$rol=$_SESSION['rol'];
							}
							
							
							?>
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow animated">
								<div class="text-center">
									<a class="dropdown-item text-center user pb-0 font-weight-bold"><?php echo $isim; ?></a>
									<span class="text-center user-semi-title"><?php echo $rol ?></span>
									<div class="dropdown-divider"></div>
								</div>
								<a class="dropdown-item d-flex" href="<?php echo URL; ?>/public/Views/kullaniciDuzenle.php?id=<?php echo $id ?>">
									<svg class="header-icon mr-3" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M19.43 12.98c.04-.32.07-.64.07-.98 0-.34-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.09-.16-.26-.25-.44-.25-.06 0-.12.01-.17.03l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.06-.02-.12-.03-.18-.03-.17 0-.34.09-.43.25l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98 0 .33.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.09.16.26.25.44.25.06 0 .12-.01.17-.03l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.06.02.12.03.18.03.17 0 .34-.09.43-.25l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zm-1.98-1.71c.04.31.05.52.05.73 0 .21-.02.43-.05.73l-.14 1.13.89.7 1.08.84-.7 1.21-1.27-.51-1.04-.42-.9.68c-.43.32-.84.56-1.25.73l-1.06.43-.16 1.13-.2 1.35h-1.4l-.19-1.35-.16-1.13-1.06-.43c-.43-.18-.83-.41-1.23-.71l-.91-.7-1.06.43-1.27.51-.7-1.21 1.08-.84.89-.7-.14-1.13c-.03-.31-.05-.54-.05-.74s.02-.43.05-.73l.14-1.13-.89-.7-1.08-.84.7-1.21 1.27.51 1.04.42.9-.68c.43-.32.84-.56 1.25-.73l1.06-.43.16-1.13.2-1.35h1.39l.19 1.35.16 1.13 1.06.43c.43.18.83.41 1.23.71l.91.7 1.06-.43 1.27-.51.7 1.21-1.07.85-.89.7.14 1.13zM12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 6c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"></path></svg>
									<div class="">Hesabım</div>
								</a>

								<a class="dropdown-item d-flex" href="../Controller/girisController.php?cikisYap=true">
									<svg class="header-icon mr-3" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24" viewBox="0 0 24 24" width="24">
										<g><rect fill="none" height="24" width="24"/></g>
										<g><path d="M11,7L9.6,8.4l2.6,2.6H2v2h10.2l-2.6,2.6L11,17l5-5L11,7z M20,19h-8v2h8c1.1,0,2-0.9,2-2V5c0-1.1-0.9-2-2-2h-8v2h8V19z"/></g>
									</svg>
									<div class="">Çıkış Yap</div>
								</a>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>