<html>
<head>
   	<title>Jeevansathi.com - MIS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
	<style>
	DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
	</style>
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <input type="hidden" name="monthName" value="~$monthName`">
        <input type="hidden" name="yearName" value="~$yearName`">
<table width="100%" align="center">
	<tr>
	        <td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
	</tr>
  <tr class="formhead" align="center">
          <td colspan="2" style="background-color:lightblue"><font size=3>Channel-wise MIS</font></td>
  </tr>
  <tr class="formhead" align="center">
          <td colspan="2" style="background-color:PeachPuff"><font size=2>For the ~if $range_format eq 'MY'`month of~else`period~/if` ~$displayDate`</font></td>
  </tr>
  <tr class="formhead" align="center">
          <td colspan="2"><font size=2>Currency Unit : ~if $currencyUnit eq 'ALL' or !$currencyUnit` Total(Converted in Rs.) ~else` ~$currencyUnit` ~/if`</font></td>
  </tr>
</table>

        <table width=100% align=center>
        <tr class=formhead style="background-color:LightSteelBlue">
                <td width=4% align=center>Gateway</td>
                <td width=4% align=center>Desktop</td>
                <td width=4% align=center>Mobile Website</td>
                <td width=4% align=center>Android App</td>
                <td width=4% align=center>iOS App</td>
                <td width=4% align=center>Total</td>
        </tr>
        ~foreach from=$info item=gg key=gateway`
          <tr class=formhead>
            <td width=4% align=center><b>~$gateway`</b></td>
            <td width=4% align=center>~$gg.desktop`</td>
            <td width=4% align=center>~$gg.mobile_website`</td>
            <td width=4% align=center>~$gg.Android_app`</td>
            <td width=4% align=center>~$gg.iOS_app`</td>
            <td width=4% align=center>~$gg.TOTAL`</td>
    			</tr>
        ~/foreach`
        <tr class=formhead style="background-color:PaleGreen">
            <td width=4% align=center><b>GRAND TOTAL</b></td>
            <td width=4% align=center><b>~$sourceArr.desktop`</b></td>
            <td width=4% align=center><b>~$sourceArr.mobile_website`</b></td>
            <td width=4% align=center><b>~$sourceArr.Android_app`</b></td>
            <td width=4% align=center><b>~$sourceArr.iOS_app`</b></td>
            <td width=4% align=center><b>~$sourceArr.TOTAL`</b></td>
       </tr>
       </table>
</body>
</html>
