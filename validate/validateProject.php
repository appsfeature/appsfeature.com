<?php
//if( $_GET["name"] || $_GET["email"] || $_GET["contact"])
//{ 
//echo "Welcome: ". $_GET['name']. "<br />";
//echo "Your Email is: ". $_GET["email"]. "<br />";
//echo "Your Mobile No. is: ". $_GET["contact"];
//}

$array=array('project'=>'ParkingEye','statue'=>'active','message'=>'Error:001, Please contact to developer');
echo json_encode($array);

?>