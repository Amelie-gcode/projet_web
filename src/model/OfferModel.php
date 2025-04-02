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

    public function getALLOffersByResearch($research, $skills, $filters)
    {
        $query = "SELECT DISTINCT o.* FROM Offers o"; // Utilisation de DISTINCT pour éviter les doublons
        $params = []; // Tableau pour stocker les valeurs des paramètres

        // 🔹 Si on a des skills, on ajoute un INNER JOIN avec la table Requires
        if (!empty($skills)) {
            $query .= " LEFT JOIN Requires r ON o.offer_id = r.offer_id";
        }

        $query .= " WHERE 1=1"; // Toujours vrai, permet d'ajouter dynamiquement des conditions avec AND

        // 🔹 Recherche par mot-clé
        if (!empty($research)) {
            $query .= " AND (o.offer_title LIKE :research OR
                          o.offer_location LIKE :research OR
                          o.company_id IN (
                              SELECT company_id FROM Companies WHERE company_name LIKE :research
                          ))";
            $params[':research'] = '%' . $research . '%';
        }

        // 🔹 Filtrage par compétences
        if (!empty($skills)) {
            $placeholders = [];
            foreach ($skills as $index => $skill) {
                $placeholder = ":skill$index";
                $placeholders[] = $placeholder;
                $params[$placeholder] = $skill;
            }
            if (!empty($placeholders)) {
                $query .= " AND r.skill_id IN (" . implode(',', $placeholders) . ")";
            }
        }

        // 🔹 Filtres supplémentaires
        if (!empty($filters)) {
            if ($filters['filter_alternance']) {
                $query .= " AND o.offer_type = 'alternance'";
            }
            if ($filters['filter_stage']) {
                $query .= " AND o.offer_type = 'stage'";
            }
            if ($filters['filter_moins_3mois']) {
                $query .= " AND TIMESTAMPDIFF(MONTH, o.offer_start_date, o.offer_end_date) <= 3";
            }
            if ($filters['filter_plus_3mois']) {
                $query .= " AND TIMESTAMPDIFF(MONTH, o.offer_start_date, o.offer_end_date) > 3";
            }
        }

        // 🔹 Exécution de la requête préparée
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    function getOfferById($id) {
        $query = "SELECT * FROM Offers WHERE offer_id = :id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function addOffer($id_company, $offerTitle, $offerLongDescription,$offerShortDescription,$offerProfileDescription,$offerSalary, $offerType,$offerStartDate,$offerEndDate, $offerLocation, $offerDate) {
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
            offer_location,
            offer_date
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
                :offerLocation,
                :offerDate)"
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
        $stmt->bindValue(":offerDate", $offerDate, PDO::PARAM_STR);
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
    public function updateOffer($offer_id,$id_company, $offerTitle, $offerLongDescription,$offerShortDescription,$offerProfileDescription,$offerSalary, $offerType,$offerStartDate,$offerEndDate, $offerLocation, $offerDate) {
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
                  offer_location = :offerLocation,
                  offer_date = :offerDate
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
        $stmt->bindValue(":offerDate", $offerDate, PDO::PARAM_STR);
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

    public function calculateOfferDuration($offer) {
        if(!empty($offer['offer_start_date']) && !empty($offer['offer_end_date'])) {
            $start = new \DateTime($offer['offer_start_date']);
            $end = new \DateTime($offer['offer_end_date']);
            $interval = $start->diff($end);
            $totalDays = $interval->days;
            return ceil($totalDays / 7);
        } else {
            return "N/A";
        }
    }

}