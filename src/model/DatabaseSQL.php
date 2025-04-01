<?php

namespace App\model;

use PDO;
use PDOException;


class DatabaseSQL {
 /*private $host = "20.229.240.117"; // Nom du serveur Azure MySQL
 private $dbname = "projet_web"; // Nom de ta base de données
 private $username = "louison"; // Utilisateur avec suffixe "@amely"
 private $password = "20051027aA&"; // Mot de passe de l'utilisateur MySQL
 */

    private $host = "localhost:3306"; // Nom du serveur Azure MySQL
    private $dbname = "amely_test"; // Nom de ta base de données
    private $username = "root"; // Utilisateur avec suffixe "@amely"
    private $password = ""; // Mot de passe de l'utilisateur MySQL
    public $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
}