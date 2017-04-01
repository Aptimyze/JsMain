<html>
<head>
<title>Payment gateway redirect page</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, , initial-scale=1.0">
<meta name="description" content=""/>
<link href="/css/fonts/fonts.css" rel="stylesheet" type="text/css"/>
</head>
<body bgcolor="#ffffff" onLoad="document.form1.submit();"> 
<!-- <body bgcolor="#ffffff" onLoad=""> -->
<div class="outerdiv" style="display:table; margin:0 auto; height:100%;">
	<div class="fontlig" style="color:#d9475c; display:table-cell; vertical-align:middle">
		<div style="font-size:42px;" class="">Re-directing</div>
		<div style="font-size:15px">Please wait ...</div>
	</div>
</div>
~if $pageRedirectTo eq 'payu'`
<form id="form1" name="form1" action="~$gatewayURL`" method="POST">
	<!-- Parameters required for payment -->
	<input type="hidden" name="key" value="~$key`"></input>
	<input type="hidden" name="txnid" value="~$txnid`"></input>
	<input type="hidden" name="amount" value="~$amount`"></input>
	<input type="hidden" name="productinfo" value="~$productinfo`"></input>
	<input type="hidden" name="udf1" value="~$udf1`"></input>
	<input type="hidden" name="udf2" value="~$udf2`"></input>
	<input type="hidden" name="udf3" value="~$udf3`"></input>
	<input type="hidden" name="udf4" value="~$udf4`"></input>
	<input type="hidden" name="udf5" value="~$udf5`"></input>
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
~else if $pageRedirectTo eq 'ccavenue'`
<form id="form1" name="form1" action="~$gatewayURL`" method="POST">
	<!-- Parameters required for payment -->
	~if $currency eq 'RS'`
		<input type="hidden" name='Merchant_Id' value="~$mid`">
		<input type="hidden" name='Amount' value="~$amount`">
		<input type="hidden" name='Order_Id' value="~$txnid`">
		<input type="hidden" name="billing_cust_tel" value="~$BILL_PHONE`"> 
		<input type="hidden" name='Redirect_Url' value="~$returnURL`">
		<input type="hidden" name='Checksum' value="~$ccavenueChecksum`">
		<input type="hidden" name="billing_cust_name" value="~$firstname`"> 
		<input type="hidden" name="billing_cust_address" value="~$address`"> 
		<input type="hidden" name="billing_cust_city" value="~$city_order`">
		<input type="hidden" name="billing_cust_country" value="~$country_order`"> 
		<input type="hidden" name="billing_cust_state" value="~$BILL_STATE`">
		<input type="hidden" name="billing_zip_code" value="~$BILL_PINCODE`">
		<input type="hidden" name="delivery_cust_tel" value=""> 
		<input type="hidden" name="billing_cust_tel" value="~$BILL_PHONE`"> 
		<input type="hidden" name="billing_cust_email" value="~$BILL_EMAIL`"> 
		<input type="hidden" name="delivery_cust_name" value="~$firstname`"> 
		<input type="hidden" name="delivery_cust_address" value="~$address`"> 
		<input type="hidden" name="delivery_cust_city" value="~$city_order`">
		<input type="hidden" name="delivery_cust_country" value="">
		<input type="hidden" name="delivery_cust_state" value="~$BILL_STATE`">
		<input type="hidden" name="delivery_zip_code" value="">
		<input type="hidden" name="delivery_cust_notes" value=""> 
		<input type="hidden" name="Merchant_Param" value="~$checksum`">
	~/if`
	~if $paymentMode eq 'NB' or $paymentMode eq 'CSH'`
		<input type="hidden" name="cardOption" value="~$card_option`">
		<input type="hidden" name="netBankingCards" value="~$net_banking_cards`">
		<input type="hidden" name="CCRDType" value="~$CCRDType`">
	~/if`
	~if $currency eq 'DOL'`
		<input type="hidden" name="encRequest" value="~$encRequest`">
		<input type="hidden" name="access_code" value="~$accessCode`">
	~/if`
	<!-- end parameters -->
</form>
~else if $pageRedirectTo eq 'paytm'`
<form id="form1" name="form1" action="~$gatewayURL`" method="POST">
	<input type=hidden name='MID' value="~$mid`">
    <input type=hidden name='ORDER_ID' value="~$txnid`">
    <input type=hidden name='CUST_ID' value="~$profileid`">
    <input type=hidden name='INDUSTRY_TYPE_ID' value="~$INDUSTRY_TYPE_ID`">
    <input type=hidden name='CHANNEL_ID' value="~$CHANNEL_ID`">
    <input type=hidden name="TXN_AMOUNT" value="~$TXN_AMOUNT`"> 
    <input type=hidden name="WEBSITE" value="~$WEBSITE`"> 
    <input type=hidden name="MERC_UNQ_REF" value="~$MERC_UNQ_REF`">
    <input type=hidden name="CHECKSUMHASH" value="~$CHECKSUMHASH`"> 
    <input type=hidden name="MOBILE_NO" value="~$MOBILE_NO`"> 
    <input type=hidden name="EMAIL" value="~$EMAIL`"> 
    <input type=hidden name="CALLBACK_URL" value="~$CALLBACK_URL`"> 
</form>
~else if $pageRedirectTo eq 'paypal'`
<form id="form1" name="form1" action="~$gatewayURL`" method="POST">
	<input type="hidden" name="cmd" value="~$cmd`">
	<input type="hidden" name="cancel_return" value="~$cancelURL`">
	<input type="hidden" name="return" value="~$returnURL`">
	<input type="hidden" name="business" value="~$mid`">
	<input type="hidden" name="item_name" value="~$PAYPALSERVICE`">
	<input type="hidden" name="item_number" value="~$PAYPALORDERID`">
	<input type="hidden" name="currency_code" value="USD">
	<input type="hidden" name="amount" value="~$PAYPALAMOUNT`">
	<input type="hidden" name="no_shipping" value="~$noShipping`">
	<input type="hidden" name="no_note" value="~$noNote`">
	<input type="hidden" name="rm" value="2">
	<input type="hidden" name="custom" value="~$PAYPALCHECKSUM`"></input>
</form>
~/if`
<script type="text/javascript">
 $(function(){
	 var vhgt = $( window ).height();
	 $('div.outerdiv').css( "height", vhgt );
 })
</script>
</body>
</html>