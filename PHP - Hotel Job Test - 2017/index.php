<?php 
        session_start();
        if($_SESSION['username']=="") { 
            
       echo '<meta http-equiv="refresh" content="0; url=/Quore Test/signin.php" />';
        };
        include('header.php'); 

        include('functions.php');

        //Get Post Data:

        $error = Array(); //Error hash array
        $error['hotelname'] = 0;
        $error['brand'] = 0;
        $error['phone'] = 0;
     
        $error['url'] = 0;
        $error['fullservice'] = 0;
        //Check to see if submited:



        if($_POST['submit']=='Add region') {
             if(isset($_POST['region']) && ($_POST['region']!="")) {
                $region = mysql_real_escape_string ($_POST['region']);

                save_region($region);
                 unset($_POST['submit']);
                 unset($_POST['region']);
                 $error=Array(); 
             } else {
                    $error['region'] = 1;
             }
        } else if(isset($_POST['submit'])=='Add Property') {


            //check to see if everything is populated
            if(isset($_POST['hotelname']) && ($_POST['hotelname']!="") &&
                isset($_POST['brand']) && ($_POST['brand']!="") &&
                isset($_POST['phone1st']) && ($_POST['phone1st']!="") &&
                isset($_POST['phone2nd']) && ($_POST['phone2nd']!="") &&
                isset($_POST['phone3rd']) && ($_POST['phone3rd']!="") &&
                isset($_POST['url']) && ($_POST['url']!="") &&
                isset($_POST['fullservice']) && ($_POST['fullservice']!="") 
                ) { 

                    //Get the values for the hotel insert
                    $hotelname = mysql_real_escape_string ($_POST['hotelname']);
                    $brand = mysql_real_escape_string ($_POST['brand']);
                    $phone1 = mysql_real_escape_string ($_POST['phone1st']);
                    $phone2 = mysql_real_escape_string ($_POST['phone2nd']);
                    $phone3 = mysql_real_escape_string ($_POST['phone3rd']);
                    $url = mysql_real_escape_string ($_POST['url']);
                    $fullservice = mysql_real_escape_string ($_POST['fullservice']);

                  //  echo $hotelname . $brand . $phone1 . $phone2 . $phone3 . $url . $fullservice . "<br/>";
                    //Set all errors to none:
                    $error['hotelname'] = 0;
                    $error['brand'] = 0;
                    $error['phone'] = 0;
                 
                    $error['url'] = 0;
                    $error['fullservice'] = 0;

                    //check regular expression match for URL and phone number:

                    
                    $patternurl = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
                    $matchurl = preg_match($patternurl, $url);
                    //set url errors if any
                    if ($matchurl==1) { $error['url'] = 0; } else {$error['url'] = 1;}
                   
                 

                    $patternPhone1 = '/[0-9]{3}/'; 
                    $patternPhone2 = '/[0-9]{4}/'; 

                    //Set phone errors if any



                    $matchphone = (preg_match($patternPhone1, $phone1) && preg_match($patternPhone1, $phone2) && preg_match($patternPhone2, $phone3));
                    

                    if ($matchphone == 1) { $error['url'] = 0; } else {$error['url'] = 1;}

                    //final test to save, since we already know there's stuff in the other fields.
                    if (($matchphone == 1) && ($matchurl==1)) {
                        $phone = "$phone1-$phone2-$phone3"; 
                        save_property($hotelname,$brand,$phone,$url,$fullservice);
                         unset($_POST['submit']);
                         unset($_POST['hotelname']);
                         unset($_POST['brand']);
                         unset($_POST['phone1st']);
                         unset($_POST['phone2nd']);
                         unset($_POST['phone3rd']);
                         unset($_POST['url']); 
                         unset($_POST['fullservice']);
                         $error=Array(); 
                    }



            }
            //If a field is left unpopulated: 
            else {
                echo "Not filled";
                if (!isset($_POST['hotelname']) || $_POST['hotelname']=="")  {
                    $error['hotelname'] = 1;
                }
                 if (!isset($_POST['brand']) || $_POST['brand']=="") {
                    $error['brand'] = 1;
                }
                 if (!isset($_POST['phone1st']) || $_POST['phone1st']=="") {
                    $error['phone'] = 1;
                }
                  if (!isset($_POST['phone2nd']) || $_POST['phone2nd']=="") {
                    $error['phone'] = 1;
                }
                  if (!isset($_POST['phone3rd']) || $_POST['phone3rd']=="") {
                    $error['phone'] = 1;
                }
                 if (!isset($_POST['url']) || $_POST['url']=="") {
                    $error['url'] = 1;
                }
                 if (!isset($_POST['fullservice']) || $_POST['fullservice']=="") {
                    $error['fullservice'] = 1;
                }
            }

        }
          
         

        ?>

        <header class="header">
            <div class="container">
                <span class="logo">
                    <img src="images/Glyph_greenshadow.png"/>
                </span>
                <span class="name">User: <?php echo $_SESSION['username']; ?>  <a class="btn" href="logout.php">Logout</a></span>
            </div>
        </header>
        <div class="wrapper">
            <div class="container">
                <section>
                    <h1>Regions</h1>
                    <table class="table">
                        <thead class="thead">

                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                        <?php 
                            //Show the Properties
                            $ShowRegions = "SELECT * FROM Region";
                            $Regions = $db->query($ShowRegions);
                            while($Region = $Regions->fetch_array()) {
                                    ?>
                            <tr>
                                <td><?php echo $Region['Id']; ?> </td>
                                <td><?php echo $Region['name']; ?> </td>
                                <td><span class="btn btn__small">Options<span class="icon"><svg  viewBox="0 0 40 24" ><g id="Iconography" transform="translate(-740.000000, -998.000000)" fill="#000000"><path d="M749.343664,1022.42292 C745.269268,1026.56389 751.380863,1032.77535 755.455259,1028.63438 L770.734247,1013.10573 C772.421918,1011.39048 772.421918,1008.60952 770.734247,1006.89427 L755.455259,991.36562 C751.380863,987.224647 745.269268,993.436107 749.343664,997.57708 L761.566855,1010 L749.343664,1022.42292 Z" id="Carat_down" transform="translate(760.000000, 1010.000000) rotate(90.000000) translate(-760.000000, -1010.000000) "></path></g></svg></span><div class="dropdown">
                                <a href="delete_record.php?table=Reg&id=<?php echo $Region['Id'] ?>">Delete</a><a href="update_record.php?table=Prop&id=<?php echo $Region['Id'] ?>"Edit</a></div></span></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                     <form class="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                       
                        <div class="form__group">
                            <label>Name</label>
                            <input name="region" type="text"/>
                            <?php if ($error['region']==1) {echo "<div class='error'>Please fill in the region name.</div>";} ?>
                        </div>
                        <div class="form__group">

                            <input type="submit" name="submit" value="Add region"/>

                        </div>
                    </form>
                </section>
                <section>
                    <h1>Properties</h1>
                    <table class="table">
                        <thead class="thead">
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Phone</th>
                                <th>Url</th>
                                <th> Full Service? </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                        
                            <?php 
                            //Show the Properties
                            $ShowProperties = "SELECT * FROM Property";
                            $Properties = $db->query($ShowProperties);
                            while($Property = $Properties->fetch_array()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $Property['Id']; ?> </td>
                                        <td><?php echo $Property['Name']; ?></td>
                                        <td><?php echo $Property['brand']; ?></td>
                                        <td><?php echo $Property['phone']; ?></td>

                                        <td><?php echo $Property['URL']; ?>
                                        <td><?php if($Property['isFullService']==0) {echo "No";} else {echo "Yes";} ?></td>
                                        <td><span class="btn btn__small">Options<span class="icon"><svg  viewBox="0 0 40 24" ><g id="Iconography" transform="translate(-740.000000, -998.000000)" fill="#000000"><path d="M749.343664,1022.42292 C745.269268,1026.56389 751.380863,1032.77535 755.455259,1028.63438 L770.734247,1013.10573 C772.421918,1011.39048 772.421918,1008.60952 770.734247,1006.89427 L755.455259,991.36562 C751.380863,987.224647 745.269268,993.436107 749.343664,997.57708 L761.566855,1010 L749.343664,1022.42292 Z" id="Carat_down" transform="translate(760.000000, 1010.000000) rotate(90.000000) translate(-760.000000, -1010.000000) "></path></g></svg></span><div class="dropdown">
                                        <a href="delete_record.php?table=Prop&id=<?php echo $Property['Id'] ?>">Delete</a><a href="update_record.php?table=Prop&id=<?php echo $Property['Id'] ?>">Edit</a></div></span></td>
                                    </tr>
                                    <?php 
                            }?>
                           
                        </tbody>
                    </table>
                    <form class="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                       <!-- <div class="form__group">
                            <label>Id</label>
                            <input type="text"/>
                        </div> -->
                        <div class="form__group">
                            <label>Name</label>
                            <input name ="hotelname" type="text"/>
                            <?php if ($error['hotelname']==1) {echo "<div class='error'>Please fill in the hotel name.</div>";} ?>
                        </div>
                        <div class="form__group">
                            <label>Brand</label>
                            <input name="brand" type="text"/>
                            <?php if ($error['brand']==1) {echo "<div class='error'>Please fill in the brand name.</div>";} ?>
                        </div>
                        <div class="form__group">
                            <label>Phone number</label>
                            <input type="text" id="phone1" maxlength="3" name="phone1st">
                            <input type="text" id="phone2" maxlength="3" name="phone2nd">
                            <input type="text" id="phone3" maxlength="4" name="phone3rd">
                            <?php if ($error['phone']==1) {echo "<div class='error'>Please use the format provided.</div>";} ?>
                        </div>
                        <div class="form__group">
                            <label>Url</label>
                            <input name="url" type="text"/>
                            <?php if ($error['url']==1) {echo "<div class='error'>Please fill and use the url format provided.</div>";} ?>
                        </div>
                         <div class="form__group">
                            <label>Select Full / Select Service</label>
                            <select name="fullservice" type="text">
                              <option value="1">Full Service</option>
                              <option value="0">Select Service</option>
                            </select>
                            
                            <?php if ($error['fullservice']==1) {echo "<div class='error'>Please indicate full or selective service.</div>";} ?>
                        </div>
                        <div class="form__group">
                            <input type="submit" name="submit" value="Add Property"/>
                        </div>
                    </form>
                </section>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                &copy; copyright Quore Systems LLC, 2017
            </container>
        </footer>
    </body>
</html>