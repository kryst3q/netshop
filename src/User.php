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

    public function setId($id) : bool {
        
        if (is_int($id)) {
            
            $this->id = $id;
            return TRUE;
            
        }
        return FALSE;
    }

    public function setName($name) : bool {
        
        if (preg_match("/^[a-z]+$/i", $name)) {
            
            $this->name = $name;
            return TRUE;
            
        }
        return FALSE;
    }

    public function setSurname($surname) : bool {
        
        if (preg_match("/^[a-z]+$/i", $surname)) {
            
            $this->surname = $surname;
            return TRUE;
            
        }
        return FALSE;
    }

    public function setEmail($email) : bool {
        
        if (User::validateEmail($email)) {
            
            $this->email = $email;
            return TRUE;
            
        }
        return FALSE;
    }

    public function setHashedPassword($password) {
        $this->hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    }

    public function setAddress($address) : bool {
        
        if (preg_match("/^[a-z0-9 ,.\-]+$/i", $address)) {
            
            $this->address = $address;
            return TRUE;
            
        }
        return FALSE;
    }

    public function setActive($active) : bool {
        
        if (is_bool($active)) {
            
            $this->active = $active;
            return TRUE;
            
        }
        return FALSE;
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
        
        if (preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]{1,})*\.([a-zA-Z]{2,}){1}$/", $email)) {
            
            return TRUE;
            
        }
        return FALSE;
    }
    
    static public function loadAllUsers() {
        
        $query = "SELECT * FROM users";
        
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
    
    static public function loadUserById(int $id) {
        
        $query = "SELECT * FROM users WHERE id=$id";
        
        $result = Connection::connect($query);
        
        if ($result) {
            
            $row = $result->fetch_assoc();
            
            return User::createUserObject($row);
            
        }
        return FALSE;
    }
    
    static public function loadUserByEmail(string $email) {
        
        if (!User::validateEmail($email)) {

            return FALSE;
            
        }
        
        $query = "SELECT * FROM users WHERE email='" . $email . "'";
        
        $result = Connection::connect($query);
        
        if ($result) {
            
            $row = $result->fetch_assoc();
            
            return User::createUserObject($row);
            
        }
        return FALSE;
        
    }
    
}

