<html>
<head>
<title>Payment gateway redirect page</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, , initial-scale=1.0">
<meta name="description" content=""/>
<link href="/css/fonts/fonts.css" rel="stylesheet" type="text/css"/>
</head>
<body bgcolor="#ffffff" onLoad="document.form1.submit();">
<div class="outerdiv" style="display:table; margin:0 auto; height:100%;">
	<div class="fontlig" style="color:#d9475c; display:table-cell; vertical-align:middle">
		<div style="font-size:42px;" class="">Re-directing</div>
		<div style="font-size:15px">Please wait ...</div>
	</div>
</div>
<form id="form1" name="form1" action="~$gatewayURL`" method="POST">
	<!-- Parameters required for payment -->
	<input type="hidden" name="key" value="~$key`"></input>
	<input type="hidden" name="txnid" value="~$txnid`"></input>
	<input type="hidden" name="amount" value="~$amount`"></input>
	<input type="hidden" name="productinfo" value="~$productinfo`"></input>
	<input type="hidden" name="udf1" value="~$udf1`"></input>
	<input type="hidden" name="firstname" value="~$firstname`"></input>
	<input type="hidden" name="email" value="~$email`"></input>
	<input type="hidden" name="phone" value="~$phone`"></input>
	<input type="hidden" name="lastname" value="~$lastname`"></input>
	<input type="hidden" name="address1" value="~$address1`"></input>
	<input type="hidden" name="city" value="~$city`"></input>
	<input type="hidden" name="state" value="~$state`"></input>
	<input type="hidden" name="country" value="~$country`"></input>
	<input type="hidden" name="zipcode" value="~$zipcode`"></input>
	<input type="hidden" name="surl" value="~$surl`"></input>
	<input type="hidden" name="furl" value="~$furl`"></input>
	<input type="hidden" name="curl" value="~$curl`"></input>
	<input type="hidden" name="hash" value="~$hash`"></input>
	<input type="hidden" name="device" value="~$device`"></input>
	<input type="hidden" name="drop_category" value="~$drop_category`"></input>
	<input type="hidden" name="pg" value="~$pg`"></input>
	<input type="hidden" name="custom_note" value="~$custom_note`"></input>
	<!-- end parameters -->
</form>
<script type="text/javascript">
 $(function(){
	 var vhgt = $( window ).height();
	 $('div.outerdiv').css( "height", vhgt );
 })
</script>
</body>
</html>