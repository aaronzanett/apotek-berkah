<?php
class SatuanModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    public function getAllSatuan(){
        $this->db->query("SELECT * FROM satuan");
        return $this->db->allResult();
    }
    
    public function addSatuan($data){
        $query = "INSERT INTO satuan (id, name) VALUES ('', :name)";
        $this->db->query($query);
        $this->db->bind('name', $data['name']);

        $this->db->execute();
        
        return $this->db->rowCount();
    }
    
    public function editSatuan($data){
        $query = "UPDATE satuan SET name = :name WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $data['id']);
        $this->db->bind('name', $data['name']);
        
        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function isSatuanUsedInProduk($satuan_id){
        $query = "SELECT COUNT(*) as jumlahDipakai FROM produk WHERE main_unit = :satuan_id";
        $this->db->query($query);
        $this->db->bind('satuan_id', $satuan_id);
        $this->db->execute();
        $result = $this->db->singleResult();

        return $result['jumlahDipakai'] > 0;
    }
    public function deleteSatuan($id){
        if($this->isSatuanUsedInProduk($id)){
            return false;
        }
        $query1 = "DELETE FROM satuan_lainnya WHERE satuan_id = :id";
        $this->db->query($query1);
        $this->db->bind('id', $id);
        $this->db->execute();

        $query2 = "DELETE FROM satuan WHERE id=:id";
        $this->db->query($query2);
        $this->db->bind('id', $id);
        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function deleteManySatuan($ids){
        $failedDeletions = [];

        foreach ($ids as $id) {
            if ($this->isSatuanUsedInProduk($id)) {
                $namaSatuan = $this->getDataEditSatuan($id);
                $failedDeletions[] = $namaSatuan['name'];
            } else {
                if ($this->deleteSatuan($id) === 0) {
                    $namaSatuan = $this->getDataEditSatuan($id);
                    $failedDeletions[] = $namaSatuan['name'];
                }
            }
        }

        return $failedDeletions;
    }
    
    public function getDataEditSatuan($id){
        $this->db->query("SELECT * FROM satuan WHERE id=:id");
        $this->db->bind('id', $id);
        return $this->db->singleResult();
    }

    public function getSatuanConfig($name) {
        $this->db->query("SELECT * FROM satuan WHERE name=:name");
        $this->db->bind('name', $name);
        return $this->db->singleResult();
    }

    public function searchSatuan($keyword){
        $query = "SELECT * FROM satuan WHERE name LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");

        $this->db->execute();

        return $this->db->allResult();
    }
}