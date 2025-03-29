<?php
namespace App\controllers;

use App\model\CompanyModel;

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
        if (isset($_GET['company_id'])) {
            $id = $_GET['company_id'];
            $company= $this->model->getCompany($id);
            return $company;
        } else {
            return false;
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
            $company=$this->model->getAllCompany();
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

