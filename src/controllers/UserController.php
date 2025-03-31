<?php

namespace App\controllers;

use App\model\ApplyModel;
use App\model\OfferModel;
use App\model\UserModel;

class UserController extends Controller
{
    public function __construct($templateEngine) {
        $this->model = new UserModel();
        $this->templateEngine = $templateEngine;
    }
    public function showAllUsers(){
        $users = $this->model->getAllUsers();
        $userstudent= $this->model->getUserByRole('Etudiant');
        echo $this->templateEngine->render('users.twig.html', ['users' => $users, 'userstudent' => $userstudent]);

    }
    public function showUser(){
        $applyModel=new ApplyModel();
        $offerModel=new OfferModel();
        if (isset($_GET['user_id'])) {
            $id = $_GET['user_id'];
            $user= $this->model->getUser($id);
            $applications=$applyModel->getApplyByUser($id);
            $offers = [];
            if (!empty($applications)) {
                foreach ($applications as $application) {
                    $offer = $offerModel->getOfferById($application['offer_id']);
                    if ($offer) { // Vérifier si l'offre existe
                        $offers[] = $offer;
                    }
                }
            }

            echo $this->templateEngine->render('userInfo.twig.html', ['user' => $user, 'offers' => $offers]);
        } else {
            header('Location: ?uri=user/index');
        }
    }
    public function deleteUser(){
        if (isset($_GET['user_id'])) {
            $id = $_GET['user_id'];
            $this->model->deleteUser($id);
        }
        header('Location: ?uri=user/index');
    }
    public function addUser(): void
    {
        if (isset($_POST['name']) &&
            isset($_POST['forname']) &&
            isset($_POST['email']) &&
            isset($_POST['password']) &&
            isset($_POST['status'])) {
            $name = $_POST['name'];
            $forname = $_POST['forname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $status = $_POST['status'];
            $this->model->addUser($name,$forname, $email, $password, $status);
            }
        header('Location: ?uri=user/index');
    }

    public function updateUser(){
        if (isset($_POST['user_id']) &&
            isset($_POST['name']) &&
            isset($_POST['forname']) &&
            isset($_POST['email']) &&
            isset($_POST['password']) &&
            isset($_POST['role'])) {

            $id = $_POST['user_id'];
            $name = $_POST['name'];
            $forname = $_POST['forname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = intval($_POST['role']);

            $this->model->updateUser($id, $name, $forname, $email, $password, $role);
            header('Location: ?uri=user/index');
        } else {
            header('Location: ?uri=user/index');
        }
    }
    public function showForm(){
        if (isset($_GET['user_id'])) {
            $id = $_GET['user_id'];
            $user= $this->model->getUser($id);
            echo $this->templateEngine->render('addUser.twig.html', ['user' => $user]);
        }
        else{
            echo $this->templateEngine->render('addUser.twig.html');
        }
    }

}