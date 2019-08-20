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
     * CREATE FONKSİYONU LOG_CV TABLOSU YOKSA OLUŞTURUR
     * */
    public function CreateLogCvTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `log_cv` (
                    `id` INT AUTO_INCREMENT NOT NULL,
                    `kimden` INT(11) NOT NULL,
                    `carihareketref` INT(11) NOT NULL,
                    `kime` TEXT,
                    `ref` TEXT,
                    `yerlesimsolpuan` TEXT,
                    `yerlesimsagpuan` TEXT,
                    `vip_sol_puan` TEXT,
                    `vip_sag_puan` TEXT,
                    `yeni_cv` INT(11),
                    `yeni_vip_cv` INT(11),
                    `created_at` timestamp(0) NULL DEFAULT NULL,
                    `updated_at` timestamp(0) NULL DEFAULT NULL,
                    PRIMARY KEY (`id`)) 
                    CHARACTER SET utf8 COLLATE utf8_general_ci";
        return $this->conn->exec($sql) !== false ? true : false;
    }

    /*
     * CREATE FONKSİYONU LOG_PV TABLOSU YOKSA OLUŞTURUR
     * */
    public function CreateLogPvTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `log_pv` (
                    `id` INT AUTO_INCREMENT NOT NULL,
                    `kimden` INT(11) NOT NULL,
                    `carihareketref` INT(11) NOT NULL,
                    `kime` TEXT,
                    `ref` TEXT,
                    `toplampuan` TEXT,
                    `vip_toplampuan` TEXT,
                    `yeni_pv` INT(11),
                    `yeni_vip_pv` INT(11),
                    `created_at` timestamp(0) NULL DEFAULT NULL,
                    `updated_at` timestamp(0) NULL DEFAULT NULL,
                    PRIMARY KEY (`id`)) 
                    CHARACTER SET utf8 COLLATE utf8_general_ci";
        return $this->conn->exec($sql) !== false ? true : false;
    }


    /*
     * CREATE FONKSİYONU LOG_PV TABLOSU YOKSA OLUŞTURUR
     * */
    public function CreateOParaTercihTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `o_para_bonus_tercih` (
                    `id` INT AUTO_INCREMENT NOT NULL,
                    `uye_id` INT(11) NOT NULL,
                    `tercih` enum('kullanima_devam','kismi_nakit','hepsi_nakit') default NULL,
                    `miktar` VARCHAR (30),
                    `created_at` timestamp(0) NULL DEFAULT NULL,
                    `updated_at` timestamp(0) NULL DEFAULT NULL,
                    PRIMARY KEY (`id`)) 
                    CHARACTER SET utf8 COLLATE utf8_general_ci";
        return $this->conn->exec($sql) !== false ? true : false;
    }


    /*
     * CREATE FONKSİYONU EK_kariyer_hesap TABLOSU YOKSA OLUŞTURUR
     * */
    public function CreateEK_kariyer_hesapTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `EK_kariyer_hesap` (
                    `id` INT AUTO_INCREMENT NOT NULL,
                    `uye_id` INT(11) NOT NULL,
                    `hedef_kariyer_id` INT(11) NOT NULL,
                    `hedef_kariyer_adi` VARCHAR (30),
                    `hedef_kariyer_puani` INT(11) NOT NULL,
                    `hesaplanan_kariyer_id` INT(11) NOT NULL,
                    `hesaplanan_kariyer_adi` VARCHAR (30),
                    `hesaplanan_kariyer_puani` INT(11) NOT NULL,
                    `hesaplanan_kariyer_min_puani` INT(11) NOT NULL,
                    `ay` VARCHAR (30),
                    `yil` VARCHAR (30),
                    `created_at` timestamp(0) NULL DEFAULT NULL,
                    `updated_at` timestamp(0) NULL DEFAULT NULL,
                    PRIMARY KEY (`id`)) 
                    CHARACTER SET utf8 COLLATE utf8_general_ci";
        return $this->conn->exec($sql) !== false ? true : false;
    }
    /*
     * CREATE FONKSİYONU EK_arac_destek TABLOSU YOKSA OLUŞTURUR
     * */
    public function EK_arac_destek()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `EK_arac_destek` (
                    `id` INT AUTO_INCREMENT NOT NULL,
                    `uye_id` INT(11) NOT NULL,
                    `kariyer_id` INT(11) NOT NULL,
                    `kariyer_adi` VARCHAR (30),
                    `onceki_paket_id` INT(11) NOT NULL,
                    `onceki_paket_adi` VARCHAR (30),
                    `tutar` INT(11) NOT NULL,
                    `ay` VARCHAR (30),
                    `yil` VARCHAR (30),
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