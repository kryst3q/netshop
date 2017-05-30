<?php

class User {
    
    public $id;
    public $name;
    public $surname;
    public $email;
    private $hashedPassword;
    public $address;
    public $active;
    
    public function __construct() {
        $this->id = -1;
        $this->name = "";
        $this->surname = "";
        $this->email = "";
        $this->hashedPassword = "";
        $this->address = "";
        $this->active = TRUE;
    }
    
    public function getId() : int {
        return $this->id;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getSurname() : string {
        return $this->surname;
    }

    public function getEmail() : string {
        return $this->email;
    }

    public function getHashedPassword() : string {
        return $this->hashedPassword;
    }

    public function getAddress() : string {
        return $this->address;
    }

    public function getActive() {
        return $this->active;
    }

    public function setId($id) {
        
        return (is_int($id)) ? $this->id = $id : FALSE;
    }
    
    static private function validateString($string) : bool {
        
        return (preg_match("/^[a-z]+$/i", $string)) ? TRUE : FALSE;
        
    }

    public function setName($name) {
        
        return (User::validateString($name)) ? $this->name = $name : FALSE;
    }

    public function setSurname($surname) {
        
        return (User::validateString($surname)) ? $this->surname = $surname : FALSE;
    }

    public function setEmail($email) {
        
        return (User::validateEmail($email)) ? $this->email = $email : FALSE;
    }

    public function setHashedPassword($password) {
        $this->hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    }

    public function setAddress($address) {
        
        return (preg_match("/^[a-z0-9 ,.\-]+$/i", $address)) ? $this->address = $address : FALSE;
    }

    public function setActive($active) {
        
        return (is_bool($active)) ? $this->active = $active : FALSE;
    }

    public function createUser(User $user) : bool {
        
        $query = "INSERT INTO users (email, hashed_password, name, surname, address, active) VALUES ('"
                . $user->getEmail() . "', '"
                . $user->getHashedPassword() . "', '"
                . $user->getName() . "', '"
                . $user->getSurname() . "', '"
                . $user->getAddress() . "', "
                . $user->getActive() . ")";
        
        return (Connection::connect($query)) ? TRUE : FALSE;
        
    }
    
    static private function createUserObject(array $row) {
        
        $user = new User();
                
        $user->setId(intval($row['id']));
        $user->setEmail($row['email']);
        $user->setHashedPassword($row['hashed_password']);
        $user->setName($row['name']);
        $user->setSurname($row['surname']);
        $user->setAddress($row['address']);
        $user->setActive($row['active']);
        
        return $user;
        
    }
    
    static public function validateEmail($email) : bool {
        
        return (preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]{1,})*\.([a-zA-Z]{2,}){1}$/", $email)) ? TRUE : FALSE;
    }
    
    static public function loadAll($query) {
        
        $result = Connection::connect($query);
        
        if ($result) {
            
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
        
        if ($result) {
            
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
        
        if (!User::validateEmail($email)) {

            return FALSE;
            
        }
        
        $query = "SELECT * FROM users WHERE email='" . $email . "'";
        return User::loadOne($query);
        
    }
    
    static public function updateEmail(User $user, string $newEmail) : bool {
        
        if (!User::validateEmail($newEmail)) {return FALSE;}
        $query = "UPDATE users SET email='" . $newEmail . "' WHERE id=" . $user->getId();
        return (Connection::connect($query)) ? TRUE : FALSE;
    }
    
    static public function updateName(User $user, string $newName) : bool {
        
        if (!User::validateString($newName)) {return FALSE;}
        $query = "UPDATE users SET name='" . $newName . "' WHERE id=" . $user->getId();
        return (Connection::connect($query)) ? TRUE : FALSE;
    }
    
    static public function updateSurname(User $user, string $newSurname) : bool {
        
        if (!User::validateString($newName)) {return FALSE;}
        $query = "UPDATE users SET surname='" . $newSurname . "' WHERE id=" . $user->getId();
        return (Connection::connect($query)) ? TRUE : FALSE;
    }
    
    static public function updateAddress(User $user, string $newAddress) : bool {
        
        if (!preg_match("/^[a-z0-9 ,.\-]+$/i", $address)) {return FALSE;}
        $query = "UPDATE users SET address='" . $newAddress . "' WHERE id=" . $user->getId();
        return (Connection::connect($query)) ? TRUE : FALSE;
    }
    
    static public function changeUserActivness(bool $active, int $userId) : bool {
        
        $query = "UPDATE users SET active='" . $newAddress . "' WHERE id=$userId";
        return (Connection::connect($query)) ? TRUE : FALSE;
        
    }
    
}

