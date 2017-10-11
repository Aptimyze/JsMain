<?php 
		if(JsConstants::$whichMachine != 'local' && JsConstants::$whichMachine != 'test')
			die;
		
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
     if(isset($_POST['submit']))  {
        $to = $_POST['email'];
        $message = $_POST['message'];
        
        send_email($to, $message);
        echo "Message has been sent....!";  
     }else{
        echo "Add an email address"; 
     }
?>
    
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>HTML Email Sending Form</title>
<link rel="stylesheet" type="text/css" href="view.css" media="all">
<script type="text/javascript" src="view.js"></script>
</head>
<body id="main_body" >
	
	<img id="top" src="top.png" alt="">
	<div id="form_container">
	
		<form id="form_896998" class="appnitro"  method="post" action="index.php">
					<div class="form_description">
			<h2>HTML Email Sending Form</h2>
			<p>Send Email HTML template</p>
		</div>						
			<ul >
			
					<li id="li_2" >
		<label class="description" for="element_2">Email </label>
		<div>
			<input id="element_2" name="email" class="element text medium" type="text" maxlength="255" value=""/> 
		</div> 
		</li>		<li id="li_1" >
		<label class="description" for="element_1">Email HTML </label>
		<div>
			<textarea id="element_1" name="message" class="element textarea large"></textarea> 
		</div> 
		</li>
			
					<li class="buttons">
			    <input type="hidden" name="form_id" value="896998" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>	
		
	</div>
	<img id="bottom" src="bottom.png" alt="">
	</body>
</html>
