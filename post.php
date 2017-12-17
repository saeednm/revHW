<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    return;
}
$type= $_POST["type"];
require_once 'Data.php';
$d = new Data();

if($type=="work"){
	$user= 1; 
	$project= $_POST["project"];
	$hour= $_POST["hour"];
	$minute= $_POST["minute"];
	$date= $_POST["date"];
	$result = $d->saveWork($user,$project,$hour,$minute,$date);
} else if ($type=="project"){
	$project= $_POST["project"];
	$description= $_POST["description"];
	$customer= $_POST["customer"];
	$result = $d->saveProject($project,$description,$customer);
} else if ($type=="customer"){
	$customer= $_POST["customer"];
	$description= $_POST["description"];
	
	$result = $d->saveCustomer($customer,$description);
}
?>