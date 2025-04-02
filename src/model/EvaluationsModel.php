<?php

namespace App\model;
use pdo;

class EvaluationsModel extends Model
{
    function __construct($connection =null){
        if(is_null($connection)){
            $this->connection = new DatabaseSQL();

        }
        else {
            $this->connection = $connection;
        }
    }
    function getEvaluations(){
        $query = "SELECT * FROM Evaluations";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }
    function getEvaluation($id){
        $query = "SELECT * FROM Evaluations WHERE evaluation_id = :id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }
    function getEvaluationByCompany($id){
        $query = "Select * From Evaluations where company_id = :id";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    function addEvaluation($id_user, $id_company, $evaluationComment, $evaluationDate, $evaluationScore)
    {
        $query = "INSERT INTO Evaluations (user_id, company_id, evaluation_comment,evaluation_date,evaluation_score) VALUES (:id_user, :id_company, :evaluationComment, :evaluationDate, :evaluationScore)";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id_user", $id_user, PDO::PARAM_INT);
        $stmt->bindValue(":id_company", $id_company, PDO::PARAM_INT);
        $stmt->bindValue(":evaluationComment", $evaluationComment, PDO::PARAM_STR);
        $stmt->bindValue(":evaluationDate", $evaluationDate, PDO::PARAM_STR);
        $stmt->bindValue(":evaluationScore", $evaluationScore, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function averageScore($id_company) {
        $query = (
            "SELECT ROUND(AVG(evaluation_score), 1) AS moyenne 
            FROM Evaluations 
            WHERE company_id = :id_company;"
        );
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id_company", $id_company, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['moyenne'] ?? 0;

    }
}
