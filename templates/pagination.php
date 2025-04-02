<?php

$page = $_GET['page'] > 0 ? (int) $_GET['page'] : 1; // Récupérer le numéro de page

$itemParPage = ($page-1)*10;
$items= array_slice($objets, $itemParPage, 10);


foreach ($items as $i) {
    echo "Nom : " . htmlspecialchars($i['nom']) . " | Secteur : " . htmlspecialchars($i['secteur']) . " | Ville : " . htmlspecialchars($i['ville']) . "<br>";
}
echo "<br><a href='?page=" . ($page - 1) . "'>Page précédente</a> | <a href='?page=" . ($page + 1) . "'>Page suivante</a>";