<?php

namespace App\model;

use PDO;

class SkillsModel extends Model
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
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }
    function getSkill($id){
        $query = "SELECT * FROM Skills WHERE skill_id = :id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getSkillsByOffer($offer_id){
        $query = "SELECT skill_id FROM Requires WHERE offer_id = :offer_id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":offer_id", $offer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }

    function addSkillToOffer($offer_id, $skill_id) {
        $query = "INSERT INTO Requires (offer_id, skill_id) VALUES (:offer_id, :skill_id)";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":offer_id", $offer_id, PDO::PARAM_INT);
        $stmt->bindValue(":skill_id", $skill_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    function deleteSkillsForOffer($offer_id) {
        $query = "DELETE FROM Requires WHERE offer_id = :offer_id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":offer_id", $offer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
}