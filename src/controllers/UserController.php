<?php

namespace App\controllers;

use App\model\ApplyModel;
use App\model\OfferModel;
use App\model\UserModel;

class UserController extends Controller
{
    public function __construct($templateEngine) {
        $this->model = new UserModel();
        $this->templateEngine = $templateEngine;
    }
    public function showAllUsers(){
        $limit = 21;
        $page = $_GET['page'] ?? 1;
        $offset = (int)($page - 1) * $limit;
        if (isset($_GET['research'])) {
            $research = $_GET['research'];
            $result = $this->model->getALLUsersByResearch($research, $limit, $offset);
        } else {
            $result = $this->model->getAllUsers($limit, $offset);
        }
        $users = $result['users'];

        $totalUsers = $result['totalUsers'] ?? count($users); // Assurer un total correct

        $totalPages = ceil($totalUsers / $limit);
        echo $this->templateEngine->render('users.twig.html', [
            'users' => $users,
            'research' => $research ?? '',
            'page' => $page,
            'totalPages' => $totalPages]);
    }
    public function showUser(){
        $applyModel = new ApplyModel();
        $offerModel = new OfferModel();
        $id = 0;

        if (isset($_GET['user_id'])) {
            $id = $_GET['user_id'];
        } elseif (isset($_SESSION['user_status']) && $_SESSION['user_status'] == "Etudiant") {
            $id = $_SESSION['user_id'];
        }

        $user = $this->model->getUser($id);
        $applications = $applyModel->getApplyByUser($id);
        $offers = [];
        $nbApply = count($applications);

        if (!empty($applications)) {
            foreach ($applications as $application) {
                $offer = $offerModel->getOfferById($application['offer_id']);
                if ($offer) {
                    // Ajouter les informations de l'entreprise
                    $companyModel = new \App\model\CompanyModel();
                    $company = $companyModel->getCompany($offer['company_id']);
                    $offer['company_name'] = $company ? $company['company_name'] : 'Entreprise inconnue';

                    // Utiliser company_location du modèle de l'entreprise si disponible
                    $offer['offer_location'] = $offer['offer_location'] ?? ($company ? $company['company_location'] ?? 'Lieu inconnu' : 'Lieu inconnu');

                    // Ajouter la durée calculée avec la méthode existante
                    $offer['duration'] = $offerModel->calculateOfferDuration($offer);

                    // Ajouter les informations de candidature depuis l'objet application
                    $offer['apply_date'] = $application['apply_date'] ?? date('Y-m-d');
                    $offer['apply_status'] = $application['apply_status'] ?? 'En attente';

                    $offers[] = $offer;
                }
            }
        }

        echo $this->templateEngine->render('userInfo.twig.html', [
            'user' => $user,
            'offers' => $offers,
            'nbApply' => $nbApply,
            'session' => $_SESSION
        ]);
    }
    public function deleteUser(){
        if (isset($_GET['user_id'])) {
            $id = $_GET['user_id'];
            $this->model->deleteUser($id);
        }
        header('Location: ?uri=user/index');
    }
    public function addUser(): void
    {
        if (isset($_POST['name']) &&
            isset($_POST['forname']) &&
            isset($_POST['email']) &&
            isset($_POST['password']) &&
            isset($_POST['status'])) {
            $name = $_POST['name'];
            $forname = $_POST['forname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $status = $_POST['status'];
            $this->model->addUser($name,$forname, $email, $password, $status);
            }
        header('Location: ?uri=user/index');
    }

    public function updateUser(){
        if (isset($_POST['user_id']) &&
            isset($_POST['name']) &&
            isset($_POST['forname']) &&
            isset($_POST['email']) &&
            isset($_POST['status'])) {

            $id = $_POST['user_id'];
            $name = $_POST['name'];
            $forname = $_POST['forname'];
            $email = $_POST['email'];
            $status = $_POST['status'];

            $this->model->updateUser($id, $name, $forname, $email,  $status);
            header('Location: ?uri=user/index');
        } else {
            header('Location: ?uri=user/index');
        }
    }
    public function showForm(){
        if($_SERVER["REQUEST_METHOD"] == "GET"){
            var_dump($_GET);
        }
        if (isset($_GET['user_id'])) {
            $id = $_GET['user_id'];
            $user= $this->model->getUser($id);
            echo $this->templateEngine->render('addUser.twig.html', ['user' => $user]);
        }
        else{
            echo $this->templateEngine->render('addUser.twig.html');
        }
    }


}