<?php

namespace App\controllers;

use App\model\ApplyModel;
use App\model\OfferModel;
use App\model\UserModel;

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
    public function showApplyByOfferAndUser()
    {
        $offerModel= new OfferModel();
        $userModel= new UserModel();
        if (isset($_GET['offer_id']) && isset($_GET['user_id'])) {
            $idOffer = $_GET['offer_id'];
            $idUser= $_GET['user_id'];
            $offer= $offerModel->getOfferById($idOffer);
            $user= $userModel->getUser($idUser);
            $apply= $this->model->getApplyByOfferAndUser($idOffer, $idUser);
            echo $this->templateEngine->render('ApplyInfo.twig.html', ['apply' => $apply, 'offer' => $offer, 'user' => $user]);
        } else {
            header('Location: offer/index');
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


        if (isset($_POST['id_offer']) &&
            isset($_POST['id_user']) &&
            isset($_POST['apply_date']) &&
            isset($_POST['motivation'])) {
            $id_offer = $_POST['id_offer'];
            $id_user = $_POST['id_user'];
            $date = $_POST['apply_date'];
            $motivation = $_POST['motivation'];

            if ($this->model->getApplyByOfferAndUser($id_offer, $id_user)) {
                $_SESSION['error_message'] = "Vous avez déjà postulé à cette offre.";
                header('Location: index.php/?uri=offer/show&offer_id=' . $id_offer);
                exit();
            }

            if(!isset($_FILES['cv']) || $_FILES['cv']['error'] != UPLOAD_ERR_OK) {
                $_SESSION['error_message'] = "Aucun fichier n'a été téléversé ou une erreur est survenue.";
                header('Location: index.php/?uri=offer/show&offer_id='.$id_offer);
                exit();
            }
            else{
                $fileTmpPath = $_FILES['cv']['tmp_name'];
                $fileType = mime_content_type($fileTmpPath);
                if ($fileType!=='application/pdf') {
                    $_SESSION['error_message'] = "le fichier n'est pas un pdf ";
                    header('Location: index.php/?uri=offer/show&offer_id='.$id_offer);
                    exit();
                }

                if($_FILES['cv']['size']>2000000){
                    $_SESSION['error_message'] = "Fichier trop volumineux";
                    header('Location: index.php/?uri=offer/show&offer_id='.$id_offer);
                    exit();
                }
                $fileName = "CV_".$id_offer."_".$id_user.".pdf";
                $destinationdir= 'CV/';

                if(move_uploaded_file($fileTmpPath, $destinationdir.$fileName)){
                    $_SESSION['success_message'] = "Votre Candidature à bien été pris en compte";
                    $this->model->addApply($id_offer, $id_user, $date, $motivation);
                }

            }
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