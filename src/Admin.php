<?php

class Admin extends GlobalUser {
    
    public $id;
    public $nickname;
    
    public function __construct() {
        $this->active = TRUE;
    }

    public function getNickname() {
        
        return $this->nickname;
        
    }

    public function setNickname($nickname) {
        
        return (User::validateString($nickname)) ? $this->nickname = $nickname : FALSE;
        
    }

    public function createNewAdmin(Admin $admin) {
        
        $query = "INSERT INTO admins (nickname, email, hashed_password, active) VALUES ('"
                . $admin->getNickname() . "', '"
                . $admin->getEmail() . "', '"
                . $admin->getHashedPassword() . "', "
                . $admin->getActive() . ")";
        
        return (Connection::connect($query)) ? TRUE : FALSE;
        
    }
    
    public function changeAdminNickname(string $newNickname) : bool {
        
        if (!GlobalUser::validateString($newNickname)) {return FALSE;}
        $query = "UPDATE admins SET nickname='" . $newNickname . "' WHERE id=" . $this->getId();
        return (Connection::connect($query)) ? TRUE : FALSE;
        
    }
    
    public function changeAdminEmail(string $newEmail) : bool {
        
        if (!GlobalUser::validateEmail($newEmail)) {return FALSE;}
        $query = "UPDATE admins SET email='" . $newEmail . "' WHERE id=" . $this->getId();
        return (Connection::connect($query)) ? TRUE : FALSE;
    }
    
    public function changeAdminPassword(string $oldPassword, string $newPassword) : bool {
        
        if (password_verify($oldPassword, $this->getHashedPassword())) {
            
            $this->setHashedPassword($newPassword);
            
            $query = "UPDATE admins SET hashed_password='" . $this->getHashedPassword() . "' WHERE id=" . $this->getId();
            return (Connection::connect($query)) ? TRUE : FALSE;
            
        }
        return FALSE;
    }
    
    public function changeAdminActivness(bool $active) : bool {
        
        $query = "UPDATE admins SET active=" . $active . " WHERE id=" . $this->getId();
        return (Connection::connect($query)) ? TRUE : FALSE;
        
    }
    
    static private function createAdminObject(array $row) {
        
        $admin = new Admin();
                
        $admin->setId(intval($row['id']));
        $admin->setEmail($row['email']);
        $admin->setPassword($row['hashed_password']);
        $admin->setNickname($row['nickname']);
        $admin->setActive($row['active']);
        
        return $admin;
        
    }
    
    static public function loadAll($query) {
        
        $result = Connection::connect($query);
        
        if ($result->num_rows != 0) {
            
            $admins = [];
            
            foreach ($result as $row) {
                
                $admins[] = Admin::createAdminObject($row);
                
            }
            
            return $admins;
            
        }
        return FALSE;
        
    }
    
    static public function loadOne($query) {
        
        $result = Connection::connect($query);
        
        if ($result->num_rows != 0) {
            
            $row = $result->fetch_assoc();
            return Admin::createAdminObject($row);
            
        }
        return FALSE;
        
    }
    
    static public function loadAllAdmins() {
        
        $query = "SELECT * FROM admins";
        return Admin::loadAll($query);
    }
    
    static public function loadAllActiveAdmins() {
        
        $query = "SELECT * FROM admins WHERE active=1";
        return Admin::loadAll($query);
    }
    
    static public function loadAdminById(int $id) {
        
        $query = "SELECT * FROM admins WHERE id=$id";
        return Admin::loadOne($query);
        
    }
    
    static public function loadAdminByEmail(string $email) {
        
        if (!GlobalUser::validateEmail($email)) {

            return FALSE;
            
        }
        
        $query = "SELECT * FROM admins WHERE email='" . $email . "'";
        return Admin::loadOne($query);
        
    }
    
    public function login() {
        
        $_SESSION['admin_id'] = $this->getId();
        
    }
    
    public function logout () {
        
        unset($_SESSION['admin_id']);
        
    }
    
}

