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
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $user= $this->model->getUser($id);
            echo $this->templateEngine->render('userInfo.twig.html', ['user' => $user]);
        } else {
            header('Location: /users');
        }
    }
    public function deleteUser(){
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->model->deleteUser($id);
        }
    }
    public function addUser(){
        if (isset($_GET['name']) &&
            isset($_GET['forname']) &&
            isset($_GET['email']) &&
            isset($_GET['password']) &&
            isset($_GET['role'])) {
            $name = $_GET['name'];
            $forname = $_GET['forname'];
            $email = $_GET['email'];
            $password = $_GET['password'];
            $role = $_GET['role'];
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

}