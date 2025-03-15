<?php

namespace App\model;


class UserModel extends Model
{
    public function __construct($connection = null) {
        if(is_null($connection)) {
            $this->connection = new FileDatabase('users', ['name','forname','email', 'password','role']);
        } else {
            $this->connection = $connection;
        }
    }
    public function getAllUsers() {
        return $this->connection->getAllRecords();
    }
    public function getUser($id) {
        return $this->connection->getRecord($id);
    }
    public function addUser($name, $forname, $email, $password, $role) {
        $user = [
            'name' => $name,
            'forname' => $forname,
            'email' => $email,
            'password' => $password,
            'role' => $role
        ];
        return $this->connection->insertRecord($user);
    }
    public function deleteUser($id) {
        return $this->connection->deleteRecord($id);
    }
    public function updateUser($id, $name, $forname, $email, $password, $role) {
        $user = [
            'name' => $name,
            'forname' => $forname,
            'email' => $email,
            'password' => $password,
            'role' => $role
        ];
        return $this->connection->updateRecord($id, $user);

    }
    public function getUserByRole($role) {
        $data = [];
        $data = $this->getAllUsers();
        foreach($data as $user) {
            if($user['role'] != $role) {
                unset($user);
            }
        }
        return $data;
    }


}