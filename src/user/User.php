<?php


/**
 * USER CLASS'I
 * Üyenin bütün bilgileri buradan dağıtılır
 * */

namespace API\src\user;


use API\src\database\Database;
use API\src\Helpers\Helper;
use API\src\Helpers\Token;

class User extends Database
{
    public $conn = null;
    public $user = null;

    public function __construct($data)
    {
        $this->conn = $this->connect();
        $this->getUser($data);
    }

    /*
     * Gelen tokeni sorgular ve token doğruysa Üyenin bilgilerini döner
     * */
    private function getUser($data)
    {
        $checkToken=Token::checkToken($data);
        $checkStatus=$checkToken['status'];
        if($checkStatus=='success'){
            Helper::response($checkStatus,$checkToken['user']);
        }else{
            Helper::response($checkStatus);
        }
    }
}