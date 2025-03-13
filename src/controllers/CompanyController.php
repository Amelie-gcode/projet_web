<?php
namespace App\controllers;

use App\model\CompanyModel;

class CompanyController extends Controller
{
    public function __construct($templateEngine) {
        $this->model = new CompanyModel();
        $this->templateEngine = $templateEngine;
    }
    public function showAllCompany()
    {
        $company = $this->model->getAllCompany();
        echo $this->templateEngine->render('company.twig.html', ['company' => $company]);
    }
    public function showCompany()
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        }
        return $this->model->getCompany($id);
    }

    public function addCompany() {
        if (isset($_POST['name']) &&
            isset($_POST['email']) &&
            isset($_POST['telephone']) &&
            isset($_POST['description'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['telephone'];
            $description = $_POST['description'];
            $this->model->addCompany($name, $email, $phone, $description);
            header('Location: /company');
        } else {
            header('Location: /company');
        }
    }

    public function updateCompany() {
        if (isset($_POST['id']) &&
            isset($_POST['name']) &&
            isset($_POST['email']) &&
            isset($_POST['telephone']) &&
            isset($_POST['description'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['telephone'];
            $description = $_POST['description'];
            $this->model->updateCompany($id, $name, $email, $phone, $description);
            header('Location: /company');
        } else {
            header('Location: /company');
        };
    }

    public function deleteCompany() {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $this->model->deleteCompany($id);
            header('Location: /company');
        } else {
            header('Location: /company');
        }
    }

};

