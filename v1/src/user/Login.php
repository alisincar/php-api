<?php

/**
 * Giriş CLASS'I
 * Üye Bu kısımda hesabına erişir
 * */

namespace API\v1\src\user;

use API\v1\src\database\Database;
use API\v1\src\Helpers\Helper;
use API\v1\src\Helpers\Token;
use Rakit\Validation\Validator;

class Login extends Database
{
    public $conn = null;

    public function __construct($tip, $data)
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
        if ($tip == 'checkEmail') {
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
            $function = $tip;
            if ($function == 'checkEmail' or $function == 'loginUser') {
                $this->$function($data);
            } else {
                Helper::response('wrongMethod');
            }
        }
    }

    /*
     * Gönderilen email uyeler tablosunda var ise olumlu sonuç dönülüyor
     * */
    private function checkEmail($data)
    {
        $user = $this->conn->prepare("SELECT * FROM uyeler WHERE (email=:email or networkno=:email)");
        $user->execute(array('email' => $data['email']));
        $user_count = $user->rowCount();
        if ($user_count > 0) {
            $user = $user->fetch();
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
        $user = $this->conn->prepare("SELECT * FROM  uyeler WHERE (email=:email and sifre=:sifre) or (networkno=:email and sifre=:sifre)");
        $user->execute(array('email' => $data['email'], 'sifre' => md5($data['sifre'])));
        $user_count = $user->rowCount();
        if ($user_count > 0) {
            $user = $user->fetch();
            $token=Token::getToken($user['ref']);
            if ($token !== false) {
               $user['token']=$token;
            }
            Helper::response('success', $user);
        } else {
            Helper::response('loginError', 'Giriş yapılamadı ');
        }
    }

}