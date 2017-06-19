<?php
        include_once("connect.inc");
	include_once("registration_functions.inc");
        include_once("screening_functions.php");
        include_once("cuafunction.php");

        $db = connect_db();
	$username_flag = validate_username($username);
	if($username_flag==1)
		$msg="Username should start with an alphabet.";	
	elseif($username_flag==2)
		$msg="Username should be of minimum 8 characters.";
	elseif($username_flag==3)
		$msg="Username cannot contain any obscene words.";
	elseif($username_flag==4)
		$msg="Username cannot contain more than 5 digits.";
	elseif($username_flag==5)
		$msg="Username cannot contain any domain names.";
	elseif($username_flag==6)
		$msg="Username cannot contain any special characters. Only underscore(_) and period (.) are allowed.";
	elseif($username_flag==7)
		$msg="Username already exists, please try a different username.";
	else
		$msg="Available.";
	echo $msg;
	die;
?>
