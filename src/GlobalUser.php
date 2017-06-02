<?php

abstract class GlobalUser {
    
    private $hashedPassword;
    public $email;
    public $active;
    
    public function getId() : int {
        
        return $this->id;
        
    }
    
    public function getEmail() : string {
        
        return $this->email;
        
    }
    
    public function getHashedPassword() : string {
        
        return $this->hashedPassword;
        
    }
    
    public function getActive() : bool {
        
        return $this->active;
        
    }
    
    public function setId($id) {
        
        return (is_int($id)) ? $this->id = $id : FALSE;
        
    }
    
    public function setEmail($email) {
        
        return (User::validateEmail($email)) ? $this->email = $email : FALSE;
    
    }
    
    public function setHashedPassword($password) {
        
        $this->hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    }
    
    protected function setPassword($password) {
        
        $this->hashedPassword = $password;
        
    }
    
    public function setActive($active) {
        
        return (is_bool($active)) ? $this->active = $active : FALSE;
    
        
    }
    
    static public function validateString($string) : bool {
        
        return (preg_match("/^[a-z]+$/i", $string)) ? TRUE : FALSE;
        
    }
    
    static public function validateEmail($email) : bool {
        
        return (preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]{1,})*\.([a-zA-Z]{2,}){1}$/", $email)) ? TRUE : FALSE;
     
    }
    
    
    
}

