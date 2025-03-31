<?php
/**
 * This is the router, the main entry point of the application.
 * It handles the routing and dispatches requests to the appropriate controller methods.
 */
session_start();


echo "<pre>";
print_r($_GET);
echo "</pre>";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "vendor/autoload.php";

use App\controllers\CompanyController;
use App\controllers\ApplyController;
use App\controllers\OfferController;
use App\controllers\UserController;
use App\controllers\EvaluationsController;
use App\controllers\LikesController;
use App\controllers\RequiresController;
use App\controllers\SkillsController;
use App\controllers\StudentsController;
use App\controllers\AuthController;


$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'debug' => true,
    'cache' => false
]);
$twig->addGlobal('session', $_SESSION);

if (isset($_GET['uri'])) {
    $uri = $_GET['uri'];
} else {
    $uri = 'offer/index';
}

$templateEngine = $twig;

$parts = explode('/', $uri);
$controllerName = isset($parts[0]) ? $parts[0] : 'offer';
$action = isset($parts[1]) ? $parts[1] : 'index';


$companyController = new CompanyController($twig);
$evalController = new EvaluationsController($twig);
$applyController = new ApplyController($twig);
$offerController = new OfferController($twig);
$likesController = new LikesController($twig);
$userController = new UserController($twig);
$authController = new AuthController($twig);

// Déterminer quel contrôleur utiliser en fonction de l'URI
switch ($controllerName) {
    case 'company':

        // Gestion des actions pour le contrôleur Company
        switch ($action) {
            case 'index':
                $companyController->showAllCompany();
                break;
            case 'admin':
                $companyController->showAdminCompany();
                break;
            case 'add':
                $companyController->addCompany();
                break;
            case 'update':
                $companyController->updateCompany();
                break;
            case 'delete':
                $companyController->deleteCompany();
                break;
            case 'show':
                $companyController->showCompany();
                break;
            case 'showForm':
                $companyController->showForm();
                break;
            default:
                echo '404 Not Found - Action inconnue';
                break;
        }
        break;

    case 'apply':


        switch ($action) {
            case 'index':
                $applyController->showAllApply();
                break;
            case 'add':
                $applyController->addApply();
                break;
            case 'delete':
                $applyController->deleteApply();
                break;
            case 'show':
                $applications=$applyController->showApplyByUser();
                $likes=$likesController->showLikesByStudent();

                echo $this->templateEngine->render('Likes.twig.html', ['applications' => $applications, 'likes' => $likes]);
                break;
            default:
                echo '404 Not Found - Action inconnue';
                break;
        }
        break;
    case 'offer':

        switch ($action) {
            case 'index':
                $offerController->showAllOffers();
                break;
            case 'show':
                $offerController->showOffer();
                break;
            case 'add':
                $offerController->addOffer();
                header('Location: index.php?uri=offer/admin');
                break;
            case 'delete':
                $offerController->deleteOffer();
                header('Location: index.php?uri=offer/admin');
                break;
            case 'update':
                $offerController->updateOffer();
                break;
            case 'showForm':
                $offerController->showForm();
                break;
            case 'admin':
                $offerController->showAdminOffer();
                break;
            default:
                echo '404 Not Found - Action inconnue';
                break;
        }
        break;
    case 'user':


        switch ($action) {
            case 'index':
                $userController->showAllUsers();
                break;
            case 'add':
                $userController->addUser();
                break;
            case 'delete':
                $userController->deleteUser();
                break;
            case 'show':
                $userController->showUser();
                break;
            case 'update':
                $userController->updateUser();
                break;
            case 'showForm':
                $userController->showForm();
                break;
            default:
                echo '404 Not Found - Action inconnue';
                break;
        }
        break;
    case 'evaluation':
        switch ($action) {
            case 'add':
                $evalController->addEvaluation();
                header('Location: index.php?uri=company/index');
                break;
            default:
                echo '404 Not Found - Action inconnue';
                break;
        }
        break;
    case 'auth':
        switch ($action) {
            case 'showForm':
                $authController->showLoginForm();
                break;
            case 'login':
                $authController->login();
                break;
            case 'logout':
                $authController->logout();
                break;
        }
        break;


    default:
        echo '404 Not Found - Contrôleur inconnu';
        break;

}