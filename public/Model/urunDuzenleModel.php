<?php
require_once '../../app/config/DB.php';

class UrunDuzenleModel {
  private $db;

  public function __construct($db) {
    $this->db = $db;
  }

  public function urunBilgisiGetir($id) {
    $query = "SELECT * FROM urunler WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  public function urunBilgileriVarMi($isim, $sKodu, $id = null) {
    $query = "SELECT isim, sKodu FROM urunler WHERE (isim = :isim OR sKodu = :sKodu)";
    if ($id) {
      $query .= " AND id != :id";
    }
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(":isim", $isim);
    $stmt->bindParam(":sKodu", $sKodu);
    if ($id) {
      $stmt->bindParam(":id", $id);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  public function urunBilgileriGuncelle($id, $kategori_id, $sKodu, $aciklama, $isim, $gram) {
    $query = "UPDATE urunler SET kategori_id = :kategori_id, sKodu = :sKodu, aciklama = :aciklama, isim = :isim, gram = :gram WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":kategori_id", $kategori_id);
    $stmt->bindParam(":sKodu", $sKodu);
    $stmt->bindParam(":aciklama", $aciklama);
    $stmt->bindParam(":isim", $isim);
    $stmt->bindParam(":gram", $gram);
    return $stmt->execute();
  }

  public function urunGorselGuncelle($id, $foto) {
    $query = "UPDATE urunler SET foto = :foto WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":foto", $foto);
    return $stmt->execute();
  }

  public function getKategoriler() {
    $query = "SELECT * FROM kategoriler WHERE ust_kategori IS NULL OR ust_kategori = 0";
    $stmt = $this->db->query($query);
    $kategoriler = array();
    while ($satir = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $kategori_id = $satir['id'];
      $kategoriler[$kategori_id] = array(
        'kategori_adi' => $satir['kategori_adi'],
        'alt_kategoriler' => $this->altKategorileriCek($kategori_id)
      );
    }
    return $kategoriler;
  }

  private function altKategorileriCek($ustKategoriId) {
    $query = "SELECT * FROM kategoriler WHERE ust_kategori = :ust_kategori";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':ust_kategori', $ustKategoriId);
    $stmt->execute();
    $alt_kategoriler = array();
    while ($satir = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $alt_kategori_id = $satir['id'];
      $alt_kategoriler[$alt_kategori_id] = array(
        'kategori_adi' => $satir['kategori_adi'],
        'alt_kategoriler' => $this->altKategorileriCek($alt_kategori_id)
      );
    }
    return $alt_kategoriler;
  }
}
?>
