<?php

namespace App\controllers;

use App\model\RequiresModel;
use App\model\StudentsModel;

class RequiresController
{
    public function __construct($templateEngine) {
        $this->model = new RequiresModel();
        $this->templateEngine = $templateEngine;
    }
    public function showAllrequires(){
        $requires = $this->model->getAllRequires();
        echo $this->templateEngine->render('users.twig.html', ['requires' => $requires]);

    }
    public function showrequire(){
        if(isset($_POST['offer_id'])){
            $id = $_POST['offer_id'];
            $require= $this->model->getRequireByOffer($id);
        }
        else{
            die('erreur');
        }
    }
    public function showRequireBySkill(){
        if(isset($_POST['skill_id'])){
            $id = $_POST['skill_id'];
            $require= $this->model->getRequireBySkill($id);
        }
        else{
            die('erreur');
        }
    }


}