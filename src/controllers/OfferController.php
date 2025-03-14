<?php

namespace App\controllers;

use App\model\ApplyModel;
use App\model\OfferModel;

class  OfferController extends Controller
{
    public function __construct($templateEngine) {
        $this->model = new OfferModel();
        $this->templateEngine = $templateEngine;
    }
    public function showAllApply()
    {
        $offers = $this->model->getAllOffers();
        echo $this->templateEngine->render('offers.twig.html', ['offers' => $offers]);
    }
    public function showOffer()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $offer= $this->model->getOffer($id);
            echo $this->templateEngine->render('offerInfo.twig.html', ['offer' => $offer]);
        } else {
            header('Location: /offer');
        }
    }
    public function deleteOffer() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->model->deleteOffer($id);
        }
    }
    public function addOffer() {
        if (isset($_GET['id_company']) &&
            isset($_GET['offer-title']) &&
            isset($_GET['offer-description']) &&
            isset($_GET['offer-date']))
            {
                $idcompany = $_GET['id_company'];
                $title = $_GET['offer-title'];
                $description = $_GET['offer-description'];
                $date = $_GET['offer-date'];
                $this->model->addOffer($idcompany, $title, $description, $date);
                header('Location: /offer');
            }
    }
    public function updateOffer() {
        if (isset($_GET['id']) &&
            isset($_GET['id_company']) &&
            isset($_GET['offer-title']) &&
            isset($_GET['offer-description']) &&
            isset($_GET['offer-date']))
            {
                $id = $_GET['id'];
                $idcompany = $_GET['id_company'];
                $title = $_GET['offer-title'];
                $description = $_GET['offer-description'];
                $date = $_GET['offer-date'];
                $this->model->updateOffer($id, $idcompany, $title, $description, $date);
                header('Location: /offer');
            }
    }

}