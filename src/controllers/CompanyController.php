<?php
namespace App\controllers;

use App\model\ApplyModel;
use App\model\CompanyModel;
use App\model\EvaluationsModel;
use App\model\OfferModel;
use App\model\UserModel;

class CompanyController extends Controller
{
    public function __construct($templateEngine)
    {
        $this->model = new CompanyModel();
        $this->templateEngine = $templateEngine;
    }

    public function showAllCompany()
    {
        $company = $this->model->getAllCompany();
        echo $this->templateEngine->render('company.twig.html', ['company' => $company]);
    }

    public function showAdminCompany()
    {
        $company = $this->model->getAllCompany();
        echo $this->templateEngine->render('adminCompany.twig.html', ['company' => $company]);
    }
    public function showForm()
    {
        if (isset($_GET['company_id'])) {
            $id = $_GET['company_id'];
            $company= $this->model->getCompany($id);
            echo $this->templateEngine->render('addCompany.twig.html', ['company' => $company]);
        }
        else{
            echo $this->templateEngine->render('addCompany.twig.html');
        }
    }
    public function showCompany()
    {
        $evalModel = new EvaluationsModel();
        $applyModel = new ApplyModel();
        $offerModel = new OfferModel();
        $userModel = new UserModel();

        if (isset($_GET['company_id'])) {
            $id = $_GET['company_id'];
            $company = $this->model->getCompany($id);
            $nbApply = $applyModel->getNumberApplyByCompany($id);
            $evaluations = $evalModel->getEvaluationByCompany($id);
            $avg = round( $evalModel->averageScore($id) ,1);
            $offers = $offerModel->getOfferByCompany($id);
            $users = [];

            foreach ($evaluations as $eval) {
                $userId = $eval['user_id']; // Récupère l'ID de l'utilisateur
                if (!isset($users[$userId])) { // Vérifie si l'utilisateur n'a pas déjà été récupéré
                    $users[$userId] = $userModel->getUser($userId);
                }
            }
            echo $this->templateEngine->render('companyInfo.twig.html', [
                'company' => $company,
                'evaluations' => $evaluations,
                'users' => $users, // Correction : utiliser user_id comme clé
                'nbApply' => $nbApply,
                'offers' => $offers,
                'moyenne' => $avg
            ]);
        } else {
            header('Location: ?uri=company/index');
            exit();
        }
    }


    public function addCompany() {
        if (isset($_GET['name']) &&
            isset($_GET['email']) &&
            isset($_GET['telephone']) &&
            isset($_GET['description'])) {
            $name = $_GET['name'];
            $email = $_GET['email'];
            $phone = $_GET['telephone'];
            $description = $_GET['description'];
            $this->model->addCompany($name, $email, $phone, $description);
            header('Location: ?uri=company/admin');

        } else {
            header('Location: ?uri=company/admin');
        }
    }

    public function updateCompany() {
        if (isset($_GET['id']) &&
            isset($_GET['name']) &&
            isset($_GET['email']) &&
            isset($_GET['telephone']) &&
            isset($_GET['description'])) {
            $id = $_GET['id'];
            $name = $_GET['name'];
            $email = $_GET['email'];
            $phone = $_GET['telephone'];
            $description = $_GET['description'];
            $this->model->updateCompany($id, $name, $email, $phone, $description);
            header('Location: ?uri=company/admin');
        } else {
            header('Location: ?uri=company/admin');
        };
    }

    public function deleteCompany() {
        if (isset($_GET['company_id'])) {
            $id = $_GET['company_id'];
            $this->model->deleteCompany($id);
            header('Location: ?uri=company/admin');
            exit;
        } else {
            header('Location: ?uri=company/admin');
            exit;
        }
    }

};

