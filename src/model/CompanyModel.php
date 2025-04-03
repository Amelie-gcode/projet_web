<?php
namespace App\model;

use PDO;

class CompanyModel extends Model
{

    public function __construct($connection = null) {
        if(is_null($connection)) {
            $this->connection = new DatabaseSQL();
        } else {
            $this->connection = $connection;
        }
    }

    public function getAllCompany($limit = null, $offset = null) {

        $queryCount = "SELECT COUNT(*) FROM Companies";
        $stmtCount = $this->connection->pdo->prepare($queryCount);
        $stmtCount->execute();
        $totalCompanies = $stmtCount->fetchColumn();

        $params = [];
        $query="SELECT * FROM Companies";
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
        $companies= $stmt->fetchall(PDO::FETCH_ASSOC);
        return [
            'companies' => $companies,
            'totalCompanies' => $totalCompanies,
        ];
    }

    public function getALLCompanyByResearch ($research,$limit = null, $offset = null)
    {
        $queryCount = "SELECT COUNT(*) FROM Companies";
        $stmtCount = $this->connection->pdo->prepare($queryCount);
        $stmtCount->execute();
        $totalCompanies = $stmtCount->fetchColumn();

        $query = (
            "SELECT * FROM Companies WHERE
                company_name LIKE :research"
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
        $companies= $stmt->fetchAll(PDO::FETCH_ASSOC);
        return [
            'companies' => $companies,
            'totalCompanies' => $totalCompanies
        ];
    }

    public function getCompany($id) {
        $query = (
            "SELECT * FROM Companies WHERE company_id = :id"
        );

        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getCompanyByName($name) {
        $query = (
            "SELECT * FROM companies WHERE company_name = :name"
        );
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        return $stmt;
    }

    public function addCompany($name, $email, $phone, $description) {
        $query = (
            "INSERT INTO Companies (company_name, company_email,company_phone , company_description)
            VALUES (:name, :email, :phone, :description)"
        );
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":phone", $phone, PDO::PARAM_STR);
        $stmt->bindValue(":description", $description, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }

    public function updateCompany($id, $name, $email, $phone, $description)
    {
        $query = (
            "UPDATE Companies SET
                company_name = :name,
                company_email = :email,
                company_phone = :phone,
                company_description = :description
            WHERE company_id = :id"
        );
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":phone", $phone, PDO::PARAM_STR);
        $stmt->bindValue(":description", $description, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;

    }
    public function deleteCompany($id) {
        $query = (
            "DELETE FROM Companies WHERE company_id = :id"
        );
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
}
