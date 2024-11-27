<?php
class RakModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    public function getAllRak(){
        $this->db->query("SELECT * FROM rak");
        return $this->db->allResult();
    }
    
    public function addRak($data){
        $query = "INSERT INTO rak (id, name) VALUES ('', :name)";
        $this->db->query($query);
        $this->db->bind('name', $data['name']);

        $this->db->execute();
        
        return $this->db->rowCount();
    }
    
    public function editRak($data){
        $query = "UPDATE rak SET name = :name WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $data['id']);
        $this->db->bind('name', $data['name']);
        
        $this->db->execute();
        
        return $this->db->rowCount();
    }
    
    public function isRakUsedInProduk($rak_id){
        $query = "SELECT COUNT(*) as jumlahDipakai FROM produk WHERE rak_id = :rak_id";
        $this->db->query($query);
        $this->db->bind('rak_id', $rak_id);
        $this->db->execute();
        $result = $this->db->singleResult();

        return $result['jumlahDipakai'] > 0;
    }
    public function deleteRak($id){
        if($this->isRakUsedInProduk($id)){
            return false;
        }
        $query = "DELETE FROM rak WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function deleteManyRak($ids){
        $failedDeletions = [];

        foreach ($ids as $id) {
            if ($this->isRakUsedInProduk($id)) {
                $namaRak = $this->getDataEditRak($id);
                $failedDeletions[] = $namaRak['name'];
            } else {
                if ($this->deleteRak($id) === 0) {
                    $namaRak = $this->getDataEditRak($id);
                    $failedDeletions[] = $namaRak['name'];
                }
            }
        }

        return $failedDeletions;
    }
    
    public function getDataEditRak($id){
        $this->db->query("SELECT * FROM rak WHERE id=:id");
        $this->db->bind('id', $id);
        return $this->db->singleResult();
    }

    public function getRakConfig($name) {
        $this->db->query("SELECT * FROM rak WHERE name=:name");
        $this->db->bind('name', $name);
        return $this->db->singleResult();
    }

    public function searchRak($keyword){
        $query = "SELECT * FROM rak WHERE name LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");

        $this->db->execute();

        return $this->db->allResult();
    }
}