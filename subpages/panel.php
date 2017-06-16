<?php

session_start();

require_once '../src/Connection.php';
require_once '../config/config.php';
require_once '../src/GlobalUser.php';
require_once '../src/User.php';
require_once '../src/Group.php';

if (isset($_GET['groups'])) {
    
    if (!empty($_GET['groups'])) {
        
        Group::deleteGroup($_GET['groups']);
        
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $newGroup = new Group($_POST['name']);
        $newGroup->addNewGroup();
        
    }
    
    include '../templates/addgroup_form.html';
    
    $groups = Group::showAllGroups();
    
    echo "<table><thead><tr><th>ID</th><th>Action</th></tr></thead><tbody>";
    
    foreach ($groups as $group) {
        
        echo "<tr>"
                . "<td>" . $group->getName() . "</td>"
                . "<td><a href='panel.php?groups=" . $group->getId() . "'>Delete</a></td>"
            . "</tr>";
        
    }
    
    echo "</tbody></table>";
    
} elseif (isset($_GET['products'])) {
    
    
    
} elseif (isset($_GET['users'])) {
    
    $users = User::loadAllUsers();
    
    echo "<table><thead><tr><th>ID</th><th>Name</th><th>Surname</th><th>Email</th><th>Status</th></tr></thead><tbody>";
    
    foreach ($users as $user) {
        
        echo "<tr>"
                . "<td><a href='panel.php?userid=" . $user->getId() . "'>" . $user->getId() . "</td>"
                . "<td>" . $user->getName() . "</td>"
                . "<td>" . $user->getSurname() . "</td>"
                . "<td>" . $user->getEmail() . "</td>"
                . "<td>" . $user->getActive() . "</td></a>"
            . "</tr>";
        
    }
    
    echo "</tbody></table>";
    
} elseif (isset($_GET['orders'])) {
    
    
    
}

?>

<a href="panel.php?groups">Groups</a>
<a href="panel.php?products">Products</a>
<a href="panel.php?users">Users</a>
<a href="panel.php?orders">Orders</a>