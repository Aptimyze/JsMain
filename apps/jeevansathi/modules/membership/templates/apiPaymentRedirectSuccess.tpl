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
<form id="form1" name="form1" action="~sfConfig::get('app_site_url')`/profile/pg/~$pageRedirectTo`" method="post">
	<!-- Parameters required for payment -->
	<input type="hidden" name="mainSubMemId" value="~$mainSubMemId`"></input>
	<input type="hidden" name="track_memberships" value="~$track_memberships`"></input> 
	<input type="hidden" name="navigationString" value="~$navigationString`"/>
	<input type="hidden" name="service" value="~$service`"/>
	<input type="hidden" name="service_main" value="~$track_memberships`"/>
	<input type="hidden" name="user-type" value="~$userObj->userType`"/>
	<input type="hidden" name="paymode" value="~$paymode`"/>
	<input type="hidden" name="paymentTab" value="~$paymentTab`"/>
	<input type="hidden" name="profileid" value="~$profileid`"/>
	<input type="hidden" name="checksum" value="~$checksum`"/>
	<input type="hidden" name="USERNAME" value="~$USERNAME`"/>
	<input type="hidden" name="EMAIL" value="~$EMAIL`"/>
	<input type="hidden" name="PINCODE" value="~$PINCODE`"/>
	<input type="hidden" name="curtype" value="~$curtype`"/>
	<input type="hidden" name="type" value="~$type`"/>
	<input type="hidden" name="track_discount" value="~$track_discount`"/>
	<input type="hidden" name="track_total" value="~$track_total`"/>
	<input type="hidden" name="discountType" value="~$discountType`"/>
	<input type="hidden" name="specialActive" value="~$specialActive`"/>
	<input type="hidden" name="discountActive" value="~$discountActive`"/>
	<input type="hidden" name="fromPaymentTab" value="~$fromPaymentTab`"/>
	<input type="hidden" name="festActive" value="~$fest`"/>
	<input type="hidden" name="fromBackend" value="~$fromBackend`"/>
	<input type="hidden" name="backendId" value="~$backendId`"/>
	<input type="hidden" name="discountBackend" value="~$discountBackend`"/>
	<input type="hidden" name="backendCheckSum" value="~$backendCheckSum`"/>
	<input type="hidden" name="discSel" value="~$discSel`"/>
	<input type="hidden" name="couponCodeVal" value="~$couponCodeVal`"/>
	<input type="hidden" name="device" value="~$device`"/>
	<input type="hidden" name="cardOption" value="~$card_option`">	
	<input type="hidden" name="netBankingCards" value="~$net_banking_cards`"/>
	<input type="hidden" name="CCRDType" value="~$CCRDType`">
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
