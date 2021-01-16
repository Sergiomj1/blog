<?php
namespace App\Controllers;

use App\Controller;
use App\Request;
use App\Session;


class PostController extends Controller
{

    public function __construct(Request $request, Session $session)
    {
        parent::__construct($request, $session);
    }

    public function index()
    {
        $this->render();
    }

    public function view() {
        $params = $this->request->getParams();
        $post = [];

        if (isset($params['id']) && !empty($params['id'])) {
            header('Location: ' . BASE . '/post/details/id/'  . $params['id']);
        }

        $this->render(['post' => $post], 'post');
    }


    public function details() {
        $params = $this->request->getParams();
        $post = [];

        if (isset($params['id']) && !empty($params['id'])) {
            $post = $this->getDB()->getPost($params['id']);
        }


        $this->render(['post' => $post], 'viewpost');
    }



    private function upload($input_file_name) {
        if (isset($_FILES[$input_file_name])) {
            $filename = substr(md5(uniqid()), 0, 10) . '_' . $_FILES[$input_file_name]['name'];
            $destination = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/' . $filename;
            $uploaded = move_uploaded_file($_FILES[$input_file_name]['tmp_name'], $destination);

            if ($uploaded) {
                return '/public/uploads/' . $filename;
            }
        }

        return false;
    }

    public function add() {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }

        if ($_SESSION['user']['rol'] != 2) {
            header('Location: ' . BASE . '/');
        }

        if (isset($_POST) && !empty($_POST['titulo']) && !empty($_POST['contenido']) && !empty($_POST['categorias'])) {
            $categoria = $_POST['categorias'];
            $category_id = $this->getDB()->addCategory($categoria);
            $img_url = null;
            $upload = $this->upload('imagen');

            if ($upload) {
                $img_url = $upload;
            }

            $post_values = [
                'title' => $_POST['titulo'],
                'contenido' => htmlspecialchars($_POST['contenido']),
                'created_at' => date('Y-m-d H:i:s'),
                'imagen' => $img_url,
                'user_id' => $_SESSION['user']['id'],
                'category_id' => $category_id,
            ];

            $post_id = $this->getDB()->addPost($post_values);

            if ($post_id) {
                header('Location: ' . BASE . '/post/view/id/' . $post_id);
            }
        }

        $this->render(null, 'addpost');
    }

    public function edit() {

        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }

        if ($_SESSION['user']['rol'] != 2) {
            header('Location: ' . BASE . '/');
        }


        if (isset($_POST) && !empty($_POST['id']) && !empty($_POST['titulo']) && !empty($_POST['contenido']) && !empty($_POST['categorias'])) {
            $categoria = $_POST['categorias'];
            $category_id = $this->getDB()->addCategory($categoria);
            $upload = $this->upload('imagen');

            $post_values = [
                'title' => $_POST['titulo'],
                'contenido' => htmlspecialchars($_POST['contenido']),
                'user_id' => $_SESSION['user']['id'],
                'category_id' => $category_id,
            ];

            if ($upload) {
                $post_values['imagen'] = $upload;
            }

            $post_id = $this->getDB()->editPost($post_values, $_POST['id']);

            if ($post_id) {
                header('Location: ' . BASE . '/post/view/id/' . $_POST['id']);
            }
        }

        $params = $this->request->getParams();
        $post = [];

        if (isset($params['id']) && !empty($params['id'])) {
            $post = $this->getDB()->getPost($params['id']);
        }

        $this->render(['post' => $post], 'editpost');
    }



    public function delete() {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }

        if ($_SESSION['user']['rol'] != 2) {
            header('Location: ' . BASE . '/');
        }

        $params = $this->request->getParams();

        if (isset($params['id']) && !empty($params['id'])) {
            $this->getDB()->remove('post', $params['id']);
        }

        header('Location: ' . BASE . '/');
    }

    public function list() {

    }

}