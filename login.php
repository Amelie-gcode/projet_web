<?php
session_start();

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "login_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupération des données du formulaire
$username = $_POST['username'];
$password = $_POST['password'];

// Préparer la requête pour chercher l'utilisateur par le nom d'utilisateur
$sql = $conn->prepare("SELECT password FROM users WHERE username = ?");
$sql->bind_param("s", $username);
$sql->execute();
$sql->bind_result($hashed_password);
$sql->fetch();
$sql->close();

// Vérification du mot de passe
if ($hashed_password && hash('sha256', $password) === $hashed_password) {
    // Si l'utilisateur est trouvé et le mot de passe est correct
    $_SESSION['user'] = $username;  // Stocke l'utilisateur dans la session
    echo "success";  // Réponse de succès
} else {
    // Si l'utilisateur ou le mot de passe est incorrect
    echo "error";  // Réponse d'erreur
}

$conn->close();
?>
