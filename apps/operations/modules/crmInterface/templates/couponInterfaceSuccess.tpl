<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<form name="generateCouponCode" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/couponInterface" id="generateCouponCode" method="POST">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="80" usemap="#Map" border="0"></td>
			</tr>
			<tr class="formhead" align="center" width="100%">
				<td colspan="3" style="background-color:lightblue" height="30">
					<font size=3>Coupon Code Generation Interface</font>
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
		<div width="100%" style="background-color:lightblue;text-align:center;padding:20px;font-size:12px;">
			<span style="font-weight:bold;">
				Select Coupon Expiry Date : &nbsp;
				<select name="expiryDate" >
					~foreach from=$dropdownData key=k item=v`
						<option value="~$k`">~$v`</option>
					~/foreach`
				</select>
			</span>
			<span style="font-weight:bold;padding-left:20px;">
				Enter the Coupon Usage Limit : &nbsp;
				<input class="couponLimit" type="text" name="usageLimit" value="0" style="text-align:center;font-size:14px;">
			</span>
		</div>
		<br>
		<table width=100% align=center>
			<tr class=formhead style="background-color:LightSteelBlue" height="25">
				<td width=~$durPerc`% align=center>Services/Durations</td>
				~foreach from=$serviceDurations key=k item=v`
					<td width=~$durPerc`% align=center>~$v`</td>
				~/foreach`
			</tr>
			~foreach from=$serviceNames key=k item=v`
			<tr height="50">
				<td align=center style="background-color:lightblue;font-size:18px;">~$v`</td>
				~foreach from=$serviceArray key=mainID item=servArr`
					~if $mainID eq $k`
						~foreach from=$servArr key=kk item=vv`
							<td align=center style="background-color:lightblue">
								<input class="serviceDisc" type="text" name="serviceDisc[~$kk`]" value="0" style="text-align:center;font-size:16px;">
							</td>
						~/foreach`
					~/if`
				~/foreach`
			</tr>
			~/foreach`
		</table>
		<div width="100%" style="background-color:#ffffbd;font-size:12px;padding:10px 30px;text-align:right;">
			*All discounts entered shall be treated as percentages
		</div>
		<br>
		<div style="margin:0 auto;text-align:center;">
			<input style="font-size:16px;" type="submit" name="submit" value="Generate Coupon Code">
			<input type="hidden" name="name" value="~$name`">
			<input type="hidden" name="cid" value="~$cid`">
		</div>
	</form>
	<script type="text/javascript">
		function validateAndSubmit(){
			var validateValues = 0;
			$(".serviceDisc").each(function(){
				if($(this).val() > 0){
					validateValues = 1;
				}
			});
			if($("option[value=select]:selected").length){
				alert("Please select a valid Expiry Date and try again!");
				return false;
			} else if($(".couponLimit").val() == 0){
				alert("Coupon Usage Limit cannot be 0, please enter a valid value!");
				return false;
			} else if(validateValues == 0){
				alert("Enter a valid Discount for atleast one service and try again!");
				return false;
			} else {
				return true;
			}
		}

		$(document).ready(function(){
			$("input[type=text]").val(0);
			$("option[value=select]").prop('selected',true);
			$(".serviceDisc").bind('focusout', function(){
				var floatVal = /^\s*(\+|-)?((\d+(\.\d+)?)|(\.\d+))\s*$/;
				var value  = $(this).val();
				if(floatVal.test(value)){
					if(value > 100){
						alert("Discount cannot be greater than 100%!");
						$(this).val(0);
					}
				} else {
					alert('Please enter a numeric value!');
					$(this).val(0);
				}
			});
			$(".couponLimit").bind('focusout', function(){
				var numericVal = /^[0-9]+$/;
				var value  = $(this).val();
				if(!numericVal.test(value)){
					alert('Please enter a numeric value, decimal values cannot be entered!');
					$(this).val(0);
				}
			});
			$("#generateCouponCode").submit(function(e){
				if(!validateAndSubmit()){
					e.preventDefault();
				}
			})
		});
	</script>
</body>
</html>
