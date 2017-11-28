~include_partial('global/header')`
<html>
<h1>Logging agent information</h1>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  style="text-align: center;">
	
<form action="/operations.php/crmInterface/logClientInfo" method="POST"><br><br>
	 <input type=hidden name="name" value="~$name`">
  UserName:<br>
  <input type="text" name="username" value="~$name`" required>
  <br><br>
  Remarks:<br>
  <textarea name="remarks" rows="5" cols="40">~$remarks`</textarea>
  <br><br>
  <input  type="submit" id="submit" name="submit" value="Submit">
</form> 
</body>
</html>
~include_partial('global/footer')`