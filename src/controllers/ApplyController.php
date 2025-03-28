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
    public function showApplyByOffer()
    {
        if (isset($_GET['offer_id'])) {
            $id = $_GET['offer_id'];
            $apply= $this->model->getApplyByOffer($id);
            echo $this->templateEngine->render('applyInfo.twig.html', ['apply' => $apply]);
        } else {
            header('Location: /apply');
        }
    }
    public function showApplyByUser()
    {
        if (isset($_GET['user_id'])) {
            $id = $_GET['user_id'];
            $apply= $this->model->getApplyByUser($id);
           return $apply;
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
        header('Location: index.php/?uri=offer/index');
    }
    public function nbApplyByCompany()
    {
        if (isset($_GET['id_company'])) {
            $id = $_GET['id_company'];
            $apply=$this->model->getNumberApplyByCompany($id);
            return $apply;
        }

    }


}