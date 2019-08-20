<?php

/**
 * FONKSİYONLAR CLASS'I
 * STATİC FONKSİYON BARINDIRIR DİREK HELPER NAMESPACE'I YAZILARAK NEW DİYE OLUŞTURMADAN ULAŞILABİLİR
 * ÖR: \Helpers\Helper::dd($degisken);
 * */

namespace API\src\Helpers;


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

    /**
     * Dizilerin içerisindeki belli bir sutunu dizi olarak veya string olarak almaya yarar
     * @param array $array Çevirilecek dizi
     * @param null $mode WHEREIN olursa string verir değilse array
     * @param null $column hangi kolonun döneceği
     * @return array|string
     */

    public static function arrayMap(array $array, $mode = null, $column = null)
    {
        $array = array_map(function ($x) use ($column) {
            return ($column != null) ? $x[$column] : $x;
        }, $array);
        if ($mode == 'whereIn') {
            $array = join("','", $array);
        }
        return $array;
    }

    /*
     * Sipariş tipinin karşılığını yazar
     * true olursa türkçe yazar false olursa ingilizce karakterlerle yazar
     * */
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
    function get_http_response_code($domain1) {
        $headers = get_headers($domain1);
        return substr($headers[0], 9, 3);
    }
    public static function response($status='success',$code=200,$data=null)
    {

        switch ($status){
            case 'success':
                $code='200';
                break;
            case 'error':
                $code='401';
                break;
            case 'emptyParams':
                $code='401';
                break;
            case 'updateApplication':
                $code='410';
                break;
            case 'loginError':
                $code='401';
                break;
            case 'registerError':
                $code='401';
                break;
            case 'updateError':
                $code='304';
                break;
            case 'insertError':
                $code='304';
                break;
            case 'tokenError':
                $code='401';
                break;
            case 'validationError':
                $code='406';
                break;
            case 'wrongMethod':
                $code='404';
                break;
        }

//        header('HTTP/1.0 $code $status');
        http_response_code($code);
        $array=['status'=>$status];
        if($data){
            $array['data']=$data;
        }
        return json_encode($array);
    }
}