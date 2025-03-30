<?php

namespace App\controllers;

use App\model\OfferModel;
use App\model\UserModel;

class AuthController extends Controller
{
    public function __construct($templateEngine) {
        $this->model= new UserModel();
        $this->templateEngine = $templateEngine;
    }
    public function showLoginForm()
    {
        $offerModel = new OfferModel();
        $offers=$offerModel->getAllOffers();
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['show_modal']=true;
        }
        echo $this->templateEngine->render('offers.twig.html',['offers'=> $offers, 'show_modal' => $_SESSION['show_modal']]);
        unset($_SESSION['show_modal']);
    }
    public function login()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->model->getUserByEmail($email);

            $hashedPassword =password_hash($password, PASSWORD_DEFAULT);

            if ($hashedPassword == $user['password']) {
                echo "Identifiants corrects";
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_status'] = $user['user_status']; // Ex: 'admin', 'user', 'company'
                header("Location: ?uri=offer/index"); // Redirection
                exit();
            } else {
                echo "Identifiants incorrects";
                header("Location: ?uri=auth/login");
                exit();
            }
            echo "oui";
        }
        echo "non";
    }
    public function logout() {
        session_destroy();
        header("Location: ?uri=login");
        exit();
    }
}