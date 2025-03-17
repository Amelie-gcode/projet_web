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
    public function showAllOffers()
    {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        else {
            $id = 1;
        }
        $offers = $this->model->getAllOffers();
        $offerI = $this->model->getOffer($id);
        echo $this->templateEngine->render('offers.twig.html', ['offers' => $offers, 'offerI' => $offerI]);
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
            isset($_GET['offerTitle']) &&
            isset($_GET['offerDescription']) &&
            isset($_GET['offerDate']))
            {
                $idcompany = $_GET['id_company'];
                $title = $_GET['offerTitle'];
                $description = $_GET['offerDescription'];
                $date = $_GET['offerDate'];
                $this->model->addOffer($idcompany, $title, $description, $date);
            }

    }
    public function updateOffer() {
        if (isset($_GET['id']) &&
            isset($_GET['id_company']) &&
            isset($_GET['offerTitle']) &&
            isset($_GET['offerDescription']) &&
            isset($_GET['offerDate']))
            {
                $id = $_GET['id'];
                $idcompany = $_GET['id_company'];
                $title = $_GET['offerTitle'];
                $description = $_GET['offerDescription'];
                $date = $_GET['offerDate'];
                $this->model->updateOffer($id, $idcompany, $title, $description, $date);
                header('Location: /offer');
            }
    }

    public function showForm() {
        echo $this->templateEngine->render('addOffer.twig.html');
    }

}