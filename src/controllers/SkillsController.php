<?php

namespace App\controllers;

use App\model\SkillsModel;

class SkillsController
{
    public function __construct($templateEngine) {
        $this->model = new SkillsModel();
        $this->templateEngine = $templateEngine;
    }
    public function showAllSkills(){
        $skills = $this->model->getSkills();
        echo $this->templateEngine->render('users.twig.html', ['skills' => $skills]);

    }
    public function showSkill($id){
        $skill = $this->model->getSkill($id);
        echo $this->templateEngine->render('users.twig.html', ['skill' => $skill]);
    }

}