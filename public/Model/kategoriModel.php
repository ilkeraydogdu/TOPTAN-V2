<?php
class KategoriModel {
	private $db;

	public function __construct($db) {
		$this->db = $db;
	}
	public function kategoriAdiVarMi($kategoriAdi, $ustKategori = null, $excludeId = null) {
		$query = "SELECT COUNT(*) as count FROM kategoriler WHERE kategori_adi = :kategoriAdi";

		if (!is_null($ustKategori)) {
			$query .= " AND ust_kategori = :ustKategori";
		} else {
			$query .= " AND (ust_kategori IS NULL OR ust_kategori = 0)";
		}

		if ($excludeId) {
			$query .= " AND id != :excludeId";
		}

		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':kategoriAdi', $kategoriAdi);

		if (!is_null($ustKategori)) {
			$stmt->bindParam(':ustKategori', $ustKategori, PDO::PARAM_INT);
		}

		if ($excludeId) {
			$stmt->bindParam(':excludeId', $excludeId);
		}

		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		return $result['count'] > 0;
	}


	public function listele() {
		$query = "SELECT * FROM kategoriler WHERE ust_kategori IS NULL OR ust_kategori = 0"; 
		$stmt = $this->db->query($query);
		$kategoriler = array();
		while ($satir = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$kategori_id = $satir['id'];
			$kategoriler[$kategori_id] = array(
				'kategori_adi' => $satir['kategori_adi'],
				'alt_kategoriler' => $this->altKategorileriCek($kategori_id) 
			);
		}
		return $kategoriler;
	}

	public function getUstKategoriAdi($kategoriId) {
		$query = "SELECT kategori_adi FROM kategoriler WHERE id = :kategori_id";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':kategori_id', $kategoriId);
		$stmt->execute();
		$kategori = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$kategori) {
			echo "Üst Kategori Bulunamadı";
			return "";
		}
		return $kategori['kategori_adi'];
	}

	public function getKategoriById($id) {
		$query = "SELECT * FROM kategoriler WHERE id = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	private function altKategorileriCek($ustKategoriId) {
		$query = "SELECT * FROM kategoriler WHERE ust_kategori = :ust_kategori";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':ust_kategori', $ustKategoriId);
		$stmt->execute();
		$alt_kategoriler = array();
		while ($satir = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$alt_kategori_id = $satir['id'];
			$alt_kategoriler[$alt_kategori_id] = array(
				'kategori_adi' => $satir['kategori_adi'],
                'alt_kategoriler' => $this->altKategorileriCek($alt_kategori_id) // Alt kategorileri de al
            );
		}
		return $alt_kategoriler;
	}

	public function kategoriListeleForm($kategoriler, $kategoriModel, $tab = 0, $ustKategoriAdi = null) {
		foreach ($kategoriler as $kategori_id => $kategori) {
			$renk = $ustKategoriAdi ? '' : 'style="color: orange;"'; 
			echo '<option value="' . $kategori_id . '"' . $renk . '>' . str_repeat('&nbsp;', $tab * 4) . $kategori['kategori_adi'] . '</option>';
			if (!empty($kategori['alt_kategoriler'])) {
				kategoriListeleForm($kategori['alt_kategoriler'], $kategoriModel, $tab + 1, $kategori['kategori_adi']);
			}
		}
	}

	public function sil($id) {
		$query = "DELETE FROM kategoriler WHERE id = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':id', $id);
		return $stmt->execute();
	}

	public function ekle($kategoriAdi, $ustKategori) {
		$query = "INSERT INTO kategoriler (kategori_adi, ust_kategori) VALUES (:kategoriAdi, :ustKategori)";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':kategoriAdi', $kategoriAdi);
		$stmt->bindParam(':ustKategori', $ustKategori, PDO::PARAM_INT);
		return $stmt->execute();
	}

	public function duzenle($id, $kategoriAdi, $ustKategori) {
		$query = "UPDATE kategoriler SET kategori_adi = :kategoriAdi, ust_kategori = :ustKategori WHERE id = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':kategoriAdi', $kategoriAdi);
		$stmt->bindParam(':ustKategori', $ustKategori, PDO::PARAM_INT);
		$stmt->bindParam(':id', $id);
		return $stmt->execute();
	}
}
?>
