<?php require 'top.php'; ?>

<?php
// implementing this requires composer, which requires linux command line access
// not possible with godaddy shared hosting, will need to wait until transferred to AWS
// for now just download through FTP
//require 'vendor/autoload.php';
//use Mailgun\Mailgun;


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

// returns an array of file name (minus ext) and extension
function get_file_components ($filename)
{
	$returnArray = array();
	$filename = strtolower($filename) ;
	$components = split("[/\\.]", $filename) ;
	$n = count($components)-1;
	$returnArray['ext'] = $components[$n];
	$returnArray['file_name'] = $components[$n-1];
	
	return $returnArray;
}


// define variables and set to empty values

$email_to = "bittids@gmail.com";
$email_subject = "Manuscript submission from Red Butterfly Books";

$nameErr = $emailErr = $telephoneErr = $fileUploadErr= "";
$bool_problem = false;
$bool_get = true;
$upload_folder = "uploads/";
// this is where the file with the timestamp goes
$upload_folder_perm = "uploads_perm/";
$name = $email_from = $comment = $telephone = $title = "";

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
    $telephoneErr = "Please enter a telephone number"; 
      $bool_problem = true;
  } 
  	else 
  {
    $telephone = test_input($_POST["telephone"]);
      // return only numbers
    $telephone = preg_replace("([^0-9]+)", "", $telephone);
    $numberOfDigits = strlen($telephone);
    if (!$numberOfDigits == 10) 
    {
      $telephoneErr = "Please enter a 10 digit telephone number including area code"; 
      $bool_problem = true;
    }    
  }

  if (empty($_POST["title"])) 
  {
    $title = "";
  } 
  	else 
  {
    $title = test_input($_POST["title"]);
  }
  

  if (empty($_FILES["uploaded_file"]["tmp_name"]))
  {
  	$fileUploadErr = "File upload is required";
  	$bool_problem = true;
  }
  else
  {
  	//Get the uploaded file information
  	$name_of_uploaded_file = basename($_FILES['uploaded_file']['name']);
//  	$ext_of_uploaded_file = $file_name_components['ext'];
	//echo "line 105, name of uploaded file = $name_of_uploaded_file";
  	//get the file extension of the file
  	$type_of_uploaded_file =substr($name_of_uploaded_file,
  							strrpos($name_of_uploaded_file, '.') + 1);
  	$size_of_uploaded_file = $_FILES["uploaded_file"]["size"]/1024;//size in KBs

  	//Settings
  	$max_allowed_file_size = 25; // size in KB
  	$allowed_extensions = array("docx", "doc", "rtf", "txt");
  	//Validations
  	if($size_of_uploaded_file > $max_allowed_file_size )
  	{
  		$fileUploadErr .= "\n Size of file should be less than $max_allowed_file_size";
      	$bool_problem = true;
  	}
  	else 
  	{
  		//------ Validate the file extension -----
  		$allowed_ext = false;
  		for($i=0; $i<sizeof($allowed_extensions); $i++)
  		{
  			if(strcasecmp($allowed_extensions[$i],$type_of_uploaded_file) == 0)
  			{
  				$allowed_ext = true;
  			}
  		}
  		if(!$allowed_ext)
  		{
  			$fileUploadErr .= "\n The uploaded file is not supported file type. ".
 			" Only the following file types are supported: ".implode(',',$allowed_extensions);
      		$bool_problem = true;
  		}
		else 
		{
			$file_name_components = get_file_components($name_of_uploaded_file);
//echo "line 155<br />";
//echo "<pre>";
//print_r($file_name_components);
//echo "</pre>";
			//copy the temp. uploaded file to uploads folder
  			$tmp_path = $_FILES["uploaded_file"]["tmp_name"];
  			if(!is_uploaded_file($tmp_path))
  			{
  				$fileUploadErr .= 'uploaded file is not a file';
  				$bool_problem = true;
  			}
  			else 
  			{
  				$path_of_uploaded_file = $upload_folder . $file_name_components['file_name'].".".$file_name_components['ext'];
//echo "line 171, path_of_uploaded_file = $path_of_uploaded_file<br />";
  				if (!copy($tmp_path,$path_of_uploaded_file))
  				{
  					$fileUploadErr .= 'error while copying the uploaded file to temp directory';
  					$bool_problem = true;
  				}
  				else
  				{
  					$path_of_uploaded_file_perm = $upload_folder_perm . $file_name_components['file_name'].date("y.m.d_G.i.s").".".$file_name_components['ext'];  				
//echo "line 180, path_of_uploaded_file_perm = $path_of_uploaded_file_perm<br />";
  					if(!move_uploaded_file($tmp_path,$path_of_uploaded_file_perm))
  					{
  						$fileUploadErr .= 'error while moving the uploaded file to perm directory';
      					$bool_problem = true;
  					}
  					else
  					{
  						
 						if (!$bool_problem)
  						{
//  				echo "send_manuscript, line 196<br>";			
  							$email_message = "Form details below.\n\n";
  							$email_message .= "Name: ".clean_string($name)."\n";
  							$email_message .= "Email: ".clean_string($email_from)."\n";
  							$email_message .= "Telephone: ".clean_string($telephone)."\n";
  							$email_message .= "Title: ".clean_string($title)."\n";
  						
  							// create email headers
  							$headers = 'From: '.$email_from."\r\n".
  									'Reply-To: '.$email_from."\r\n" .
  									'X-Mailer: PHP/' . phpversion();
  							@mail($email_to, $email_subject, $email_message, $headers);
  						} // end if not bool_proglem
  					// mailgun API to send mail with attachemtn
// see comments at top of this file,
// this is the mailgun code to implement sending with attachment
  /* 					
  						# Include the Autoloader (see "Libraries" for install instructions)
  						
  						# Instantiate the client.
  							$mgClient = new Mailgun('YOUR_API_KEY');
  							$domain = "redbutterflybooks.com";
  						
  						# Make the call to the client.
  							$result = $mgClient->sendMessage($domain, array(
  							'from'    => 'messages@redbutterflybooks.com',
  							'to'      => 'bittids@yahoo.com',
//  						'cc'      => 'baz@example.com',
//  						'bcc'     => 'bar@example.com',
  							'subject' => 'manuscript from contact page',
  							'text'    => 'Testing some Mailgun awesomness!',
  							'html'    => '<html>HTML version of the body</html>'
  						), array(
  						'attachment' => array('/path/to/file.txt', '/path/to/file.txt')
  						));
 */ 						
  						
  					} // end else, if file moved OK
  				} // end else, if uploaded file is not copied ok
  			} // end else, if uploaded file is not a file
		}  // end else, if extension is OK
  	} // end else, if size of uploaded file si OK  		 
  } // end file uploaded file not empty
//  echo "line 161 reached<br />";
  
  // if form fields present and validated, send email
	if (!$bool_problem)
	{
//		echo "send_manuscript, line 244<br>";
		
  		$email_message = "Form details below.\n\n";
  		$email_message .= "Name: ".clean_string($name)."\n";
  		$email_message .= "Email: ".clean_string($email_from)."\n";
  		$email_message .= "Telephone: ".clean_string($telephone)."\n";
  		$email_message .= "Title: ".clean_string($title)."\n";
  
 	 	// create email headers
  		$headers = 'From: '.$email_from."\r\n".
  			'Reply-To: '.$email_from."\r\n" .
  			'X-Mailer: PHP/' . phpversion();
  		@mail($email_to, $email_subject, $email_message, $headers);
  	?>
  Thank you for sending us your manuscript. We will be in touch with you very soon.
 
	<?php		
	}  // end if not $bool_problem   
}  // end if ($_SERVER["REQUEST_METHOD"] == "POST") 

if ($bool_get || $bool_problem)
{
	// display contact form
?>

<h2>Upload your manuscript</h2>
<p><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">  
 
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
		<div class="rTableCell"><span class="error">* <?php echo $telephoneErr;?></span></div>
	</div><!-- end row -->
	
	<div class="rTableRow"><!-- start row -->
		<div class="rTableCell">Book title:<br /><br /></div>
		<div class="rTableCellFormInput"><input type="text" name="title" value="<?php echo $title;?>"></div>
		<div class="rTableCell">&nbsp;</div>
	</div><!-- end row -->
	
	<div class="rTableRow"><!-- start row -->
		<div class="rTableCell">Select a file to upload:<br /><br /></div>
		<div class="rTableCellFormInput"><input type="file" name="uploaded_file"></div>
		<div class="rTableCell"><span class="error">* <?php echo $fileUploadErr;?></span></div>
	</div><!-- end row -->

	<div class="rTableRow"><!-- start row -->
		<div class="rTableCell">&nbsp;</div>
		<div class="rTableCell">
  			<input type="submit" name="submit" value="Submit"></div>
		<div class="rTableCell">&nbsp;</div>
	</div><!-- end row -->	
</div><!-- end table -->

	
	<!-- 
<div class="rTable">
<div class="rTableRow">
<div class="rTableCell">asdfasdf asdf</div>
<div class="rTableCell">asdfasdf asdf  asdf </div>
<div class="rTableCell">&nbsp;</div>
</div><!-- end row -->

<!--
<div class="rTableRow"><!-- start row -->
<!--<div class="rTableCell">Name:<br /><br /><div>
<div class="rTableCell"><input type="text" name="name" value="<?php echo $name;?>"></div>
<div class="rTableCell"><span class="error">* <?php echo $nameErr;?></span></div>
</div><!-- end row -->
<!--
<div class="rTableRow"><!-- start row -->
<!--<div class="rTableCell">E-mail:<br /><br /><div>
<div class="rTableCell"><input type="text" name="email" value="<?php echo $email_from;?>"></div>
<div class="rTableCell"><span class="error">* <?php echo $emailErr;?></span></div>
</div><!-- end row -->
<!--
<div class="rTableRow"><!-- start row -->
<!--<div class="rTableCell"><div>
<div class="rTableCell"></div>
<div class="rTableCell"></div>
</div><!-- end row -->
<!--
<div class="rTableRow"><!-- start row -->
<!--<div class="rTableCell"><div>
<div class="rTableCell"></div>
<div class="rTableCell"></div>
</div><!-- end row -->
<!--
</div><!-- end table -->
<!-- 
Name: <input type="text" name="name" value="<?php echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br /><br />
  E-mail: <input type="text" name="email" value="<?php echo $email_from;?>">
  <span class="error">* <?php echo $emailErr;?></span>
  <br /><br />
  Telephone: <input type="text" name="telephone" value="<?php echo $telephone;?>">
  <span class="error"><?php echo $telephoneErr;?></span>
  <br /><br />
  Book title: <input type="text" name="title" value="<?php echo $title;?>">
  <br /><br />
  Select a file to upload: <input type="file" name="uploaded_file">
  <span class="error">* <?php echo $fileUploadErr;?></span>
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
