<?php

namespace App\model;
use pdo;
class StudentsModel
{
    function __construct($connection=null){
        if(is_null($connection)){
            $this->connection = new DatabaseSQL();
        }
        else {
            $this->connection = $connection;
        }
    }
    function getStudents(){
        $query = "SELECT * FROM Students";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        return $stmt;
    }
    function getStudent($id){
        $query = "SELECT * FROM Students WHERE user_id = :id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        return $stmt;
    }

}