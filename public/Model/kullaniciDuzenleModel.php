<?php
require_once '../../app/config/DB.php';

class kullaniciDuzenleModel {
  private $db;

  public function __construct() {
    $this->db = getDbConnection();
  }

  public function kullaniciBilgisiGetir($id) {
    $query = "SELECT * FROM kullanici WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);
    return $kullanici;
  }
  public function checkDuplicate($firma, $email, $tel, $currentId) {
    $query = "SELECT firma, email, tel FROM kullanici WHERE id != :currentId AND (firma = :firma OR email = :email OR tel = :tel)";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(":firma", $firma);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":tel", $tel);
    $stmt->bindParam(":currentId", $currentId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  public function kullaniciBilgileriGuncelle($id, $firma, $email, $tel, $adres, $durum) {
    $query = "UPDATE kullanici SET firma = :firma, email = :email, tel = :tel, adres = :adres, durum = :durum WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":firma", $firma);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":tel", $tel); 
    $stmt->bindParam(":adres", $adres); 
    $stmt->bindParam(":durum", $durum);
    return $stmt->execute();
  }

  public function kullaniciOnayla($id) {
    $durum = 'aktif'; // Onaylandığında durum 'aktif' olarak güncellenecek
    $query = "UPDATE kullanici SET durum = :durum WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":durum", $durum);
    return $stmt->execute();
  }

  public function sifreGuncelle($id, $sifre) {
    $query = "UPDATE kullanici SET sifre = :sifre WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":sifre", $sifre);
    return $stmt->execute();
  }
}
?>
