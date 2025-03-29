<?php

namespace App\model;
use PDO;

class UserModel extends Model
{
    public function __construct($connection = null) {
        if(is_null($connection)) {
            $this->connection = new DatabaseSQL();
        } else {
            $this->connection = $connection;
        }
    }
    public function getAllUsers() {
        $query="SELECT * FROM Users";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->execute();
        return  $stmt->fetchall(PDO::FETCH_ASSOC);
    }
    public function getUser($id) {
        $query = "SELECT * FROM Users WHERE user_id = :id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function addUser($name, $forname, $email, $password, $role) {
        $query="INSERT INTO Users (user_lastname,user_firstname,user_email,user_password,user_status) VALUES (:name, :forname, :email, :password, :role)";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":forname", $forname, PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);
        $stmt->bindValue(":role", $role, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function deleteUser($id) {
        $query= "DELETE  FROM Users WHERE user_id = :id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch( PDO::FETCH_ASSOC);
    }
    public function updateUser($id, $name, $forname, $email, $password, $role) {
        $query = (
            "UPDATE Users SET
                  user_lastname = :name,
                  user_firstname = :forname,
                  user_email = :email,
                  user_password = :password,
                  user_status = :role
              WHERE user_id = :id"
        );
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":forname", $forname, PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);
        $stmt->bindValue(":role", $role, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }
    public function getUserByRole($role) {
        $query = "SELECT * FROM Users WHERE user_status = :role";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":role", $role, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}