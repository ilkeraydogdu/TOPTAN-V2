<?php
require_once '../../app/config/DB.php';
class UrunModel {
  private $db;
  public function __construct() {
    $this->db = getDbConnection();
  }
  public function getAllUrunler() {
    $query = "SELECT u.*, k.kategori_adi FROM urunler u LEFT JOIN kategoriler k ON u.kategori_id = k.id WHERE u.durum = 'aktif'";
    $statement = $this->db->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }
  public function getPasifUrunler() {
    $query = "SELECT * FROM urunler WHERE durum = 'pasif'";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function aktifYap($urunId) {
    try {
      $stmt = $this->db->prepare("UPDATE urunler SET durum = 'aktif' WHERE id = :urunId");
      $stmt->bindParam(":urunId", $urunId, PDO::PARAM_INT);
      $stmt->execute();
      
      $etkilenenSatir = $stmt->rowCount();
      
      if ($etkilenenSatir > 0) {
        return ['success' => true, 'message' => 'Ürün aktif hale getirildi.'];
      } else {
        return ['success' => false, 'message' => 'Hiçbir satır etkilenmedi veya ürün zaten aktif.'];
      }
    } catch (PDOException $e) {
      error_log("Hata oluştu: " . $e->getMessage());
      return ['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()];
    }
  }

  public function pasifYap($urunId) {
    try {
      $stmt = $this->db->prepare("UPDATE urunler SET durum = 'pasif' WHERE id = :urunId");
      $stmt->bindParam(":urunId", $urunId, PDO::PARAM_INT);
      $stmt->execute();
      
      $etkilenenSatir = $stmt->rowCount();
      
      if ($etkilenenSatir > 0) {
        return ['success' => true, 'message' => 'Ürün pasif hale getirildi.'];
      } else {
        return ['success' => false, 'message' => 'Hiçbir satır etkilenmedi veya ürün zaten pasif.'];
      }
    } catch (PDOException $e) {
      error_log("Hata oluştu: " . $e->getMessage());
      return ['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()];
    }
  }
  public function getir($urunId) {
    $query = "SELECT *, (SELECT foto FROM urunler WHERE id = :urunId) AS foto FROM urunler WHERE id = :urunId";
    $statement = $this->db->prepare($query);
    $statement->bindParam(":urunId", $urunId);
    $statement->execute();
    $urun = $statement->fetch(PDO::FETCH_ASSOC);
    return $urun ? $urun : null;
  }
  public function getById($id) {
    $stmt = $this->db->prepare("SELECT * FROM urunler WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  public function searchUrunler($searchTerm) {
    $query = "SELECT * FROM urunler WHERE (isim LIKE :searchTerm OR sKodu LIKE :searchTerm) AND durum = 'aktif'";
    $statement = $this->db->prepare($query);
    $statement->execute([':searchTerm' => "%$searchTerm%"]);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getUrunlerByKategori($kategori_id) {
    $query = "SELECT u.*, k.kategori_adi FROM urunler u LEFT JOIN kategoriler k ON u.kategori_id = k.id WHERE u.kategori_id = :kategori_id AND u.durum = 'aktif'";
    $statement = $this->db->prepare($query);
    $statement->execute([':kategori_id' => $kategori_id]);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getAltKategoriler($kategori_id) {
    $query = "SELECT * FROM kategoriler WHERE ust_kategori = :kategori_id";
    $statement = $this->db->prepare($query);
    $statement->execute([':kategori_id' => $kategori_id]);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }


}
?>
