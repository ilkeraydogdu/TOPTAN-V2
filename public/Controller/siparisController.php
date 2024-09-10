<?php 
require_once '../Model/siparisModel.php';
require_once '../../app/config/DB.php';

class siparisController {
    private $siparisModel;
    private $db;

    public function __construct() {
        $this->siparisModel = new siparisModel();
        $this->db = getDbConnection();
    }

    public function kullaniciSiparisleri($kullaniciId) {
        return $this->siparisModel->kullaniciSiparisleri($kullaniciId);
    }

    public function siparisOlustur($kullaniciId, $urunIdler, $adetler, $gramlar, $note, $aciklamalar) {
        $siparisNumarasi = $this->generateSiparisNumarasi();
        for ($i = 0; $i < count($urunIdler); $i++) {
            $urunId = $urunIdler[$i];
            $adet = $adetler[$i];
            $gram = $gramlar[$i];
            $aciklama = $aciklamalar[$i]; // Düzeltme: $aciklamalar dizisinden doğru değeri alın

            $this->siparisModel->siparisOlustur($kullaniciId, $urunId, $adet, $gram, $note, $aciklama, $siparisNumarasi);

            unset($_SESSION['sepet'][$urunId]);
        }
    }

    public function generateSiparisNumarasi() {
        try {
            $stmt = $this->db->query("SELECT MAX(siparisNumarasi) AS max_siparis_numarasi FROM siparisler");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $maxSiparisNumarasi = $row['max_siparis_numarasi'];
            if (!$maxSiparisNumarasi || substr($maxSiparisNumarasi, 3, 4) != date('Y')) {
                return "KBL" . date('Y') . "01";
            }
            $sonNumara = intval(substr($maxSiparisNumarasi, 7));
            $yeniSiparisNumarasi = "KBL" . date('Y') . str_pad($sonNumara + 1, 2, '0', STR_PAD_LEFT);
            return $yeniSiparisNumarasi;
        } catch (PDOException $e) {
            echo "Sipariş numarası oluşturma hatası: " . $e->getMessage();
            return false;
        }
    }

    public function iptalEt($siparis_numarasi) {
        return $this->siparisModel->updateSiparisDurum($siparis_numarasi, 3);
    }

    public function onayla($siparis_numarasi) {
        return $this->siparisModel->updateSiparisDurum($siparis_numarasi, 1);
    }
}
?>