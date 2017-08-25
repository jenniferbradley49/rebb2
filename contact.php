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
$email_cc = "edit.dept.rbb@gmail.com";

$email_subject = "Contact from Red Butterfly Books";

$nameErr = $emailErr = $genderErr = $telephoneErr = "";
$bool_problem = false;
$bool_get = true;
$name = $email_from = $comment = $telephone = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	$bool_get = false;
  if (empty($_POST["name"])) 
  {
    $nameErr = "Name is required";
    $bool_problem = true;
  } 
  	else 
  {
    $name = test_input($_POST["name"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) 
    {
      $nameErr = "Only letters and white space allowed"; 
      $bool_problem = true;
    }
  }
  
  if (empty($_POST["email"])) 
  {
    $emailErr = "Email is required";
    $bool_problem = true;
  } 
  	else
  {
    $email_from = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email_from, FILTER_VALIDATE_EMAIL)) 
    {
      $emailErr = "Invalid email format"; 
      $bool_problem = true;
    }
  }
    
  if (empty($_POST["telephone"])) 
  {
    $telephone = "";
  } 
  	else 
  {
    $telephone = test_input($_POST["telephone"]);
      // return only numbers
    $telephone = preg_replace("([^0-9]+)", "", $telephone);
  }

  if (empty($_POST["comment"])) 
  {
    $comment = "";
  } 
  	else 
  {
    $comment = test_input($_POST["comment"]);
  }
  
  // if form fields present and validated, send email
	if (!$bool_problem)
	{
  		$email_message = "Form details below.\n\n";
  		$email_message .= "Name: ".clean_string($name)."\n";
  		$email_message .= "Email: ".clean_string($email_from)."\n";
  		$email_message .= "Telephone: ".clean_string($telephone)."\n";
  		$email_message .= "Comments: ".clean_string($comment)."\n";
  
 	 	// create email headers
  		$headers = 'From: '.$email_from."\r\n".
  			'Reply-To: '.$email_from."\r\n" .
  			"CC: ".$email_cc."\r\n".
  			'X-Mailer: PHP/' . phpversion();
  		@mail($email_to, $email_subject, $email_message, $headers);
  	?>
  Thank you for contacting us. We will be in touch with you very soon.
 
	<?php		
	}  // end if not $bool_problem   
}  // end if ($_SERVER["REQUEST_METHOD"] == "POST") 

if ($bool_get || $bool_problem)
{
	// display contact form
?>

<h2>Contact Us</h2>
<p><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
 
<div class="rTable">
	<div class="rTableRow"><!-- start row -->
		<div class="rTableCell">Name:<br /><br />
		</div>
		<div class="rTableCellFormInput"><input type="text" name="name" value="<?php echo $name;?>">
		</div>
		<div class="rTableCell"><span class="error">* <?php echo $nameErr;?></span>
		</div>
	</div><!-- end row -->
	
	 
	<div class="rTableRow"><!-- start row -->
		<div class="rTableCell">E-mail:<br /><br />
		</div>
		<div class="rTableCellFormInput"><input type="text" name="email" value="<?php echo $email_from;?>">
		</div>
		<div class="rTableCell"><span class="error">* <?php echo $emailErr;?></span>
		</div>
	</div><!-- end row -->
	
	<div class="rTableRow"><!-- start row -->
		<div class="rTableCell">Telephone:<br /><br /></div>
		<div class="rTableCellFormInput"><input type="text" name="telephone" value="<?php echo $telephone;?>"></div>
		<div class="rTableCell"><span class="error"><?php echo $telephoneErr;?></span></div>
	</div><!-- end row -->
	
	<div class="rTableRow"><!-- start row -->
		<div class="rTableCell">Comment:</div>
		<div class="rTableCell"><textarea name="comment" rows="5" cols="40"><?php echo $comment;?></textarea></div>
		<div class="rTableCell"></div>
	</div><!-- end row -->

	<div class="rTableRow"><!-- start row -->
		<div class="rTableCell">&nbsp;</div>
		<div class="rTableCell"><br /><br />
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
  <br /><br />
  E-mail: <input type="text" name="email" value="<?php // echo $email_from;?>">
  <span class="error">* <?php echo $emailErr;?></span>
  <br /><br />
  Telephone: <input type="text" name="telephone" value="<?php // echo $telephone;?>">
  <span class="error"><?php echo $telephoneErr;?></span>
  <br /><br />
  Comment: <textarea name="comment" rows="5" cols="40"><?php // echo $comment;?></textarea>
  <br /><br />
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
echo "<br />";
echo $email_from;
echo "<br />";
echo $telephone;
echo "<br />";
echo $comment;
*/
?>
<?php include('bottom.php') ?>
