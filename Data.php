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
        $qry = "select * from `project` left join `customer` on(`project`.`customer_id` = `customer`.`c_id`)"; 
        $result = $this->conn->query($qry);
        #$this->conn->close();
        return $result;
    }
	function deleteProjects(){
        $qry = "DELETE FROM `project`";       
        $result = $this->conn->query($qry);		
        return $result;
    }
	function getCustomers(){
        $qry = "select * from customer";      
        $result = $this->conn->query($qry);
        return $result;
    }
	function deleteCustomers(){
        $qry = "DELETE FROM customer";       
        $result = $this->conn->query($qry);
        return $result;
    }
	
    function getSummary()
    {
        $qry = "select * from summary";
		$q="
		select `user`.`u_name` AS `username`,`project`.`p_name` AS `project`,
		(sum(`workload`.`minute`) % 60) AS `minute`,(sum(`workload`.`minute`) DIV 60) AS `hour` 
		from ((`workload` 
		join `user` on((`workload`.`user_id` = `user`.`u_id`))) 
		join `project` on((`workload`.`project_id` = `project`.`p_id`))) 
		group by `username`,`project` order by `username`,`project` ;";
       
        $res = $this->conn->query($qry);
        return $res;
    }
	function filterSummaryByTime($timeFrom, $timeTo)
	{
		$qry= 
		"select `user`.`u_name` AS `username`,`projectforcustomer`.`c_name` AS `customer`,
		`projectforcustomer`.`p_name` AS `project`,`workload`.`minute` AS `minute` ,`workload`.`date` AS `date` 
		from (
		(`workload` join `user` on((`workload`.`user_id` = `user`.`u_id`)))
		join (
		  select `project`.`p_id` AS `p_id`,`project`.`p_name` AS `p_name`,`customer`.`c_id` ,`customer`.`c_name` AS `c_name` 
		  from (`project` join `customer` on((`project`.`customer_id` = `customer`.`c_id`)))
		  )as `projectforcustomer` on((`workload`.`project_id` = `projectforcustomer`.`p_id`))
		) 
		 " ;
		if (strlen($timeFrom) > 1){
            $qry .=" where date>= ? ";
			if (strlen($timeTo) > 1){
				$qry .=" and date<= ? ";
			}
		}else if (strlen($timeTo) > 1){
				$qry .=" where date<= ? ";
		}
		
		
		$qry .="order by `user`.`u_name`,`projectforcustomer`.`c_name`,`projectforcustomer`.`p_name` " ;
		#echo $qry;
		$stmt=$this->conn->prepare($qry);
		
		if(strlen($timeFrom) > 1){
			if (strlen($timeTo) > 1){
				$stmt->bind_param( "ss",$timeFrom,$timeTo );
			}else{
				$stmt->bind_param( "s",$timeFrom);
			}
		}else if (strlen($timeTo) > 1){
				$stmt->bind_param( "s",$timeTo ); 
		}
		
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		return $result;
	}
	function filterSummaryByProjects($projects,$timeFrom, $timeTo)
	{
		if (strlen($projects) ==0){
			return;
		}
		$qry= 
		"select `user`.`u_name` AS `username`,`projectforcustomer`.`c_name` AS `customer`,
		`projectforcustomer`.`p_name` AS `project`,`workload`.`minute` AS `minute` ,`workload`.`date` AS `date` 
		from (
		(`workload` join `user` on((`workload`.`user_id` = `user`.`u_id`)))
		join (
		  select `project`.`p_id` AS `p_id`,`project`.`p_name` AS `p_name`,`customer`.`c_id` ,`customer`.`c_name` AS `c_name` 
		  from (`project` join `customer` on((`project`.`customer_id` = `customer`.`c_id`)))
		  )as `projectforcustomer` on((`workload`.`project_id` = `projectforcustomer`.`p_id`))
		) 
		where p_id in (" .$projects. ") " ;
		if (strlen($timeFrom) > 1){
            $qry .=" and date>= ? ";
		}
		if (strlen($timeTo) > 1){
            $qry .=" and date<= ? ";
		}
		
		$qry .="order by `user`.`u_name`,`projectforcustomer`.`c_name`,`projectforcustomer`.`p_name` " ;
		#echo $qry;
		$stmt=$this->conn->prepare($qry);
		
		if(strlen($timeFrom) > 1){
			if (strlen($timeTo) > 1){
				$stmt->bind_param( "ss",$timeFrom,$timeTo );
			}else{
				$stmt->bind_param( "s",$timeFrom);
			}
		}else if (strlen($timeTo) > 1){
				$stmt->bind_param( "s",$timeTo ); 
		}
		
		$stmt->execute();
		$result = $stmt->get_result();	
		$stmt->close();
		return $result;
	}
	function filterSummaryByCustomers($customers,$timeFrom, $timeTo)
	{
		
		if (strlen($customers) ==0){
			return;
		}
		$qry= 
		"select `user`.`u_name` AS `username`,`projectforcustomer`.`c_name` AS `customer`,
		`projectforcustomer`.`p_name` AS `project`,`workload`.`minute` AS `minute` ,`workload`.`date` AS `date` 
		from (
		(`workload` join `user` on((`workload`.`user_id` = `user`.`u_id`)))
		join (
		  select `project`.`p_id` AS `p_id`,`project`.`p_name` AS `p_name`,`customer`.`c_id` ,`customer`.`c_name` AS `c_name` 
		  from (`project` join `customer` on((`project`.`customer_id` = `customer`.`c_id`)))
		  )as `projectforcustomer` on((`workload`.`project_id` = `projectforcustomer`.`p_id`))
		) 
		where c_id in (" .$customers. ") " ;
		if (strlen($timeFrom) > 1){
            $qry .=" and date>= ? ";
		}
		if (strlen($timeTo) > 1){
            $qry .=" and date<= ? ";
		}
		
		$qry .="order by `user`.`u_name`,`projectforcustomer`.`c_name`,`projectforcustomer`.`p_name` " ;
		
		$stmt=$this->conn->prepare($qry);
		
		if(strlen($timeFrom) > 1){
			if (strlen($timeTo) > 1){
				$stmt->bind_param( "ss",$timeFrom,$timeTo );
			}else{
				$stmt->bind_param( "s",$timeFrom);
			}
		}else if (strlen($timeTo) > 1){
				$stmt->bind_param( "s",$timeTo ); 
		}
		
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		return $result;
		
	}

	function getTotal()
	{
		$qry= 
		"select `user`.`u_name` AS `username`,`projectforcustomer`.`c_name` AS `customer`,
		projectforcustomer.fee * workload.minute /60.0  AS wage,fee,
		`projectforcustomer`.`p_name` AS `project`,`workload`.`minute` AS `minute` ,`workload`.`date` AS `date` 
		from (
		(`workload` join `user` on((`workload`.`user_id` = `user`.`u_id`)))
		join (
		  select `project`.`p_id` AS `p_id`,`project`.`p_name` AS `p_name`,`project`.`fee`,`customer`.`c_name` AS `c_name` 
		  from (`project` join `customer` on((`project`.`customer_id` = `customer`.`c_id`)))
		  )as `projectforcustomer` on((`workload`.`project_id` = `projectforcustomer`.`p_id`))
		) 
		order by `user`.`u_name`,`projectforcustomer`.`c_name`,`projectforcustomer`.`p_name` " ;
		
		$res = $this->conn->query($qry);
        return $res;		
	}
	function saveWork($user,$project,$hour,$minute,$date)
    {
		$stmt=$this->conn->prepare( "INSERT INTO workload(project_id, user_id, minute,date) VALUES ( ? , ? , ? , ? )");
		$totalMin=(intval($hour)*60+intval($minute));
		$stmt->bind_param( "iiis", $project,$user,$totalMin, $date );
		$res=$stmt->execute();
		$stmt->close();
        return $res;
	}
	function saveProject($project, $description, $customer_id, $fee)
    {
		$stmt=$this->conn->prepare("INSERT INTO `project`(`p_name`, `p_description`,`customer_id`,`fee`) 
		VALUES ( ? , ? , ? , ? )");
		$stmt->bind_param( "ssid", $project,$description,$customer_id, $fee );
		$res=$stmt->execute();	
		$res =$stmt->get_result();
		$stmt->close();
        return $res;
	}
	function saveCustomer($customer,$description)
    {	
		$stmt=$this->conn->prepare("INSERT INTO `customer`(`c_name`, `c_description`) VALUES ( ? , ? )") ;
		$stmt->bind_param( "ss", $customer,$description);
		$res=$stmt->execute();
		$res =$stmt->get_result();
		$stmt->close();
        return $res;
	}
}
?>