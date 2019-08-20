<?php

/**
 * HTTP İSTEK YÖNLENDİRME Class'ı
 * DÜZENİ KORUMAK İÇİN KESİNLİKLE BURAYA İŞLEM YAZILMAZ SADECE YÖNLENDİRME YAPILIR
 */

namespace API\src;

use API\src\database\Database;
use API\src\Helpers\Helper;

class HttpIslem extends Database
{
    public $error_msg = ['status' => 'error', 'result' => 'File or directory not found.'];
    public $return_msg = ['status' => 'error', 'data' => array('users' => array(), 'sa' => array(), 'alert' => array('title' => 'a', 'message' => ''))];

    public function __construct()
    {
        $conn = $this->Connect();
        $sql = "SELECT * FROM network_ip_listesi ORDER BY id ASC";
        $ip_listesi = $conn->query($sql)->fetchAll();
        $ip_listesi_dizi = array();
        foreach ($ip_listesi as $key => $value) {
            $ip_listesi_dizi[] = $value['ip'];
        }

        if (in_array($_SERVER['REMOTE_ADDR'], $ip_listesi_dizi)) {
            $islem = $_GET['islem'];
            try {
                $this->$islem($_GET);
            } catch (\Exception $e) {
                echo Helper::response('wrongMethod');
            }catch (\Throwable $t){
                echo Helper::response('wrongMethod');
            }
        } else {
            echo Helper::response('emptyParams');
        }
    }

    public function setCv($data)
    {
        if (!empty($data['id']) && !empty($data['cv']) && !empty($data['vip_cv']) && !empty($data['carihareketref'])) {
            $cv = new Cv;
            $cv->set($data['id'], $data['id'], $data['cv'], $data['vip_cv'], $data['carihareketref']);
        } else {
            echo Helper::response('emptyParams');
        }
    }

    public function setPv($data)
    {
        if (!empty($data['id']) && !empty($data['toplampuan']) && !empty($data['vip_toplampuan']) && !empty($data['carihareketref'])) {
            $pv = new Pv;
            $pv->set($data['id'], $data['id'], $data['toplampuan'], $data['vip_toplampuan'], $data['carihareketref']);
        } else {
            echo Helper::response('emptyParams');
        }
    }

    public function rollbackCv($data)
    {
        if (!empty($data['log_id'])) {
            $cv = new Cv;
            $cv->rollBack($data['log_id']);
        } else {
            echo Helper::response('emptyParams');
        }
    }

    public function rollbackPv($data)
    {
        if (!empty($data['log_id'])) {
            $pv = new Pv;
            $pv->rollBack($data['log_id']);
        } else {
            echo Helper::response('emptyParams');
        }
    }


}