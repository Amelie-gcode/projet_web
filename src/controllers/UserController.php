<?php

namespace App\controllers;

use App\model\UserModel;

class UserController extends Controller
{
    public function __construct($templateEngine) {
        $this->model = new UserModel();
        $this->templateEngine = $templateEngine;
    }
    public function showAllUsers(){
        $users = $this->model->getAllUsers();
        echo $this->templateEngine->render('users.twig.html', ['users' => $users]);

    }
    public function showUser(){
        if (isset($_GET['user_id'])) {
            $id = $_GET['user_id'];
            $user= $this->model->getUser($id);
            echo $this->templateEngine->render('userInfo.twig.html', ['user' => $user]);
        } else {
            header('Location: /users');
        }
    }
    public function deleteUser(){
        if (isset($_GET['user_id'])) {
            $id = $_GET['user_id'];
            $this->model->deleteUser($id);
        }
    }
    public function addUser(): void
    {
        if (isset($_POST['name']) &&
            isset($_POST['forname']) &&
            isset($_POST['email']) &&
            isset($_POST['password']) &&
            isset($_POST['role'])) {
            $name = $_POST['name'];
            $forname = $_POST['forname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];
            $this->model->addUser($name,$forname, $email, $password, $role);
            }


    }
    public function updateUser(){
        if (isset($_GET['id']) &&
            isset($_GET['name']) &&
            isset($_GET['forname']) &&
            isset($_GET['email']) &&
            isset($_GET['password']) &&
            isset($_GET['role'])) {
            $id = $_GET['id'];
            $name = $_GET['name'];
            $forname = $_GET['forname'];
            $email = $_GET['email'];
            $password = $_GET['password'];
            $role = $_GET['role'];
            $this->model->updateUser($id, $name, $forname, $email, $password, $role);
            }
    }
    public function showForm(){
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $user= $this->model->getUser($id);
            echo $this->templateEngine->render('addUser.twig.html', ['user' => $user]);
        }
        else{
            echo $this->templateEngine->render('addUser.twig.html');
        }
    }

}