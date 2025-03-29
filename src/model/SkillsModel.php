<?php

namespace App\model;

use PDO;

class SkillsModel
{
    function __construct($connection=null){
        if(is_null($connection)){
            $this->connection = new DatabaseSQL();
        }
        else {
            $this->connection = $connection;
        }
    }
    function getSkills(){
        $query = "SELECT * FROM Skills";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    function getSkill($id){
        $query = "SELECT * FROM Skills WHERE skill_id = :id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        return $stmt;
    }


}