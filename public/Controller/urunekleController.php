<?php
require_once '../Model/urunekleModel.php';

class urunekleController {
    public $db;
    private $model;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new urunekleModel($this->db);
    }

    public function index() {
        // Kategorileri al ve view dosyasına gönder
        $kategoriler = $this->model->getKategoriler();
        require_once '../Views/urunekle.php';
    }

    public function urunEkle($kategori_id, $isim, $sKodu, $aciklama, $gram, $foto) {
    // Ürünün stok kodunun mevcut olup olmadığını kontrol et
        if ($this->model->urunMevcutMu($sKodu)) {
            return "Bu stok kodu ile zaten bir ürün mevcut!";
        }

        $ekleSonucu = $this->model->urunEkle($kategori_id, $isim, $sKodu, $aciklama, $gram, $foto);
        if ($ekleSonucu) {
            return "Ürün başarıyla eklendi.";
        } else {
            return "Ürün eklenirken bir hata oluştu.";
        }
    }

}


?>
