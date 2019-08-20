<?php


namespace API\src\user;


use API\src\database\Database;
use API\src\Helpers\Helper;
use Rakit\Validation\Validator;

class Login extends Database
{
    public $conn = null;

    public function __construct($data)
    {
        $this->conn = $this->connect();
        $validator = new Validator;
        // make it
        $validation = $validator->make($data, [
            'email' => 'required|email',
            'sifre' => 'required|min:6'
        ]);

        // then validate
        $validation->validate();
        if ($validation->fails()) {
            Helper::response('validationError', $validation->errors()->toArray());
        } else {
            $this->loginUser($data);
        }
    }

    private function loginUser($data)
    {
        $user = $this->conn->prepare("SELECT * FROM tokens as t join uyeler as u on u.ref=t.user_id WHERE u.email=:email and u.sifre=:sifre");
        $user->execute(array('email' => $data['email'],'sifre'=>md5($data['sifre'])));
        $user_count = $user->rowCount();
        if ($user_count > 0) {
            $user=$user->fetch();
            Helper::response('success',$user);
        } else {
            Helper::response('loginError', 'Giriş yapılamadı');
        }
    }

}