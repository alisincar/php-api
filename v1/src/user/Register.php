<?php


/**
 * KAYIT CLASS'I
 * Üyenin Bu kısımda veritabanına kaydedilir
 * */

namespace API\v1\src\user;

use API\v1\src\database\Database;
use API\v1\src\Helpers\Helper;
use API\v1\src\Helpers\Token;
use Rakit\Validation\Validator;

class Register extends Database
{
    public $conn = null;

    public function __construct($tip,$data)
    {

        /*
         * Gelen verilerin doğruluğunu kontrol eder
         * */
        $this->conn = $this->connect();

        $validator = new Validator;

        /*
         * register tipi sponsor doğrulama ise zorunlulukları düzenliyoruz
         * */
        if ($tip == 'checkSponsor') {
            $validation_array=[
                'sponsor_kodu' => 'required'
            ];
        }else{
            $validation_array=[
                'ad' => 'required',
                'soyad' => 'required',
                'email' => 'required|email',
                'sifre' => 'required|min:6',
                'sifre_tekrar' => 'required|same:sifre'
            ];
        }
        $validation = $validator->make($data,$validation_array);

        // then validate
        $validation->validate();

        //Veriler istediğimiz gibi değilse hata mesajını veriyoruz
        if ($validation->fails()) {
            Helper::response('validationError',  ['hata'=>$validation->errors()->toArray()]);
        } else {
            /*
             * Kayıt tipine göre login isteği ilgili fonksiyonda işleniyor
             * */
            $function = $tip;
            if ($function == 'checkSponsor' or $function == 'createUser') {
                $this->$function($data);
            } else {
                Helper::response('wrongMethod');
            }
        }
    }


    /*
     * Gönderilen OGA kodu uyeler tablosunda var ise olumlu sonuç dönülüyor
     * */
    private function checkSponsor($data)
    {
        $user = $this->conn->prepare("SELECT * FROM uyeler WHERE networkno=:sponsor_kodu");
        $user->execute(array('sponsor_kodu' => $data['sponsor_kodu']));
        $user_count = $user->rowCount();
        if ($user_count > 0) {
            $user = $user->fetch();
            Helper::response('success');
        } else {
            Helper::response('registerError', 'OGA kodu bulunamadı');
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
                if (Token::getToken($last_id) !== false) {
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