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
    public function login()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            error_log("Login attempt for email: $email");

            // Vérifie si l'email existe dans la base de données
            $user = $this->model->getUserByEmail($email);

            if ($user) {
                // Vérifie le mot de passe avec `password_verify`
                error_log("Stored hash: " . $user['user_password']);
                error_log("decrypted" . password_hash($password, PASSWORD_DEFAULT));
                error_log("Password verification result: " . (password_verify($password, $user['user_password']) ? "true" : "false"));


                if (password_verify($password, $user['user_password'])) {
                    // Ne pas appeler session_start() si déjà démarré dans index.php
                    setcookie( 'email', $email, time() + 60 * 60 * 24 * 31);
                    setcookie( 'password', $password, time() + 60 * 60 * 24 * 31);

                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_status'] = $user['user_status'];
                    $_SESSION['user_name'] = $user['user_firstname'];

                    error_log("Login successful for user ID: {$user['user_id']}");
                    header("Location: ?uri=offer/index");
                    $_SESSION['show_modal'] = 'false';
                    exit();
                } else {
                    error_log("Password verification failed for email: $email");
                    $_SESSION['login_error'] = "Mot de passe incorrect";
                    $_SESSION['show_modal'] = 'true';
                }
            } else {
                error_log("No user found with email: $email");
                $_SESSION['login_error'] = "Aucun utilisateur trouvé avec cet email";
                $_SESSION['show_modal'] = 'true';
            }

            // Redirection vers la page des offres avec message d'erreur dans la session
            header("Location: ?uri=offer/index");
            exit();
        }
    }

    public function logout() {
        $_SESSION = array();
        session_destroy();
        header("Location: ?uri=offer/index");
        exit();
    }
}