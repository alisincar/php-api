<?php


/**
 * KAYIT CLASS'I
 * Üyenin Bu kısımda veritabanına kaydedilir
 * */

namespace API\src\user;

use API\src\database\Database;
use API\src\Helpers\Helper;
use API\src\Helpers\Token;
use Rakit\Validation\Validator;

class Register extends Database
{
    public $conn = null;

    public function __construct($data)
    {

        /*
         * Gelen verilerin doğruluğunu kontrol eder
         * */
        $this->conn = $this->connect();

        $validator = new Validator;
        $validation = $validator->make($_GET, [
            'ad' => 'required',
            'soyad' => 'required',
            'email' => 'required|email',
            'sifre' => 'required|min:6',
            'sifre_tekrar' => 'required|same:sifre'
        ]);

        // then validate
        $validation->validate();

        //Veriler istediğimiz gibi değilse hata mesajını veriyoruz
        if ($validation->fails()) {
            Helper::response('validationError',  ['hata'=>$validation->errors()->toArray()]);
        } else {
            $this->createUser($data);
        }
    }
    /*
     * Kullanıcı oluşturma fonksiyonu
     * Email kullanılmıyorsa üyeyi ve tokeni oluşturur geriye üye ve token tablosunu döner
     * herhangi bir hata durumunda hata mesajı dönecek
     * */
    private function createUser($data)
    {
        $user_email = $this->conn->prepare("SELECT * FROM uyeler WHERE email=:email");
        $user_email->execute(['email'=>$data['email']]);
        $user_email_count = $user_email->rowCount();
        if ($user_email_count > 0) {
            Helper::response('validationError', 'E-mail kullanılıyor');
        } else {
            $create_user = $this->conn->prepare('INSERT INTO uyeler (ad, soyad, email,sifre)
                                                VALUES (:ad, :soyad, :email,:sifre)');
            $create_user->execute([
                'ad' => $data['ad'],
                'soyad' => $data['soyad'],
                'email' => $data['email'],
                'sifre' => md5($data['sifre']),
            ]);
            $last_id = $this->conn->lastInsertId();
            if ($create_user) {

                /*
                 * Token oluşturuluyor hata olursa hata mesajı verilecek
                 * */
                if (Token::createToken($last_id) !== false) {
                    $user = $this->conn->query("SELECT * FROM uyeler as u join tokens as t ON u.ref=t.user_id WHERE u.ref='$last_id'")->fetch();
                    Helper::response('success', $user);
                } else {
                    Helper::response('registerError', 'Kayıt başarısız');
                }
            } else {
                Helper::response('registerError', 'Kayıt başarısız');
            }
        }
    }

}