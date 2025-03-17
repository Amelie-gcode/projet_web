<?php

$page = $_GET['page'] > 0 ? (int) $_GET['page'] : 1; // Récupérer le numéro de page

$itemParPage = ($page-1)*10;
$entreprisesPaginees= array_slice($entreprise, $itemParPage, 10);


foreach ($entreprisesPaginees as $e) {
    echo "Nom : " . htmlspecialchars($e['nom']) . " | Secteur : " . htmlspecialchars($e['secteur']) . " | Ville : " . htmlspecialchars($e['ville']) . "<br>";
}
echo "<br><a href='?page=" . ($page - 1) . "'>Page précédente</a> | <a href='?page=" . ($page + 1) . "'>Page suivante</a>";