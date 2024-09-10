<?php
session_start();
require_once '../../app/config/DB.php';
require_once '../Controller/mailController.php';
require_once '../Model/kayitModel.php';

$hataMesaji = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $firma = validateInput($_POST['firma']);
  $email = validateInput($_POST['email']);
  $tel = validateInput($_POST['tel']);
  $adres = validateInput($_POST['adres']);
  $sifre = validateInput($_POST['sifre']);
  $sifre2 = validateInput($_POST['sifre2']);
  $ip = $_SERVER['REMOTE_ADDR'];
    $rol = $_POST['rol'] ?? 'musteri'; // Varsayılan değer
    $durum = $_POST['durum'] ?? 'pasif'; // Varsayılan değer
    $referer = $_POST['referer'] ?? 'kayit.php'; // Varsayılan olarak 'kayit.php'

    // E-posta adresinin domain kısmını al
    $domain = explode("@", $email)[1] ?? '';

    // Domain kısmının var olup olmadığını kontrol et
    if ($email && $domain && !checkdnsrr($domain, "MX")) {
      header("location: ../Views/$referer?durum=domainHatali");
      exit;
    }

    if ($sifre === $sifre2) {
      $hataMesaji = [];
      $fields = ['firma' => $firma, 'tel' => $tel, 'email' => $email];

      foreach ($fields as $field => $value) {
        if (kontrolVarMi($field, $value)) {
          $hataMesaji[] = strtoupper($field);
        }
      }

      if (!empty($hataMesaji)) {
        $hataMesaji = "AYNI " . implode(", ", $hataMesaji) . " İLE KULLANICI MEVCUT.";
        header("location: ../Views/$referer?durum=basarisiz&mesaj=" . urlencode($hataMesaji));
        exit;
      }

        // Şifreleri hashleme
      $sifre = hashPassword($sifre);

        // Onay token'ı oluşturma
      $token = bin2hex(random_bytes(32));

        // Veri kaydetme işlemi
      if (kaydet($firma, $email, $tel, $adres, $sifre, $ip, $token, $rol, $durum)) {
        $mailController = new mailController();
        $mailController->sendConfirmationEmail($email, $token);

        header("location: ../Views/giris.php?durum=pasif&mesaj=Onay maili gönderildi, lütfen emailinizi kontrol edin.");
        exit;
      } else {
        header("location: ../Views/$referer?durum=basarisiz");
        exit;
      }
    } else {
      header("location: ../Views/$referer?durum=sifreHatali");
      exit;
    }
  }
  ?>
