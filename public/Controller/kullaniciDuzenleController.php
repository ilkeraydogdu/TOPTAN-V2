<?php
require_once '../Model/kullaniciDuzenleModel.php';

class KullaniciDuzenleController {
  private $kullaniciDuzenleModel;

  public function __construct() {
    $this->kullaniciDuzenleModel = new KullaniciDuzenleModel();
  }

  public function kullaniciBilgisiGetir($id) {
    return $this->kullaniciDuzenleModel->kullaniciBilgisiGetir($id);
  }
  public function kullaniciBilgileriGuncelle($id, $firma, $email, $tel, $adres, $durum) {
    // Check for duplicates
    $duplicateData = $this->kullaniciDuzenleModel->checkDuplicate($firma, $email, $tel, $id);

    if ($duplicateData) {
        // Return specific error messages
      $errorMessages = [];
      if ($duplicateData['firma'] == $firma) {
        $errorMessages[] = "Bu firma adı zaten mevcut.";
      }
      if ($duplicateData['email'] == $email) {
        $errorMessages[] = "Bu e-posta adresi zaten mevcut.";
      }
      if ($duplicateData['tel'] == $tel) {
        $errorMessages[] = "Bu telefon numarası zaten mevcut.";
      }
      return implode(' ', $errorMessages);
    }

    // Proceed with update if no duplicates
    return $this->kullaniciDuzenleModel->kullaniciBilgileriGuncelle($id, $firma, $email, $tel, $adres, $durum);
  }

  public function kullaniciOnayla($id) {
    $success = $this->kullaniciDuzenleModel->kullaniciOnayla($id);
    return $success;
  }

  public function sifreGuncelle($id, $sifre) {
    return $this->kullaniciDuzenleModel->sifreGuncelle($id, $sifre);
  }
}
?>
