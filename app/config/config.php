<?php
// Hata raporlamayı aç
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// SMTP ayarları
define('MAIL_HOST', 'ilkeraydogdu.com.tr');
define('MAIL_USERNAME', 'mail@ilkeraydogdu.com.tr');
define('MAIL_PASSWORD', 'ilkN.2801');
define('MAIL_PORT', 465); // veya 465, güvenlik türüne bağlı olarak
define('MAIL_ENCRYPTION', 'ssl'); // TLS için 'tls', SSL için 'ssl'
define('MAIL_FROM', 'mail@ilkeraydogdu.com.tr');
define('MAIL_FROM_NAME', 'İLKER AYDOĞDU');
?>
