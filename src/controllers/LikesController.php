<?php

namespace App\controllers;

use App\model\LikesModel;
use App\model\OfferModel;

class LikesController extends controller
{
    public function __construct($templateEngine) {
        $this->templateEngine = $templateEngine;
        $this->model = new LikesModel();
    }
    public function showLikesByStudent(){
        $offerModel= new OfferModel();
        if(isset($_SESSION['user_id'])){
            $user_id=$_SESSION['user_id'];
            $likes=$this->model->getLikesByUsers($user_id);
            $offers=[];
            foreach ($likes as $like){
                $offers[]= $offerModel->getOfferById($like['offer_id']);
            }
            echo $this->templateEngine->render('Likes.twig.html', ['offers' => $offers]);
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
        if(isset($_SESSION['user_id']) && isset($_GET['offer_id'])){
            $user_id=$_SESSION['user_id'];
            $offer_id=$_GET['offer_id'];
            $this->model->addLike($user_id,$offer_id);
        }
        if(isset($_GET['page'])){
            header('Location: index.php?uri=offer/show&offer_id='.$offer_id);
        }
        else {
            header('Location: index.php?uri=offer/index&offer_id=' . $offer_id);
        }
    }

    public function deleteLike(){
        if(isset($_SESSION['user_id']) && isset($_GET['offer_id'])){
            $user_id=$_SESSION['user_id'];
            $offer_id=$_GET['offer_id'];
            $this->model->deleteLike($user_id,$offer_id);
        }
        if(isset($_GET['page'])){
            header('Location: index.php?uri=offer/show&offer_id='.$offer_id);
        }
        else {
            header('Location: index.php?uri=offer/index&offer_id=' . $offer_id);
        }
    }
    public function isLiked(){
        if(isset($_SESSION['user_id']) && isset($_POST['offer_id'])){
            $user_id=$_SESSION['user_id'];
            $offer_id=$_POST['offer_id'];
            $this->model->isLiked($user_id,$offer_id);
        }
    }
    public function nbLikesByStudent()
    {
        if (isset($_SESSION['user_id'])){
            $user_id=$_SESSION['user_id'];
            $likes=$this->model->nbLikesByUsers($user_id);
            echo $likes;
        }

    }

}