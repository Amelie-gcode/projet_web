<?php
namespace App\model;
class CompanyModel extends Model
{

    public function __construct($connection = null) {
        if(is_null($connection)) {
            $this->connection = new FileDatabase('company', ['name', 'email', 'phone', 'description']);
        } else {
            $this->connection = $connection;
        }
    }

    public function getAllCompany() {
        return $this->connection->getAllRecords();
    }

    public function getCompany($id) {
        return $this->connection->getRecord($id);
    }


    public function getCompanyByName($name) {
        $data = [];
        $data = $this->getAllCompany();
        foreach($data as $company) {
            if($company['name'] != $name) {
                unset($company);
            }
        }
        return $data;
    }

    public function addCompany($name, $email, $phone, $description) {

        $company = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'description' => $description
        ];

        return $this->connection->insertRecord($company);
    }

    public function updateCompany($id, $name, $email, $phone, $description)
    {
        $company = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'description' => $description
        ];

        return $this->connection->updateRecord($id, $company);

    }
    public function deleteCompany($id) {
        return $this->connection->deleteRecord($id);
    }

}
