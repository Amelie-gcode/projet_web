<?php

namespace App\model;
use pdo;
class RequiresModel
{
    function __construct($connection=null){
        if(is_null($connection)){
            $this->connection = new DatabaseSQL();
        }
        else {
            $this->connection = $connection;
        }
    }
    function getRequires(){
        $query = "SELECT * FROM Requires";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        return $stmt;
    }
    function getRequireBySkill($id){
        $query = "SELECT * FROM Requires WHERE skill_id = :id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        return $stmt;
    }
    function getRequireByOffer($id){
        $query = "SELECT * FROM Requires WHERE offer_id = :id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        return $stmt;
    }


}