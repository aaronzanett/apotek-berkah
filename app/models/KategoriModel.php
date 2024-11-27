<?php
class KategoriModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    public function getAllKategori(){
        $this->db->query("SELECT * FROM kategori");
        return $this->db->allResult();
    }
    
    public function addKategori($data){
        $query = "INSERT INTO kategori (id, name) VALUES ('', :name)";
        $this->db->query($query);
        $this->db->bind('name', $data['name']);

        $this->db->execute();
        
        return $this->db->rowCount();
    }
    
    public function editKategori($data){
        $query = "UPDATE kategori SET name = :name WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $data['id']);
        $this->db->bind('name', $data['name']);
        
        $this->db->execute();
        
        return $this->db->rowCount();
    }
    
    public function isKategoriUsedInProduk($kategori_id){
        $query = "SELECT COUNT(*) as jumlahDipakai FROM produk WHERE kategori_id = :kategori_id";
        $this->db->query($query);
        $this->db->bind('kategori_id', $kategori_id);
        $this->db->execute();
        $result = $this->db->singleResult();

        return $result['jumlahDipakai'] > 0;
    }
    public function deleteKategori($id){
        if($this->isKategoriUsedInProduk($id)){
            return false;
        }

        $query = "DELETE FROM kategori WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function deleteManyKategori($ids){
        $failedDeletions = [];

        foreach ($ids as $id) {
            if ($this->isKategoriUsedInProduk($id)) {
                $namaKategori = $this->getDataEditKategori($id);
                $failedDeletions[] = $namaKategori['name'];
            } else {
                if ($this->deleteKategori($id) === 0) {
                    $namaKategori = $this->getDataEditKategori($id);
                    $failedDeletions[] = $namaKategori['name'];
                }
            }
        }

        return $failedDeletions;
    }
    
    public function getDataEditKategori($id){
        $this->db->query("SELECT * FROM kategori WHERE id=:id");
        $this->db->bind('id', $id);
        return $this->db->singleResult();
    }

    public function getKategoriConfig($name) {
        $this->db->query("SELECT * FROM kategori WHERE name=:name");
        $this->db->bind('name', $name);
        return $this->db->singleResult();
    }

    public function searchKategori($keyword){
        $query = "SELECT * FROM kategori WHERE name LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");

        $this->db->execute();

        return $this->db->allResult();
    }
}