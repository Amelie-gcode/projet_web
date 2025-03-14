<?php

namespace App\model;

class OfferModel extends Model
{
    public function __construct($connection = null) {
        if(is_null($connection)) {
            $this->connection = new FileDatabase('offers', ['id_company','offer-title','offer-description', 'offer-date']);
        } else {
            $this->connection = $connection;
        }
    }
    public function getAllOffers() {
        return $this->connection->getAllRecords();
    }
    public function getOffer($id) {
        return $this->connection->getRecord($id);
    }
    public function addOffer($id_company, $offer_title, $offer_description, $offer_date) {
        $offer = [
            'id_company' => $id_company,
            'offer-title' => $offer_title,
            'offer-description' => $offer_description,
            'offer-date' => $offer_date
        ];
        return $this->connection->insertRecord($offer);
    }
    public function deleteOffer($id) {
        return $this->connection->deleteRecord($id);
    }
    public function updateOffer($id, $id_company, $offer_title, $offer_description, $offer_date) {
        $offer = [
            'id_company' => $id_company,
            'offer-title' => $offer_title,
            'offer-description' => $offer_description,
            'offer-date' => $offer_date
        ];
            return $this->connection->updateRecord($id, $offer);
    }
    public function getOfferByCompany($id_company) {
        $data = [];
        $data = $this->getAllOffers();
        foreach($data as $offer) {
            if($offer['id_company'] != $id_company) {
                unset($offer);
            }
        }
        return $data;
    }


}