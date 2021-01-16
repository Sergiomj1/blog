<?php

namespace App\Controllers;

use App\Controller;
use App\Request;
use App\Session;



class ComentController extends Controller
{
    public function __construct(Request $request, Session $session)
    {
        parent::__construct($request, $session);
    }

    public function index()
    {
        $this->render();
    }


    public function add(){

        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }


        if (isset($_POST) && !empty($_POST['comment'])){



            $comment_values = [

                'comment' => $_POST['comment'],
                'post_id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
                'created_at' => date('Y-m-d H:i:s'),
            ];


            $comment_id = $this->getDB()->addcomment($comment_values);

        }

        if ($comment_id) {
            header('Location: ' . BASE . '/post/details/id/'  . $_POST['id']);
        }

    }





}