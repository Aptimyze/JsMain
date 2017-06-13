<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<form name="applyCashDiscount" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/productWiseCashDiscount" id="applyCashDiscount" method="POST">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="80" usemap="#Map" border="0"></td>
			</tr>
			<tr class="formhead" align="center" width="100%">
				<td colspan="3" style="background-color:lightblue" height="30">
					<font size=3>Product Wise Cash Discount Interface</font>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="background-color:lightblue" height="30" align="center">
					<a href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php?cid=~$cid`">Click here to go to main page</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/manageCashDiscountOffer?cid=~$cid`">Back</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="~sfConfig::get('app_site_url')`/jsadmin/logout.php?cid=~$cid`">Logout</a>
				</td>
			</tr>
		</table>
		<br>
		~if $successMsg`
		<div width="100%" style="background-color:#ffffbd;font-size:12px;padding:10px 30px;text-align:center;color:green;">
			~$successMsg`
		</div>
		~/if`
		~if $errorMsg`
		<div width="100%" style="background-color:#ffffbd;font-size:12px;padding:10px 30px;text-align:center;color:red;">
			~$errorMsg`
		</div>
		~/if`
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
						~foreach from=$serviceDurations key=dur item=txt`
							~if $servArr[$k|cat:$dur]`
							<td align=center style="background-color:lightblue">
								<input id="~$k`~$dur`" class="serviceDisc" type="text" name="serviceDisc[~$k`~$dur`]" ~if $errorMsg` value="0" ~/if` style="text-align:center;font-size:16px;">
							</td>
							~else`
							<td align=center style="background-color:lightblue"></td>
							~/if`
						~/foreach`
					~/if`
				~/foreach`
			</tr>
			~/foreach`
		</table>
		<div width="100%" style="background-color:#ffffbd;font-size:12px;padding:10px 30px;text-align:center;">
			1) All discounts entered shall be treated as percentages<br>
			2) Blank spaces indicate inactive services <br>
			3) Pre-filled values imply currently active discounts
		</div>
		<br>
		<div style="margin:0 auto;text-align:center;">
			<input style="font-size:16px;" type="submit" name="submit" value="Apply Discount Values">
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
			if(validateValues == 0){
				alert("Enter a valid Discount for atleast one service and try again!");
				return false;
			} else {
				return true;
			}
		}

		var servVal = new Array();

		~foreach from=$activeServices key=k item=v`
			servVal["~$k`"]="~$v`";
		~/foreach`

		function getValue(id){
			return servVal[id];
		}

		$(document).ready(function(){
			$("input[type=text]").each(function(){
				var id = $(this).attr('id');
				$(this).val(getValue(id));
			});
			$("option[value=select]").prop('selected',true);
			$(".serviceDisc").bind('focusout', function(){
				var floatVal = /^\s*(\+|-)?((\d+(\.\d+)?)|(\.\d+))\s*$/;
				var value  = $(this).val();
				if(floatVal.test(value)){
					if(value < 0 || value > 100){
						$(this).val(0);
						alert("Discount cannot be less than 0% or greater than 100%!");
					}
				} else {
					$(this).val(0);
					alert('Please enter a numeric value!');
				}
			});
			
			$("#applyCashDiscount").submit(function(e){
				if(!validateAndSubmit()){
					e.preventDefault();
				} else {
					window.reload();
				}
			})
		});
	</script>
</body>
</html>
