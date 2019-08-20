<?php
/**
 * CONFIG VE START DOSYASI
 * VERİTABANI BİLGİLERİNİ BURAYA YAZIYORUZ VE AYARLAMALARI YAPIYORUZ
 */

define('host', 'localhost');
define('database', 'default');
define('username', 'root');
define('password', 'root');
define('port', '8889');

/*
 * PHP AYARLARI
 * */
session_start();
//Hata raporlama
ini_set('display_errors', 1); //1=Açık 0=Kapalı
error_reporting(E_ALL); //Bütün hatalar

//Zamanaşımı süresi
ini_set('max_execution_time', 0); //0=NOLIMIT


