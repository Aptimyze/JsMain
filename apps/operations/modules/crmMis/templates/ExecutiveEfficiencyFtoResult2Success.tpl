<html>
<head>
   <title>Jeevansathi.com - MIS</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<script>

function page_load(to_page)
{
        var page="~$CUR_PAGE`&j="+to_page;
        //page=page+"&date_search_submit=1&date1xx=~$date1`&&date2xx=~$date2`";
        document.location=page;
}

</script>
<style>
DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
</style>

</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
        <td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
</tr>
<tr>
        <td align="center" class="label">
	<a href="/mis/mainpage.php?cid=~$cid`">Mainpage</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	~if $RESULT || $err`
	<a href="/mis/fto_efficiency_report.php?cid=~$cid`&outside=~$outside`">Back</a>	
	~/if`
	</td>
</tr>
</table>

<br>
  <table width="100%" border="0" cellpadding="4" cellspacing="4" align="center">
	<tr class="formhead" align="center" style="background-color:lightblue";>
                <td colspan="100%">FTA FTO Executive Efficiency MIS</td>
        </tr>
	<tr class="formhead" align="center">
		<td colspan="100%">~$head_label`</td>
	</tr>
	<tr class="formhead" align="center">
                <td colspan="100%">Executive Name: ~$allotedTo`</td>
        </tr>
	<tr class="label" align="center">
		<td></td>
		~foreach from=$labelArr key=k item=v`
			<td>~$v.NAME`</td>
		~/foreach`
	</tr>
	<tr align="center">
			<td class="fieldsnew"><font color="red"><b>~$name`</b></font></td>
	</tr>	
	~foreach from=$dataArr item=data_val1 key=d1`	
		<tr align="center">
			~assign var="index" value=$d1 +1`
			<td class="fieldsnew"></td>
			<td class="fieldsnew">~$index`</a></td>
		        <td class="fieldsnew">~$data_val1.USERNAME`</a></td>
			<td class="fieldsnew">~$data_val1.ALLOT_TIME`</td>
			<td class="fieldsnew">~$data_val1.PHOTO_DT`</td>
                        <td class="fieldsnew">~$data_val1.PHONE_VERIFY_DT`</td>
                        <td class="fieldsnew">~$data_val1.FTO_OFFER_DT`</td>
                        <td class="fieldsnew">~$data_val1.FIRST_EOI_DT`</td>
			<td class="fieldsnew">~if $incentiveEligible eq Y`~$data_val1.FTO_INCENTIVE_DT`~else`~$data_val1.FTO_ACTIVATION_DT`~/if`</td>
			<td class="fieldsnew">~$data_val1.DEALLOCATION_DT`</td>
		</tr>
	~/foreach`
  </table>
</body>
</html>
