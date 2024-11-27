<?php
class OutletModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    public function getAllOutlet(){
        $this->db->query("SELECT * FROM outlet WHERE type='Outlet' ORDER BY serial_number ASC");
        return $this->db->allResult();
    }
    
    public function addOutlet($data){
        $query = "INSERT INTO outlet (id, name, address, type, serial_number, status) VALUES ('', :name, :address, :type, :serial_number, :status)";
        $this->db->query($query);
        $this->db->bind('name', $data['name']);
        $this->db->bind('address', $data['address']);
        $this->db->bind('type', $data['type']);
        $this->db->bind('serial_number', $data['serial_number']);
        $this->db->bind('status', $data['status']);

        $this->db->execute();
        
        return $this->db->rowCount();
    }
    
    public function editOutlet($data){
        $query = "UPDATE outlet SET name = :name, address = :address, type = :type, serial_number = :serial_number, status = :status WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $data['id']);
        $this->db->bind('name', $data['name']);
        $this->db->bind('address', $data['address']);
        $this->db->bind('type', $data['type']);
        $this->db->bind('serial_number', $data['serial_number']);
        $this->db->bind('status', $data['status']);
        
        $this->db->execute();
        
        return $this->db->rowCount();
    }
    
    public function deleteOutlet($id){
        $query = "DELETE FROM outlet WHERE id=:id";
        
        $this->db->query($query);
        $this->db->bind('id', $id);
        
        $this->db->execute();
        
        return $this->db->rowCount();
    }
    
    public function getDataEditOutlet($id){
        $this->db->query("SELECT * FROM outlet WHERE id=:id");
        $this->db->bind('id', $id);
        return $this->db->singleResult();
    }

    public function getOutletConfig($serial_number) {
        $this->db->query("SELECT * FROM outlet WHERE serial_number=:serial_number");
        $this->db->bind('serial_number', $serial_number);
        return $this->db->singleResult();
    }

    public function searchOutlet($keyword){
        $query = "SELECT * FROM outlet WHERE (name LIKE :keyword OR address LIKE :keyword OR type LIKE :keyword OR serial_number LIKE :keyword OR status LIKE :keyword) AND type != 'headoffice'";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");

        $this->db->execute();

        return $this->db->allResult();
    }
}
?>