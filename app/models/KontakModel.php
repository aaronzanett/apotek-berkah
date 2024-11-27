<?php
class KontakModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    // kontak karyawan
    public function getAllkontakKaryawan(){
        $this->db->query("SELECT * FROM kontak_karyawan");
        return $this->db->allResult();
    }

    public function addKontakKaryawan($data){
        $query = "INSERT INTO kontak_karyawan (id, fullname, phonenumber, email, address, rekening, note) VALUES ('', :fullname, :phonenumber, :email, :address, :rekening, :note)";
        $this->db->query($query);
        $this->db->bind('fullname', $data['fullname']);
        $this->db->bind('phonenumber', $data['phonenumber']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('address', $data['address']);
        $this->db->bind('rekening', $data['rekening']);
        if(empty($data['note'])) {
            $data['note'] = '-';
        }
        $this->db->bind('note', $data['note']);

        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function editKontakKaryawan($data){
        $query = "UPDATE kontak_karyawan SET fullname = :fullname, phonenumber = :phonenumber, email = :email, address = :address, rekening = :rekening, note = :note WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $data['id']);
        $this->db->bind('fullname', $data['fullname']);
        $this->db->bind('phonenumber', $data['phonenumber']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('address', $data['address']);
        $this->db->bind('rekening', $data['rekening']);
        if(empty($data['note'])) {
            $data['note'] = '-';
        }
        $this->db->bind('note', $data['note']);

        
        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function deleteKontakKaryawan($id){
        $query = "DELETE FROM kontak_karyawan WHERE id=:id";
        
        $this->db->query($query);
        $this->db->bind('id', $id);
        
        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function deleteManyKontakKaryawan($ids){
        $idString = implode(',', array_map('intval', $ids));
        $query = "DELETE FROM kontak_karyawan WHERE id IN ($idString)";

        $this->db->query($query);

        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function getDataEditKontakKaryawan($id){
        $this->db->query("SELECT * FROM kontak_karyawan WHERE id=:id");
        $this->db->bind('id', $id);
        return $this->db->singleResult();
    }

    public function getKontakKaryawanConfig($phonenumber) {
        $this->db->query("SELECT * FROM kontak_karyawan WHERE phonenumber=:phonenumber");
        $this->db->bind('phonenumber', $phonenumber);
        return $this->db->singleResult();
    }

    public function searchKontakKaryawan(){
        $keyword = $_POST['keyword'];

        $query = "SELECT * FROM kontak_karyawan WHERE fullname LIKE :keyword OR phonenumber LIKE :keyword OR email LIKE :keyword OR address LIKE :keyword OR rekening LIKE :keyword OR note LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");

        $this->db->execute();

        return $this->db->allResult();
    }

    // kontak pelanggan
    public function getAllkontakPelanggan(){
        $this->db->query("SELECT * FROM kontak_pelanggan");
        return $this->db->allResult();
    }

    public function addKontakPelanggan($data){
        $query = "INSERT INTO kontak_pelanggan (id, fullname, phonenumber, email, address, note) VALUES ('', :fullname, :phonenumber, :email, :address, :note)";
        $this->db->query($query);
        $this->db->bind('fullname', $data['fullname']);
        $this->db->bind('phonenumber', $data['phonenumber']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('address', $data['address']);
        if(empty($data['note'])) {
            $data['note'] = '-';
        }
        $this->db->bind('note', $data['note']);

        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function editKontakPelanggan($data){
        $query = "UPDATE kontak_pelanggan SET fullname = :fullname, phonenumber = :phonenumber, email = :email, address = :address, note = :note WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $data['id']);
        $this->db->bind('fullname', $data['fullname']);
        $this->db->bind('phonenumber', $data['phonenumber']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('address', $data['address']);
        if(empty($data['note'])) {
            $data['note'] = '-';
        }
        $this->db->bind('note', $data['note']);

        
        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function deleteKontakPelanggan($id){
        $query = "DELETE FROM kontak_pelanggan WHERE id=:id";
        
        $this->db->query($query);
        $this->db->bind('id', $id);
        
        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function deleteManyKontakPelanggan($ids){
        $idString = implode(',', array_map('intval', $ids));
        $query = "DELETE FROM kontak_pelanggan WHERE id IN ($idString)";

        $this->db->query($query);

        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function getDataEditKontakPelanggan($id){
        $this->db->query("SELECT * FROM kontak_pelanggan WHERE id=:id");
        $this->db->bind('id', $id);
        return $this->db->singleResult();
    }

    public function getKontakpelangganConfig($phonenumber) {
        $this->db->query("SELECT * FROM kontak_pelanggan WHERE phonenumber=:phonenumber");
        $this->db->bind('phonenumber', $phonenumber);
        return $this->db->singleResult();
    }

    public function searchKontakPelanggan(){
        $keyword = $_POST['keyword'];

        $query = "SELECT * FROM kontak_pelanggan WHERE fullname LIKE :keyword OR phonenumber LIKE :keyword OR email LIKE :keyword OR address LIKE :keyword OR note LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");

        $this->db->execute();

        return $this->db->allResult();
    }

    // kontak supplier
    public function getAllkontakSupplier(){
        $this->db->query("SELECT * FROM kontak_supplier");
        return $this->db->allResult();
    }

    public function addKontakSupplier($data){
        $query = "INSERT INTO kontak_supplier (id, fullname, phonenumber, email, address, rekening, note) VALUES ('', :fullname, :phonenumber, :email, :address, :rekening, :note)";
        $this->db->query($query);
        $this->db->bind('fullname', $data['fullname']);
        $this->db->bind('phonenumber', $data['phonenumber']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('address', $data['address']);
        $this->db->bind('rekening', $data['rekening']);
        if(empty($data['note'])) {
            $data['note'] = '-';
        }
        $this->db->bind('note', $data['note']);

        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function editKontakSupplier($data){
        $query = "UPDATE kontak_supplier SET fullname = :fullname, phonenumber = :phonenumber, email = :email, address = :address, rekening = :rekening, note = :note WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $data['id']);
        $this->db->bind('fullname', $data['fullname']);
        $this->db->bind('phonenumber', $data['phonenumber']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('address', $data['address']);
        $this->db->bind('rekening', $data['rekening']);
        if(empty($data['note'])) {
            $data['note'] = '-';
        }
        $this->db->bind('note', $data['note']);

        
        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function deleteKontakSupplier($id){
        $query = "DELETE FROM kontak_supplier WHERE id=:id";
        
        $this->db->query($query);
        $this->db->bind('id', $id);
        
        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function deleteManyKontakSupplier($ids){
        $idString = implode(',', array_map('intval', $ids));
        $query = "DELETE FROM kontak_supplier WHERE id IN ($idString)";

        $this->db->query($query);

        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function getDataEditKontakSupplier($id){
        $this->db->query("SELECT * FROM kontak_supplier WHERE id=:id");
        $this->db->bind('id', $id);
        return $this->db->singleResult();
    }

    public function getKontaksupplierConfig($phonenumber) {
        $this->db->query("SELECT * FROM kontak_supplier WHERE phonenumber=:phonenumber");
        $this->db->bind('phonenumber', $phonenumber);
        return $this->db->singleResult();
    }

    public function searchKontakSupplier(){
        $keyword = $_POST['keyword'];

        $query = "SELECT * FROM kontak_supplier WHERE fullname LIKE :keyword OR phonenumber LIKE :keyword OR email LIKE :keyword OR address LIKE :keyword OR rekening LIKE :keyword OR note LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");

        $this->db->execute();

        return $this->db->allResult();
    }
}
