<?php

/**
 * Giriş CLASS'I
 * Üye Bu kısımda hesabına erişir
 * */

namespace API\src\user;

use API\src\database\Database;
use API\src\Helpers\Helper;
use API\src\Helpers\Token;
use Rakit\Validation\Validator;

class Login extends Database
{
    public $conn = null;

    public function __construct($data)
    {
        $this->conn = $this->connect();
        $validator = new Validator;
        // make it
        $validation_array = [
            'email' => 'required|email',
            'sifre' => 'required|min:6'
        ];

        /*
         * login tipi email doğrulama ise şifre zorunluluğunu siliyoruz
         * */
        if ($data['tip'] == 'loginCheckEmail') {
            unset($validation_array['sifre']);
        }
        $validation = $validator->make($data, $validation_array);
        // then validate
        $validation->validate();
        /*
         * Validasyonda bir sorun yoksa giriş yapılıyor sorun varsa hata mesajı yazılacak
         * */
        if ($validation->fails()) {
            Helper::response('validationError', ['hata' => $validation->errors()->toArray()]);
        } else {
            /*
             * Giriş tipine göre login isteği ilgili fonksiyonda işleniyor
             * */
            $function = $data['tip'];
            if ($function == 'loginCheckEmail' or $function == 'loginUser') {
                $this->$function($data);
            } else {
                Helper::response('wrongMethod');
            }
        }
    }

    /*
     * Gönderilen email uyeler tablosunda var ise olumlu sonuç dönülüyor
     * */
    private function loginCheckEmail($data)
    {
        $user = $this->conn->prepare("SELECT * FROM tokens as t join uyeler as u on u.ref=t.user_id WHERE (u.email=:email or u.networkno=:email)");
        $user->execute(array('email' => $data['email']));
        $user_count = $user->rowCount();
        if ($user_count > 0) {
            $user = $user->fetch();
            Token::updateToken($user['push_token']);
            Helper::response('success');
        } else {
            Helper::response('loginError', 'E-mail ve Oga Kodu bulunamadı');
        }
    }


    /*
     * Gönderilen veriler doğruysa giriş sağlanıyor ve token veriliyor
     * */
    private function loginUser($data)
    {
        $user = $this->conn->prepare("SELECT * FROM tokens as t join uyeler as u on u.ref=t.user_id WHERE (u.email=:email or u.networkno=:email) and u.sifre=:sifre");
        $user->execute(array('email' => $data['email'], 'sifre' => md5($data['sifre'])));
        $user_count = $user->rowCount();
        if ($user_count > 0) {
            $user = $user->fetch();
            Token::updateToken($user['push_token']);
            Helper::response('success', $user);
        } else {
            Helper::response('loginError', 'Giriş yapılamadı');
        }
    }

}