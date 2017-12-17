<?php

class Data
{
    var $servername = "localhost";
    var $username = "root";
    var $password = "";
    var $dbname = "reviso";
    // Create connection
    var $conn ;
    public function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    function getProjects(){
        $ct = "select * from project";
       
        $result = $this->conn->query($ct);
        #$this->conn->close();
        return $result;
    }
	function getCustomers(){
        $ct = "select * from customer";
       
        $result = $this->conn->query($ct);
        #$this->conn->close();
        return $result;
    }
    function getSummary()
    {
        $qry = "select * from summary";
        
        $res = $this->conn->query($qry);
       
        #$this->conn->close();
        return $res;
    }
	function saveWork($user,$project,$hour,$minute,$date)
    {
		$qry= "INSERT INTO `workload`(`project_id`, `user_id`, `minute`,`date`) VALUES (" 
		. $project ."," . $user . "," .  (intval($hour)*60+intval($minute)) .",'".$date. "');" ;
		echo $qry;
		$res = $this->conn->query($qry);
		if (!$res) throw new Exception("Database error");
		$this->conn->close();
        return $res;
	}
	function saveProject($project,$description,$customer_id)
    {
		
		$qry= "INSERT INTO `project`(`p_name`, `p_description`,`customer_id`) VALUES ('" 
		. $project ."','" . $description ."',".$customer_id. ");" ;
		echo $qry;
		$res = $this->conn->query($qry);
		if (!$res) throw new Exception("Database error");
		$this->conn->close();
        return $res;
	}
	function saveCustomer($customer,$description)
    {
		
		$qry= "INSERT INTO `customer`(`c_name`, `c_description`) VALUES ('" 
		. $customer ."','" . $description ."');" ;
		echo $qry;
		$res = $this->conn->query($qry);
		if (!$res) throw new Exception("Database error");
		$this->conn->close();
        return $res;
	}
}
?>