<html>
<head>
<title>Redirection Error</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, , initial-scale=1.0">
<meta name="description" content=""/>
<link href="/css/fonts/fonts.css" rel="stylesheet" type="text/css"/>
</head>
<body bgcolor="#ffffff" onLoad="document.form1.submit();"> 
<!-- <body bgcolor="#ffffff" onLoad=""> -->
<div class="outerdiv" style="display:table; margin:0 auto; height:100%;">
	<div class="fontlig" style="color:#d9475c; display:table-cell; vertical-align:middle">
		<div style="font-size:32px;" class="">Something went wrong !</div>
		<div style="font-size:15px">Redirecting you to login page, please wait ...</div>
	</div>
</div>
<form id="form1" name="form1" action="/static/logoutPage" method="POST"></form>
<script type="text/javascript">
 $(function(){
	 var vhgt = $( window ).height();
	 $('div.outerdiv').css( "height", vhgt );
 })
</script>
</body>
</html>