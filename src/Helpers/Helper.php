<?php

/**
 * FONKSİYONLAR CLASS'I
 * STATİC FONKSİYON BARINDIRIR DİREK HELPER NAMESPACE'I YAZILARAK NEW DİYE OLUŞTURMADAN ULAŞILABİLİR
 * ÖR: \Helpers\Helper::dd($degisken);
 * */

namespace modules\src\Helpers;


class Helper
{
    /**
     * Değişken içeriğini siyah ekranda detaylı bir şekilde ekrana yazdırmaya yarar
     * Void fonksiyondur.
     * Tipi static her yerden erişilebilir.
     * */
    public static function dd($var)
    {
        ob_end_clean();
        $backtrace = debug_backtrace();
        echo "\n<pre style='background: #5a6268;color:#f0fff0;padding: 5px;'>\n";
        if (isset($backtrace[0]['file'])) {
            $filename = $backtrace[0]['file'];
            $filename = explode('\\', $filename);
            echo end($filename) . "\n\n";
        }
        echo "---------------------------------\n\n";
        var_dump($var);
        echo "</pre>\n";
        die;
    }

    public static function arrayMap(array $array,$mode = null, $column=null)
    {
        $array = array_map(function ($x) use ($column) {
            return ($column!=null)?$x[$column]:$x;
        }, $array);
        if ($mode == 'whereIn') {
            $array = join("','", $array);
        }
        return $array;
    }

    public static function siparisTip($tip, $type = false)
    {
        switch ($tip) {
            case 0:
                $siparis_tipi = "odeme";
                $siparis_tipi_tr = "Ödeme";
                break;
            case 1:
                $siparis_tipi = "siparis";
                $siparis_tipi_tr = "Sipariş";
                break;
            case 2:
                $siparis_tipi = "iade";
                $siparis_tipi_tr = "İade";
                break;
            case 3:
                $siparis_tipi = "prim_yukleme";
                $siparis_tipi_tr = "Prim Yükleme";
                break;
            case 4:
                $siparis_tipi = "paket_yukseltme";
                $siparis_tipi_tr = "Paket Yükseltme";
                break;
            case 5:
                $siparis_tipi = "yeni_kayit";
                $siparis_tipi_tr = "Yeni Kayıt";
                break;
        }
        return ($type) ? $siparis_tipi_tr : $siparis_tipi;
    }
}