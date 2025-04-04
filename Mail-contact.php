<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire et les sécuriser
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    $to = "swayam0906@gmail.com"; // Adresse e-mail de destination
    $subject = "Nouveau message de $name";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $body = "Nom: $name\nEmail: $email\n\nMessage:\n$message";

    // Envoyer l'email
    if (mail($to, $subject, $body, $headers)) {
        echo "<script>alert('Message envoyé avec succès !'); window.location.href = 'contact.html';</script>";
    } else {
        echo "<script>alert('Échec de l\'envoi. Vérifiez votre serveur !'); window.location.href = 'contact.html';</script>";
    }
}
?>
