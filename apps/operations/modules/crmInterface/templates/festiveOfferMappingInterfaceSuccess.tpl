<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<form name="applyFestiveMapping" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/festiveOfferMappingInterface" id="applyFestiveMapping" method="POST">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="80" usemap="#Map" border="0"></td>
			</tr>
			<tr class="formhead" align="center" width="100%">
				<td colspan="3" style="background-color:lightblue" height="30">
					<font size=3>Festive Offer Mapping Interface</font>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="background-color:lightblue" height="30" align="center">
					<a href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php?cid=~$cid`">Click here to go to main page</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/manageFestiveOffer?cid=~$cid`">Back</a>&nbsp;&nbsp;&nbsp;&nbsp;
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
				<td width=~$durPerc`% align=center>Service Duration</td>
				<td width=~$durPerc`% align=center>Discount Duration</td>
				<td width=~$durPerc`% align=center>Discount Percentage</td>
			</tr>
			~foreach from=$offerArr key=k item=v`
			~if $v.DISPLAY_FLAG eq 'Y'`
			<tr height="50">
			~else`
			<tr height="50" style="display:none;">
			~/if`
				<td align=center style="background-color:lightblue;font-size:18px;">~$v.NAME`</td>
				<td align=center style="background-color:lightblue">
					~if $v.DUR_ENABLE eq 'Y'`
						<input id="~$k`" class="discDur" months="~$v.MONTHS`" type="text" name="discDur[~$k`]" style="text-align:center;font-size:16px;" value="~$v.DUR`">
					~/if`
				</td>
				<td align=center style="background-color:lightblue">
					~if $v.PERC_ENABLE eq 'Y'`
						<input id="~$k`" class="discPerc" months="~$v.MONTHS`" type="text" name="discPerc[~$k`]" style="text-align:center;font-size:16px;" value="~$v.PERC`">
					~/if`
				</td>
			</tr>
			~/foreach`
		</table>
		<div width="100%" style="background-color:#ffffbd;font-size:12px;padding:10px 30px;text-align:center;">
			1) All discounts/durations entered shall be treated as percentages/months<br>
			2) Pre-filled values imply currently active discounts/durations
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
			var validDurations = 0;
			var validPercentages = 0;
			$(".discDur").each(function(){
				if(parseInt($(this).val()) >= 0 || parseInt($(this).attr('months'))+parseInt($(this).val()) <= 11){
					validDurations = 1;
				}
				if(parseInt($(this).val())!=0){
					var value  = parseInt($(this).val().replace(/^0+/, ''));
				} else {
					var value  = parseInt($(this).val());
				}
				var months = parseInt($(this).attr('months').replace(/^0+/, ''));
				$(".discDur").each(function(){
					if(parseInt($(this).val())!=0){
						var v2 = parseInt($(this).val().replace(/^0+/, ''));
					} else {
						var v2 = 0;
					}
					var m2 = parseInt($(this).attr('months'));
					if(m2!=months){
						if(v2+m2 == value+months){
							$("input[months="+months+"]").val(0);
							$("input[months="+months+"]").text(0);
							validDurations = 0;
						}
					}
				});
			});
			$(".discPerc").each(function(){
				if(parseInt($(this).val()) >= 0 || parseInt($(this).val()) <= 100){
					validPercentages = 1;
				}
			});
			if(validDurations == 0 || validPercentages == 0){
				alert("Enter a values for duration/percentages and try again!");
				return false;
			} else {
				return true;
			}
		}

		$(document).ready(function(){
			$('input').keypress(function(event){
				if (event.keyCode == 10 || event.keyCode == 13) {
					event.preventDefault();
				}
			});
			$(".discPerc").bind('focusout', function(){
				var floatVal = /^\s*(\+|-)?((\d+(\.\d+)?)|(\.\d+))\s*$/;
				if(parseFloat($(this).val())!=0){
					var value  = parseFloat($(this).val().replace(/^0+/, ''));
				} else {
					var value  = parseFloat($(this).val());
				}
				if(typeof $(this).attr('months') === "string"){
					var months = $(this).attr('months');	
				} else {
					var months = parseInt($(this).attr('months').replace(/^0+/, ''));
				}
				if(floatVal.test(value)){
					if(value > 100 || value < 0){
						alert("Discount cannot be greater than 100%! or less the 0%");
						$(this).val(0);
					} else {
						$("input[months="+months+"]").val(value);
						$("input[months="+months+"]").text(value);
					}
				} else {
					alert('Please enter a numeric value!');
					$(this).val(0);
				}
			});
			$(".discDur").bind('focusout', function(){
				var floatVal = /^[0-9]*$/;
				if(parseInt($(this).val())!=0){
					var value  = parseInt($(this).val().replace(/^0+/, ''));
				} else {
					var value  = parseInt($(this).val());
				}
				var months = parseInt($(this).attr('months').replace(/^0+/, ''));
				var checkFlag = 0;
				if(floatVal.test(value)){
					$(".discDur").each(function(){
						if(checkFlag != 1){
							if(parseInt($(this).val())!=0){
								var v = parseInt($(this).val().replace(/^0+/, ''));
							} else {
								var v = 0;
							}
							var m = parseInt($(this).attr('months'));
							if(m!=months){
								if(v+m == value+months){
									checkFlag = 1;
								}
							}
						}
					});
					if((value+months < 0 || value+months > 11) || checkFlag==1){
						alert("Please note :: \n1) Total Duration cannot exceed 11 Months \n2) You cannot have two duration offers with same final duration \nTry Again!");
						$(".discDur").each(function(){
							if(parseInt($(this).val())!=0){
								var v2 = parseInt($(this).val().replace(/^0+/, ''));
							} else {
								var v2 = 0;
							}
							var m2 = parseInt($(this).attr('months'));
							if(v2+m2 == value+months){
								$("input[months="+months+"]").val(0);
								$("input[months="+months+"]").text(0);
							}
						});
					} else {
						$("input[months="+months+"]").val(value);
						$("input[months="+months+"]").text(value);

					}
				} else {
					alert("Only numeric values(no decimals) allowed !");
					$(this).val(0);
				}
			});
			$("#applyFestiveMapping").submit(function(e){
				if(!validateAndSubmit()){
					e.preventDefault();
				}
			})
		});
	</script>
</body>
</html>
