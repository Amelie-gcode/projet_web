<?php

namespace App\model;

use PDO;

class   LikesModel extends Model
{
    public function __construct($connection = null) {

        if(is_null($connection)) {
            $this->connection = new DatabaseSQL();
        } else {
            $this->connection = $connection;
        }
    }

    public function addLike($id_user, $id_offer) {
        $query = (
            "INSERT INTO Likes (user_id, offer_id) VALUES (:id_user, :id_offer)"
        );
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id_user", $id_user, PDO::PARAM_INT);
        $stmt->bindValue(":id_offer", $id_offer, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function deleteLike($id_user, $id_offer) {
        $query = (
            "DELETE FROM Likes WHERE user_id = :id_user AND offer_id = :id_offer"
        );
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id_user", $id_user, PDO::PARAM_INT);
        $stmt->bindValue(":id_offer", $id_offer, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function getLikesByUsers($id_user) {
        $query = (
            "SELECT * FROM Likes WHERE user_id = :id_user"
        );
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id_user", $id_user, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function nbLikesByUsers($id_user) {
        $query = (
            "COUNT(*) FROM Likes WHERE user_id = :id_user"
        );
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id_user", $id_user, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        return $stmt;
    }

    public function nbLikesByOffers($id_offer) {
        $query = (
            "Select COUNT(*) as like_count FROM Likes WHERE offer_id = :id_offer"
        );
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id_offer", $id_offer, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Récupération du résultat

        return $result ? (int)$result['like_count'] : 0; // Assurer un retour en entier
    }

    public function isLiked($id_user, $id_offer) {
        $query = "SELECT * FROM Likes WHERE user_id = :id_user AND offer_id = :id_offer";
        $stmt = $this->connection->pdo->prepare($query);
        $stmt->bindValue(":id_user", $id_user, PDO::PARAM_INT);
        $stmt->bindValue(":id_offer", $id_offer, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Récupère la ligne

        return $result !== false; // Retourne true si une ligne existe, sinon false
    }



}