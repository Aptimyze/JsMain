~include_partial('global/header')`
<html>
<h1>Logging Client information</h1>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  style="text-align: center;">
<h2  style="font-size:20px;color:#000" id="Message"></h2>	
<form id="form"><br><br>

	 
  UserName:<br>
  <input id="inputUserName" type="text" name="username" value="~$name`" required>
  <br><br>
  Remarks:<br>
  <textarea id="inputRemarks" name="remarks" rows="5" cols="40">~$remarks`</textarea>
  <br><br>
  <input type="submit" id="submit" name="submit" value="Submit">
</form> 
</body>
</html>

~include_partial('global/footer')`
<script>
$(document).ready(function(){readyFN();});
	function readyFN(){
	$("#Message").text("");
		$("#submit").click(function(e){
		
			e.preventDefault();
			
			
			var UserName = $("#inputUserName").val();
			var Remarks = $("#inputRemarks").val();
			var Url = "/operations.php/crmInterface/logClientInfo";
			var Data = {
				
				username : UserName,
				remarks : Remarks
			};
			console.log("Data" , Data);
			if( UserName.length > 0){
				$.post(Url, Data, function(response){
					$("#inputUserName").val("");
					$("#inputRemarks").val("");
					$("#Message").text(response.message);
				}, "json");
			}
		});	
	}
	
</script>
