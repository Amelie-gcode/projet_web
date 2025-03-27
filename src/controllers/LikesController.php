<?php

namespace App\controllers;

use App\model\LikesModel;

class LikesController
{
    public function __construct($templateEngine) {
        $this->templateEngine = $templateEngine;
        $this->model = new LikesModel();
    }
    public function showLikesByStudent(){
        if(isset($_GET['user_id'])){
            $user_id=$_GET['user_id'];
            $likes=$this->model->getLikesByUsers($user_id);
            echo $this->templateEngine->render('likes.twig.html', ['likes' => $likes]);
        }
    }
    public function nbLikesByOffer()
    {
        if (isset($_GET['offer_id'])){
            $offer_id=$_GET['offer_id'];
            $likes=$this->model->nbLikesByOffers($offer_id);
            echo $likes;
        }

    }
    public function addLike(){
        if(isset($_POST['user_id']) && isset($_POST['offer_id'])){
            $user_id=$_POST['user_id'];
            $offer_id=$_POST['offer_id'];
            $this->model->addLike($user_id,$offer_id);
        }
    }

    public function deleteLike(){
        if(isset($_POST['user_id']) && isset($_POST['offer_id'])){
            $user_id=$_POST['user_id'];
            $offer_id=$_POST['offer_id'];
            $this->model->deleteLike($user_id,$offer_id);
        }
    }
    public function isLiked(){
        if(isset($_POST['user_id']) && isset($_POST['offer_id'])){
            $user_id=$_POST['user_id'];
            $offer_id=$_POST['offer_id'];
            $this->model->isLiked($user_id,$offer_id);
        }
    }
    public function nbLikesByStudent()
    {
        if (isset($_GET['user_id'])){
            $user_id=$_GET['user_id'];
            $likes=$this->model->nbLikesByUsers($user_id);
            echo $likes;
        }

    }

}