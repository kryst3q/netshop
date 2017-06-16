<?php

session_start();

require_once '../src/Connection.php';
require_once '../config/config.php';
require_once '../src/GlobalUser.php';
require_once '../src/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $allOK = TRUE;
    
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
        $allOK = FALSE;
        $_SESSION['email_err'] = "<p class='text-danger'>Invalid email format</p>";
        
    } else {
        
        if (User::loadUserByEmail($_POST['email'])) {
            
            $allOK = FALSE;
            $_SESSION['email_err'] = "<p class='text-danger'>User with entered email already exists</p>";
            
        }
        
    }
    
    if (ctype_alnum($_POST['name']) == FALSE) {
        
        $allOK = FALSE;
        $_SESSION['name_err'] = "<p class='text-danger'>Use only alphanumeric characters</p>";
        
    }
    
    if (ctype_alnum($_POST['surname']) == FALSE) {
        
        $allOK = FALSE;
        $_SESSION['surname_err'] = "<p class='text-danger'>Use only alphanumeric characters</p>";
        
    }
    
    if (!preg_match("/^[a-z0-9 ,.\-]+$/i", $_POST['address'])) {
        
        $allOK = FALSE;
        $_SESSION['address_err'] = "<p class='text-danger'>Using forbidden characters</p>";
        
    }
    
    if (strlen($_POST['password']) < 8) {
        
        $allOK = FALSE;
        $_SESSION['password_err'] = "<p class='text-danger'>Use minimum eight characters</p>";
        
    }
    
    if ($_POST['password'] != $_POST['verif_passwd']) {
        
        $allOK = FALSE;
        $_SESSION['verif_passwd'] = "<p class='text-danger'>Given passwords are not identical</p>";
        
    }
    
    if ($allOK == TRUE) {
        
        $newUser = new User();
        $newUser->setEmail($_POST['email']);
        $newUser->setName($_POST['name']);
        $newUser->setSurname($_POST['surname']);
        $newUser->setHashedPassword($_POST['password']);
        $newUser->setAddress($_POST['address']);
        
        $newUser->createUser($newUser);
        
        $newUser->login();
        
        header('Location: ../index.php');
        exit();
        
    }
    
}

?>

<div class="form-group jumbotron">
    <form action="register.php" method="POST">
        <div class="row">
            <div class="col-xs-4"></div>
            <div class="col-xs-4">
                <input required type="email" class="form-control" name="email" placeholder="email">
            </div>
            <div class="col-xs-4"></div>
        </div>
        <?php 
        
            if (isset($_SESSION['email_err'])) {
                
                echo $_SESSION['email_err'];
                
            }
        
        ?>
        <div class="row">
            <div class="col-xs-4"></div>
            <div class="col-xs-4">
                <input type="text" class="form-control" name="name" placeholder="name">
            </div>
            <div class="col-xs-4"></div>
        </div>
        <?php 
        
            if (isset($_SESSION['name_err'])) {
                
                echo $_SESSION['name_err'];
                
            }
        
        ?>
        <div class="row">
            <div class="col-xs-4"></div>
            <div class="col-xs-4">
                <input type="text" class="form-control" name="surname" placeholder="surname">
            </div>
            <div class="col-xs-4"></div>
        </div>
        <?php 
        
            if (isset($_SESSION['surname_err'])) {
                
                echo $_SESSION['surname_err'];
                
            }
        
        ?>
        <div class="row">
            <div class="col-xs-4"></div>
            <div class="col-xs-4">
                <input type="text" class="form-control" name="address" placeholder="address">
            </div>
            <div class="col-xs-4"></div>
        </div>
        <?php 
        
            if (isset($_SESSION['address_err'])) {
                
                echo $_SESSION['address_err'];
                
            }
        
        ?>
        <div class="row">
            <div class="col-xs-4"></div>
            <div class="col-xs-4">
                <input type="password" class="form-control" name="password" placeholder="password">
            </div>
            <div class="col-xs-4"></div>
        </div>
        <?php 
        
            if (isset($_SESSION['password_err'])) {
                
                echo $_SESSION['password_err'];
                
            }
        
        ?>
        <div class="row">
            <div class="col-xs-4"></div>
            <div class="col-xs-4">
                <input type="password" class="form-control" name="verif_passwd" placeholder="verify password">
            </div>
            <div class="col-xs-4"></div>
        </div>
        <?php 
        
            if (isset($_SESSION['verif_passwd'])) {
                
                echo $_SESSION['verif_passwd'];
                
            }
        
        ?>
        <div class="row">
            <div class="col-xs-4"></div>
            <div class="col-xs-4">
                <button type="submit" class="btn btn-default form-control">Sign up</button>
            </div>
            <div class="col-xs-4"></div>
        </div>
    </form>
</div>

<?php


