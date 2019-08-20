<?php


namespace API\src\user;


use API\src\database\Database;
use API\src\Helpers\Helper;
use Rakit\Validation\Validator;

class Register extends Database
{
    public $conn = null;

    public function __construct($data)
    {
        $this->conn = $this->connect();

        $validator = new Validator;
        // make it
        $validation = $validator->make($_GET, [
            'ad' => 'required',
            'soyad' => 'required',
            'email' => 'required|email',
            'sifre' => 'required|min:6',
            'sifre_tekrar' => 'required|same:sifre'
        ]);

        // then validate
        $validation->validate();
        if ($validation->fails()) {
            Helper::response('validationError',  ['hata'=>$validation->errors()->toArray()]);
        } else {
            $this->createUser($data);
        }
    }

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
                if ($this->getToken($last_id) !== false) {
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

    public function getToken($user_id)
    {
        do {
            $token = base64_encode(base64_encode(md5(rand(10000000, 99999999999))));
            $token_table = $this->conn->query("SELECT * FROM tokens WHERE push_token='$token'")->fetch();
        } while ($token_table);
        $user = $this->conn->query("SELECT * FROM tokens WHERE user_id='$user_id'")->fetch();
        if ($user) {
            $sql = $this->conn->exec("UPDATE tokens SET push_token='$token' WHERE user_id='$user_id'");
        } else {
            $sql = $this->conn->exec("INSERT INTO tokens (push_token,user_id) VALUES ('$token',$user_id)");
        }
        return ($sql) ? $token : false;
    }
}