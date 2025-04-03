<?php

namespace App\controllers;

use App\model\ApplyModel;
use App\model\EvaluationsModel;
use App\model\LikesModel;
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
        $limit = 10;
        $page = $_GET['page'] ?? 1;
        $offset = (int)($page - 1) * $limit;

        $filters = [
            "filter_alternance" => isset($_GET['filter_alternance']),
            "filter_stage" => isset($_GET['filter_stage']),
            "filter_aime" => isset($_GET['filter_aime']),
            "filter_moins_3mois" => isset($_GET['filter_moins_3mois']),
            "filter_plus_3mois" => isset($_GET['filter_plus_3mois'])
        ];


        if(isset($_GET['offer_id'])) {
            $id = $_GET['offer_id'];
        }
        else {
            $id = 17;
        }

        $skillsModel = new SkillsModel();
        $companyModel = new CompanyModel();
        $evaluationsModel = new EvaluationsModel();

        $hasActiveFilters = in_array(true, $filters, true);
        $hasValidDomaines = isset($_GET['domaines']) && is_array($_GET['domaines']) && array_filter($_GET['domaines']);

        if (!empty($_GET['research']) || $hasValidDomaines || $hasActiveFilters) {
            $research = $_GET['research'] ?? '';
            $skills = isset($_GET['domaines']) ? array_filter($_GET['domaines'], function($skill) {
                return !empty(trim($skill));
            }) : [];


            $result = $this->model->getALLOffersByResearch($research, $skills, $filters, $limit, $offset);
        } else {
            $result = $this->model->getAllOffers($limit, $offset);
        }

        $offers = $result['offers'];

        $totalOffers = $result['totalOffers'] ?? count($offers); // Assurer un total correct

        $totalPages = ceil($totalOffers / $limit);


        // Récupérer les compétences pour chaque offre
        foreach($offers as &$offer) {
            // Ajouter le nom de l'entreprise
            $company = $companyModel->getCompany($offer['company_id']);
            $companyRate = $evaluationsModel->averageScore($offer['company_id']);
            $offer['company_name'] = $company ? $company['company_name'] : 'Entreprise inconnue';
            $offer['company_phone'] = $company ? $company['company_phone'] : 'Téléphone inconnue';
            $offer['company_email'] = $company ? $company['company_email'] : 'Email inconnue';
            $offer['company_rate'] = $companyRate ? $companyRate : 'Aucune évaluation';


            $offer['duration'] = $this->model->calculateOfferDuration($offer);

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
        $skillsModel = new SkillsModel();
        $skills = $skillsModel->getSkills();
        $likeModel = new LikesModel(); // Assure-toi que LikeModel est bien inclus
        $isLiked = false;

        if (isset($_SESSION['user_id']) && isset($offerI['offer_id'])) {
            $isLiked = $likeModel->isLiked($_SESSION['user_id'], $offerI['offer_id']);
        }
        $nbLike=$likeModel->nbLikesByOffers($offerI['offer_id']);

        echo $this->templateEngine->render('offers.twig.html', [
            'offers' => $offers,
            'offerI' => $offerI,
            'research' => $research ?? '',
            'session' => $_SESSION,
            'skills' => $skills,
            'isLiked'=> $isLiked,
            'nbLike'=> $nbLike,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }
    public function showOffer()
    {
        if(isset($_GET['offer_id'])) {
            $id = $_GET['offer_id'];
            $offer = $this->model->getOfferById($id);

            if (!$offer) {
                header('Location: index.php?uri=offer/all');
                return;
            }

            // Récupérer les informations de l'entreprise
            $companyModel = new CompanyModel();
            $evaluationsModel = new EvaluationsModel();
            $company = $companyModel->getCompany($offer['company_id']);
            $companyRate = $evaluationsModel->averageScore($offer['company_id']);

            // Enrichir l'offre avec les données de l'entreprise
            $offer['company_name'] = $company ? $company['company_name'] : 'Entreprise inconnue';
            $offer['company_phone'] = $company ? $company['company_phone'] : 'Téléphone inconnu';
            $offer['company_email'] = $company ? $company['company_email'] : 'Email inconnu';
            $offer['company_rate'] = $companyRate ?: 'Aucune évaluation';

            // Récupérer d'autres offres pour les suggestions
            $result = $this->model->getAllOffers();
            $offers = $result['offers'];
            foreach($offers as &$suggestedOffer) {
                $company = $companyModel->getCompany($suggestedOffer['company_id']);
                $suggestedOffer['company_name'] = $company ? $company['company_name'] : 'Entreprise inconnue';
                $suggestedOffer['duration'] = $this->model->calculateOfferDuration($suggestedOffer);
            }
            $likeModel = new LikesModel(); // Assure-toi que LikeModel est bien inclus
            $isLiked = false;

            if (isset($_SESSION['user_id']) && isset($offer['offer_id'])) {
                $isLiked = $likeModel->isLiked($_SESSION['user_id'], $offer['offer_id']);
            }
            $nbLike=$likeModel->nbLikesByOffers($offer['offer_id']);

            echo $this->templateEngine->render('offerInfo.twig.html', [
                'offer' => $offer,
                'offers' => $offers,
                'session' => $_SESSION,
                'isLiked'=> $isLiked,
                'nbLike'=> $nbLike,
            ]);
        } else {
            header('Location: index.php?uri=offer/all');
        }
    }
    public function showForm()
    {
        $companyModel = new CompanyModel();
        $skillsModel = new SkillsModel();

        $result = $companyModel->getAllCompany();
        $companies = $result['companies'];
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
            isset($_POST['offerLocation'])&&
            isset($_POST['offerDate'])
        )
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
            $offerDate = $_POST['offerDate'];

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
                $offerLocation,
                $offerDate
            );

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
            isset($_POST['offerLocation'])&&
            isset($_POST['offerDate']))
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
            $offerDate = $_POST['offerDate'];

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
                $offerLocation,
                $offerDate
            );

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
        $limit = 20;
        $page = $_GET['page'] ?? 1;
        $offset = (int)($page - 1) * $limit;

        $filters = [
            "filter_alternance" => isset($_GET['filter_alternance']),
            "filter_stage" => isset($_GET['filter_stage']),
            "filter_aime" => isset($_GET['filter_aime']),
            "filter_moins_3mois" => isset($_GET['filter_moins_3mois']),
            "filter_plus_3mois" => isset($_GET['filter_plus_3mois'])
        ];

        $skillsModel = new SkillsModel();
        $companyModel = new CompanyModel();
        $evaluationsModel = new EvaluationsModel();

        $hasActiveFilters = in_array(true, $filters, true);
        $hasValidDomaines = isset($_GET['domaines']) && is_array($_GET['domaines']) && array_filter($_GET['domaines']);

        if (!empty($_GET['research']) || $hasValidDomaines || $hasActiveFilters) {
            $research = $_GET['research'] ?? '';
            $skills = isset($_GET['domaines']) ? array_filter($_GET['domaines'], function($skill) {
                return !empty(trim($skill));
            }) : [];


            $result = $this->model->getALLOffersByResearch($research, $skills, $filters, $limit, $offset);
        } else {
            $result = $this->model->getAllOffers($limit, $offset);
        }

        $offers = $result['offers'];

        $totalOffers = $result['totalOffers'] ?? count($offers); // Assurer un total correct

        $totalPages = ceil($totalOffers / $limit);

        // Ajouter les informations d'entreprise et compétences pour chaque offre
        foreach ($offers as &$offer) {
            $company = $companyModel->getCompany($offer['company_id']);
            $companyRate = $evaluationsModel->averageScore($offer['company_id']);
            $offer['company_name'] = $company ? $company['company_name'] : 'Entreprise inconnue';
            $offer['company_phone'] = $company ? $company['company_phone'] : 'Téléphone inconnue';
            $offer['company_email'] = $company ? $company['company_email'] : 'Email inconnue';
            $offer['company_rate'] = $companyRate ? $companyRate : 'Aucune évaluation';

            $offer['duration'] = $this->model->calculateOfferDuration($offer);

            $skillIds = $skillsModel->getSkillsByOffer($offer['offer_id']);
            $offer['skills'] = [];
            foreach ($skillIds as $skillId) {
                $skill = $skillsModel->getSkill($skillId['skill_id']);
                if ($skill) {
                    $offer['skills'][] = $skill;
                }
            }
        }

        $skills = $skillsModel->getSkills();

        echo $this->templateEngine->render('adminOffer.twig.html', [
            'offers' => $offers,
            'research' => $research ?? '',
            'session' => $_SESSION,
            'skills' => $skills,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
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