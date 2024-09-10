<?php
require_once '../../app/config/DB.php'; // Veritabanı bağlantısı

function kontrolVarMi($field, $value) {
  global $db;
  $sql = "SELECT COUNT(*) FROM kullanici WHERE $field = ?";
  $stmt = $db->prepare($sql);
  $stmt->execute([$value]);
  return $stmt->fetchColumn() > 0;
}

function kaydet($firma, $email, $tel, $adres, $sifre, $ip, $token, $rol = 'musteri', $durum = 'pasif') {
  global $db;
  try {
    $sql = "INSERT INTO kullanici (firma, email, tel, adres, sifre, ip, onay_token, rol, durum) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$firma, $email, $tel, $adres, $sifre, $ip, $token, $rol, $durum]);
    return $stmt->rowCount() > 0;
  } catch (PDOException $e) {
    throw new Exception("Veritabanı hatası: " . $e->getMessage());
  }
}

function getKullaniciByToken($token) {
  global $db;
  $sql = "SELECT * FROM kullanici WHERE onay_token = ?";
  $stmt = $db->prepare($sql);
  $stmt->execute([$token]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function activateUser($id) {
  global $db;
  $sql = "UPDATE kullanici SET durum = 'aktif', onay_token = NULL WHERE id = ?";
  $stmt = $db->prepare($sql);
  return $stmt->execute([$id]);
}

function validateInput($data) {
  return htmlspecialchars(strip_tags(trim($data)));
}

function hashPassword($password) {
  return password_hash($password, PASSWORD_DEFAULT);
}
?>
