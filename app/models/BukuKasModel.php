<?php
class BukuKasModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    public function getAllBukuKasHeadoffice(){
        $this->db->query("SELECT * FROM cashbook_headoffice ORDER BY datetime DESC");
        $bukuKas = $this->db->allResult();
        foreach ($bukuKas as &$b) {
            $b['datetime'] = date('d-m-Y H:i:s', strtotime($b['datetime']));
        }
        return $bukuKas;
    }

    public function getSaldoHeadoffice(){
        $this->db->query("SELECT 
            SUM(CASE 
                    WHEN status = 'pengeluaran' THEN -total 
                    WHEN status = 'pemasukan' THEN total 
                    ELSE 0 
                END) AS total_nominal
        FROM cashbook_headoffice;");
        return $this->db->singleResult();
    }
    
    public function getAllBukuKasOutletById($id){
        $this->db->query("SELECT * FROM cashbook_outlet WHERE outlet_id = :id ORDER BY datetime DESC");
        $this->db->bind('id', $id);
        $bukuKas = $this->db->allResult();
        foreach ($bukuKas as &$b) {
            $b['datetime'] = date('d-m-Y H:i:s', strtotime($b['datetime']));
        }
        return $bukuKas;
    }

    public function getSaldoOutletById($id){
        $this->db->query("SELECT 
            SUM(CASE 
                    WHEN status = 'pengeluaran' THEN -total 
                    WHEN status = 'pemasukan' THEN total 
                    ELSE 0 
                END) AS total_nominal
        FROM cashbook_outlet WHERE outlet_id = :id ");
        $this->db->bind('id', $id);
        return $this->db->singleResult();
    }


    // unactivated
    // public function addBukuKasHeadoffice($data){
    //     $query = "INSERT INTO cashbook_headoffice (id, cTransaction, status, faktur, activity, user, datetime, total) VALUES ('', \"common\", :status, :faktur, :activity, :user, :datetime, :nominal)";
    //     $this->db->query($query);
    //     $this->db->bind('status', $data['status']);
    //     $this->db->bind('faktur', $data['faktur']);
    //     $this->db->bind('activity', $data['aktivitas']);
    //     $this->db->bind('user', $data['user']);
    //     $this->db->bind('datetime', $data['datetime']);
    //     $this->db->bind('nominal', $data['nominal']);

    //     $this->db->execute();
        
    //     return $this->db->rowCount();
    // }
    
    // public function editBukuKasHeadoffice($data){
    //     $query = "UPDATE cashbook_headoffice SET status = :status, faktur = :faktur, activity = :activity, user = :user, datetime = :datetime, total = :nominal WHERE id=:id";
    //     $this->db->query($query);
    //     $this->db->bind('id', $data['id']);
    //     $this->db->bind('status', $data['status']);
    //     $this->db->bind('faktur', $data['faktur']);
    //     $this->db->bind('activity', $data['aktivitas']);
    //     $this->db->bind('user', $data['user']);
    //     $this->db->bind('datetime', $data['datetime']);
    //     $this->db->bind('nominal', $data['nominal']);
        
    //     $this->db->execute();
        
    //     return $this->db->rowCount();
    // }
    
    // public function deleteBukuKasHeadoffice($id){
    //     $query = "DELETE FROM cashbook_headoffice WHERE id=:id";
    //     $this->db->query($query);
    //     $this->db->bind('id', $id);
    //     $this->db->execute();
        
    //     return $this->db->rowCount();
    // }
    
    // public function getDataEditBukuKasHeadoffice($id){
    //     $this->db->query("SELECT * FROM cashbook_headoffice WHERE id=:id");
    //     $this->db->bind('id', $id);
    //     return $this->db->singleResult();
    // }

    // public function searchBukuKasHeadoffice($keyword){
    //     $query = "SELECT * FROM cashbook_headoffice WHERE status LIKE :keyword OR faktur LIKE :keyword OR activity LIKE :keyword OR user LIKE :keyword";

    //     $this->db->query($query);
    //     $this->db->bind('keyword', "%$keyword%");

    //     $this->db->execute();

    //     return $this->db->allResult();
    // }
}