<?php
session_start();
require_once '../Model/girisModel.php';

class girisController {
  private $girisModel;

  public function __construct() {
    $this->girisModel = new girisModel();
  }

  public function girisYap() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['girisYap'])) {
      $email = trim($_POST['email']);
      $sifre = trim($_POST['sifre']);
      $userOrStatus = $this->girisModel->girisYap($email, $sifre); // Değişiklik yapıldı

      if (is_array($userOrStatus)) {
        $_SESSION['id'] = $userOrStatus['id'];
        $_SESSION['firma'] = $userOrStatus['firma'];
        $_SESSION['rol'] = $userOrStatus['rol'];
        $_SESSION['durum'] = $userOrStatus['durum'];

        if ($_SESSION['durum'] == "aktif") {
          if ($_SESSION['rol'] == 'admin') {
            header('Location: ../Views/index.php'); // Admin sayfasının yolu düzeltilmiştir
          } else if ($_SESSION['rol'] == 'musteri') {
            header('Location: ../Views/index.php'); // Kullanıcı sayfasının yolu düzeltilmiştir
          } else {
            echo 'Yetkiniz bulunmamaktadır.';
          }
          exit;
        } else {
          header('Location: ../Views/giris.php?durum=pasif');
          exit;
        }
      } else if ($userOrStatus === 'pasif') {
        header('Location: ../Views/giris.php?durum=pasif');
        exit;
      } else {
        header('Location: ../Views/giris.php?durum=sifreHatali'); 
        exit;
      }
    }

    // Çıkış işlemini kontrol et
    $this->cikisYap();
  }

  public function cikisYap() {
    if (isset($_GET['cikisYap']) && $_GET['cikisYap'] == 'true') {
      session_unset();
      session_destroy();
      header("Location: ../Views/giris.php");
      exit;
    }
  }
}
$controller = new girisController();
$controller->girisYap();
?>
