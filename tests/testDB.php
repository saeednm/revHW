<?php
require_once(dirname(__FILE__) . '\simpletest\autorun.php');
require_once('..\Data.php');
 
class TestDB extends UnitTestCase {
		
	function testAddAndGetUser()
	{
		$db = new Data();
		
		$user_id=-1;
		$username="test";
		
		$db->addUser($user_id,$username);
		$entries = $db->getUser($user_id); 
		$count_is_one = (mysqli_num_rows($entries)==1);
		$this->assertTrue($count_is_one);
		$entry = $entries->fetch_assoc();
		$this->assertIsA($entry, 'array');
		$this->assertTrue(isset($entry['u_id']));
		$this->assertTrue(isset($entry['u_name']));
		
	}
	function testSaveAndDeleteAndGetCustomers()
	{
		$db = new Data();
		
		$user_id=-1;
		$customer_name="testCustomer";
		$customer_description="testDescription";
		
		// Add new record
		$db->saveCustomer($customer_name,$customer_description,$user_id);
		$entries = $db->getCustomers($user_id);   
		$count_is_greater_than_zero = (mysqli_num_rows($entries) > 0);
		$this->assertTrue($count_is_greater_than_zero);	
		
		// Delete all customer records. 
		$db->deleteWorks($user_id);		//delete works to solve foreign key dependency
		$db->deleteProjects($user_id);	//delete projects to solve foreign key dependency
		$db->deleteCustomers($user_id);
		$entries = $db->getCustomers($user_id);
		$count_is_zero = (mysqli_num_rows($entries)==0);
		$this->assertTrue($count_is_zero);
		
		// Add new record
		$db->saveCustomer($customer_name,$customer_description,$user_id);
		$entries = $db->getCustomers($user_id);
		$count_is_one = (mysqli_num_rows($entries)==1);
		$this->assertTrue($count_is_one);
		$entry = $entries->fetch_assoc();
		$this->assertIsA($entry, 'array');
		$this->assertTrue(isset($entry['c_id']));
		$this->assertTrue(isset($entry['c_name']));
		$this->assertTrue(isset($entry['c_description']));
		$this->assertEqual($entry['c_name'],$customer_name);
		
	}
	function testSaveAndDeleteAndGetProjects()
	{
		$db = new Data();
		//  Set test data
		$user_id=-1;
		$project_name="testProject";
		$project_description="testDescription";		
		$fee = 10.00;		
		$entries = $db->getCustomers($user_id);
		$entry = $entries->fetch_assoc();
		$customer_id=$entry['c_id'];
		
		// Add new record
		$db->saveProject($project_name,$project_description,$customer_id,$fee,$user_id );
		$entries = $db->getProjects($user_id); 
		$count_is_greater_than_zero = (mysqli_num_rows($entries) > 0);
		$this->assertTrue($count_is_greater_than_zero);
		
		$db->deleteWorks($user_id);		//delete works to solve foreign key dependency
		$db->deleteProjects($user_id);
		$entries = $db->getProjects($user_id);
		$count_is_zero = (mysqli_num_rows($entries)==0);
		$this->assertTrue($count_is_zero);
		
		// Add new record
		$db->saveProject($project_name,$project_description,$customer_id,$fee,$user_id );
		$entries = $db->getProjects($user_id);
		$count_is_one = (mysqli_num_rows($entries)==1);
		$this->assertTrue($count_is_one);
		$entry = $entries->fetch_assoc();
		$this->assertIsA($entry, 'array');
		$this->assertTrue(isset($entry['p_id'])); 
		$this->assertTrue(isset($entry['p_name']));
		$this->assertTrue(isset($entry['c_name']) or array_key_exists("c_name",$entry));		
		$this->assertEqual($entry['p_name'],$project_name);
	}  
	function testSaveAndDeleteWorkAndGetSummaryAndGetTotal()
	{
		$db = new Data();
		//  Set test data
		$user_id=-1;
		$entries = $db->getProjects($user_id);
		$entry = $entries->fetch_assoc();
		$project_id=$entry['p_id'];
		$hour=1;
		$minute=10;
		$date='2017-12-18';
		
		$db->saveWork($user_id,$project_id,$hour,$minute,$date);
		$entries = $db->getSummary($user_id); 
		$count_is_greater_than_zero = (mysqli_num_rows($entries) > 0);
		$this->assertTrue($count_is_greater_than_zero);
		
		$db->deleteWorks($user_id);
		$entries = $db->getSummary($user_id);
		$count_is_zero = (mysqli_num_rows($entries)==0);
		$this->assertTrue($count_is_zero);
		
		$db->saveWork($user_id,$project_id,$hour,$minute,$date);
		$db->saveWork($user_id,$project_id,$hour,$minute,$date);
		$entries =$db->getSummary($user_id);
		$count_is_one = (mysqli_num_rows($entries)==1);
		$this->assertTrue($count_is_one);
		$entry = $entries->fetch_assoc();
		$this->assertIsA($entry, 'array');
		$this->assertTrue(isset($entry['p_name'])); 
		$this->assertTrue(isset($entry['minute']));
		$this->assertEqual($entry['minute'],($hour*60+$minute)*2);
		
		$entries =$db->getTotal($user_id);
		$count_is_two = (mysqli_num_rows($entries)==2);
		$this->assertTrue($count_is_two);
		$entry = $entries->fetch_assoc();
		$this->assertIsA($entry, 'array');
		$this->assertTrue(isset($entry['customer'])); 
		$this->assertTrue(isset($entry['project'])); 
		$this->assertTrue(isset($entry['minute']));
		$this->assertTrue(isset($entry['date']));
		
		
	}
}
