<?php

/**
 * VERİ TABANI BAĞLANT CLASS'I
 * PDO MİMARİSİ KULLANILDI
 * VERİTABANI BAĞLANTISI BURADA ÇAĞRI ÜZERİNE OLUŞTURULUR VE KAPATILIR
 * */

namespace modules\src\database;

use PDO;
use PDOException;

class Database
{

    public $conn = null;

    /*
     * VERİTABANI BAĞLANTI BAŞLATMA FONKSİYONU
     * index.php'den gelen bilgilere göre bağlantı yapar sorun varsa hata çıktısı verir
     * */
    public function Connect()
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
     * CREATE FONKSİYONU LOG_PV TABLOSU YOKSA OLUŞTURUR
     * */
    public function CreateOParaTransferTable()
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
     * VERİTABANI BAĞLANTISINI KAPATIR
     * SONUÇ DÖNMEZ
     * */
    public
    function Close()
    {
        $this->conn = null;
    }
}