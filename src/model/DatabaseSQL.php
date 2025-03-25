<?php

namespace App\model;

class DatabaseSQL {
    private $host = "20.229.240.117"; // Adresse du serveur MySQL
    private $dbname = "projet_web"; // Nom de la base de données
    private $username = "louison"; // Nom d'utilisateur MySQL
    private $password = "20051027aA&"; // Mot de passe MySQL
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
