<?php
class MetodePembayaranModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    public function getAllMetodePembayaran(){
        $this->db->query("SELECT * FROM metode_pembayaran");
        return $this->db->allResult();
    }
    
    public function addMetodePembayaran($data){
        $query = "INSERT INTO metode_pembayaran (id, name) VALUES ('', :name)";
        $this->db->query($query);
        $this->db->bind('name', $data['name']);

        $this->db->execute();
        
        return $this->db->rowCount();
    }
    
    public function editMetodePembayaran($data){
        $query = "UPDATE metode_pembayaran SET name = :name WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $data['id']);
        $this->db->bind('name', $data['name']);
        
        $this->db->execute();
        
        return $this->db->rowCount();
    }
    
    public function deleteMetodePembayaran($id){
        $query = "DELETE FROM metode_pembayaran WHERE id=:id";
        
        $this->db->query($query);
        $this->db->bind('id', $id);
        
        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function deleteManyMetodePembayaran($ids){
        $idString = implode(',', array_map('intval', $ids));
        $query = "DELETE FROM metode_pembayaran WHERE id IN ($idString)";

        $this->db->query($query);

        $this->db->execute();
        
        return $this->db->rowCount();
    }
    
    public function getDataEditMetodePembayaran($id){
        $this->db->query("SELECT * FROM metode_pembayaran WHERE id=:id");
        $this->db->bind('id', $id);
        return $this->db->singleResult();
    }

    public function getMetodePembayaranConfig($name) {
        $this->db->query("SELECT * FROM metode_pembayaran WHERE name=:name");
        $this->db->bind('name', $name);
        return $this->db->singleResult();
    }

    public function searchMetodePembayaran($keyword){
        $query = "SELECT * FROM metode_pembayaran WHERE name LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");

        $this->db->execute();

        return $this->db->allResult();
    }
}