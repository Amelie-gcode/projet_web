<?php

namespace App\controllers;

use App\model\StudentsModel;

class StudentsController
{
    public function __construct($templateEngine) {
        $this->model = new StudentsModel();
        $this->templateEngine = $templateEngine;
    }
    public function showAllStudents(){
        $students = $this->model->getStudents();
        echo $this->templateEngine->render('users.twig.html', ['students' => $students]);

    }
    public function showStudent($id){
        $student = $this->model->getStudent($id);
        echo $this->templateEngine->render('user.twig.html', ['student' => $student]);
    }


}