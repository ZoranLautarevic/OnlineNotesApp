<!--This file receives the user_id and key generated to create the new password-->
<!--This file displays a form to input new password-->
<?php
session_start();
include("connection.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Password Reset</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        h1 {
            color: #ffd24d;
        }
        
        body {
            background-image: url(images/picture.jpg);
            /* background-color: #85e085; */
        }
        
        .contactform {
            /* background-image: url(images/picture.jpg); */
            /* background-color: #ffd24d; */
            border: 1px solid #7c73f6;
            margin-top: 50px;
            border-radius: 15px;
        }

        .btn {
            margin-bottom: 10px;
        }
    </style>
  </head>
  <body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-offset-1 col-sm-10 contactform">
                <h1>Reset Password:</h1>
                <div id="resultmessage"></div>
<?php
//If user_id or key is missing
if(!isset($_GET["user_id"]) || !isset($_GET["key"])) {
    echo "<div class='alert alert-danger'>There was an error. Please click on the link you received by email.</div>";
    exit;
}
//else
//Store them in two variables
$user_id = $_GET["user_id"]; 
$key = $_GET["key"]; 
$time = time() - 86400; 
//Prepare variables for the query
$user_id = mysqli_real_escape_string($link, $user_id);
$key = mysqli_real_escape_string($link, $key);
//Run Query: Check combination of user_id & key exists and less than 24h old
$sql = "SELECT user_id FROM forgotpassword WHERE rkey='$key' AND user_id='$user_id' AND time > '$time' AND status='pending'";
if(!$result) {
    echo "<div class='alert alert-danger'>Error running the query!</div>";
    exit;
}
//If combination does not exist 
//show an error message
$count = mysqli_num_rows($result);
        if($count !== 1) {
        echo "<div class='alert alert-danger'>Please try again!</div>";
        exit;
        }
//print reset password form with hidden user_id and key fields
echo "
<form method=post id='passwordreset'>
<input type=hidden name=key value=$key>
<input type=hidden name=key value=$user_id>
<div class='form-group'>
<label for='password'>Enter your new password:</label>
<input type='password' name='password' id='password' placeholder='Enter Password' class='form-control'>
</div>
<div class='form-group'>
<label for='password2'>Re-enter Password:</label>
<input type='password2' name='password2' id='password2' placeholder='Re-enter Password' class='form-control'>
</div>
<input type='submit' class='btn btn-success btn-lg' name='resetpassword' value='Reset Password'>
</form>
";
?>                               
            </div>
        </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <!--Script for Ajax Call to storeresetpassword.php which processes form data-->
    <script>
    //Once the form is submitted
    $("#passwordreset").submit(function(event) {
	    //prevent default php processing
        event.preventDefault();
        //collect user inputs
        var datatopost = $(this).serializeArray();
        // console.log(datatopost);
        //send them to forgot_password.php using AJAX
        $.ajax({
            url: "storeresetpassword.php",
            type: "POST",
            data: datatopost,
            success: function(data) {
                $("#resultmessage").html(data);
            },
            error: function() {
                $("#signupmessage").html(
                    "<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>"
                );
            }
        });
});
    </script>
  </body>
</html>

                
