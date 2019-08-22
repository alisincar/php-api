<?php


/**
 * USER CLASS'I
 * Üyenin bütün bilgileri buradan dağıtılır
 * */

namespace API\v1\src\user;


use API\v1\src\database\Database;
use API\v1\src\Helpers\Helper;
use API\v1\src\Helpers\Token;

class User extends Database
{
    public $conn = null;
    public $user = null;

    public function __construct()
    {
        $this->conn = $this->connect();
    }

    /*
     * Gelen tokeni sorgular ve token doğruysa Üyenin bilgilerini döner
     * */
    public function getUser($data)
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