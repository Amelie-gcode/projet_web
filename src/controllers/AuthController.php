<?php

namespace App\controllers;

use App\model\UserModel;

class AuthController extends Controller
{
    public function __construct($templateEngine) {
        $this->model= new UserModel();
    }
    public function showLoginForm()
    {
        echo $this->templateEngine->render('login.twig.html');
    }
    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->model->getUserByEmail($email);
            if ($user && password_verify($password, $user['user_password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_status'] = $user['user_status']; // Ex: 'admin', 'user', 'company'

                header("Location: ?uri=offer/index"); // Redirection
                exit();
            } else {
                echo "Identifiants incorrects";
            }
        }
    }
    public function logout() {
        session_destroy();
        header("Location: ?uri=login");
        exit();
    }
}