<?php
require_once '../../app/config/DB.php';
require_once '../Model/kategoriModel.php';

$kategoriModel = new kategoriModel($db);

if (isset($_POST['ekle'])) {
	$kategoriAdi = trim($_POST['kategori_adi']);
	$ustKategori = $_POST['ust_kategori'];

    // Kategori adının boş olup olmadığını kontrol et
	if (empty($kategoriAdi)) {
		$errorMessage = 'Kategori adı boş bırakılamaz.';
		header("Location: ../Views/kategoriler.php?error=" . urlencode($errorMessage));
		exit;
	}

    // Kategori adı zaten mevcut mu kontrol et
	if ($kategoriModel->kategoriAdiVarMi($kategoriAdi, $ustKategori)) {
		$errorMessage = 'Bu kategori adı zaten mevcut.';
		header("Location: ../Views/kategoriler.php?error=" . urlencode($errorMessage));
		exit;
	}

	if ($kategoriModel->ekle($kategoriAdi, $ustKategori)) {
		$successMessage = 'Kategori başarıyla eklendi.';
	} else {
		$errorMessage = 'Kategori ekleme işlemi başarısız.';
	}
	header("Location: ../Views/kategoriler.php");
	exit;
}

if (isset($_POST['duzenle'])) {
	$id = $_POST['edit_id'];
	$kategoriAdi = trim($_POST['kategori_adi']);
	$ustKategori = $_POST['ust_kategori'];

    // Kategori adının boş olup olmadığını kontrol et
	if (empty($kategoriAdi)) {
		$errorMessage = 'Kategori adı boş bırakılamaz.';
		header("Location: ../Views/kategoriler.php?error=" . urlencode($errorMessage));
		exit;
	}

    // Kategori adı zaten mevcut mu kontrol et
	if ($kategoriModel->kategoriAdiVarMi($kategoriAdi, $ustKategori, $id)) {
		$errorMessage = 'Bu kategori adı zaten mevcut.';
		header("Location: ../Views/kategoriler.php?error=" . urlencode($errorMessage));
		exit;
	}

	if ($kategoriModel->duzenle($id, $kategoriAdi, $ustKategori)) {
		$successMessage = 'Kategori başarıyla düzenlendi.';
	} else {
		$errorMessage = 'Kategori düzenleme işlemi başarısız.';
	}
}

header("Location: ../Views/kategoriler.php");
exit;

?>
