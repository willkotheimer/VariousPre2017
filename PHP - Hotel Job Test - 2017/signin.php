<?php
session_start(); 
include('header.php');
include('access.php'); 

$error = Array();
$error['userpasswrong']=1;

//ini_set('display_errors', 1); 
unset($password);
unset($username);
$password = mysql_real_escape_string ($_POST['password']);
$username = mysql_real_escape_string ($_POST['username']);

if(isset($_POST['submit'])) {

		
		
		$User = $db->query("SELECT * FROM users WHERE username='$username' AND password='$password'"); 
		

		$row = $User->fetch_array();

		if(!empty($row['username']) AND !empty($row['password'])) { 

			$_SESSION['username'] = $row['username']; 
		//header('Location: index.php');
		echo '<meta http-equiv="refresh" content="0; url=/Quore Test/index.php" />';

		//echo $_SESSION['username'];
		} else { $error['userpasswrong'] = 0; } 


} 
 






?>

		<body>

				<section class="signin-position">

				<table class="table">
					<form class="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                      
                        
                        <h1>
                        
               			     Sign in to Quore
                		

                        </h1>

                        <div class="signin_form">
                            <label>Username</label>
                            <input name ="username" type="text" />
                           
                        </div>
                        <div class="signin_form">
                            <label>Password</label>
                            <input name="password" type="text" />
                        <?php if ($error['userpasswrong']==0) {echo "<div class='error'>Either username or password is incorrect.</div>";} ?>
                      
                        <div class="form__group top-padding">
                            <input type="submit" name="submit" value="Signin"/>
                        </div>
                    </form>

                    </table>
                </section>

        </body>



