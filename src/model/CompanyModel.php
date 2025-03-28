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

    public function getAllCompany() {
        $query = (
            "SELECT * FROM Companies"
        );

        $stmt = $this->connection->pdo->prepare($query);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        return $stmt;
    }

    public function getCompany($id) {
        $query = (
            "SELECT * FROM Companies WHERE id_company = :id"
        );

        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        return $stmt;
    }


    public function getCompanyByName($name) {
        $query = (
            "SELECT * FROM companies WHERE name_compnay = :name"
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
                name_company = :name,
                email_company = :email,
                phone_company = :phone,
                description_company = :description
            WHERE id_company = :id"
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
            "DELETE FROM Companies WHERE id_company = :id"
        );
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
}
