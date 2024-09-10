<?php
require_once '../Model/kullaniciFaturaModel.php';

class kullaniciFaturaController {
    private $model;

    public function __construct() {
        $this->model = new KullaniciFaturaModel();
    }

    public function getSiparisDetay($kullanici_id, $siparis_numarasi) {
        if (!empty($kullanici_id) && !empty($siparis_numarasi)) {
            return $this->model->getSiparisDetay($kullanici_id, $siparis_numarasi);
        } else {
            return array(); 
        }
    }

    public function fetchCategoryInfo($categoryId) {
        return $this->model->fetchCategoryInfo($categoryId);
    }
}
?>
