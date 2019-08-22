<?php

/**
 * VERİ TABANI BAĞLANT CLASS'I
 * PDO MİMARİSİ KULLANILDI
 * VERİTABANI BAĞLANTISI BURADA ÇAĞRI ÜZERİNE OLUŞTURULUR VE KAPATILIR
 * */

namespace API\v1\src\database;

use PDO;
use PDOException;

class Database
{

    public $conn = null;

    /*
     * VERİTABANI BAĞLANTI BAŞLATMA FONKSİYONU
     * index.php'den gelen bilgilere göre bağlantı yapar sorun varsa hata çıktısı verir
     * */
    public function connect()
    {
        try {
            $dsn = "mysql:dbname=" . database . "; host=" . host.';charset=utf8';
            $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            );
            $this->conn = new PDO($dsn, username, password, $options);
            $this->conn->exec('SET NAMES UTF8');
            return $this->conn;

        } catch (PDOException $e) {
            echo 'Connection error: ' . $e->getMessage();
        }
    }


    /*
     * CREATE FONKSİYONU o_para_bonus_tercih TABLOSU YOKSA OLUŞTURUR
     * */
    public function createOParaTransferTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `o_para_bonus_tercih` (
                    `id` INT AUTO_INCREMENT NOT NULL,
                    `gonderen_id` INT(11) NOT NULL,
                    `alici_id` INT(11) NOT NULL,
                    `miktar` FLOAT (10,2),
                    `sms_kodu` VARCHAR (10),
                    `onay_kodu` VARCHAR (10),
                    `onay_tarihi` timestamp(0) NULL DEFAULT NULL,
                    `created_at` timestamp(0) NULL DEFAULT NULL,
                    `updated_at` timestamp(0) NULL DEFAULT NULL,
                    PRIMARY KEY (`id`)) 
                    CHARACTER SET utf8 COLLATE utf8_general_ci";
        return $this->conn->exec($sql) !== false ? true : false;
    }

    /*
     * CREATE FONKSİYONU tokens TABLOSU YOKSA OLUŞTURUR
     * */
    public function createTokensTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `tokens` (
                  `id` int AUTO_INCREMENT NOT NULL,
                  `user_id` int(11) DEFAULT NULL,
                  `facebook_login_id` int(11) DEFAULT NULL,
                  `google_login_id` int(11) DEFAULT NULL,
                  `access_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `device_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `push_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `os_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `os_version` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `device_brand` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `device_model` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `app_version` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `locale` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `allow_push` tinyint(1) DEFAULT NULL,
                  `manuel_logged` tinyint(1) DEFAULT NULL,
                  `facebook_logged` tinyint(1) DEFAULT NULL,
                  `google_logged` tinyint(1) DEFAULT NULL,
                  `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `last_login` datetime DEFAULT NULL,
                  `created_at` datetime DEFAULT NULL,
                  `updated_at` datetime DEFAULT NULL,
                   PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        return $this->conn->exec($sql) !== false ? true : false;
    }


    /*
     * VERİTABANI BAĞLANTISINI KAPATIR
     * SONUÇ DÖNMEZ
     * */
    public function close()
    {
        $this->conn = null;
    }
}