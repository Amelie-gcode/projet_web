<?php

namespace App\model;

class ApplyModel extends Model
{
    public function __construct($connection = null) {
        if(is_null($connection)) {
            $this->connection = new FileDatabase('apply', ['id_offer','id_user','date', 'motivation']);
        } else {
            $this->connection = $connection;
        }
    }
    public function getAllApply() {
        return $this->connection->getAllRecords();
    }
    public function getApply($id) {
        return $this->connection->getRecord($id);
    }
    public function addApply($id_offer, $id_user, $date, $motivation) {
        $apply = [
            'id_offer' => $id_offer,
            'id_user' => $id_user,
            'date' => $date,
            'motivation' => $motivation
        ];
        return $this->connection->insertRecord($apply);
    }
    public function deleteApply($id) {
        return $this->connection->deleteRecord($id);
    }
    public function updateApply($id, $id_offer, $id_user, $date, $motivation) {
        $apply = [
            'id_offer' => $id_offer,
            'id_user' => $id_user,
            'date' => $date,
            'motivation' => $motivation
        ];
        return $this->connection->updateRecord($id, $apply);
    }
    public function getApplyByOffer($id_offer) {
        $data = [];
        $data = $this->getAllApply();
        foreach($data as $apply) {
            if($apply['id_offer'] != $id_offer) {
                unset($apply);
            }
        }
        return $data;
    }
    public function getApplyByUser($id_user) {
        $data = [];
        $data = $this->getAllApply();
        foreach($data as $apply) {
            if($apply['id_user'] != $id_user) {
                unset($apply);
            }
        }
    }


}