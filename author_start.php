<?php require 'top.php'; ?>

<?php


function clean_string($string) 
{
	$bad = array("content-type","bcc:","to:","cc:","href");
	return str_replace($bad,"",$string);
}


function test_input($data) 
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

// define variables and set to empty values

$email_to = "bittids@gmail.com";
$email_subject = "Contact from Red Butterfly Books";

$emailErr = $passwordErr = $loginErr = "";
$bool_problem = false;
$bool_get = true;
$bool_try_login = true;
$email = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	$bool_get = false;
  if (empty($_POST["email"])) 
  {
    $emailErr = "Email is required";
    $bool_problem = true;
    $bool_try_login = false;
  } 
  	else 
  {
    $email = test_input($_POST["email"]);
    // check if name only contains letters and whitespace
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
      $emailErr = "Invalid email format"; 
      $bool_problem = true;
      $bool_try_login = false;
    }
  }
      
  if (empty($_POST["password"])) 
  {
    $passwordErr = "Password is required";
    $bool_problem = true;
    $bool_try_login = false;
    $password = "";
  } 
  	else 
  {
    $password = test_input($_POST["password"]);
      // return only numbers
//    $telephone = preg_replace("([^0-9]+)", "", $telephone);
  }
  
  // the following is temp code, to simulate the actual app
  // always returns login error, as there are no authors
	$bool_problem = true;
	if ($bool_try_login)
	{
		$loginErr = "Either the user name or the password was incorrect";
	}
  // if form fields present and validated, send email
	if (!$bool_problem)
	{
//  		$email_message = "Form details below.\n\n";
//  		$email_message .= "Name: ".clean_string($name)."\n";
//  		$email_message .= "Email: ".clean_string($email_from)."\n";
//  		$email_message .= "Telephone: ".clean_string($telephone)."\n";
//  		$email_message .= "Comments: ".clean_string($comment)."\n";
 /* 
 	 	// create email headers
  		$headers = 'From: '.$email_from."\r\n".
  			'Reply-To: '.$email_from."\r\n" .
  			'X-Mailer: PHP/' . phpversion();
  		@mail($email_to, $email_subject, $email_message, $headers);
*/
  	?>
//  Thank you for contacting us. We will be in touch with you very soon.
 
	<?php		
	}  // end if not $bool_problem   
}  // end if ($_SERVER["REQUEST_METHOD"] == "POST") 

if ($bool_get || $bool_problem)
{
	// display contact form
?>

<h2>Login</h2>
<p><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
 
<div class="rTable">
<?php 
if ($loginErr != '')
{
?>
	<div class="rTableRow"><!-- start row -->
		<div class="rTableCell">&nbsp;<br><br>
		</div>
		<div class="rTableCell"><span class="error">* <?php echo $loginErr;?></span></div>
		<div class="rTableCell">&nbsp;
		</div>
	</div><!-- end row -->
<?php 
} // end if loginErr != ''
?>	

	<div class="rTableRow"><!-- start row -->
		<div class="rTableCell">User name (email):<br><br>
		</div>
		<div class="rTableCellFormInput"><input type="text" name="email" value="<?php echo $email;?>">
		</div>
		<div class="rTableCell"><span class="error">* <?php echo $emailErr;?></span>
		</div>
	</div><!-- end row -->
	
	 
	<div class="rTableRow"><!-- start row -->
		<div class="rTableCell">Password:<br><br>
		</div>
		<div class="rTableCellFormInput"><input type="text" name="password" value="<?php echo $password;?>">
		</div>
		<div class="rTableCell"><span class="error">* <?php echo $passwordErr;?></span>
		</div>
	</div><!-- end row -->
	

	<div class="rTableRow"><!-- start row -->
		<div class="rTableCell">&nbsp;</div>
		<div class="rTableCell"><br><br>
  <input type="submit" name="submit" value="Submit"></div>
		<div class="rTableCell">&nbsp;</div>
	</div><!-- end row -->	
</div><!-- end table -->
	
<!--  	
	<div class="rTableRow"><!-- start row -->
<!--		<div class="rTableCell"></div>
		<div class="rTableCellFormInput"></div>
		<div class="rTableCell"></div>
	</div><!-- end row -->

	<!--
<div class="rTableRow"><!-- start row -->
<!--<div class="rTableCell"></div>
<div class="rTableCellFormInput"></div>
<div class="rTableCell"></div>
</div><!-- end row -->
<!--
</div><!-- end table -->

<!--  
Name: <input type="text" name="name" value="<?php // echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  E-mail: <input type="text" name="email" value="<?php // echo $email_from;?>">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>
  Telephone: <input type="text" name="telephone" value="<?php // echo $telephone;?>">
  <span class="error"><?php echo $telephoneErr;?></span>
  <br><br>
  Comment: <textarea name="comment" rows="5" cols="40"><?php // echo $comment;?></textarea>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
  -->
</form>
<?php 
} // end if ($bool_get || $bool_problem)
?>

<?php
/*
echo "<h2>Your Input:</h2>";
echo $name;
echo "<br>";
echo $email_from;
echo "<br>";
echo $telephone;
echo "<br>";
echo $comment;
*/
?>
<?php include('bottom.php') ?>
