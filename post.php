<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    return;
}
$type= $_POST["type"];
require_once 'Data.php';
$d = new Data();
$userID=1; 
if($type=="filter"){
	
	
	$filterType= $_POST["filterType"];
	$fromDate= $_POST["fromDate"];
	$toDate= $_POST["toDate"];
	
	
	if($filterType=="Projects"){
		$projects= $_POST["projects"];
		$result=$d->filterSummaryByProjects($projects,$fromDate, $toDate);
	}else if ($filterType=="Customers"){
		$customers= $_POST["customers"];
		$result=$d->filterSummaryByCustomers($customers,$fromDate, $toDate);
		
	}else{
		
			$result=$d->filterSummaryByTime($fromDate, $toDate);
		
	}
	echo "
	<table border='2'> 
					<tr>
						<th> customer </th>
						<th> project </th>
						<th> date </th>
						<th> minute(s) </th>
					</tr>
					";
            if($result){    
                while ($row = $result->fetch_assoc()) {
					echo "<tr>";
                    echo "<td> " . $row["customer"] . "</td>";
					echo "<td> " . $row["project"] . "</td>";
					echo "<td> " . $row["date"] . "</td>";
					echo "<td> " . $row["minute"] . "</td>";
					echo "</tr>";
                }
			}  
			echo " </table>";
	
	
	
	#$result = $d->saveWork($user,$project,$hour,$minute,$date);
} else if($type=="work"){
	
	$project= $_POST["project"];
	$hour= $_POST["hour"];
	$minute= $_POST["minute"];
	$date= $_POST["date"];
	$result = $d->saveWork($userID,$project,$hour,$minute,$date);
} else if ($type=="project"){
	$project= $_POST["project"];
	$description= $_POST["description"];
	$customer= $_POST["customer"];
	$fee= $_POST["fee"];
	$result = $d->saveProject($project,$description,$customer,$fee,$userID);
} else if ($type=="customer"){
	$customer= $_POST["customer"];
	$description= $_POST["description"];
	
	$result = $d->saveCustomer($customer,$description,$userID);
}
?>