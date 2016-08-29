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
					<font size=3>Service Activation Change Interface</font>
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
		~if $detailedView neq 1`
		<form name="retrieveBillidVal" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/serviceActivationChangeInterface" id="retrieveBillidVal" method="POST">
			<div width="100%" style="background-color:lightblue;text-align:center;padding:20px;font-size:12px;">
				<span style="font-weight:bold;padding-left:20px;">
					Enter Billid : &nbsp;
					<input class="billid" type="text" name="billid" value="" style="text-align:center;font-size:14px;">
				</span>
			</div>
			<br>
			~if $error eq 1`
			<div width="100%" style="background-color:#ffffbd;font-size:12px;padding:10px 30px;text-align:right;">
				~$errorMsg`
			</div>
			<br>
			~/if`
			<div style="margin:0 auto;text-align:center;">
				<input style="font-size:16px;" type="submit" name="submitBillid" value="Submit">
				<input type="hidden" name="name" value="~$name`">
				<input type="hidden" name="cid" value="~$cid`">
			</div>
		</form>
		~else`
		<form name="changeBillidVal" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/serviceActivationChangeInterface" id="changeBillidVal" method="POST">
			<div width="100%" style="background-color:lightblue;text-align:center;padding:20px;font-size:12px;">
				<span style="font-weight:bold;padding-left:20px;">
					Enter Billid : &nbsp;
					<input disabled class="billid" type="text" name="billid" value="~$billid`" style="text-align:center;font-size:14px;">
				</span>
				<span style="font-weight:bold;padding-left:20px;">
					Enter ServiceID : &nbsp;
					<input class="serviceid" type="text" name="serviceid" value="" style="text-align:center;font-size:14px;">
				</span>
				<span style="font-weight:bold;padding-left:20px;">
					Enter Service Status (Y/N/E) : &nbsp;
					<input class="serviceStatus" type="text" name="serviceStatus" value="" style="text-align:center;font-size:14px;">
				</span>
			</div>
			<br>
			<div width="100%" style="background-color:orange;font-size:12px;padding:5px 0px;text-align:center;font-weight:bold">
				<font size=2>
				JPROFILE Details for Username - ~$jprofileDet.USERNAME`
				</font>
			</div>
			<table width=100%>
				<tr style="background-color:LightSteelBlue">
					<td align=center>USERNAME</td>
					<td align=center>PROFILEID</td>
					<td align=center>SBUSCRIPTION</td>
				</tr>
				<tr style="background-color:#ffffbd">
					<td align=center>~$jprofileDet.USERNAME`</td>
					<td align=center>~$jprofileDet.PROFILEID`</td>
					<td align=center>~$jprofileDet.SUBSCRIPTION`</td>
				</tr>
			</table>
			<br>
			<div width="100%" style="background-color:orange;font-size:12px;padding:5px 0px;text-align:center;font-weight:bold">
				<font size=2>
				SERVICE_STATUS Details for Billid ~$billid`
				</font>
			</div>
			<table width=100%>
				<tr style="background-color:LightSteelBlue">
					~foreach from=$serStatDet key=k item=v`
					~if $k eq 0`
					~foreach from=$v key=kk item=vv`
					<td align=center>~$kk`</td>
					~/foreach`
					~/if`
					~/foreach`
				</tr>
				~foreach from=$serStatDet key=k item=v`
				<tr style="background-color:#ffffbd">
					~foreach from=$v key=kk item=vv`
					<td align=center>~$vv`</td>
					~/foreach`
				</tr>
				~/foreach`
			</table>
			<br>
			<div width="100%" style="background-color:#ffffbd;font-size:12px;padding:10px 30px;text-align:right;font-weight:bold">
				* Enter ServiceID one by one for which to change activation status(N->Y, Y->N) and submit to see changes
			</div>
			<br>
			<div style="margin:0 auto;text-align:center;">
				<input style="font-size:16px;" type="submit" name="submitServiceid" value="Submit">
				<input type="hidden" name="name" value="~$name`">
				<input type="hidden" name="cid" value="~$cid`">
				<input type="hidden" name="billid" value="~$billid`">
			</div>
		</form>
		~/if`
		<script type="text/javascript">
			function validateAndSubmit(){
				if($(".billid").val() == 0){
					alert("Billid cannot be 0, please enter a valid value!");
					return false;
				} else {
					return true;
				}
			}
			function validateAndSubmit2(){
				if($(".serviceid").val() == ''){
					alert("Serviceid cannot be empty, please enter a valid value!");
					return false;
				} else {
					return true;
				}
				var value = $(".serviceStatus").val();
				if(value == '' || (value != 'N' || value != 'Y'|| value != 'E')){
					alert("Service Status can be either Y/N/E and cannot be empty, please enter a valid value!");
					return false;
				} else {
					return true;
				}
			}
			$(document).ready(function(){
				$(".billid").bind('focusout', function(){
					var numericVal = /^[0-9]+$/;
					var value  = $(this).val();
					if(!numericVal.test(value)){
						alert('Please enter a numeric value');
						$(this).val(0);
					}
				});
				$("#retrieveBillidVal").submit(function(e){
					if(!validateAndSubmit()){
						e.preventDefault();
					}
				});
				$("#changeBillidVal").submit(function(e){
					if(!validateAndSubmit2()){
						e.preventDefault();
					}
				});
			});
		</script>
	</body>
</html>