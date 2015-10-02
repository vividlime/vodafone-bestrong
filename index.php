<?php
	
	require_once('config.php');

	$invalidschoolName = null;
	$invalidpostCode = null;
	$invalidemailAddress = null;
	$detailsFilledIn = false;
	$valid = true;
	
	//check that its the post
	if(isset($_POST['submit']))
	{	
	     $schoolName = cleanInput($_POST['school']);
	     $postCode = cleanInput($_POST['postcode']);
		 $emailAddress = cleanInput($_POST['email']);
		
	
		//check that the fields have been filled in
		if (empty($schoolName)) {
			$invalidschoolName = 'Invalid';
			$valid = false;
		}
		
		if (empty($postCode)) {
			$invalidpostCode = 'Invalid';
			$valid = false;
		}
		
		if (empty($emailAddress)) {
			$invalidemailAddress = 'Invalid';
			$valid = false;
		}

		if($valid)
		{
			//add them to the database
			$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
			
			if (!$con)
			{
				die('Could not connect: ' . mysql_error());
			}
				 
			$query = "INSERT INTO People (SchoolName,Postcode,Email) VALUES ('$schoolName', '$postCode', '$emailAddress');";
			
			mysqli_query($con, $query);
			mysqli_close($con);			
			$detailsFilledIn = true;
		}
	}
	
	function cleanInput($input) {
 
			  $search = array(
			    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
			    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
			    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
			    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
			  );
			 
			    $output = preg_replace($search, '', $input);
			    return $output;
  	}	

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vodafone | Be Strong Online</title>

    <link href="../build/processed/styles/css/screen.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon">
  </head>
  <body>
    <div class="container wrapper">
    <section>
      <div class="container header">
        <div class="row">
          <div class="col-sm-12">
            <h1 class="heading">Be Strong Online</h1>
            <p>Vodafone and The Diana Award’s Anti-Bullying Campaign have developed Be Strong Online for schools. The 10 modules in this peer-to-peer learning programme help young people to explore the digital world safely.</p>
          </div>
        </div>
      </div>
    </section>

    <section>
      <div class="container-fluid">
        <div class="row">
		<?php
	if(empty($detailsFilledIn)): ?>
		<form action="#" method="POST">
          <div class="col-sm-7 form-box">
            <p class="box-text">Please enter your details below to download the first module. You'll be notified when future modules become available.</p>
            <div class="input-group">
            <input name="school" id="school" type="text" class="form-control" placeholder="School name" aria-label="School">
			 <?php if (!empty($invalidschoolName)): ?>
					 <small class="error">Invalid entry</small><br/>
                <?php endif;?>
          </div>

          <div class="input-group">
            <input name="postcode" id="postcode" type="text" class="form-control" placeholder="Postcode" aria-label="Postcode">
			 <?php if (!empty($invalidpostCode)): ?>
					 <small class="error">Invalid entry</small><br/>
                <?php endif;?>
          </div>

          <div class="input-group">
            <input name="email" id="email"  type="text" class="form-control" placeholder="Email address" aria-label="Email address">
			 <?php if (!empty($invalidemailAddress)): ?>
					 <small class="error">Invalid entry</small><br/>
                <?php endif;?>
          </div>
		  <input type="submit" class="btn btn-primary" role="button" name="submit" value="Download" id ="submit"></input><i class="icon-download2 form-icon"></i>
            <!-- <a class="btn btn-primary" href="#" role="button"><i class="icon-download2"></i>Download</a> -->
        </div>
		</form>
<?php endif;?>
	<?php  if($detailsFilledIn): ?>
			
			<p>1.  <a href="http://www.antibullyingpro.com/s/Be-Strong-Online-Staff-Guide.pdf" target="_blank" >Click here </a> to download our "Be Strong Online" guide for staff.</p>
<p>2.  <a href="http://www.antibullyingpro.com/s/Be-Strong-Online-Lesson-Plan-for-Tech-Trainers-to-use.pdf" target="_blank">Click here </a> to download our "Be Strong Online" lesson plan for students.</p>
<p>3.  <a href="http://www.antibullyingpro.com/s/Be-Strong-Online-Student-Parent-Info-Sheet.pdf" target="_blank">Click here </a> to download our "Be Strong Online" student/<br/> parent information sheet.</p>

    <?php endif;?>
      </div>
    </div>
  </section>
</div>

    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>
</html>