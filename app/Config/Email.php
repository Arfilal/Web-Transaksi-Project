<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    // Konfigurasi utama yang sudah benar
    public string $fromEmail  = 'arfilalfaiznadi04@gmail.com'; // Ganti dengan email Anda
    public string $fromName   = 'Web Transaksi';
    public string $protocol   = 'smtp';
    public string $SMTPHost   = 'smtp.gmail.com';
    public string $SMTPUser   = 'arfilalfaiznadi04@gmail.com'; // Ganti dengan email Anda
    public string $SMTPPass   = 'password-email-anda'; // Ganti dengan App Password
    public int    $SMTPPort   = 587;
    public string $SMTPCrypto = 'tls';
    public string $mailType   = 'html';
    public string $charset    = 'UTF-8';

    // Konfigurasi lainnya, biarkan default
    public string $userAgent = 'CodeIgniter';
    public string $mailPath = '/usr/sbin/sendmail';
    public int $SMTPTimeout = 5;
    public bool $SMTPKeepAlive = false;
    public bool $wordWrap = true;
    public int $wrapChars = 76;
    public bool $validate = false;
    public int $priority = 3;
    public string $CRLF = "\r\n";
    public string $newline = "\r\n";
    public bool $BCCBatchMode = false;
    public int $BCCBatchSize = 200;
    public bool $DSN = false;
}
