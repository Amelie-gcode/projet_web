<?php

namespace App\model;
use PDO ;

class ApplyModel extends Model
{
    public function __construct($connection = null) {
        if(is_null($connection)) {
            $this->connection = new DatabaseSQL();
        } else {
            $this->connection = $connection;
        }
    }
    public function getAllApply() {
        $query = "SELECT * FROM Applications";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        return $stmt;

    }
    public function getApplyByUser($id) {
        $query="Select * From Applications where user_id=:id";
        $stmt= $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id",$id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    public function getApplyByOfferAndUser($idOffer, $idUser) {
        $query="Select * From Applications where offer_id=:idOffer and user_id=:idUser";
        $stmt= $this->connection->pdo->prepare($query);
        $stmt->bindValue(":idOffer",$idOffer,PDO::PARAM_INT);
        $stmt->bindValue(":idUser",$idUser,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getApplyByOffer($id) {
        $query = "SELECT * FROM Applications WHERE offer_id= :id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        return $stmt;
    }
    public function addApply($id_offer, $id_user, $date, $motivation) {
        $query="Insert into Applications (user_id,offer_id,application_date,application_cover_letter) values (:id_user, :id_offer, :date, :motivation)";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id_user", $id_user, PDO::PARAM_INT);
        $stmt->bindValue(":id_offer", $id_offer, PDO::PARAM_INT);
        $stmt->bindValue(":date", $date, PDO::PARAM_STR);
        $stmt->bindValue(":motivation", $motivation, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;

    }
    public function getNumberApplyByUser($id) {
        $query = "SELECT COUNT(*) FROM Applications WHERE user_id = :id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        return $stmt;
    }
    public function getNumberApplyByCompany($id_company) {
        $query = "SELECT COUNT(*) as count
                FROM Applications 
                WHERE offer_id IN (SELECT offer_id FROM Offers WHERE company_id = :id)";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id_company, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch( PDO::FETCH_ASSOC)['count'] ?? 0;
    }


}