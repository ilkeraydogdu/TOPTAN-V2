<?php
session_start();
$rol = $_SESSION['rol'];
if ($rol !== 'admin') {
    header('Location: index.php');
    exit;
}
require_once 'inc/header.php';
require_once 'inc/sidebar.php';
require_once '../../app/config/DB.php';
require_once '../Model/kategoriModel.php';

$kategoriModel = new kategoriModel($db);
$kategoriler = $kategoriModel->listele();

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($kategoriModel->sil($id)) {
        $successMessage = 'Kategori başarıyla silindi.';
    } else {
        $errorMessage = 'Kategori silme işlemi başarısız.';
    }
    echo '<script>setTimeout(function() { window.location.href = "kategoriler.php"; }, 50);</script>';
    exit;
}

if (isset($_GET['edit'])) {
    $editId = $_GET['edit'];
    $editKategori = $kategoriModel->getKategoriById($editId);
}

$kategoriler = $kategoriModel->listele();


?>

<div class="page-header">
    <div class="page-leftheader">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-shopping-cart mr-2 fs-14"></i>Kabaloğlu Kuyumculuk Toptan</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">Kategoriler</a></li>
        </ol>
    </div>
</div>
<?php 
if (isset($successMessage)) {
    echo '<div class="alert alert-success" role="alert">' . $successMessage . '</div>';
}
if (isset($errorMessage) || isset($_GET['error'])) {
    $errorMessage = isset($_GET['error']) ? $_GET['error'] : $errorMessage;
    echo '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>';
}
?>
<div class="row">
    <div class="col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kategoriler</h3>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap">
                    <?php if (!empty($kategoriler)) : ?>
                        <tbody>
                            <?php
                            function listCategories($categories, $parentName = "Ana Kategori", $indent = 0) {
                                global $kategoriModel;

                                foreach ($categories as $categoryId => $category) {
                                    echo '<tr>';
                                    echo '<td>' . $category['kategori_adi'] . '</td>';
                                    echo '<td>';
                                    if (isset($category['ust_kategori'])) {
                                        if ($category['ust_kategori'] != 0) {
                                            echo '<span style="color: orange;">' . $kategoriModel->getUstKategoriAdi($category['ust_kategori']) . '</span>';
                                        } else {
                                            echo '<span style="color: orange;">' . $parentName . '</span>';
                                        }
                                    } else {
                                        echo '<span style="color: orange;">' . $parentName . '</span>';
                                    }
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<div class="btn-list">';
                                    echo '<a href="kategoriler.php?delete=' . $categoryId . '" class="btn btn-pill btn-secondary">Sil</a>';
                                    echo '<a href="kategoriler.php?edit=' . $categoryId . '" class="btn btn-pill btn-info">Düzenle</a>';
                                    echo '</div>';
                                    echo '</td>';
                                    echo '</tr>';
                                    if (!empty($category['alt_kategoriler'])) {
                                        listCategories($category['alt_kategoriler'], $category['kategori_adi'], $indent + 1);
                                    }
                                }
                            }

                            echo '<table class="table card-table table-vcenter text-nowrap">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>Kategori Adı</th>';
                            echo '<th>Üst Kategori Adı</th>';
                            echo '<th>İşlemler</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                            if (!empty($kategoriler)) {
                                listCategories($kategoriler);
                            } else {
                                echo '<tr>';
                                echo '<td colspan="3">';
                                echo '<div class="alert alert-info" role="alert">Kategori bulunamadı!</div>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody>';
                            echo '</table>';
                            ?>
                        </tbody>
                    <?php else:
                        echo '<tr><td colspan="3"><div class="alert alert-info" role="alert">Kategori bulunamadı!</div></td></tr>';
                    endif; ?>
                </table>

            </div>
        </div>
    </div>

    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Kategori Ekle</div>
            </div>
            <div class="card-body">
                <form action="../Controller/kategoriController.php" method="POST">
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label" required>Kategori Adı:</label>
                                <input type="text" class="form-control" name="kategori_adi">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="form-label">Üst Kategori</label>
                                <select class="form-control" name="ust_kategori">
                                    <optgroup label="Kategoriler">
                                        <option value="0">Ana Kategori</option>
                                        <?php 
                                        function kategoriListeleForm($kategoriler, $kategoriModel, $tab = 0) {
                                            foreach ($kategoriler as $kategori_id => $kategori) {
                                                echo '<option value="' . $kategori_id . '">' . str_repeat('&nbsp;', $tab * 2) . $kategori['kategori_adi'] . '</option>';
                                                if (!empty($kategori['alt_kategoriler'])) {
                                                    kategoriListeleForm($kategori['alt_kategoriler'], $kategoriModel, $tab + 1);
                                                }
                                            }
                                        }

                                        kategoriListeleForm($kategoriler, $kategoriModel);
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" name="ekle" class="btn btn-success">Ekle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php if (isset($_GET['edit']) && isset($editKategori)): ?>
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kategori Düzenle</h3>
            </div>                
            <div class="card-body">
                <form action="../Controller/kategoriController.php" method="POST">
                    <input type="hidden" name="edit_id" value="<?php echo isset($editKategori['id']) ? $editKategori['id'] : ''; ?>">
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label">Kategori Adı:</label>
                                <input type="text" class="form-control" name="kategori_adi" value="<?php echo isset($editKategori['kategori_adi']) ? $editKategori['kategori_adi'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Üst Kategori</label>
                                <select class="form-control" name="ust_kategori">
                                    <optgroup label="Ana Kategoriler">
                                        <option value="0" style="color: orange;">Ana Kategori YOK</option>
                                        <?php 
                                        kategoriListeleForm($kategoriler, $kategoriModel);
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" name="duzenle" class="btn btn-success">Düzenle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
</div>

<?php require_once 'inc/footer.php'; ?>
