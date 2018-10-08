<?php 

include('functions.php');


if($_POST['submit']=='Edit Property') {

//Edit the property:
        $id= mysql_real_escape_string ($_POST['id']);
        $hotelname = mysql_real_escape_string ($_POST['hotelname']);
        $brand = mysql_real_escape_string ($_POST['brand']);
        $phone1 = mysql_real_escape_string ($_POST['phone1st']);
        $phone2 = mysql_real_escape_string ($_POST['phone2nd']);
        $phone3 = mysql_real_escape_string ($_POST['phone3rd']);
        $phone = "$phone1-$phone2-$phone3"; 
        $url = mysql_real_escape_string ($_POST['url']);
        $isfullservice = mysql_real_escape_string ($_POST['fullservice']);
        update_property($id,$hotelname,$brand,$phone,$url,$isfullservice);
   //send back to index page:
        header('Location: index.php');
        //echo '<meta http-equiv="refresh" content="0; url=index.php" />';

}


?>