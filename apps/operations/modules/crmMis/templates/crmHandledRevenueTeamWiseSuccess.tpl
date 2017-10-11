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
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	        <td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
	</tr>
        <tr class="formhead" align="center">
                <td colspan="2" style="background-color:lightblue"><font size=3>CRM Handled Revenue MIS</font></td>
        </tr>
        <tr class="formhead" align="center">
                <td colspan="2" style="background-color:lightGray"><font size=2>For the month of ~$monthName` - ~$yearName` - ~if $fortnight eq 1`H1~else`H2 ~/if`</font></td>
        </tr>
        <tr class="formhead" align="center">
                <td colspan="2"><font size=2>Sales = Sales without tax * (1 + ~$TAX_RATE`%)</font></td>
        </tr>
</table>

	~if $overall_sales_head_check eq 1`
		<p align=center><font size=3 color=red><b> Please give 'Sales Head - Overall' privilege to one user. </b></font></p>	
	~/if`
	~if $overall_sales_head_check neq 1`
        <table width=100% align=center>
        <tr class=formhead style="background-color:LightSteelBlue">
                <td width=4% align=center>Manager/Supervisor/Executive</td>
                <td width=4% align=center>Employee ID</td>
                <td width=4% align=center>Target</td>
                <td width=4% align=center>Sales(without tax)</td>
                <td width=4% align=center>Target Achievement</td>
                ~foreach from=$ddarr item=dd`
	                <td width=3% align=center>~$dd`</td>
                ~/foreach`
                <td width=4% align=center>Total Sales</td>
        </tr>
        	~foreach from=$hierarchy item=uu`
               		<tr class=formhead style="background-color:~$detail[$uu.USERNAME]['COLOR']`">
				<td width=4%>
				~if $team[$uu.USERNAME]['IS_HYPERLINK']`
					~for $it=0 to $uu.LEVEL` &nbsp;&nbsp;&nbsp;~/for`
						<b><a href="/crm/ncr_individual_operator_detail_sales_new.php?cid=~$cid`&opsname=~$uu.USERNAME`&yy=~$yearName`&mm=~$monthNum`">~$uu.USERNAME`</a></b>
				~else` 
					~for $it=0 to $uu.LEVEL` &nbsp;&nbsp;&nbsp;~/for` 
						~$uu.USERNAME`
				~/if`
				</td>
                          <td width=4% align=center>~$empDetailArr[$uu.USERNAME]`</td>
               		        <td width=4% align=center>~if $team[$uu.USERNAME]['FINAL_TARGET'] neq 0` ~$team[$uu.USERNAME]['FINAL_TARGET']` ~/if`</td>
               		        <td width=4% align=center>~if $team[$uu.USERNAME]['SALES_WITHOUT_TAX'] neq 0` ~$team[$uu.USERNAME]['SALES_WITHOUT_TAX']` ~/if`</td>
               		        <td width=4% align=center>~if $team[$uu.USERNAME]['TARGET_ACHIEVEMENT'][0] neq 0`<font color=~$team[$uu.USERNAME]['TARGET_ACHIEVEMENT'][1]`>~$team[$uu.USERNAME]['TARGET_ACHIEVEMENT'][0]`</font>~/if`</td>
        			~foreach from=$team[$uu.USERNAME]['AMOUNT'] item=aa`
					<td width=4% align=center>~if $aa neq 0` ~$aa` ~/if`</td>
        			~/foreach`
               		        <td width=4% align=center>~if $team[$uu.USERNAME]['TOTAL_AMOUNT'] neq 0` ~$team[$uu.USERNAME]['TOTAL_AMOUNT']` ~/if`</td>
			</tr>
        	~/foreach`
       </table>
       ~/if`
</body>
</html>
