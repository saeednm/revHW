<?php
require_once(dirname(__FILE__) . '\simpletest\autorun.php');
require_once('..\Data.php');
 
class TestDB extends UnitTestCase {
	
    function testSaveAndDeleteAndGetCustomers()
    {
        $db = new Data();
        // Add new records first
        $db->saveCustomer("testCustomer","testDescription");
        $entries = $db->getCustomers();   
        $count_is_greater_than_zero = (mysqli_num_rows($entries) > 0);
        $this->assertTrue($count_is_greater_than_zero);
		$db->deleteCustomers();
		$entries = $db->getCustomers();
		$count_is_zero = (mysqli_num_rows($entries)==0);
        $this->assertTrue($count_is_zero);
		$db->saveCustomer("testCustomer","testDescription");
		$entries = $db->getCustomers();
		$count_is_one = (mysqli_num_rows($entries)==1);
        $this->assertTrue($count_is_one);
		$entry = $entries->fetch_assoc();
		$this->assertIsA($entry, 'array');
		$this->assertTrue(isset($entry['c_id']));
        $this->assertTrue(isset($entry['c_name']));
        $this->assertTrue(isset($entry['c_description']));
		$this->assertEqual($entry['c_name'],"testCustomer");
    }
	function testSaveAndDeleteAndGetProjects()
    {
        $db = new Data();
        // Add new record first
        $db->saveProject("testProject","testDescription",1,1.00);
        $entries = $db->getProjects(); 
        $count_is_greater_than_zero = (mysqli_num_rows($entries) > 0);
        $this->assertTrue($count_is_greater_than_zero);
		$db->deleteProjects();
		$entries = $db->getProjects();
		$count_is_zero = (mysqli_num_rows($entries)==0);
        $this->assertTrue($count_is_zero);
		$db->saveProject("testProject","testDescription",1,1.00);
		$entries = $db->getProjects();
		$count_is_one = (mysqli_num_rows($entries)==1);
        $this->assertTrue($count_is_one);
		$entry = $entries->fetch_assoc();
		$this->assertIsA($entry, 'array');
		$this->assertTrue(isset($entry['p_id'])); 
        $this->assertTrue(isset($entry['p_name']));
        $this->assertTrue(isset($entry['c_name']) or array_key_exists("c_name",$entry));		
		$this->assertEqual($entry['p_name'],"testProject");
    }
     
   
}