<?php

namespace App\controllers;

use App\model\EvaluationsModel;

class EvaluationsController
{
    public function __construct($templateEngine) {
        $this->templateEngine = $templateEngine;
        $this->model = new EvaluationsModel();
    }
    public function showAllEvaluations(){
        $evaluations = $this->model->getEvaluations();
        echo $this->templateEngine->render('company.twig.html', ['evaluations' => $evaluations]);
    }
    public function showEvaluation($id){
        $evaluation = $this->model->getEvaluation($id);
        echo $this->templateEngine->render('company.twig.html', ['evaluation' => $evaluation]);
    }
    public function addEvaluation()
    {
        if(isset($_POST['evaluation_comment'])
        && isset($_POST['evaluation_score'])
        && isset($_POST['evaluation_date'])
        && isset($_POST['user_id'])
        && isset($_POST['company_id']))
        {
            $evaluationComment = $_POST['evaluation_comment'];
            $evaluationScore = $_POST['evaluation_score'];
            $evaluationDate = $_POST['evaluation_date'];
            $userId = $_POST['user_id'];
            $companyId = $_POST['company_id'];
            $this->model->addEvaluation($evaluationComment, $evaluationScore, $evaluationDate, $userId, $companyId);
        }
    }
    public function getEvaluationByCompany()
    {
        $companyId = $_POST['company_id'];
        $evaluation = $this->model->getEvaluationByCompany($companyId);
        return $evaluation;

    }
    public function getavgscore()
    {
        if(isset($_POST['company_id'])){
            $companyId = $_POST['company_id'];
            $avg=$this->getavgscore($companyId);
            echo $avg;
        }

    }
}