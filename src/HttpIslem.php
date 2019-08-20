<?php

/**
 * HTTP İSTEK YÖNLENDİRME Class'ı
 * DÜZENİ KORUMAK İÇİN KESİNLİKLE BURAYA İŞLEM YAZILMAZ SADECE YÖNLENDİRME YAPILIR
 */

namespace modules\src;

use modules\src\cv_pv\Cv as Cv;
use modules\src\cv_pv\Pv as Pv;
use modules\src\database\Database;
use modules\src\first_line_ciro\FirstLineCiro;
use modules\src\first_line_ciro\NetworkUyepaketDebug;
use modules\src\first_line_ciro\TemmuzAktiflik;
use modules\src\first_line_ciro\TemmuzPrim;
use modules\src\first_line_ciro\TemmuzPrim2;
use modules\src\Helpers\Helper;
use modules\src\o_para\oParaTercih;
use modules\src\rapor\CiroRapor;
use modules\src\relation\Relationship;
use modules\src\uye\AracDestek;
use modules\src\uye\EtkileyenKariyerIslemleri;
use modules\src\uye\KariyerHesapla;
use modules\src\uye\KariyerUnvan;
use modules\src\uye\Paket;
use modules\src\uye\PassiveUser;
use modules\src\rapor\UyeKontrol;
use modules\src\vip\Vip;
use modules\src\hakedis_prim\HakedisPrim;

class HttpIslem extends Database
{
    public $error_msg = ['status'=>'error','result'=>'File or directory not found.'];

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

            if (isset($_GET['islem'])) {
                switch ($_GET['islem']) {
                    case 'setCv':
                        $this->setCv($_GET);
                        break;
                    case 'setPv':
                        $this->setPv($_GET);
                        break;
                    case 'rollbackCv':
                        $this->rollbackCv($_GET);
                        break;
                    case 'rollbackPv':
                        $this->rollbackPv($_GET);
                        break;
                    default:
                        echo $this->error_msg;
                }
            } else {
                echo $this->error_msg;
            }
        } else {
            echo $this->error_msg;
        }
    }

    public function setCv($data)
    {
        if (!empty($data['id']) && $data['cv'] && $data['vip_cv'] && $data['carihareketref']) {
            $cv = new Cv;
            $cv->set($data['id'], $data['id'], $data['cv'], $data['vip_cv'], $data['carihareketref']);
        } else {
            echo $this->error_msg;
        }
    }

    public function setPv($data)
    {
        if (!empty($data['id']) && $data['toplampuan'] && $data['vip_toplampuan'] && $data['carihareketref']) {
            $pv = new Pv;
            $pv->set($data['id'], $data['id'], $data['toplampuan'], $data['vip_toplampuan'], $data['carihareketref']);
        } else {
            echo $this->error_msg;
        }
    }

    public function rollbackCv($data)
    {
        if (!empty($data['log_id'])) {
            $cv = new Cv;
            $cv->rollBack($data['log_id']);
        } else {
            echo $this->error_msg;
        }
    }

    public function rollbackPv($data)
    {
        if (!empty($data['log_id'])) {
            $pv = new Pv;
            $pv->rollBack($data['log_id']);
        } else {
            echo $this->error_msg;
        }
    }


}