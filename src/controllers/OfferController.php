<?php

namespace App\controllers;

use App\model\ApplyModel;
use App\model\OfferModel;
use App\model\SkillsModel;
use App\model\CompanyModel;

class  OfferController extends Controller
{
    public function __construct($templateEngine) {
        $this->model = new OfferModel();
        $this->templateEngine = $templateEngine;
    }
    public function showAllOffers()
    {
        if(isset($_GET['offer_id'])) {
            $id = $_GET['offer_id'];
        }
        else {
            $id = 1;
        }
        $offers = $this->model->getAllOffers();
        $offerI = $this->model->getOfferById($id);
        echo $this->templateEngine->render('offers.twig.html', [
            'offers' => $offers,
            'offerI' => $offerI]);
    }
    public function showOffer()
    {
        if(isset($_GET['offer_id'])) {
            $id = $_GET['offer_id'];
            $offer = $this->model->getOfferById($id);
            $offers=$this->model->getAllOffers();
            echo $this->templateEngine->render('offerInfo.twig.html', [
                'offer' => $offer,
                'offers' => $offers]);
        }


    }
    public function showForm()
    {
        $companyModel = new CompanyModel();
        $skillsModel = new SkillsModel();

        $companies = $companyModel->getAllCompany();
        $skills = $skillsModel->getSkills();
        if (isset($_GET['offer_id'])) {
            $id = $_GET['offer_id'];
            $offer = $this->model->getOfferById($id);

            echo $this->templateEngine->render('addOffer.twig.html', [
                'offer' => $offer,
                'companies' => $companies,
                'skills' => $skills
            ]);
        }
        else {
            echo $this->templateEngine->render('addOffer.twig.html', [
                'companies' => $companies,
                'skills' => $skills
            ]);
        }
    }
    public function deleteOffer() {
        if (isset($_GET['offer_id'])) {
            $id = $_GET['offer_id'];
            $this->model->deleteOffer($id);
        }
    }
    public function addOffer() {
        if (isset($_GET['id_company']) &&
            isset($_GET['offerTitle']) &&
            isset($_GET['offerLongDescription']) &&
            isset($_GET['offerShortDescription']) &&
            isset($_GET['offerProfileDescription']) &&
            isset($_GET['offerSalary'])&&
            isset($_GET['offerType'])&&
            isset($_GET['offerStartDate'])&&
            isset($_GET['offerEndDate']))
            {
                $id_company = $_GET['id_company'];
                $offerTitle = $_GET['offerTitle'];
                $offerLongDescription = $_GET['offerLongDescription'];
                $offerShortDescription = $_GET['offerShortDescription'];
                $offerProfileDescription = $_GET['offerProfileDescription'];
                $offerSalary = $_GET['offerSalary'];
                $offerType = $_GET['offerType'];
                $offerStartDate = $_GET['offerStartDate'];
                $offerEndDate = $_GET['offerEndDate'];
                $this->model->addOffer($id_company,
                    $offerTitle,
                    $offerLongDescription,
                    $offerShortDescription,
                    $offerProfileDescription,
                    $offerSalary,
                    $offerType,
                    $offerStartDate,
                    $offerEndDate);
            }

    }
    public function updateOffer() {
        if (isset($_GET['id']) &&
            isset($_GET['id_company']) &&
            isset($_GET['offerTitle']) &&
            isset($_GET['offerLongDescription']) &&
            isset($_GET['offerShortDescription']) &&
            isset($_GET['offerProfileDescription']) &&
            isset($_GET['offerSalary'])&&
            isset($_GET['offerType'])&&
            isset($_GET['offerStartDate'])&&
            isset($_GET['offerEndDate'])

        )
            {
                $offer_id = $_GET['id'];
                $id_company = $_GET['id_company'];
                $offerTitle = $_GET['offerTitle'];
                $offerLongDescription = $_GET['offerLongDescription'];
                $offerShortDescription = $_GET['offerShortDescription'];
                $offerProfileDescription = $_GET['offerProfileDescription'];
                $offerSalary = $_GET['offerSalary'];
                $offerType = $_GET['offerType'];
                $offerStartDate = $_GET['offerStartDate'];
                $offerEndDate = $_GET['offerEndDate'];
                $this->model->updateOffer( $offer_id,$id_company,
                    $offerTitle,
                    $offerLongDescription,
                    $offerShortDescription,
                    $offerProfileDescription,
                    $offerSalary,
                    $offerType,
                    $offerStartDate,
                    $offerEndDate);

            }
    }

    public function showAdminoffer(){
        $offers = $this->model->getAllOffers();
        echo $this->templateEngine->render('adminOffer.twig.html', ['offers' => $offers]);
    }
    public function showOfferByCompany() {
        if (isset($_GET['company_id'])) {
            $id = $_GET['company_id'];
            $offers = $this->model->getOfferByCompany($id);
            return $offers;
        } else {
            return false;
        }
    }

}