<?php
require_once '../Model/kayitModel.php';

if (!empty($_GET['token'])) {
    $token = $_GET['token'];

    // Kullanıcıyı token ile bul
    $user = getKullaniciByToken($token);

    if ($user) {
        // Kullanıcı durumunu aktif yap
        $id = $user['id'];
        activateUser($id);

        header("location: giris.php?durum=aktif&mesaj=Emailiniz başarıyla onaylandı.");
        exit;
    } else {
        header("location: giris.php?durum=gecersiz&mesaj=Geçersiz onay linki.");
        exit;
    }
} else {
    header("location: giris.php?durum=gecersiz&mesaj=Geçersiz onay linki.");
    exit;
}
