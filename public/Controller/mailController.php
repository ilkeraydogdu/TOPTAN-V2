<?php
require_once '../Model/mailModel.php';

class mailController {
    private $mailModel;

    public function __construct() {
        $this->mailModel = new mailModel();
    }

    public function sendPasswordResetEmail($email, $token) {
        $subject = 'Şifre Sıfırlama';
        $body = '<!DOCTYPE html>
        <html lang="tr">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Şifre Sıfırlama</title>
        <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        </style>
        </head>
        <body>
        <div class="container">
        <h2>Şifre Sıfırlama</h2>
        <p>Şifrenizi sıfırlamak için lütfen aşağıdaki düğmeye tıklayın:</p>
        <p>
        <a href="' . URL . '/public/Views/sifre-sifirla.php?token=' . $token . '" class="btn">Şifremi Sıfırla</a>
        </p>
        </div>
        </body>
        </html>';

        $this->mailModel->sendMail($email, $subject, $body);
    }
    public function sendConfirmationEmail($email, $token) {
        $subject = 'Mail Onaylama';
        $body = '<!DOCTYPE html>
        <html lang="tr">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Şifre Sıfırlama</title>
        <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        </style>
        </head>
        <body>
        <div class="container">
        <h2>Mail Onaylama</h2>
        <p>Mailinizi onaylamak için lütfen aşağıdaki düğmeye tıklayın:</p>
        <p>
        <a href="' . URL . '/public/Views/confirm.php?token=' . $token. '" class="btn">Mailimi Onayla</a>
        </p>
        </div>
        </body>
        </html>';

        $this->mailModel->sendMail($email, $subject, $body);
    }
}
?>
