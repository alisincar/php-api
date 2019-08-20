<?php


namespace modules\src\log;


class Log
{

    public $dir = null;
    public $log = null;

    public function __construct($name,$context)
    {
        $dir = str_replace('src' . DIRECTORY_SEPARATOR . basename(__DIR__), '', __DIR__);
        $this->dir = $dir . 'storage/';
        $this->log = $this->dir. DIRECTORY_SEPARATOR .$name.'_log';

        $this->write($context);
    }

    private function write($context)
    {

            $log_file = fopen($this->log, 'a');
            fwrite($log_file, $context);
            fclose($log_file);
            return true;

    }

}