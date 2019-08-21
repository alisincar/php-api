<?php

/**
 * HTTP İSTEK YÖNLENDİRME Class'ı
 * DÜZENİ KORUMAK İÇİN KESİNLİKLE BURAYA İŞLEM YAZILMAZ SADECE YÖNLENDİRME YAPILIR
 */

namespace API\src;

use API\src\database\Database;
use API\src\Helpers\Helper;
use API\src\user\Login;
use API\src\user\Register;

class HttpIslem extends Database
{

    public function __construct()
    {

        $conn = $this->connect();
        $sql = "SELECT * FROM network_ip_listesi ORDER BY id ASC";
        $ip_listesi = $conn->query($sql)->fetchAll();
        $ip_listesi_dizi = array();
        foreach ($ip_listesi as $key => $value) {
            $ip_listesi_dizi[] = $value['ip'];
        }

        if (in_array($_SERVER['REMOTE_ADDR'], $ip_listesi_dizi)) {
            $islem = $_GET['islem'];
            $this->$islem($_GET);
            try {
            } catch (\Exception $e) {
                #TODO: hataları mail ile gönder
                Helper::response('wrongMethod');
            } catch (\Throwable $t) {
                Helper::response('wrongMethod');
            }
        } else {
            Helper::response('emptyParams');
        }
    }

    public function login($data)
    {
        new Login($data);

    }

    public function register($data)
    {
        new Register($data);
    }


}