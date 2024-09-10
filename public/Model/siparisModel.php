<?php
require_once '../../app/config/DB.php';

class siparisModel {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function kullaniciSiparisleri($kullaniciId) {
        try {
            $stmt = $this->db->prepare("SELECT s.*, u.firma as kullanici_adi, p.isim as urun_isim, p.gram as urun_gram 
                FROM siparisler s 
                INNER JOIN kullanici u ON s.kullanici_id = u.id 
                INNER JOIN urunler p ON s.urun_id = p.id 
                WHERE s.kullanici_id = ?");
            $stmt->execute([$kullaniciId]);
            $siparisler = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $siparisler;
        } catch (PDOException $e) {
            echo "Siparişleri getirme hatası: " . $e->getMessage();
            return [];
        }
    }
    
    public function siparisOlustur($kullaniciId, $urunId, $adet, $gram, $note, $aciklama, $siparisNumarasi) {
        try {
            // Siparişi veritabanına ekle
            $stmt = $this->db->prepare("INSERT INTO siparisler (siparisNumarasi, kullanici_id, urun_id, adet, gram, note, aciklama, tarih) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$siparisNumarasi, $kullaniciId, $urunId, $adet, $gram, $note, $aciklama]);

            return true;
        } catch (PDOException $e) {
            echo "Sipariş oluşturma hatası: " . $e->getMessage();
            return false;
        }
    }
    public function updateSiparisDurum($siparis_numarasi, $durum) {
        try {
            $sql = "UPDATE siparisler SET durum = :durum WHERE siparisNumarasi = :siparis_numarasi";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':durum', $durum);
            $stmt->bindParam(':siparis_numarasi', $siparis_numarasi);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Hata: " . $e->getMessage();
            return false;
        }
    }
}
?>