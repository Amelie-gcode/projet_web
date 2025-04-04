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
    public function getAllUsers($limit = null, $offset = null) {
        $queryCount = "SELECT COUNT(*) FROM Users";
        $stmtCount = $this->connection->pdo->prepare($queryCount);
        $stmtCount->execute();
        $totalUsers = $stmtCount->fetchColumn();


        $params = [];
        $query="SELECT * FROM Users";
        if (!is_null($limit) && !is_null($offset)) {
            $query .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = (int) $limit;
            $params[':offset'] = (int) $offset;
        }

        $stmt = $this->connection->pdo->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        }
        $stmt->execute();
        $users= $stmt->fetchall(PDO::FETCH_ASSOC);
        return [
            'users' => $users,
            'totalUsers' => $totalUsers
        ];
    }

    public function getALLUsersByResearch($research, $limit = null, $offset = null) {

        $queryCount = "SELECT COUNT(*) FROM Users";
        $stmtCount = $this->connection->pdo->prepare($queryCount);
        $stmtCount->execute();
        $totalUsers = $stmtCount->fetchColumn();


        $query = (
            "SELECT * FROM Users WHERE
                CONCAT(user_firstname, ' ', user_lastname) LIKE :research OR
                CONCAT(user_lastname, ' ', user_firstname) LIKE :research OR
                user_status LIKE :research"
        );
        $params=[];
        if (!is_null($limit) && !is_null($offset)) {
            $query .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = (int) $limit;
            $params[':offset'] = (int) $offset;
        }
        $stmt = $this->connection->pdo->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->bindValue(':research', '%' . $research . '%');
        $stmt->execute();
        $users= $stmt->fetchAll(PDO::FETCH_ASSOC);
        return [
            'users' => $users,
            'totalUsers' => $totalUsers
        ];
    }

    public function getUser($id) {
        $query = "SELECT * FROM Users WHERE user_id = :id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function addUser($name, $forname, $email, $password, $role) {

        $queryCheck = "SELECT COUNT(*) FROM Users WHERE user_email = :email";
        $stmtCheck = $this->connection->pdo->prepare($queryCheck);
        $stmtCheck->bindValue(":email", $email, PDO::PARAM_STR);
        $stmtCheck->execute();
        $result = $stmtCheck->fetchColumn();

        if ($result > 0) {
            // L'email existe déjà, retourner une erreur ou un message
            // Après avoir vérifié si l'email existe
            $_SESSION['error_message'] = "Cet email est déjà utilisé.";

        } else {

            $query="INSERT INTO Users (user_lastname,user_firstname,user_email,user_password,user_status) VALUES (:name, :forname, :email, :password, :role)";
            $stmt = $this->connection->pdo->prepare($query);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindValue(":name", $name, PDO::PARAM_STR);
            $stmt->bindValue(":forname", $forname, PDO::PARAM_STR);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->bindValue(":password", $hashedPassword, PDO::PARAM_STR);
            $stmt->bindValue(":role", $role, PDO::PARAM_STR);
            $stmt->execute();
            $_SESSION['success_message'] = "Utilisateur ajouté avec succès!";
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
    public function deleteUser($id) {
        $query= "DELETE  FROM Users WHERE user_id = :id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch( PDO::FETCH_ASSOC);
    }
    public function updateUser($id, $name, $forname, $email, $role) {
        $query = (
            "UPDATE Users SET
                  user_lastname = :name,
                  user_firstname = :forname,
                  user_email = :email,
                  user_status = :role
              WHERE user_id = :id"
        );
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":forname", $forname, PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":role", $role, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }
    public function getUserByRole($role) {
        $query = "SELECT * FROM Users WHERE user_status = :role";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":role", $role, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getUserByEmail($email) {
        $query = "SELECT * FROM Users WHERE user_email = :email";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}