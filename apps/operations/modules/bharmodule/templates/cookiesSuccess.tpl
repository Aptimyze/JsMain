<html>
	<body>
		<table>
			<h3>Using session and cookies. </h3>
			<form method="post" action = "/operations.php/bharmodule/validate" >
				<tr><th>Email</th> <td><input type="text" name="email" id='email'></td> </tr>
				<tr><th>Password</th> <td><input type="text" name="password"></td> </tr>
				<tr><td> <input type="checkbox" name="remember" value="1">Remember</td> </tr>
				<tr><td><input type="submit" name="login" value="login"></td> </tr>
			</form>
		</table>
	</body>
</html>

<?php
	if(isset($_COOKIE['email'])){
		$email = $_COOKIE['email'];
		echo "	<script>
					document.getElementById('email').value = '$email';
					document.getElementById('password').value = '$password';
				</script>";

	}
	
?>