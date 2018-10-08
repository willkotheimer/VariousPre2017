<?php


require_once('access.php');


if($_GET['table']=='Prop') {
	$id = mysql_real_escape_string ($_GET['id']);
	$DeleteStatement = "DELETE FROM Property WHERE Id=".$id; 
	$db->query($DeleteStatement);
	header('Location: index.php');
}

if($_GET['table']=='Reg') {
	$id = mysql_real_escape_string ($_GET['id']);
	$DeleteStatement = "DELETE FROM Region WHERE Id=".$id; 
	$db->query($DeleteStatement);
	header('Location: index.php');
}



?>