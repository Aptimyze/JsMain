<!DOCTYPE html>
<html>
<head>
	<title>PHP simple validation form</title>
</head>
<body>
	<h2>PHP Form Validation Example</h2>
	<form name="myForm" method="post" action="/operations.php/bharmodule/profile" >  
  		Name: <input type="text" name="name">
 	 	<br><br>
		E-mail: <input type="text" name="email">
 		<br><br>
 		Website: <input type="text" name="website">
 		 
 		<br><br>
 		Comment: <textarea name="comment" rows="5" cols="40"></textarea>
  		<br><br>
  		Gender:
  		<input type="radio" name="gender" value="F">Female
	  	<input type="radio" name="gender" value="M">Male
  		<br><br>
  		<input type="submit" name="submit" value="submit">  
	</form>
</body>
</html>