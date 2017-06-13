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
					<font size=3>CRM SMS Interface</font>
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
		<form name="crmSmsFunctionality" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/crmSmsFunctionalityInterface" id="crmSmsFunctionality" method="POST">
			~if $errorMsg`
			<div width="100%" style="background-color:lightorange;text-align:center;padding:20px;font-size:12px;color:red">~$errorMsg`</div>
			~/if`
			~if $successMsg`
			<div width="100%" style="background-color:lightorange;text-align:center;padding:20px;font-size:12px;color:green">~$successMsg`</div>
			~/if`
			<div width="100%" style="background-color:lightblue;text-align:center;padding:20px;font-size:12px;">
				<span style="font-weight:bold;padding-left:20px;">
					Username : &nbsp;~$username`
				</span>
			</div>
			<br>
			<br>
			<div width="100%" style="background-color:lightblue;text-align:center;padding:20px;font-size:12px;">
				<span style="font-weight:bold;padding-left:20px;">
					<input type="checkbox" name="smsType[]" value="B" style="text-align:center;font-size:14px;"> Branch Details <br>
				</span>
				<span style="font-weight:bold;padding-left:20px;">
					<input type="checkbox" name="smsType[]" value="O" style="text-align:center;font-size:14px;"> Current Offer with Discount <br>
				</span>
				<span style="font-weight:bold;padding-left:20px;">
					<input type="checkbox" name="smsType[]" value="N" style="text-align:center;font-size:14px;"> Not able to reach you <br>
				</span>
				<span style="font-weight:bold;padding-left:20px;">
					<input type="checkbox" name="smsType[]" value="M" style="text-align:center;font-size:14px;"> Mobile App Download Link <br>
				</span>
			</div>
			<br>
			<div style="margin:0 auto;text-align:center;">
				<input style="font-size:16px;width:25%;" type="submit" id="submit" name="submit" value="Submit">
				<input type="hidden" name="name" value="~$name`">
				<input type="hidden" name="cid" value="~$cid`">
				<input type="hidden" name="profileid" value="~$profileid`">
			</div>
			<br>
			<br>
		</form>
		<script type="text/javascript">
			function disableF5(e) { 
				if ((e.which || e.keyCode) == 116) {
					e.preventDefault();
				} 
			};
			$(document).bind("keydown", disableF5);
			$(document).on("keydown", disableF5);
			function GetSelectedCheckboxCount() {
			    var selectedCheckboxCount = 0;
			    $("input:checkbox").each(function() {
			        if ($(this).is(":checked")) {
			            selectedCheckboxCount++;
			        }
			    });
			    return selectedCheckboxCount;
			}
			$('input[type="checkbox"]').click(function(event) {
			    if (GetSelectedCheckboxCount() > 2) {
			        event.preventDefault();
			        event.stopPropagation();
			        alert('You\'re not allowed to choose more than 2 boxes');
			    }
			});
			$("#submit").click(function(e){
				var count = GetSelectedCheckboxCount();
				if(count > 2 || count == 0){
					e.preventDefault();
					e.stopPropagation();
					if (count == 0) {
						alert('You have to select atleast one checkbox before submitting');
					} else  {
						alert('You\'re not allowed to choose more than 2 boxes');
					}
				}
			});
		</script>
	</body>
</html>