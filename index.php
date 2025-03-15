<?php
/**
 * This is the router, the main entry point of the application.
 * It handles the routing and dispatches requests to the appropriate controller methods.
 */


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "vendor/autoload.php";

use App\controllers\CompanyController;
use App\controllers\ApplyController;
use App\controllers\OfferController;

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'debug' => true
]);

if (isset($_GET['uri'])) {
    $uri = $_GET['uri'];
} else {
    $uri = 'offer/index';
}

$parts = explode('/', $uri);
$controllerName = isset($parts[0]) ? $parts[0] : 'company';
$action = isset($parts[1]) ? $parts[1] : 'index';

// Déterminer quel contrôleur utiliser en fonction de l'URI
switch ($controllerName) {
    case 'company':
        $controller = new CompanyController($twig);

        // Gestion des actions pour le contrôleur Company
        switch ($action) {
            case 'index':
                $controller->showAllCompany();
                break;
            case 'add':
                $controller->addCompany();
                break;
            case 'update':
                $controller->updateCompany();
                break;
            case 'delete':
                $controller->deleteCompany();
                break;
            case 'show':
                $controller->showCompany();
                break;
            default:
                echo '404 Not Found - Action inconnue';
                break;
        }
        break;

    case 'apply':
        $controller = new ApplyController($twig);

        switch ($action) {
            case 'index':
                $controller->showAllApply();
                break;
            case 'add':
                $controller->addApply();
                break;
            case 'delete':
                $controller->deleteApply();
                break;
            case 'show':
                $controller->showApply();
                break;
            default:
                echo '404 Not Found - Action inconnue';
                break;
        }
        break;
    case 'offer':
        $controller = new OfferController($twig);

        switch ($action) {
            case 'index':
                $controller->showAllOffers();
                break;
            case 'add':
                $controller->addOffer();
                break;
            case 'delete':
                $controller->deleteOffer();
                break;
            case 'show':
                $controller->showOffer();
                break;
            case 'update':
                $controller->updateOffer();
                break;
            default:
                echo '404 Not Found - Action inconnue';
                break;
        }
        break;

    default:
        echo '404 Not Found - Contrôleur inconnu';
        break;

}