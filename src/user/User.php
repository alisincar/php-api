<?php


namespace API\src\user;


use API\src\database\Database;
use API\src\Helpers\Helper;

class User extends Database
{
    public $conn = null;
    public $user = null;

    public function __construct($data)
    {
        $this->conn = $this->connect();
        $this->checkToken($data);
    }

    public function checkToken($token){
            $user = $this->conn->prepare("SELECT * FROM tokens as t join uyeler as u on u.ref=t.user_id WHERE push_token :token");
            $user->execute(array('token' => $token));
            $user_count = $user->rowCount();
            if ($user_count > 0) {
                $this->user=$user->fetch();
                Helper::response('success', $user);
            } else {
                Helper::response('tokenError', 'Hatalı Token');
            }
    }
}