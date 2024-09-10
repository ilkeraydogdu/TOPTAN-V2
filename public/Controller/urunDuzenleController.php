<?php
require_once '../../app/config/DB.php';
require_once '../Model/urunDuzenleModel.php';

class UrunDuzenleController {
  private $model;

  public function __construct($db) {
    $this->model = new UrunDuzenleModel($db);
  }

  

  public function urunGorselGuncelle($id, $foto) {
    return $this->model->urunGorselGuncelle($id, $foto);
  }

  public function urunBilgisiGetir($id) {
    return $this->model->urunBilgisiGetir($id);
  }

  public function listCategories($selectedCategory = null) {
    $kategoriler = $this->model->getKategoriler();
    return $this->buildCategoryOptions($kategoriler, $selectedCategory);
  }
  public function urunBilgileriGuncelle($id, $kategori_id, $sKodu, $aciklama, $isim, $gram) {
    $hataMesaji = '';
    $mevcutUrunler = $this->model->urunBilgileriVarMi($isim, $sKodu, $id);
    
    foreach ($mevcutUrunler as $urun) {
      if ($urun['isim'] == $isim && $urun['sKodu'] == $sKodu) {
        $hataMesaji = 'Bu ürün adı ve stok kodu ile ürün zaten mevcut.';
        return array('sonuc' => false, 'hataMesaji' => $hataMesaji);
      }
      if ($urun['isim'] == $isim) {
        $hataMesaji = 'Bu ürün adı ile ürün zaten mevcut.';
        return array('sonuc' => false, 'hataMesaji' => $hataMesaji);
      }
      if ($urun['sKodu'] == $sKodu) {
        $hataMesaji = 'Bu stok kodu ile zaten ürün mevcut.';
        return array('sonuc' => false, 'hataMesaji' => $hataMesaji);
      }
    }

    $sonuc = $this->model->urunBilgileriGuncelle($id, $kategori_id, $sKodu, $aciklama, $isim, $gram);
    return array('sonuc' => $sonuc, 'hataMesaji' => $hataMesaji);
  }

  private function buildCategoryOptions($categories, $selectedCategory = null, $indent = 0) {
    $options = '';
    foreach ($categories as $kategori_id => $kategori) {
      $selected = ($kategori_id == $selectedCategory) ? 'selected' : '';
      $options .= '<option value="' . $kategori_id . '" ' . $selected . '>' . str_repeat("&nbsp;&nbsp;&nbsp;", $indent) . $kategori['kategori_adi'] . '</option>';
      if (!empty($kategori['alt_kategoriler'])) {
        $options .= $this->buildCategoryOptions($kategori['alt_kategoriler'], $selectedCategory, $indent + 1);
      }
    }
    return $options;
  }
}
?>
