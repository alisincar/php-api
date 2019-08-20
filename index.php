<?php
/**
 * BU SAYFADAN HTTP YÖNLENDİRME CLASS'INI (HttpIslem) ÇAĞIRIYORUZ
 * POST VE GET İŞLEMLERİNİ BU SAYFAYA YAPACAĞIZ
 *
 * Diğer Modülleri kullanmak için Islem classını kullandığımız yere dahil etmeliyiz.
 * */

include_once 'config.php';
/*
 * Plugin içerisinde kullanılacak Classları dahil ediyoruz.
 */
function autoload($className)
{
    $dir = str_replace(basename(__DIR__), '', __DIR__);
    $classPath = str_replace("\\", DIRECTORY_SEPARATOR, $dir . $className . '.php');
//    $classPath = $className . '.php';
    if (file_exists($classPath)) {
        require_once($classPath);
    }
}

//Sınıfımızı arayıp bulacak olan metotu belirliyoruz.
spl_autoload_register('autoload');
require 'vendor/autoload.php';

/**
 * HTTP isteklerini yanıtlayacak class'ımız
 * */
if ((isset($_GET['islem']) && !empty($_GET['islem'])) || (isset($_POST['islem']) && !empty($_POST['islem']))) {
    new \API\src\HttpIslem();
}
