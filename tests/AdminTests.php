<?php

include "src/Connection.php";
include "src/Message.php";
include "config/config.php";
include "src/GlobalUser.php";
include "src/User.php";
include "src/Admin.php";

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class AdminTests extends TestCase {
    
    use TestCaseTrait;
    
    public function getConnection() {
        
        $conn = new PDO(
            $GLOBALS['DB_DSN'],
            $GLOBALS['DB_USER'],
            $GLOBALS['DB_PASSWD']
        );
        
        return $this->createDefaultDBConnection($conn, $GLOBALS['DB_DBNAME']);
        
    }
    
    public function getDataSet() {
        
        $dataXML = $this->createFlatXMLDataSet('tests/resources/testDataset.xml');
        
        return $dataXML;
        
    }
    
    public function testIfCreateNewAdmin() {
        
        $this->assertEquals(2, $this->getConnection()->getRowCount('admins'));
        
        $admin = new Admin();
        $admin->setNickname('aaadmin');
        $admin->setEmail('aaadmin@it.pl');
        $admin->setHashedPassword('admin1');
        
        $this->assertTrue($admin->createNewAdmin($admin));
        $this->assertEquals(3, $this->getConnection()->getRowCount('admins'));
        
    }
    
    public function testIfAdminCanChangeNickname() {
        
        $admin = Admin::loadAdminById(1);
        $this->assertEquals('admin', $admin->getNickname());
        
        $this->assertTrue($admin->changeAdminNickname('masterAdmin'));
        
        $admin2 = Admin::loadAdminById(1);
        $this->assertEquals('masterAdmin', $admin2->getNickname());
        
    }
    
    public function testIfAdminCanChangeEmail() {
        
        $admin = Admin::loadAdminById(1);
        $this->assertTrue($admin->changeAdminEmail('im@rock.ok'));
        
        $admin2 = Admin::loadAdminById(1);
        $this->assertEquals('im@rock.ok', $admin2->getEmail());
        
    }
    
    public function testIfAdminCanChangePassword() {
        
        $admin = Admin::loadAdminById(1);
        $this->assertTrue($admin->changeAdminPassword('admin1','haslo'));
        
        $admin2 = Admin::loadAdminById(1);
        $this->assertTrue(password_verify('haslo', $admin2->getHashedPassword()));
        
    }
     
//    public function testIfCanChangeAdminActivness() {
//        
//        $admin = Admin::loadAdminById(1);
//        $this->assertTrue($admin->changeAdminActivness(FALSE));
//        
//        $admin2 = Admin::loadAdminById(1);
//        $this->assertEquals(0, $admin2->getActive());
//        
//    }
    
    public function testIfloadAllAdmins() {
        
        $this->assertEquals(2, count(Admin::loadAllAdmins()));
        
    }
    
    public function testIfLoadAllActiveAdmins() {
        
        $this->assertEquals(1, count(Admin::loadAllActiveAdmins()));
        
    }
    
    public function testIfLoadAdminByEmail() {
        
        $this->assertEquals('admindwa', Admin::loadAdminByEmail('admin2@netshop.pl')->getNickname());
        
    }
    
}