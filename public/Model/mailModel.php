<?php
require_once '../../app/config/config.php'; 
require_once '../../app/PHPMailer/src/PHPMailer.php';
require_once '../../app/PHPMailer/src/Exception.php';
require_once '../../app/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class mailModel {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->setupMailServer();
    }

    private function setupMailServer() {
        $this->mail->isSMTP();
        $this->mail->Host = MAIL_HOST;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = MAIL_USERNAME;
        $this->mail->Password = MAIL_PASSWORD;
        $this->mail->SMTPSecure = MAIL_ENCRYPTION;
        $this->mail->Port = MAIL_PORT;
        $this->mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $this->mail->isHTML(true);
        $this->mail->CharSet = 'UTF-8';
        $this->mail->SMTPDebug = 0; // SMTP hata ayıklama düzeyi (0: kapat, 2: tüm hataları göster)
    }

    public function sendMail($to, $subject, $body) {
        try {
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->send();
            echo "Mail gönderildi.";
        } catch (Exception $e) {
            error_log("Mail gönderimi başarısız: {$this->mail->ErrorInfo}");
            echo "Mail gönderimi başarısız: {$this->mail->ErrorInfo}";
        }
    }
}
?>
