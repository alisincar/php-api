<?php


namespace API\src\Helpers;


use API\src\database\Database;

class Token
{
    public static $user=null;
    public static function checkToken($token){
        $user = (new Database())->connect()->prepare("SELECT * FROM tokens as t join uyeler as u on u.ref=t.user_id WHERE t.push_token :token");
        $user->execute(array('token' => $token));
        $user_count = $user->rowCount();
        if ($user_count > 0) {
            self::$user=$user->fetch();
            self::updateToken();
            return ['status'=>'success','user'=>self::$user];
        } else {
            return ['status'=>'tokenError'];
        }
    }


    public static function createToken($user_id)
    {
        $db=(new Database())->connect();
        do {
            $token = base64_encode(base64_encode(md5(rand(10000000, 99999999999))));
            $token_sql = $db->query("SELECT * FROM tokens WHERE push_token='$token'")->fetch();
        } while ($token_sql);
        $user = $db->query("SELECT * FROM tokens WHERE user_id='$user_id'")->fetch();
        if ($user) {
            $sql = $db->exec("UPDATE tokens SET push_token='$token' WHERE user_id='$user_id'");
        } else {
            $sql = $db->exec("INSERT INTO tokens (push_token,user_id,manuel_logged,last_login,created_at) VALUES ('$token',$user_id,1,now(),now())");
        }
        return ($sql) ? $token : false;
    }

    public static function updateToken($token,$login_type=null)
    {
        $token_sql = (new Database())->connect()->prepare("UPDATE tokens SET manuel_logged=1,last_login=now() WHERE push_token =:token");
        $token_sql->execute(array('token' => "$token"));

    }

    public static function removeToken($token)
    {
        $token_sql = (new Database())->connect()->prepare("DELETE FROM tokens WHERE t.push_token =:token");
        $token_sql->execute(array('token' => $token));
    }
}