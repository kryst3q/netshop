<?php

class Group {
    
    private $id;
    private $name;
    
    public function __construct($name = '') {
        
        $this->name = $name;
    
    }
    
    public function setId($id) {
        
        $this->id = $id;
        
    }
    
    public function getId() {
        
        return $this->id;
        
    }
    
    function getName() {
        
        return $this->name;
        
    }

    function setName($name) {
        
        $this->name = $name;
        
    }

    
    public function addNewGroup() {
        
        $query = "INSERT INTO groups (name) VALUES ('" . $this->name . "')";
        return Connection::connect($query);
        
    }
    
    static public function deleteGroup($id) {
        
        $query = "DELETE FROM groups WHERE id=" . $id;
        return Connection::connect($query);
        
    }
    
    static public function showAllGroups() {
        
        $query = "SELECT * FROM groups";
        $result = Connection::connect($query);
        
        if ($result->num_rows != 0) {
            
            $groups = [];
            
            foreach ($result as $row) {
                
                $group = new Group();
                $group->setId($row['id']);
                $group->setName($row['name']);
            
                $groups[] = $group;
                
            }
            
            return $groups;
            
        }
        return FALSE;
         
    }
    
}

