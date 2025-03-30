<?php

namespace App\model;
use PDO;


class OfferModel extends Model
{
    public function __construct($connection = null) {
        if(is_null($connection)) {
            $this->connection = new DatabaseSQL();
        } else {
            $this->connection = $connection;
        }
    }
    public function getAllOffers(){
        $query="SELECT * FROM Offers";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getOfferById($id) {
        $query = "SELECT * FROM Offers WHERE offer_id = :id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function addOffer($id_company, $offerTitle, $offerLongDescription,$offerShortDescription,$offerProfileDescription,$offerSalary, $offerType,$offerStartDate,$offerEndDate, $offerLocation) {
        $query = (
        "INSERT INTO Offers (
            company_id,
            offer_title,
            offer_long_description,
            offer_short_description,
            offer_profile_description,
            offer_salary,
            offer_type,
            offer_start_date,
            offer_end_date,
            offer_location
        )
        VALUES (
                :id_company,
                :offerTitle,
                :offerLongDescription,
                :offerShortDescription,
                :offerProfileDescription,
                :offerSalary,
                :offerType,
                :offerStartDate,
                :offerEndDate,
                :offerLocation)"
        );
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id_company", $id_company, PDO::PARAM_INT);
        $stmt->bindValue(":offerTitle", $offerTitle, PDO::PARAM_STR);
        $stmt->bindValue(":offerLongDescription", $offerLongDescription, PDO::PARAM_STR);
        $stmt->bindValue(":offerShortDescription", $offerShortDescription, PDO::PARAM_STR);
        $stmt->bindValue(":offerProfileDescription", $offerProfileDescription, PDO::PARAM_STR);
        $stmt->bindValue(":offerSalary", $offerSalary, PDO::PARAM_STR);
        $stmt->bindValue(":offerType", $offerType, PDO::PARAM_STR);
        $stmt->bindValue(":offerStartDate", $offerStartDate, PDO::PARAM_STR);
        $stmt->bindValue(":offerEndDate", $offerEndDate, PDO::PARAM_STR);
        $stmt->bindValue(":offerLocation", $offerLocation, PDO::PARAM_STR);
        $stmt->execute();

        // Retourner l'ID de l'offre créée
        return $this->connection->pdo->lastInsertId();
    }
    public function deleteOffer($id) {
        $query= "DELETE  FROM Offers WHERE offer_id = :id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function updateOffer($offer_id,$id_company, $offerTitle, $offerLongDescription,$offerShortDescription,$offerProfileDescription,$offerSalary, $offerType,$offerStartDate,$offerEndDate, $offerLocation) {
        $query = (
            "UPDATE Offers SET
                  company_id = :id_company,
                  offer_title = :offerTitle,
                  offer_long_description = :offerLongDescription,
                  offer_short_description = :offerShortDescription,
                  offer_profile_description = :offerProfileDescription,
                  offer_salary = :offerSalary,
                  offer_type = :offerType,
                  offer_start_date = :offerStartDate,
                  offer_end_date = :offerEndDate,
                  offer_location = :offerLocation
              WHERE offer_id = :id"
        );
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id_company", $id_company, PDO::PARAM_INT);
        $stmt->bindValue(":offerTitle", $offerTitle, PDO::PARAM_STR);
        $stmt->bindValue(":offerLongDescription", $offerLongDescription, PDO::PARAM_STR);
        $stmt->bindValue(":offerShortDescription", $offerShortDescription, PDO::PARAM_STR);
        $stmt->bindValue(":offerProfileDescription", $offerProfileDescription, PDO::PARAM_STR);
        $stmt->bindValue(":offerSalary", $offerSalary, PDO::PARAM_STR);
        $stmt->bindValue(":offerType", $offerType, PDO::PARAM_STR);
        $stmt->bindValue(":offerStartDate", $offerStartDate, PDO::PARAM_STR);
        $stmt->bindValue(":offerEndDate", $offerEndDate, PDO::PARAM_STR);
        $stmt->bindValue(":id", $offer_id, PDO::PARAM_INT);
        $stmt->bindValue(":offerLocation", $offerLocation, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }
    public function getOfferByCompany($id_company) {
        $query = "SELECT * FROM Offers WHERE company_id = :id_company";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id_company", $id_company, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}