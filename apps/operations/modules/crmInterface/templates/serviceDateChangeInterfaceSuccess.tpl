<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
		<script type="text/javascript">
		$(function () {
			var count = 0;
		$('#date1').dateDropDowns({ dateFormat: 'DD-mm-yy',yearStart: "~$startYear`", yearEnd: "~$rangeYear`"});
		$('#date2').dateDropDowns({ dateFormat: 'DD-mm-yy',yearStart: "~$startYear`", yearEnd: "~$rangeYear`"});
		$('#date1_dateLists_day_list option:selected').prop('selected', false);
		$('#date1_dateLists_day_list').on('click', function(){
			count = 1;
		});
		$('#date1_dateLists_month_list').on('click', function(){
			if(count != 1){
				$('#date1_dateLists_day_list option:selected').prop('selected', false);
			}
		});
		});
		</script>
	</head>
	<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="80" usemap="#Map" border="0"></td>
			</tr>
			<tr class="formhead" align="center" width="100%">
				<td colspan="3" style="background-color:lightblue" height="30">
					<font size=3>Service Date Change Interface</font>
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
		<form name="retrieveBillidVal" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/serviceDateChangeInterface" id="retrieveBillidVal" method="POST">
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
		<form name="changeBillidVal" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/serviceDateChangeInterface" id="changeBillidVal" method="POST">
			<div width="100%" style="background-color:lightblue;text-align:center;padding:20px;font-size:12px;">
				<span style="font-weight:bold;padding-left:20px;">
					Enter Billid : &nbsp;
					<input disabled class="billid" type="text" name="billid" value="~$billid`" style="text-align:center;font-size:14px;">
				</span>
				<span style="font-weight:bold;padding-left:20px;">
					Enter ServiceID : &nbsp;
					<input class="serviceid" type="text" name="serviceid" value="" style="text-align:center;font-size:14px;">
				</span>
				<br>
				<table align="center" style="padding-left:20px;">
					<tr align="center">
						<td>
							<input type="radio" name="range_format" value="DMY" checked="yes" />
							<font size=2>Select Service Start/End Date</font>
						</td>
						<td>
							<input id="date1" type="text" value="">
							&nbsp;&nbsp;&nbsp;
							<b>To</b>
							&nbsp;&nbsp;&nbsp;
							<input id="date2" type="text" value="">
						</td>
					</tr>
				</table>
			</div>
			<br>
			<div width="100%" style="background-color:orange;font-size:12px;padding:5px 0px;text-align:center;font-weight:bold">
				<font size=2>
				PURCHASE_DETAIL Details for Billid ~$billid`
				</font>
			</div>
			<table width=100%>
				<tr style="background-color:LightSteelBlue">
					~foreach from=$purDet key=k item=v`
					~if $k eq 0`
					~foreach from=$v key=kk item=vv`
					<td align=center>~$kk`</td>
					~/foreach`
					~/if`
					~/foreach`
				</tr>
				~foreach from=$purDet key=k item=v`
				<tr style="background-color:#ffffbd">
					~foreach from=$v key=kk item=vv`
					<td align=center>~$vv`</td>
					~/foreach`
				</tr>
				~/foreach`
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
				* Enter ServiceID and Date changes one by one for which to change start/end dates and submit to see changes
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