<?php
require_once '../../app/config/DB.php';

class girisModel {
    private $db;

    public function __construct() {
        $this->db = getDbConnection(); // getDbConnection() fonksiyonunu doğrudan çağırın
    }

    // Kullanıcıyı veritabanından sorgulama
    public function girisYap($email, $sifre) {
        $query = "SELECT * FROM kullanici WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($sifre, $user['sifre'])) {
            if ($user['durum'] == 'pasif') {
                // Kullanıcı pasif durumdaysa giriş izni verme
                return 'pasif';
            } else {
                // Kullanıcı aktif durumdaysa bilgilerini döndür
                return $user;
            }
        } else {
            return false;
        }
    }
}
?>
