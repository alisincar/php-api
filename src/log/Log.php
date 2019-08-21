<?php

/**
 * LOG CLASS'ı
 * veriler burada storage dosyasına yazılır ve log_ ön ekli diğer tablolara yazılabilir
 * */
namespace API\src\log;


use API\src\database\Database;

class Log extends Database
{

    public $conn = null;
    public $dir = null;
    public $log = null;

    public function __construct($name,$context)
    {
        $this->conn=$this->connect();
        $dir = str_replace('src' . DIRECTORY_SEPARATOR . basename(__DIR__), '', __DIR__);
        $this->dir = $dir . 'storage/';
        $this->log = $this->dir. DIRECTORY_SEPARATOR .$name.'_log';

        $this->write($context);
    }

    private function write($context)
    {
            #TODO veritabanına yazma işini yap

            $log_file = fopen($this->log, 'a');
            fwrite($log_file, $context);
            fclose($log_file);
            return true;

    }

}