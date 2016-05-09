<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
	</head>
	<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="80" usemap="#Map" border="0"></td>
			</tr>
			<tr class="formhead" align="center" width="100%">
				<td colspan="3" style="background-color:lightblue" height="30">
					<font size=3>Split Sales Interface</font>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="background-color:lightblue" height="30" align="center">
					<a href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php?cid=~$cid`">Click here to go to main page</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="~sfConfig::get('app_site_url')`/jsadmin/logout.php?cid=~$cid`">Logout</a>
				</td>
			</tr>
		</table>
		<br>
		~if $flag eq '0'`
		<form name="splitSalesUsernameEntry" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/splitSalesInterface" id="splitSalesUsernameEntry" method="POST">
			~if $errorMsg`
			<div width="100%" style="background-color:lightorange;text-align:center;padding:20px;font-size:12px;color:red">~$errorMsg`</div>
			~/if`
			<div width="100%" style="background-color:lightblue;text-align:center;padding:20px;font-size:12px;">
				<span style="font-weight:bold;padding-left:20px;">
					Enter the Username : &nbsp;
					<input type="text" name="username" placeholder="username" value="" style="text-align:center;font-size:14px;">
				</span>
				<span style="font-weight:bold;padding-left:20px;">
					Select date of Payment Captured : &nbsp;
					<select name="selectedDate" id="selectDate">
						~foreach from=$dateDropDown key=k item=v`
						<option ~if $todaysDt eq $k`selected ~/if`value="~$k`">~$v`</option>
						~/foreach`
					</select>
				</span>
			</div>
			<br>
			<br>
			<div style="margin:0 auto;text-align:center;">
				<input style="font-size:16px;width:25%;" type="submit" name="submitSalesUsername" value="Go">
				<input type="hidden" name="name" value="~$name`">
				<input type="hidden" name="cid" value="~$cid`">
			</div>
			<br>
			<br>
		</form>
		~/if`
		~if $flag eq '1'`
		<form name="splitSalesUpdateDetails" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/splitSalesInterface" id="splitSalesUpdateDetails" method="POST">
			~if $errorMsg`
			<div width="100%" style="background-color:lightorange;text-align:center;padding:20px;font-size:12px;color:red">~$errorMsg`</div>
			~/if`
			~if $successMsg`
			<div width="100%" style="background-color:lightorange;text-align:center;padding:20px;font-size:12px;color:green">~$successMsg`</div>
			~/if`
			<div width="100%" style="background-color:lightblue;text-align:center;padding:20px;font-size:12px;">
				<span style="font-weight:bold;padding-left:20px;">
					Enter the Username : &nbsp;
					<input type="text" readonly="true" name="username" placeholder="~$username`" value="~$username`" style="text-align:center;font-size:14px;">
				</span>
				<span style="font-weight:bold;padding-left:20px;">
					Payment Captured Date : &nbsp;
					<input type="text" readonly="true" name="selectedDate" placeholder="~$selectedDate`" value="~$selectedDate`" style="text-align:center;font-size:14px;">
				</span>
			</div>
			<br>
			<br>
			<div width="100%" style="background-color:lightblue;text-align:center;padding:20px;font-size:12px;">
				<span style="font-weight:bold;padding-left:20px;">
					Allottee Executive : &nbsp;
					<input type="text" readonly="true" name="allotedAgent" placeholder="~$allotedAgent`" value="~$allotedAgent`" style="text-align:center;font-size:14px;">
				</span>
				<span style="font-weight:bold;padding-left:20px;">
					Share With : &nbsp;
					<select name="selectedAgent" id="selectAgent">
						~foreach from=$allAgents key=k item=v`
							<option value="~$v`">~$v`</option>
						~/foreach`
					</select>
				</span>
				<br><br><br><br>
				<span style="font-weight:bold;padding-left:20px;">
					Allotee Share : &nbsp;
					<input type="text" name="alloteeShare" placeholder="Enter share here" value="0" style="text-align:center;font-size:14px;">
				</span>
			</div>
			<br>
			<div style="margin:0 auto;text-align:center;">
				<input style="font-size:16px;width:25%;" type="submit" id="submitSalesUpdate" name="submitSalesUpdate" value="Submit">
				<input type="hidden" name="name" value="~$name`">
				<input type="hidden" name="cid" value="~$cid`">
				<input type="hidden" name="profileid" value="~$profileid`">
			</div>
			<br>
			<br>
		</form>
		~/if`
		<script type="text/javascript">
			function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
			$(document).bind("keydown", disableF5);
			$(document).on("keydown", disableF5);
			function validate(){
				var value = $("input[name=alloteeShare]").val();
				var numericVal = /^[0-9]+$/;
				if(!numericVal.test(value)){
					alert('Please enter a numeric value, decimal values cannot be entered!');
					$(this).val(0);
					return false;
				} else if(value > 100){
					alert("Share cannot be greater than 100%!");
					$(this).val(0);
					return false;
				} else if(value <= 0){
					alert("Share cannot be less than or equal to 0%!");
					$(this).val(0);
					return false;
				} else {
					return true;
				}
			}
			$(document).ready(function(){
				$("input[name=alloteeShare]").bind('focusout', function(){
					var numericVal = /^[0-9]+$/;
					var value  = $(this).val();
					if(!numericVal.test(value)){
						alert('Please enter a numeric value, decimal values cannot be entered!');
						$(this).val(0);
					}
					if(value > 100){
						alert("Share cannot be greater than 100%!");
						$(this).val(0);
					}
					if(value <= 0){
						alert("Share cannot be less than or equal to 0%!");
						$(this).val(0);
					}
				});
				$("#submitSalesUpdate").click(function(e){
					if(!validate()){
						e.preventDefault();
						e.stopPropagation();
					}
				});
			});
		</script>
	</body>
</html>