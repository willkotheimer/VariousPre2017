<?php 
session_start();
        if($_SESSION['username']=="") {   
        echo '<meta http-equiv="refresh" content="0; url=/Quore Test/signin.php" />';
        };
include('header.php'); 


?>


 <header class="header">
            <div class="container">
                <span class="logo">
                    <img src="images/Glyph_greenshadow.png"/>
                </span>
                <span class="name">User: <?php echo $_SESSION['username']; ?></span>
            </div>
</header>
    <div class="wrapper">
            <div class="container">

<?php
       


if($_GET['table']=='Prop') {
  
        $id = mysql_real_escape_string ($_GET['id']);
        $ShowProperties = "SELECT * FROM Property WHERE Id=".$id;
        $Property = $db->query($ShowProperties)->fetch_array();

        //Get the values for the hotel update

        $id =  $Property['Id']; 
        $Name = $Property['Name']; 
        $brand = $Property['brand'];
        list($phone1,$phone2,$phone3) = explode("-",$Property['phone']);
        $url = $Property['URL']; 
        $isFullService = $Property['isFullService'];


?>
<section>
    <form class="form" method="post" action="update_save.php">
                     
                        <input name ="id" type="hidden" value="<?php echo $id; ?>"/>
                        

                        <div class="form__group">
                            <label>Name</label>
                            <input name ="hotelname" type="text" value="<?php echo $Name; ?>"/>
                          
                        </div>
                        <div class="form__group">
                            <label>Brand</label>
                            <input name="brand" type="text" value="<?php echo $brand; ?>"/>
                       
                        </div>
                        <div class="form__group">
                            <label>Phone number</label>
                            <input type="text" id="phone1" maxlength="3" name="phone1st" value="<?php echo $phone1; ?>">
                            <input type="text" id="phone2" maxlength="3" name="phone2nd" value="<?php  echo $phone2; ?>">
                            <input type="text" id="phone3" maxlength="4" name="phone3rd" value="<?php  echo $phone3; ?>">
                       
                        </div>
                        <div class="form__group">
                            <label>Url</label>
                            <input name="url" value="<?php echo $url ?>" type="text"/>
                        
                        </div>
                         <div class="form__group">
                            <label>Select Full / Select Service</label>
                            <select name="fullservice" type="text">
                              <option value="1" <?php if($isFullService==1) { echo 'selected'; } ?>>Full Service</option>
                              <option value="0" <?php if($isFullService==0) { echo 'selected'; } ?>>Select Service</option>
                            </select>
                            
                          
                        </div>
                        <div class="form__group">
                            <input type="submit" name="submit" value="Edit Property"/>
                        </div>
    </form>
</section>

<?php

 } 

?>

                    
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                &copy; copyright Quore Systems LLC, 2017
            </container>
        </footer>
    </body>
</html>