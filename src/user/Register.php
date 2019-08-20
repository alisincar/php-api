<?php


namespace API\src\user;


use API\src\database\Database;
use API\src\Helpers\Helper;
use Rakit\Validation\Validator;

class Register extends Database
{
    private $conn = null;

    public function __construct($data)
    {
        $this->conn = $this->connect();

        $validator = new Validator;
        // make it
        $validation = $validator->make($data, [
            'name' => 'required|alpha',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password'
        ]);

        // then validate
        $validation->validate();
        if ($validation->fails()) {
            Helper::response('validationError', $validation->errors());
        } else {
            $this->createUser($data);
        }
    }

    private function createUser($data)
    {
        $user_email = $this->conn->prepare("SELECT * FROM uyeler WHERE email :email");
        $user_email = $user_email->execute(array('email' => $data['email']))->fetchAll();
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
                    $user = $this->conn->query("SELECT * FROM uyeler as u join tokens as t ON u.ref=t.uye_id WHERE u.ref='$last_id'")->fetch();
                    Helper::response('success', $user);
                } else {
                    Helper::response('error', 'Kayıt başarısız');
                }
            } else {
                Helper::response('error', 'Kayıt başarısız');
            }
        }
    }

    public function getToken($user_id)
    {
        do {
            $token = base64_encode(rand(1111, 999999999999999));
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