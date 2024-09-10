<?php
require_once '../../app/config/DB.php';
class kullaniciModel {
    private $db;
    public function __construct() {
        $this->db = getDbConnection();
    }
    public function getKullanicilar($arananKelime = '') {
        $sql = 'SELECT * FROM kullanici';
        if (!empty($arananKelime)) {
            $sql .= " WHERE firma LIKE '%$arananKelime%'";
        }

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function silKullanici($id) {
        $stmt = $this->db->prepare('DELETE FROM kullanici WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function siparisVerenKullanicilariGetir() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM kullanici WHERE id IN (SELECT DISTINCT kullanici_id FROM siparisler)");
            $stmt->execute();
            $kullanicilar = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $kullanicilar;
        } catch (PDOException $e) {
            echo "Kullanıcıları getirme hatası: " . $e->getMessage();
            return [];
        }
    }
    public function enSonSiparisleriListele() {
        try {
            $stmt = $this->db->prepare("SELECT s.siparisNumarasi, MAX(s.tarih) AS tarih, k.firma AS kullanici_adi, s.kullanici_id, s.durum as durum
                FROM siparisler s
                INNER JOIN kullanici k ON s.kullanici_id = k.id
                GROUP BY s.siparisNumarasi
                ORDER BY tarih DESC");
            $stmt->execute();
            $enSonSiparisler = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $enSonSiparisler;
        } catch (PDOException $e) {
            echo "Siparişleri getirme hatası: " . $e->getMessage();
            return [];
        }
    }
    public function getKullaniciByEmail($email) {
        $sql = 'SELECT * FROM kullanici WHERE email = :email';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function sifreSifirlamaTokenKaydet($id, $token) {
        $sql = 'UPDATE kullanici SET sifre_sifirlama_token = :token WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':token', $token);
        return $stmt->execute();
    }
    public function getKullaniciByToken($token) {
        $sql = 'SELECT * FROM kullanici WHERE sifre_sifirlama_token = :token';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($id, $hashedPassword) {
        $sql = 'UPDATE kullanici SET sifre = :sifre WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':sifre', $hashedPassword);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function invalidateToken($id) {
        $sql = 'UPDATE kullanici SET sifre_sifirlama_token = NULL WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
