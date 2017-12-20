<?php

class Data
{
    private  $serverAddr = "localhost";
    private  $username = "root";
    private  $password = "";
    private  $dbName = "reviso";
    private  $conn ;
	
    public function __construct()
    {
        $this->conn = new mysqli($this->serverAddr, $this->username, $this->password, $this->dbName);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    function getProjects($user_id){
        $qry = "select * from `project` left join `customer` on(`project`.`customer_id` = `customer`.`c_id`) 
		        where project.user_id = ? and customer.user_id = ? " ; 
		$stmt=$this->conn->prepare($qry);
		$stmt->bind_param( "ii", $user_id,$user_id);
		$stmt->execute();
		$res =$stmt->get_result();
		$stmt->close();
        return $res;
    }
	function deleteProjects($user_id){
        $qry = "DELETE FROM `project` where project.user_id = ?  ";    
        $stmt=$this->conn->prepare($qry);
		$stmt->bind_param( "i", $user_id);
		$stmt->execute();
		$res =$stmt->get_result();
		$stmt->close();
        return $res;
    }
	function getCustomers($user_id){
        $qry = "select * from customer where customer.user_id = ?  ";      
        $stmt=$this->conn->prepare($qry);
		$stmt->bind_param( "i", $user_id);
		$stmt->execute();
		$res =$stmt->get_result();
		$stmt->close();
        return $res;
    }
	function deleteCustomers($user_id){
        $qry = "DELETE FROM customer where customer.user_id = ?  ";     
        $stmt=$this->conn->prepare($qry);
		$stmt->bind_param( "i", $user_id);
		$stmt->execute();
		$res =$stmt->get_result();
		$stmt->close();
        return $res;
    }
	
    function getSummary($user_id)
    {
		$qry="
		select `project`.`p_name`,
		sum(`workload`.`minute`) AS `minute`
		from (`workload` join `project` on((`workload`.`project_id` = `project`.`p_id`))) 
		WHERE workload.user_id = ? 
		group by `project_id` order by `project_id` " ;     
        $stmt=$this->conn->prepare($qry);
		$stmt->bind_param( "i", $user_id);
		$stmt->execute();
		$res =$stmt->get_result();
		$stmt->close();
        return $res;
    }
	
	function filterSummaryByTime($timeFrom, $timeTo)
	{
		$qry= 
		"select `projectforcustomer`.`c_name` AS `customer`,
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
		"select `projectforcustomer`.`c_name` AS `customer`,
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
		"select `projectforcustomer`.`c_name` AS `customer`,
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

	function getTotal($user_id)
	{
		$qry= 
		"select `projectforcustomer`.`c_name` AS `customer`,
		projectforcustomer.fee * workload.minute /60.0  AS wage,fee,
		`projectforcustomer`.`p_name` AS `project`,`workload`.`minute` AS `minute` ,`workload`.`date` AS `date` 
		from ( `workload` join (
		  select `project`.`p_id` AS `p_id`,`project`.`p_name` AS `p_name`,`project`.`fee`,`customer`.`c_name` AS `c_name` 
		  from (`project` join `customer` on((`project`.`customer_id` = `customer`.`c_id`)))
		  WHERE project.user_id= ? and customer.user_id= ?
		  )as `projectforcustomer` on((`workload`.`project_id` = `projectforcustomer`.`p_id`))
		)
		WHERE workload.user_id= ?
		order by `projectforcustomer`.`c_name`,`projectforcustomer`.`p_name` " ;
		
		$stmt=$this->conn->prepare($qry);
		$stmt->bind_param( "iii", $user_id,$user_id,$user_id );
		$stmt->execute();	
		$res =$stmt->get_result();
		$stmt->close();
        return $res;
			
	}
	function saveWork($user,$project,$hour,$minute,$date)
    {
		$qry="INSERT INTO workload( user_id, project_id, minute,date) VALUES ( ? , ? , ? , ? )";
		$stmt=$this->conn->prepare($qry);
		$totalMin=(intval($hour)*60+intval($minute));
		$stmt->bind_param( "iiis", $user,$project,$totalMin, $date );
		$stmt->execute();	
		$res =$stmt->get_result();
		$stmt->close();
        return $res;
	}
	function deleteWorks($user_id){
		$stmt=$this->conn->prepare( "DELETE FROM workload WHERE user_id= ? ");
		$stmt->bind_param( "i", $user_id);
		$stmt->execute();	
		$res =$stmt->get_result();
		$stmt->close();
        return $res;
	}
	function saveProject($project, $description, $customer_id, $fee, $user_id)
    {
		$stmt=$this->conn->prepare("INSERT INTO `project`(`p_name`, `p_description`,`customer_id`,`fee`,user_id) 
		VALUES ( ? , ? , ? , ? , ? )");
		$stmt->bind_param( "ssidi", $project,$description,$customer_id, $fee ,$user_id);
		$stmt->execute();	
		$res =$stmt->get_result();
		$stmt->close();
        return $res;
	}
	function saveCustomer($customer,$description,$user_id)
    {	
		$stmt=$this->conn->prepare("INSERT INTO `customer`(`c_name`, `c_description`,`user_id`) VALUES ( ? , ? , ?)") ;
		$stmt->bind_param( "ssi", $customer,$description,$user_id);
		$stmt->execute();
		$res =$stmt->get_result();
		$stmt->close();
        return $res;
	}
	function addUser($user_id, $username)
    {	
		$stmt=$this->conn->prepare("INSERT INTO `user`(`u_id`, `u_name`) VALUES ( ? , ? )") ;
		$stmt->bind_param( "is", $user_id,$username);
		$stmt->execute();
		$res =$stmt->get_result();
		$stmt->close();
        return $res;
	}
	function getUser($user_id)
    {	
		$stmt=$this->conn->prepare("SELECT * from`user` WHERE `u_id`= ? ") ;
		$stmt->bind_param( "i", $user_id);
		$stmt->execute();
		$res =$stmt->get_result();
		$stmt->close();
        return $res;
	}
}
?>