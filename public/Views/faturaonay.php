<?php
session_start();
require_once '../../app/config/DB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['siparis_numarasi']) && isset($_POST['durum'])) {
    $siparis_numarasi = $_POST['siparis_numarasi'];
    $durum = $_POST['durum'];

    try {
        // Sipariş numarasına ve duruma göre siparisler tablosundaki durum sütununu güncelliyoruz
        $sql = "UPDATE siparisler SET durum = :durum WHERE siparisNumarasi = :siparis_numarasi";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':durum', $durum);
        $stmt->bindParam(':siparis_numarasi', $siparis_numarasi);
        $stmt->execute();

        if ($durum == 3) {
            echo "Fatura iptal edildi!";
            header("Location: iptalsiparislerim.php");
            exit;
        } elseif ($durum == 2) {
            echo "Fatura hazırlanıyor!";
            header("Location: hazirlaniyorsiparislerim.php");
            exit;
        } elseif ($durum == 1) {
            echo "Fatura onaylandı!";
            header("Location: onaylanansiparislerim.php");
            exit;
        }
    } catch (PDOException $e) {
        echo "Güncelleme hatası: " . $e->getMessage();
    }
} else {
    echo "Geçersiz istek!";
}
?>
