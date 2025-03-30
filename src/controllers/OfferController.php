<?php

namespace App\controllers;

use App\model\ApplyModel;
use App\model\EvaluationsModel;
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
        $skillsModel = new SkillsModel();
        $companyModel = new CompanyModel();
        $evaluationsModel = new EvaluationsModel();
        $offers = $this->model->getAllOffers();

        // Récupérer les compétences pour chaque offre
        foreach($offers as &$offer) {
            // Ajouter le nom de l'entreprise
            $company = $companyModel->getCompany($offer['company_id']);
            $companyRate = $evaluationsModel->averageScore($offer['company_id']);
            $offer['company_name'] = $company ? $company['company_name'] : 'Entreprise inconnue';
            $offer['company_phone'] = $company ? $company['company_phone'] : 'Téléphone inconnue';
            $offer['company_email'] = $company ? $company['company_email'] : 'Email inconnue';
            $offer['company_rate'] = $companyRate ? $companyRate : 'Aucune évaluation';

            // Reste du code inchangé
            if(!empty($offer['offer_start_date']) && !empty($offer['offer_end_date'])) {
                $start = new \DateTime($offer['offer_start_date']);
                $end = new \DateTime($offer['offer_end_date']);
                $interval = $start->diff($end);
                $totalDays = $interval->days;
                $offer['duration'] = ceil($totalDays / 7);
            } else {
                $offer['duration'] = "N/A";
            }

            // Récupérer les compétences liées à l'offre
            $skillIds = $skillsModel->getSkillsByOffer($offer['offer_id']);
            $offer['skills'] = [];
            foreach($skillIds as $skillId) {
                $skill = $skillsModel->getSkill($skillId['skill_id']);
                if($skill) {
                    $offer['skills'][] = $skill;
                }
            }
        }

        $offerI = $this->model->getOfferById($id);

        // Même chose pour l'offre sélectionnée
        if($offerI) {
            // Ajouter le nom de l'entreprise
            $company = $companyModel->getCompany($offerI['company_id']);
            $companyRate = $evaluationsModel->averageScore($offerI['company_id']);
            $offerI['company_name'] = $company ? $company['company_name'] : 'Entreprise inconnue';
            $offerI['company_phone'] = $company ? $company['company_phone'] : 'Téléphone inconnue';
            $offerI['company_email'] = $company ? $company['company_email'] : 'Email inconnue';
            $offerI['company_rate'] = $companyRate ? $companyRate : 'Aucune évaluation';

            $skillIds = $skillsModel->getSkillsByOffer($offerI['offer_id']);
            $offerI['skills'] = [];
            foreach($skillIds as $skillId) {
                $skill = $skillsModel->getSkill($skillId['skill_id']);
                if($skill) {
                    $offerI['skills'][] = $skill;
                }
            }
        }

        echo $this->templateEngine->render('offers.twig.html', [
            'offers' => $offers,
            'offerI' => $offerI]);
    }
    public function showOffer()
    {
        if(isset($_GET['offer_id'])) {
            $id = $_GET['offer_id'];
            $offer = $this->model->getOfferById($id);

            // Récupérer les informations de l'entreprise
            $companyModel = new CompanyModel();
            $company = $companyModel->getCompany($offer['company_id']);
            $offer['company_name'] = $company ? $company['company_name'] : 'Entreprise inconnue';

            $offers = $this->model->getAllOffers();
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

            // Récupérer les compétences liées à l'offre
            $skillsForOffer = $skillsModel->getSkillsByOffer($id);
            $selectedSkills = array_column($skillsForOffer, 'skill_id');

            echo $this->templateEngine->render('addOffer.twig.html', [
                'offer' => $offer,
                'companies' => $companies,
                'skills' => $skills,
                'selectedSkills' => $selectedSkills
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
        if (isset($_POST['id_company']) &&
            isset($_POST['offerTitle']) &&
            isset($_POST['offerLongDescription']) &&
            isset($_POST['offerShortDescription']) &&
            isset($_POST['offerProfileDescription']) &&
            isset($_POST['offerSalary'])&&
            isset($_POST['offerType'])&&
            isset($_POST['offerStartDate'])&&
            isset($_POST['offerEndDate'])&&
            isset($_POST['offerLocation']))
        {
            $id_company = $_POST['id_company'];
            $offerTitle = $_POST['offerTitle'];
            $offerLongDescription = $_POST['offerLongDescription'];
            $offerShortDescription = $_POST['offerShortDescription'];
            $offerProfileDescription = $_POST['offerProfileDescription'];
            $offerSalary = $_POST['offerSalary'];
            $offerType = $_POST['offerType'];
            $offerStartDate = $_POST['offerStartDate'];
            $offerEndDate = $_POST['offerEndDate'];
            $offerLocation = $_POST['offerLocation'];

            // Ajouter l'offre et récupérer son ID
            $offer_id = $this->model->addOffer(
                $id_company,
                $offerTitle,
                $offerLongDescription,
                $offerShortDescription,
                $offerProfileDescription,
                $offerSalary,
                $offerType,
                $offerStartDate,
                $offerEndDate,
                $offerLocation);

            // Traiter les compétences sélectionnées
            if (isset($_POST['domaines']) && is_array($_POST['domaines'])) {
                $skillsModel = new SkillsModel();
                foreach ($_POST['domaines'] as $skill_id) {
                    if (!empty($skill_id)) {
                        $skillsModel->addSkillToOffer($offer_id, $skill_id);
                    }
                }
            }

            header('Location: index.php?uri=offer/admin');
        }
    }

    public function updateOffer() {
        if (isset($_POST['id']) &&
            isset($_POST['id_company']) &&
            isset($_POST['offerTitle']) &&
            isset($_POST['offerLongDescription']) &&
            isset($_POST['offerShortDescription']) &&
            isset($_POST['offerProfileDescription']) &&
            isset($_POST['offerSalary'])&&
            isset($_POST['offerType'])&&
            isset($_POST['offerStartDate'])&&
            isset($_POST['offerEndDate'])&&
            isset($_POST['offerLocation']))
        {
            $offer_id = $_POST['id'];
            $id_company = $_POST['id_company'];
            $offerTitle = $_POST['offerTitle'];
            $offerLongDescription = $_POST['offerLongDescription'];
            $offerShortDescription = $_POST['offerShortDescription'];
            $offerProfileDescription = $_POST['offerProfileDescription'];
            $offerSalary = $_POST['offerSalary'];
            $offerType = $_POST['offerType'];
            $offerStartDate = $_POST['offerStartDate'];
            $offerEndDate = $_POST['offerEndDate'];
            $offerLocation = $_POST['offerLocation'];

            $this->model->updateOffer(
                $offer_id,
                $id_company,
                $offerTitle,
                $offerLongDescription,
                $offerShortDescription,
                $offerProfileDescription,
                $offerSalary,
                $offerType,
                $offerStartDate,
                $offerEndDate,
                $offerLocation);

            // Mettre à jour les compétences
            if (isset($_POST['domaines']) && is_array($_POST['domaines'])) {
                $skillsModel = new SkillsModel();
                // Supprimer les anciennes relations
                $skillsModel->deleteSkillsForOffer($offer_id);
                // Ajouter les nouvelles
                foreach ($_POST['domaines'] as $skill_id) {
                    if (!empty($skill_id)) {
                        $skillsModel->addSkillToOffer($offer_id, $skill_id);
                    }
                }
            }

            header('Location: index.php?uri=offer/admin');
        } else {
            header('Location: index.php?uri=offer/admin');
        }
    }

    public function showAdminOffer(){
        $skillsModel = new SkillsModel();
        $companyModel = new CompanyModel();
        $evaluationsModel = new EvaluationsModel();
        $offers = $this->model->getAllOffers();

        // Ajouter les informations d'entreprise et compétences pour chaque offre
        foreach($offers as &$offer) {
            // Ajouter le nom de l'entreprise
            $company = $companyModel->getCompany($offer['company_id']);
            $offer['company_name'] = $company ? $company['company_name'] : 'Entreprise inconnue';

            // Calculer la durée en semaines
            if(!empty($offer['offer_start_date']) && !empty($offer['offer_end_date'])) {
                $start = new \DateTime($offer['offer_start_date']);
                $end = new \DateTime($offer['offer_end_date']);
                $interval = $start->diff($end);
                $totalDays = $interval->days;
                $offer['duration'] = ceil($totalDays / 7);
            } else {
                $offer['duration'] = "N/A";
            }

            // Récupérer les compétences liées à l'offre
            $skillIds = $skillsModel->getSkillsByOffer($offer['offer_id']);
            $offer['skills'] = [];
            foreach($skillIds as $skillId) {
                $skill = $skillsModel->getSkill($skillId['skill_id']);
                if($skill) {
                    $offer['skills'][] = $skill;
                }
            }
        }

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