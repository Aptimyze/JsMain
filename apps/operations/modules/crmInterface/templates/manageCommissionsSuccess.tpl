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
					<font size=3>Manage Apple/Franchisee Commissions Interface</font>
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
		<form name="alterFranchiseePercentage" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/manageCommissions" id="alterFranchiseePercentage" method="POST">
			~if $errorMsgFran`
			<div width="100%" style="background-color:lightorange;text-align:center;padding:20px;font-size:12px;color:red">~$errorMsgFran`</div>
			~/if`
			~if $successMsgFran`
			<div width="100%" style="background-color:lightorange;text-align:center;padding:20px;font-size:12px;color:green">~$successMsgFran`</div>
			~/if`
			<div width="100%" style="background-color:lightblue;text-align:center;padding:20px;font-size:12px;">
				<span style="font-weight:bold;">
					Select Franchisse Agent : &nbsp;
					<select name="agentName" >
						~foreach from=$franchiseeAgentsArr key=k item=v`
						<option value="~$k`">~$v`</option>
						~/foreach`
					</select>
				</span>
				<span style="font-weight:bold;padding-left:20px;">
					Select month for which alteration is required : &nbsp;
					<select name="selectedMonth" >
						~foreach from=$monthDropDown key=k item=v`
						<option value="~$k`">~$v`</option>
						~/foreach`
					</select>
				</span>
				<span style="font-weight:bold;padding-left:20px;">
					Enter New Percentage : &nbsp;
					<input type="text" name="franPerc" value="0" style="text-align:center;font-size:14px;">
					%
				</span>
			</div>
			<br>
			<div style="margin:0 auto;text-align:center;">
				<input style="font-size:16px;width:25%;" type="submit" name="submitFranchisee" value="Apply New Franchisee Percentage">
				<input type="hidden" name="name" value="~$name`">
				<input type="hidden" name="cid" value="~$cid`">
			</div>
			<br>
			<div width="100%" style="background-color:#ffffbd;font-size:12px;padding:10px 30px;text-align:right;">
				*Clicking on Apply will alter the records for the selected agent in the selected month
			</div>
			<br>
		</form>
		<form name="alterApplePercentage" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/manageCommissions" id="alterApplePercentage" method="POST">
			~if $errorMsgApple`
			<div width="100%" style="background-color:lightorange;text-align:center;padding:20px;font-size:12px;color:red">~$errorMsgApple`</div>
			~/if`
			~if $successMsgApple`
			<div width="100%" style="background-color:lightorange;text-align:center;padding:20px;font-size:12px;color:green">~$successMsgApple`</div>
			~/if`
			<div width="100%" style="background-color:lightblue;text-align:center;padding:20px;font-size:12px;">
				<span style="font-weight:bold;padding-left:20px;">
					Select date from which new commission should be applicable : &nbsp;
					<select name="selectedMonth" id="selectMonth">
						~foreach from=$monthDropDownApple key=k item=v`
						<option value="~$k`">~$v`</option>
						~/foreach`
					</select>
					<span>
						<select name="selectedDay">
						~foreach from=$daysDropDownApple key=k item=v`
							<option value="~$k`">~$v`</option>
						~/foreach`
						</select>
					</span>
				</span>
				<span style="font-weight:bold;padding-left:20px;">
					Enter New Apple Comissions Percentage : &nbsp;
					<input type="text" name="applePerc" value="0" style="text-align:center;font-size:14px;">
					%
				</span>
			</div>
			<br>
			<div style="margin:0 auto;text-align:center;">
				<input style="font-size:16px;width:25%;" type="submit" name="submitApple" value="Apply New Apple Commission Percentage">
				<input type="hidden" name="name" value="~$name`">
				<input type="hidden" name="cid" value="~$cid`">
			</div>
			<br>
			<div width="100%" style="background-color:#ffffbd;font-size:12px;padding:10px 30px;text-align:right;">
				*Clicking on Apply button will make all Apple Calculations use new Percentage
			</div>
			<br>
		</form>
		<script type="text/javascript">
			function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
			// To disable f5
			/* jQuery < 1.7 */
			$(document).bind("keydown", disableF5);
			/* OR jQuery >= 1.7 */
			$(document).on("keydown", disableF5);
			// $(document).ready(function(e){
			// 	$("#selectMonth").change(function(event){
			// 		 if ($(this).find(':selected').val() === '5') {
   //          			$('div#custom_proptions').slideDown('slow');
   //          		}
			// 	})
			// });
		</script>
	</body>
</html>
