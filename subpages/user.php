<?php

session_start();

require_once '../src/Connection.php';
require_once '../config/config.php';
require_once '../src/GlobalUser.php';
require_once '../src/User.php';

if (!isset($_SESSION['user_id'])) {
    
    header('Location: ../index.php');
    
}

$user = User::loadUserById($_SESSION['user_id']);

if (isset($_GET['history'])) {
    
    $ordersHistory = $user->showUsersOrdersHistory();
    
    echo "<a href='user.php'>Userinfo</a><br>";
    
    echo "<table><thead><tr><th>Product</th><th>Execution datetime</th></tr></thead><tbody>";
    
    foreach ($ordersHistory as $order) {
    
        echo "<tr><td>" . $order['name'] . "</td><td>" . $order['execution_datetime'] . "</td></tr>";
        
    }
    echo "</tbody></table>";
    
} elseif (isset($_GET['update'])) {
    
    require_once '../templates/update_userinfo_form.html';
    
} else {
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if (!empty($_POST['email'])) {
            
            $user->setEmail($_POST['email']);
            
        }
        
        if (!empty($_POST['name'])) {
            
            $user->setName($_POST['name']);
            
        }
        
        if (!empty($_POST['surname'])) {
            
            $user->setSurname($_POST['surname']);
            
        }
        
        if (!empty($_POST['address'])) {
            
            $user->setAddress($_POST['address']);
            
        }
        
        if (!empty($_POST['oldpassword']) && !empty($_POST['newpassword'])) {
            
            $hash = $user->getHashedPassword();
            
            if (password_verify($_POST['oldpassword'], $hash)) {
                
                $user->setHashedPassword($_POST['newpassword']);
                
            }
            
        }
        
        $user->addToDB($user);
        
    }
    
    echo "<a href='user.php?history'>History</a><br>";

    echo "Name: " . $user->getName() . "<br>";
    echo "Surname: " . $user->getSurname() . "<br>";
    echo "Address: " . $user->getAddress() . "<br>";
    echo "Email: " . $user->getEmail() . "<br>";
    
    echo "<a href='user.php?update'>Update</a>";
    
}

