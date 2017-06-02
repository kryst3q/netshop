<?php

include "src/Connection.php";
include "src/Message.php";
include "config/config.php";
include "src/GlobalUser.php";
include "src/User.php";

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class UserTests extends TestCase {
    
    use TestCaseTrait;
    
    public function getConnection() {
        
        $conn = new PDO(
            $GLOBALS['DB_DSN'],
            $GLOBALS['DB_USER'],
            $GLOBALS['DB_PASSWD']
        );
        
//        $conn->query("set foreign_key_checks=0");
        
        return $this->createDefaultDBConnection($conn, $GLOBALS['DB_DBNAME']);
        
    }
    
    public function getDataSet() {
        
        $dataXML = $this->createFlatXMLDataSet('tests/resources/testDataset.xml');
        
        return $dataXML;
        
    }
    
    public function testIfCreateNewUser() {
        
        $this->assertEquals(4, $this->getConnection()->getRowCount('users'));
        
        $user = new User();
        $user->setEmail('test@mail.com');
        $user->setHashedPassword('secretpass');
        $user->setName('Test');
        $user->setSurname('Test');
        $user->setAddress('Test Road 1, San Francisco');
        
        $this->assertTrue($user->createUser($user));
        $this->assertEquals(5, $this->getConnection()->getRowCount('users'));
        
    }
    
    public function testIfLoadAllUsers() {
        
        $this->assertEquals(4, count(User::loadAllUsers()));
        
    }
    
    public function testIfLoadAllActiveUsers() {
        
        $this->assertEquals(3, count(User::loadAllActiveUsers()));
        
    }
    
    public function testIfLoadUserById() {
        
        $this->assertEquals('Smith', User::loadUserById(1)->getSurname());
        
    }
    
    public function testIfLoadUserByEmail() {
        
        $this->assertEquals('Stark', User::loadUserByEmail('email@yahoo.com')->getSurname());
        
    }
    
    public function testIfChangeUserEmail() {
        
        $this->assertTrue(User::loadUserById(4)->changeUserEmail('tony@stark.com'));
        $this->assertEquals('Stark', User::loadUserByEmail('tony@stark.com')->getSurname());
        
    }
    
    public function testIfChangeUserName() {
        
        $this->assertTrue(User::loadUserById(1)->changeUserName('Johny'));
        $this->assertEquals('Johny', User::loadUserByEmail('mail@domain.com')->getName());
        
    }
    
    public function testIfChangeUserSurname() {
        
        $this->assertTrue(User::loadUserById(1)->changeUserSurname('Bravo'));
        $this->assertEquals('Bravo', User::loadUserByEmail('mail@domain.com')->getSurname());
        
    }
    
    public function testIfChangeUserAddress() {
        
        $this->assertTrue(User::loadUserById(1)->changeUserAddress('Cartoon 2, NY'));
        $this->assertEquals('Cartoon 2, NY', User::loadUserByEmail('mail@domain.com')->getAddress());
        
    }
    
//    public function testIfChangeUserActivness() {
//        
//        $this->assertTrue(User::loadUserById(1)->changeUserActivness('FALSE'));
//        $this->assertFalse(User::loadUserById(1)->getActive());
//        
//    }
    
    public function testIfChangeUserPassword() {
        
        $user = User::loadUserById(1);
        
        $this->assertTrue(password_verify('secretpass', $user->getHashedPassword()));
        $this->assertTrue($user->changeUserPassword('secretpass', 'admin1'));
        $this->assertFalse(password_verify('secretpass', $user->getHashedPassword()));
        $this->assertTrue(password_verify('admin1', $user->getHashedPassword()));
        
    }
    
    public function testIfShowAllUsersMessages() {
        
        $messages = User::loadUserById(4)->showUsersMessages();
//        var_dump(Message::loadMessageById(4)); //właściwie wyszukuje wiadomości po id, ale w konsoli zamiast int-gerów zwraca NULL-e
        $this->assertEquals('Random message 4', $messages[2]->getMessage());
        $this->assertEquals(3, count($messages));
        
    }
    
//    public function testIfUserCanLogIn() {
//        
//        session_start();
//        
//        $user = User::loadUserById(4);
//        $user->login();
//        $this->assertEquals(4, $_SESSION['user_id']);
//        
//    }
    
}