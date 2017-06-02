<?php

class User extends GlobalUser {
    
    public $id;
    public $name;
    public $surname;
    public $address;
    
    public function __construct() {
        $this->active = TRUE;
    }

    public function getName() : string {
        
        return $this->name;
        
    }

    public function getSurname() : string {
        
        return $this->surname;
        
    }

    public function getAddress() : string {
       
        return $this->address;
        
    }
    
    public function setName($name) {
        
        return (User::validateString($name)) ? $this->name = $name : FALSE;
        
    }

    public function setSurname($surname) {
        
        return (User::validateString($surname)) ? $this->surname = $surname : FALSE;
        
    }

    public function setAddress($address) {
        
        return (preg_match("/^[a-z0-9 ,.\-]+$/i", $address)) ? $this->address = $address : FALSE;
        
    }

    public function createUser(User $user) : bool {
        
        $query = "INSERT INTO users (email, hashed_password, name, surname, address, active) VALUES ('"
                . $user->getEmail() . "', '"
                . $user->getHashedPassword() . "', '"
                . $user->getName() . "', '"
                . $user->getSurname() . "', '"
                . $user->getAddress() . "', "
                . $user->getActive() . ")";
        
        $result = Connection::connect($query);
        
        return ($result) ? TRUE : FALSE;
        
    }
    
    static private function createUserObject(array $row) {
        
        $user = new User();
                
        $user->setId(intval($row['id']));
        $user->setEmail($row['email']);
        $user->setPassword($row['hashed_password']);
        $user->setName($row['name']);
        $user->setSurname($row['surname']);
        $user->setAddress($row['address']);
        $user->setActive($row['active']);
        
        return $user;
        
    }
    
    static public function loadAll($query) {
        
        $result = Connection::connect($query);
        
        if ($result->num_rows != 0) {
            
            $users = [];
            
            foreach ($result as $row) {
                
                $users[] = User::createUserObject($row);
                
            }
            
            return $users;
            
        }
        return FALSE;
        
    }
    
    static public function loadOne($query) {
        
        $result = Connection::connect($query);
        
        if ($result->num_rows != 0) {
            
            $row = $result->fetch_assoc();
            return User::createUserObject($row);
            
        }
        return FALSE;
        
    }
    
    static public function loadAllUsers() {
        
        $query = "SELECT * FROM users";
        return User::loadAll($query);
    }
    
    static public function loadAllActiveUsers() {
        
        $query = "SELECT * FROM users WHERE active=1";
        return User::loadAll($query);
    }
    
    static public function loadUserById(int $id) {
        
        $query = "SELECT * FROM users WHERE id=$id";
        return User::loadOne($query);
        
    }
    
    static public function loadUserByEmail(string $email) {
        
        if (!GlobalUser::validateEmail($email)) {

            return FALSE;
            
        }
        
        $query = "SELECT * FROM users WHERE email='" . $email . "'";
        return User::loadOne($query);
        
    }
    
    public function changeUserEmail(string $newEmail) : bool {
        
        if (!GlobalUser::validateEmail($newEmail)) {return FALSE;}
        $query = "UPDATE users SET email='" . $newEmail . "' WHERE id=" . $this->getId();
        return (Connection::connect($query)) ? TRUE : FALSE;
    }
    
    public function changeUserName(string $newName) : bool {
        
        if (!GlobalUser::validateString($newName)) {return FALSE;}
        $query = "UPDATE users SET name='" . $newName . "' WHERE id=" . $this->getId();
        return (Connection::connect($query)) ? TRUE : FALSE;
    }
    
    public function changeUserSurname(string $newSurname) : bool {
        
        if (!GlobalUser::validateString($newSurname)) {return FALSE;}
        $query = "UPDATE users SET surname='" . $newSurname . "' WHERE id=" . $this->getId();
        return (Connection::connect($query)) ? TRUE : FALSE;
    }
    
    public function changeUserAddress(string $newAddress) : bool {
        
        if (!preg_match("/^[a-z0-9 ,.\-]+$/i", $newAddress)) {return FALSE;}
        $query = "UPDATE users SET address='" . $newAddress . "' WHERE id=" . $this->getId();
        return (Connection::connect($query)) ? TRUE : FALSE;
    }
    
    public function changeUserActivness($active) : bool {
        
        $query = "UPDATE users SET active = $active WHERE id=" . $this->getId();
        return (Connection::connect($query)) ? TRUE : FALSE;
        
    }
    
    public function changeUserPassword(string $oldPassword, string $newPassword) : bool {
        
        if (password_verify($oldPassword, $this->getHashedPassword())) {
            
            $this->setHashedPassword($newPassword);
            
            $query = "UPDATE users SET hashed_password='" . $this->getHashedPassword() . "' WHERE id=" . $this->getId();
            
            return (Connection::connect($query)) ? TRUE : FALSE;
            
        }
        return FALSE;
    }
    
    public function showUsersMessages() {
        
        return Message::loadAllMessagesByRecipientId($this->getId());
        
    }
    
    public function login() {
        
        $_SESSION['user_id'] = $this->getId();
        
    }
    
    public function logout () {
        
        unset($_SESSION['user_id']);
        
    }
    
}

