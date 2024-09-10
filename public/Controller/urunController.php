<?php
require_once '../Model/urunModel.php';
require_once '../../app/config/DB.php';

class UrunController {
    private $urunModel;

    public function __construct() {
        $this->urunModel = new UrunModel();
    }

    // Tek bir metod ile ürünleri filtreleme ve arama işlemi yapabiliriz.
    public function getFilteredUrunler($searchTerm = '', $kategoriId = 0, $showPasif = false) {
        if (!empty($searchTerm)) {
            return $this->urunModel->searchUrunler($searchTerm);
        } elseif ($showPasif) {
            return $this->urunModel->getPasifUrunler();
        } elseif ($kategoriId > 0) {
            return $this->urunModel->getUrunlerByKategori($kategoriId);
        }
        return $this->urunModel->getAllUrunler();
    }

    public function aktifYap($urunId) {
        return $this->urunModel->aktifYap($urunId);
    }

    public function pasifYap($urunId) {
        return $this->urunModel->pasifYap($urunId);
    }
}
?>
