<?php

require_once('access.php');


function save_property($name,$brand,$phone,$url,$isFullService) {

global $db;


$query = "INSERT INTO Property (Name,brand,phone,URL,isFullService) VALUES ('$name','$brand','$phone','$url',$isFullService)";
$db->query($query);

}


function update_property($id,$name,$brand,$phone,$url,$isFullService) {

global $db;

$query = "UPDATE Property SET Name='$name' , brand='$brand', phone='phone', URL='$url', isFullService='$isFullService' WHERE Id=".$id;
$db->query($query);

}


function save_region($name) {

global $db;
$db->query("INSERT INTO Region (name) VALUES ('$name')");

}


function delete_property($id) {

global $db;
$db->query("DELETE FROM Property WHERE Id=$id");

}



?>