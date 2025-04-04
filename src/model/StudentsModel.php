<?php

namespace App\model;
use pdo;
class StudentsModel extends Model
{
    function __construct($connection=null){
        if(is_null($connection)) {
            $this->connection = new DatabaseSQL();
        } else {
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
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }
    function addStudent($user_id){
        $query = "INSERT INTO Students (user_id, job_search_status, hours_per_week) VALUES (:user, :status, :time)";
        $stmt = $this->connection->pdo->prepare($query);
        $status= "Recherche";
        $time= 0;
        $stmt->bindValue(":user", $user_id, PDO::PARAM_INT);
        $stmt->bindValue(":status", $status, PDO::PARAM_STR);
        $stmt->bindValue(":time", $time, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }

}