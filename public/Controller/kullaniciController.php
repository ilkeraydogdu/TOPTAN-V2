<?php
require_once '../Model/kullaniciModel.php';
require_once 'mailController.php';
class kullaniciController {
    private $kullaniciModel;
    private $mailController;
    public function __construct() {
        $this->mailController = new mailController();
        $this->kullaniciModel = new kullaniciModel;
    }
    public function listele($arananKelime = '') {
        $kullanicilar = $this->kullaniciModel->getKullanicilar($arananKelime);
        return $kullanicilar;
    }
    public function sil($id) {
        if ($this->kullaniciModel->silKullanici($id)) {
            echo '<script>window.location.href = "kullanici.php";</script>';
            exit;
        }
        else {
            die('Kullanıcı silme işlemi başarısız.');
        }
    }
    public function siparisVerenKullanicilariListele() {
        return $this->kullaniciModel->siparisVerenKullanicilariGetir();
    }
    public function enSonSiparisleriListele() {
        return $this->kullaniciModel->enSonSiparisleriListele();
    }
    public function sifreSifirlamaIstegi($email) {
        $user = $this->kullaniciModel->getKullaniciByEmail($email);
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $this->kullaniciModel->sifreSifirlamaTokenKaydet($user['id'], $token);
            $this->mailController->sendPasswordResetEmail($user['email'], $token); 
            return true;
        } else {
            return false;
        }
    }
    //Mailden sonraki adım 
    public function sifreSifirla($token, $yeniSifre) {
        $user = $this->kullaniciModel->getKullaniciByToken($token);
        if ($user) {
            $hashedPassword = password_hash($yeniSifre, PASSWORD_DEFAULT);
            $this->kullaniciModel->updatePassword($user['id'], $hashedPassword);
            // Token'i geçersiz kıl
            $this->kullaniciModel->invalidateToken($user['id']);
            return true;
        } else {
            return false;
        }
    }
    public function kullaniciSiparisleriListele($kullaniciId) {
        global $db;
        try {
            // Örneğin:
            $sql = "SELECT siparisNumarasi, MAX(tarih) AS tarih, MAX(durum) AS durum, kullanici_id
            FROM siparisler 
            WHERE kullanici_id = :kullanici_id 
            GROUP BY siparisNumarasi";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':kullanici_id', $kullaniciId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Bağlantı hatası: " . $e->getMessage();
            return [];
        }
    }




}
?>