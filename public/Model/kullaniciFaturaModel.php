<?php
require_once '../../app/config/DB.php';

class kullaniciFaturaModel {
    private $db;

    public function __construct() {
        $this->db = getDbConnection();
    }

    public function getSiparisDetay($kullanici_id, $siparis_numarasi) {
        $query = "SELECT sip.*, kul.firma AS kullanici_adi, kul.adres, kul.tel, kul.email, urun.isim AS urun_adi, urun.foto AS foto, urun.gram AS urun_gram, urun.kategori_id AS alt_kategori_id, kat2.kategori_adi AS ust_kategori
        FROM siparisler sip
        INNER JOIN kullanici kul ON sip.kullanici_id = kul.id
        INNER JOIN urunler urun ON sip.urun_id = urun.id
        LEFT JOIN kategoriler kat2 ON urun.kategori_id = kat2.id
        WHERE sip.kullanici_id = :kullanici_id AND sip.siparisNumarasi = :siparis_numarasi
        ORDER BY sip.tarih";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':kullanici_id', $kullanici_id);
        $stmt->bindParam(':siparis_numarasi', $siparis_numarasi);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function fetchCategoryInfo($categoryId) {
        $categoryInfo = array();
        $currentCategory = $categoryId;

        while ($currentCategory !== NULL) {
            $query = "SELECT * FROM kategoriler WHERE id = :categoryId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':categoryId', $currentCategory, PDO::PARAM_INT);
            $stmt->execute();
            $kategori = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($kategori) {
                $categoryInfo[] = array(
                    'kategori_adi' => $kategori['kategori_adi'],
                    'ust_kategori_id' => $kategori['ust_kategori']
                );
                $currentCategory = $kategori['ust_kategori'];
            } else {
                $currentCategory = NULL;
            }
        }

        return $categoryInfo;
    }
}
?>
