<?php
namespace App\Controllers;

use App\Request;
use App\Session;
use App\Controller;
use App\DB;


final class UserController extends Controller{

    public function __construct(Request $request,Session $session){
        parent::__construct($request,$session);



    }


    public function login()
    {
        if (isset($_POST) && !empty($_POST['email']) && !empty($_POST['password'])) {
            if (session_status() != PHP_SESSION_ACTIVE) {
                session_start();
            }

            $user = $this->auth($_POST['email'], $_POST['password']);

            if ($user) {
                $_SESSION['user'] = $user;
                header('Location:' . BASE);
                return;
            }
        }

        header('Location: ' . BASE . '/user/gologin');
    }


    private function auth($email,$password)
    {

        try{
            $db=$this->getDB();
            $stmt=$db->prepare('SELECT * FROM user WHERE email = :email LIMIT 1');
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $count=$stmt->rowCount();
            $row=$stmt->fetchAll(\PDO::FETCH_ASSOC);

            if($count==1){
                $user=$row[0];

                $res = password_verify($password,$user['password']);

                if ($res){
                    return $user;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }catch(\PDOException $e){
            return false;
        }
    }



    public function register()
    {


        $db = $this->getDB();


        $params = [
            'email' => filter_input(INPUT_POST, 'email'),
            'username' => filter_input(INPUT_POST, 'username'),
            'password' => password_hash(filter_input(INPUT_POST, 'password'), PASSWORD_BCRYPT, ['cost' => 4]),
            'rol' => 1
        ];


       $db->insert('user',$params);



         header('Location:'.BASE);
    }


    function logout(){
        session_destroy();
        header('Location: ' . BASE);
    }






    public function goregister(){


        $this->render(NULL, 'register');



    }


    public function gologin(){


        $this->render(NULL, 'login');



    }



}