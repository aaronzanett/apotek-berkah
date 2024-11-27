<?php

use function PHPSTORM_META\type;

class PenggunaModel {
    private $db;
    private $encryptkey, $iv;
    
    public function __construct() {
        $this->db = new Database;
        $this->encryptkey = openssl_random_pseudo_bytes(32);
        $this->iv = openssl_random_pseudo_bytes(16);
    }

    // encryption decryption
    public function encryptData($data, $key, $iv) {
        return openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    }
    public function decryptData($data, $key, $iv) {
        return openssl_decrypt($data, 'aes-256-cbc', $key, 0, $iv);
    }
    
    public function getAllPengguna(){
        $this->db->query("SELECT * FROM pengguna ORDER BY CASE WHEN outlet_id = '0' THEN 0 ELSE 1 END,outlet_id");
        return $this->db->allResult();
    }

    public function addPengguna($data){
        $encryptkey = $this->encryptkey;
        $iv = $this->iv;

        if(!isset($data['outlet_id'])){
            $data['outlet_id'] = 0;
        }

        $encryptedPassword = $this->encryptData($data['password'], $encryptkey, $iv);

        $query = "INSERT INTO pengguna (id, fullname, username, encrypted_password, outlet_id, role_id, encryptkey, iv) VALUES ('', :fullname, :username, :encrypted_password, :outlet_id, :role_id, :encryptkey, :iv)";
        $this->db->query($query);
        $this->db->bind('fullname', $data['fullname']);
        $this->db->bind('username', $data['username']);
        $this->db->bind('encrypted_password', $encryptedPassword);
        $this->db->bind('outlet_id', $data['outlet_id']);
        $this->db->bind('role_id', $data['role_id']);
        $this->db->bind('encryptkey', $encryptkey);
        $this->db->bind('iv', $iv);

        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function editPengguna($data){
        // check old password
        $passwordSameCheck = $this->checkOldPassword($data['id'], $data['password']);

        if($passwordSameCheck['status'] == true) {
            $encryptkey = $passwordSameCheck['encryptkey'];
            $iv = $passwordSameCheck['iv'];
            $encryptedPassword = $this->encryptData($data['password'], $encryptkey, $iv);
        } else {
            $encryptkey = $this->encryptkey;
            $iv = $this->iv;
            $encryptedPassword = $this->encryptData($data['password'], $encryptkey, $iv);
        }

        $query = "UPDATE pengguna SET fullname = :fullname, username = :username, encrypted_password = :encrypted_password, outlet_id = :outlet_id, role_id = :role_id, encryptkey = :encryptkey, iv=:iv WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $data['id']);
        $this->db->bind('fullname', $data['fullname']);
        $this->db->bind('username', $data['username']);
        $this->db->bind('encrypted_password', $encryptedPassword);
        $this->db->bind('outlet_id', $data['outlet_id']);
        $this->db->bind('role_id', $data['role_id']);
        $this->db->bind('encryptkey', $encryptkey);
        $this->db->bind('iv', $iv);

        
        $this->db->execute();
        
        return $this->db->rowCount();
    }
    public function checkOldPassword($id, $newPassword) {
        $query = 'SELECT * FROM pengguna WHERE id = :id';
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();
        $result = $this->db->singleResult();
        
        $newEncryptedPassword = $this->encryptData($newPassword, $result['encryptkey'], $result['iv']);
        $oldEncryptedPassword = $result['encrypted_password'];
        
        if($newEncryptedPassword == $oldEncryptedPassword){
            return [
                'status' => true,
                'encryptkey' => $result['encryptkey'],
                'iv' => $result['iv']
            ];
        } else {
            return [
                'status' => false
            ];
        }
    }

    public function deletePengguna($id){
        $query = "DELETE FROM pengguna WHERE id=:id";
        
        $this->db->query($query);
        $this->db->bind('id', $id);
        
        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function deleteManyPengguna($ids){
        $idString = implode(',', array_map('intval', $ids));
        $query = "DELETE FROM pengguna WHERE id IN ($idString)";

        $this->db->query($query);

        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function getDataEditPengguna($idPengguna, $idKaryawan, $idOutlet, $idRole){
        $query = "SELECT k.fullname AS nama_karyawan,
        p.id, p.fullname, p.username, p.encrypted_password, p.outlet_id, role_id, o.name AS nama_outlet, r.name AS nama_role
        FROM pengguna p JOIN kontak_karyawan k ON k.id = :idKaryawan JOIN outlet o ON o.id = :idOutlet JOIN roles r ON r.id = :idRole WHERE p.id = :idPengguna";
        $this->db->query($query);
        $this->db->bind('idPengguna', $idPengguna);
        $this->db->bind('idKaryawan', $idKaryawan);
        $this->db->bind('idOutlet', $idOutlet);
        $this->db->bind('idRole', $idRole);
        $this->db->execute();
        
        $result = $this->db->singleResult();
        // return $result;
        
        $querydp = "SELECT encrypted_password, encryptkey, iv FROM pengguna WHERE id=:idPengguna";
        $this->db->query($querydp);
        $this->db->bind('idPengguna', $idPengguna);
        $this->db->execute();
        $resultdp = $this->db->singleResult();

        $decryptedPassword = $this->decryptData($resultdp['encrypted_password'], $resultdp['encryptkey'], $resultdp['iv']);
        $result['decryptedPassword'] = $decryptedPassword;

        return $result;
    }

    public function getPenggunaConfig($username) {
        $this->db->query("SELECT username FROM pengguna WHERE username=:username");
        $this->db->bind('username', $username);
        return $this->db->singleResult();
    }

    public function searchPengguna($keyword){
        $keyword = $_POST['keyword'];

        $query = "SELECT pengguna.id, pengguna.fullname, pengguna.outlet_id, pengguna.role_id, pengguna.username
        FROM pengguna
        LEFT JOIN kontak_karyawan ON pengguna.fullname = kontak_karyawan.id
        LEFT JOIN outlet ON pengguna.outlet_id = outlet.id
        LEFT JOIN roles ON pengguna.role_id = roles.id
        WHERE 
            pengguna.username LIKE :keyword OR
            kontak_karyawan.fullname LIKE :keyword OR
            outlet.name LIKE :keyword OR
            roles.name LIKE :keyword;";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");

        $this->db->execute();

        return $this->db->allResult();
    }

    public function getPenggunaByUsername($username) {
        $query = "SELECT 
        pengguna.*, 
        kontak_karyawan.fullname AS karyawan_fullname, 
        outlet.name AS outlet_name, 
        roles.name AS role_name ,
        roles.outlet_type AS outlet_type
        FROM pengguna 
        JOIN kontak_karyawan ON pengguna.fullname = kontak_karyawan.id 
        JOIN outlet ON pengguna.outlet_id = outlet.id 
        JOIN roles ON pengguna.role_id = roles.id 
        WHERE pengguna.username = :username";
        $this->db->query($query);
        $this->db->bind('username', $username);
        return $this->db->allResult();
    }
}
