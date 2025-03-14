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

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'debug' => true
]);

if (isset($_GET['uri'])) {
    $uri = $_GET['uri'];
} else {
    $uri = 'company';
}


$controller = new CompanyController($twig);
switch ($uri) {
    case 'company':
        $controller->showAllCompany();

        break;
    case 'add_company':
        $controller->addCompany();
        echo 'Add Company action';
        break;
    case 'update_company':
        // TODO : call the checkTask method of the controller
        $controller->updateCompany();
        echo 'Update Company action';
        break;
    case 'delete_company':
        // TODO : call the historyPage method of the controller
        $controller->deleteCompany();
        echo 'Delete Company';
        break;
    case 'get_company':
        // TODO : call the aboutPage method of the controller
        $controller->showCompany();
        break;
    default:
        // TODO : return a 404 error
        echo '404 Not Found';
        break;
}