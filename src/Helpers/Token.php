<?php

/**
 * TOKEN YONETIM CLASS'I
 * STATİC FONKSİYON BARINDIRIR DİREK HELPER NAMESPACE'I YAZILARAK NEW DİYE OLUŞTURMADAN ULAŞILABİLİR
 * ÖR: \Helpers\Token::checkToken($degisken);
 * */

namespace API\src\Helpers;

use API\src\database\Database;

class Token
{
    public static $user=null;


    /*
     * Tokenin varlığını sorgular ve geriye kullanıcı ve token tablosunu birleşik dizide döner
     * */
    public static function checkToken($token){
        $user = (new Database())->connect()->prepare("SELECT * FROM tokens as t join uyeler as u on u.ref=t.user_id WHERE t.push_token :token");
        $user->execute(array('token' => $token));
        $user_count = $user->rowCount();
        if ($user_count > 0) {
            self::$user=$user->fetch();
            self::updateToken($token);
            return ['status'=>'success','user'=>self::$user];
        } else {
            return ['status'=>'tokenError'];
        }
    }


    /*
     * Token oluşturur ve tokens tablosunda parametre aldığı user_id ile kaydeder
     * */
    public static function getToken($user_id)
    {
        $db=(new Database())->connect();
        do {
            $token = base64_encode(base64_encode(md5(rand(10000000, 99999999999))));
            $token_sql = $db->query("SELECT * FROM tokens WHERE push_token='$token'")->fetch();
        } while ($token_sql);
        $user = $db->query("SELECT * FROM tokens WHERE user_id='$user_id'")->fetch();
        if ($user) {
            $sql = $db->exec("UPDATE tokens SET push_token='$token',last_login=now(),updated_at=now() WHERE user_id='$user_id'");
        } else {
            $sql = $db->exec("INSERT INTO tokens (push_token,user_id,manuel_logged,last_login,created_at) VALUES ('$token',$user_id,1,now(),now())");
        }
        return ($sql) ? $token : false;
    }

    /*
     * Token'in son kullanım tarihini ve kullanım tipini günceller
     * */
    public static function updateToken($token,$login_type=null)
    {
        #TODO kullanım tipini ayarla

        $token_sql = (new Database())->connect()->prepare("UPDATE tokens SET manuel_logged=1,last_login=now() WHERE push_token =:token");
        $token_sql->execute(array('token' => "$token"));

    }

    /*
     * Tokeni siler
     * */
    public static function removeToken($token)
    {
        $token_sql = (new Database())->connect()->prepare("DELETE FROM tokens WHERE t.push_token =:token");
        $token_sql->execute(array('token' => $token));
    }
}