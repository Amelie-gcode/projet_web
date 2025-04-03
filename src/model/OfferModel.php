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
    public function getAllOffers($limit = null, $offset = null) {
        // 🔹 1️⃣ Récupérer le nombre total d'offres (sans pagination)
        $queryCount = "SELECT COUNT(*) FROM Offers";
        $stmtCount = $this->connection->pdo->prepare($queryCount);
        $stmtCount->execute();
        $totalOffers = $stmtCount->fetchColumn(); // Nombre total d'offres

        // 🔹 2️⃣ Construire la requête pour récupérer les offres avec pagination
        $query = "SELECT * FROM Offers";
        $params = [];

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
        $offers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 🔹 3️⃣ Retourner les offres et le total des offres
        return [
            'offers' => $offers,
            'totalOffers' => $totalOffers
        ];
    }



    public function getALLOffersByResearch($research, $skills, $filters, $limit = null, $offset = null)
    {
        $queryBase = " FROM Offers o";
        $params = [];

        if (!empty($skills)) {
            $queryBase .= " LEFT JOIN Requires r ON o.offer_id = r.offer_id";
        }

        $queryBase .= " WHERE 1=1";

        if (!empty($research)) {
            $queryBase .= " AND (o.offer_title LIKE :research OR
                         o.offer_location LIKE :research OR
                         o.company_id IN (
                             SELECT company_id FROM Companies WHERE company_name LIKE :research
                         ))";
            $params[':research'] = '%' . $research . '%';
        }

        if (!empty($skills)) {
            $placeholders = [];
            foreach ($skills as $index => $skill) {
                $placeholder = ":skill$index";
                $placeholders[] = $placeholder;
                $params[$placeholder] = $skill;
            }
            if (!empty($placeholders)) {
                $queryBase .= " AND r.skill_id IN (" . implode(',', $placeholders) . ")";
            }
        }

        if (!empty($filters)) {
            if (!empty($filters['filter_alternance'])) {
                $queryBase .= " AND o.offer_type = 'alternance'";
            }
            if (!empty($filters['filter_stage'])) {
                $queryBase .= " AND o.offer_type = 'stage'";
            }
            if (!empty($filters['filter_moins_3mois'])) {
                $queryBase .= " AND TIMESTAMPDIFF(MONTH, o.offer_start_date, o.offer_end_date) <= 3";
            }
            if (!empty($filters['filter_plus_3mois'])) {
                $queryBase .= " AND TIMESTAMPDIFF(MONTH, o.offer_start_date, o.offer_end_date) > 3";
            }
        }

        // 🔹 1️⃣ Récupération du nombre total d'offres (sans pagination)
        $countQuery = "SELECT COUNT(DISTINCT o.offer_id) AS total" . $queryBase;
        $countStmt = $this->connection->pdo->prepare($countQuery);

        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $countStmt->execute();
        $totalOffers = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        // 🔹 2️⃣ Récupération des offres avec pagination (si définie)
        $query = "SELECT DISTINCT o.*" . $queryBase;

        if (!is_null($limit) && !is_null($offset)) {
            $query .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = (int) $limit;
            $params[':offset'] = (int) $offset;
        }

        $stmt = $this->connection->pdo->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $stmt->execute();
        $offers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 🔹 3️⃣ Retour des offres + total des offres
        return [
            'offers' => $offers,
            'totalOffers' => $totalOffers
        ];
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