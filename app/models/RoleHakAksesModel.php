<?php
class RoleHakAksesModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
        
    public function getAllRole(){
        $this->db->query("SELECT * FROM roles ORDER BY CASE WHEN outlet_type = 'Headoffice' THEN 0 ELSE 1 END,outlet_type");
        return $this->db->allResult();
    }
    
    public function addRolePermission($data){
        $query = "INSERT INTO roles (id, name, outlet_type) VALUES ('', :name, :outlet_type)";
        
        $this->db->query($query);
        $this->db->bind('name', $data['name']);
        $this->db->bind('outlet_type', $data['chooseOutlet']);
        
        $this->db->execute();
        
        if ($data['chooseOutlet'] == 'Headoffice') {
            foreach ($data['headofficePermissions'] as $hp) {
                $queryrp = "INSERT INTO role_permission (id, role_id, permission_id) VALUES
                ('', (SELECT id FROM roles WHERE name = :name), (SELECT id FROM headoffice_permissions WHERE name = :permission_name))";
    
                $this->db->query($queryrp);
                $this->db->bind('name', $data['name']);
                $this->db->bind('permission_name', $hp);
                $this->db->execute();
            }
        } else if ($data['chooseOutlet'] == 'Admin') {
            foreach ($data['adminPermissions'] as $ap) {
                $queryrp = "INSERT INTO role_permission (id, role_id, permission_id) VALUES
                ('', (SELECT id FROM roles WHERE name = :name), (SELECT id FROM admin_permissions WHERE name = :permission_name))";
    
                $this->db->query($queryrp);
                $this->db->bind('name', $data['name']);
                $this->db->bind('permission_name', $ap);
                $this->db->execute();
            }
        }
        
        return $this->db->rowCount();
    }

    public function detailRolePermission($id){
        
    }
    
    public function editRolePermission($data){
        $query = "UPDATE roles SET name = :name, outlet_type = :outlet_type WHERE id=:id";

        $this->db->query($query);
        $this->db->bind('id', $data['id']);
        $this->db->bind('name', $data['name']);
        $this->db->bind('outlet_type', $data['chooseOutlet']);
        $this->db->execute();

        $query2 = "DELETE FROM role_permission WHERE role_id=:id";

        $this->db->query($query2);
        $this->db->bind('id', $data['id']);
        $this->db->execute();

        if ($data['chooseOutlet'] == 'Headoffice') {
            foreach ($data['headofficePermissions'] as $hp) {
                $queryrp = "INSERT INTO role_permission (id, role_id, permission_id) VALUES
                ('', (SELECT id FROM roles WHERE name = :name), (SELECT id FROM headoffice_permissions WHERE name = :permission_name))";
    
                $this->db->query($queryrp);
                $this->db->bind('name', $data['name']);
                $this->db->bind('permission_name', $hp);
                $this->db->execute();
            }
        } else if ($data['chooseOutlet'] == 'Admin') {
            foreach ($data['adminPermissions'] as $ap) {
                $queryrp = "INSERT INTO role_permission (id, role_id, permission_id) VALUES
                ('', (SELECT id FROM roles WHERE name = :name), (SELECT id FROM admin_permissions WHERE name = :permission_name))";
    
                $this->db->query($queryrp);
                $this->db->bind('name', $data['name']);
                $this->db->bind('permission_name', $ap);
                $this->db->execute();
            }
        }
        
        return $this->db->rowCount();
    }
        
    public function deleteRolePermission($id){
        $query1 = "DELETE FROM roles WHERE id=:id";
        $this->db->query($query1);
        $this->db->bind('id', $id);
        $this->db->execute();
            
        $query2 = "DELETE FROM role_permission WHERE role_id=:id";
        $this->db->query($query2);
        $this->db->bind('id', $id);
        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function deleteManyRolePermission($ids){
        $idString = implode(',', array_map('intval', $ids));
    
        $query1 = "DELETE FROM roles WHERE id IN ($idString)";
        $this->db->query($query1);
        $this->db->execute();
            
        $query2 = "DELETE FROM role_permission WHERE role_id IN ($idString)";
        $this->db->query($query2);
        $this->db->execute();
        
        return $this->db->rowCount();
    }
    
    public function getDataEditRole($id, $outletType){
        if($outletType == 'Headoffice') {
            $query = "SELECT * FROM role_permission INNER JOIN headoffice_permissions ON role_permission.permission_id=headoffice_permissions.id WHERE role_id=:id";
        } else if ($outletType == 'Admin') {
            $query = "SELECT * FROM role_permission INNER JOIN admin_permissions ON role_permission.permission_id=admin_permissions.id WHERE role_id=:id";
        }

        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();

        return $this->db->allResult();
    } 

    public function getRoleConfig($name) {
        $this->db->query("SELECT * FROM roles WHERE name=:name");
        $this->db->bind('name', $name);
        return $this->db->singleResult();
    }

    public function searchRole($keyword){
        $query = "SELECT * FROM roles WHERE name LIKE :keyword OR outlet_type LIKE :keyword ORDER BY CASE WHEN outlet_type = 'Headoffice' THEN 0 ELSE 1 END,outlet_type";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");

        $this->db->execute();

        return $this->db->allResult();
    }
}   