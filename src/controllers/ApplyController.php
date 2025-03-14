<?php

namespace App\controllers;

use App\model\ApplyModel;

class ApplyController extends Controller
{
    public function __construct($templateEngine) {
        $this->model = new ApplyModel();
        $this->templateEngine = $templateEngine;
    }
    public function showAllApply()
    {
        $apply = $this->model->getAllApply();
        echo $this->templateEngine->render('Apply.twig.html', ['apply' => $apply]);
    }
    public function showApply()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $apply= $this->model->getApply($id);
            echo $this->templateEngine->render('applyInfo.twig.html', ['apply' => $apply]);
        } else {
            header('Location: /apply');
        }
    }
    public function deleteApply() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->model->deleteApply($id);
        }
    }
    public function addApply() {
        if (isset($_GET['id_offer']) &&
            isset($_GET['id_user']) &&
            isset($_GET['date']) &&
            isset($_GET['motivation'])) {
            $id_offer = $_GET['id_offer'];
            $id_user = $_GET['id_user'];
            $date = $_GET['date'];
            $motivation = $_GET['motivation'];
            $this->model->addApply($id_offer, $id_user, $date, $motivation);
            }
        $header = 'Location: offer/index';
    }


}