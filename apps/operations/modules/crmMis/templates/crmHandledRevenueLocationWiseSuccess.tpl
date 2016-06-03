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

        <table width=100% align=center>
        <tr class=formhead style="background-color:LightSteelBlue">
                <td width=4% align=center>Center/Executive</td>
                <td width=4% align=center>Employee ID</td>
                <td width=4% align=center>Target</td>
                <td width=4% align=center>Sales(without tax)</td>
                <td width=4% align=center>Target Achievement</td>
                ~foreach from=$ddarr item=dd`
	                <td width=3% align=center>~$dd`</td>
                ~/foreach`
                <td width=4% align=center>Total Sales</td>
        </tr>
        ~foreach from=$location item=it key=loc`
               <tr class=formhead><td width=4% align=center style="background-color:LightSalmon"><b>~$loc`</b></td></tr>
        	~foreach from=$it.USERNAME item=uu`
               		<tr class=formhead>
				<td width=4% align=center><a href="/crm/ncr_individual_operator_detail_sales_new.php?cid=~$cid`&opsname=~$uu`&yy=~$yearName`&mm=~$monthNum`">~$uu`</a></td>
                          <td width=4% align=center>~$empDetailArr[$uu]`</td>
               		        <td width=4% align=center>~if $detail[$uu]['INDIVIDUAL_TARGET'] neq 0` ~$detail[$uu]['INDIVIDUAL_TARGET']` ~else` ~if $blank_cells_past eq 1` 0 ~/if`  ~/if`</td>
               		        <td width=4% align=center>~if $detail[$uu]['SALES_WITHOUT_TAX'] neq 0` ~$detail[$uu]['SALES_WITHOUT_TAX']` ~else` ~if $blank_cells_past eq 1 or $blank_cells_curr eq 1` 0 ~/if` ~/if`</td>
               		        <td width=4% align=center>~if $detail[$uu]['TARGET_ACHIEVEMENT'][0] neq 0`<font color=~$detail[$uu]['TARGET_ACHIEVEMENT'][1]`>~$detail[$uu]['TARGET_ACHIEVEMENT'][0]`</font> ~else` ~if $blank_cells_past eq 1` 0 ~/if` ~/if`</td>
        			~foreach from=$detail[$uu]['AMOUNT'] name=info item=aa`
					<td width=4% align=center>~if $aa neq 0` ~$aa` ~else` ~if $blank_cells_past eq 1 or $blank_cells_curr eq 1 and $ddarr[$smarty.foreach.info.index] le $dt_curr` 0 ~/if` ~/if`</td>
        			~/foreach`
               		        <td width=4% align=center>~if $detail[$uu]['TOTAL_AMOUNT'] neq 0` ~$detail[$uu]['TOTAL_AMOUNT']` ~else` ~if $blank_cells_past eq 1 or $blank_cells_curr eq 1` 0 ~/if` ~/if`</td>
			</tr>
        	~/foreach`
               <tr class=formhead style="background-color:Moccasin">
			<td width=4% align=center><font color=DarkRed>~$loc` Total</font></td><td></td>
			<td width=4% align=center>~if $it.TOTAL_TARGET neq 0` ~$it.TOTAL_TARGET` ~else` ~if $blank_cells_past eq 1` 0 ~/if` ~/if`</td>
			<td width=4% align=center>~if $it.TOTAL_SALES neq 0` ~$it.TOTAL_SALES` ~else` ~if $blank_cells_past eq 1 or $blank_cells_curr eq 1` 0 ~/if` ~/if`</td>
			<td width=4% align=center>~if $it.ACHIEVEMENT[0] neq 0`<font color=~$it.ACHIEVEMENT[1]`>~$it.ACHIEVEMENT[0]`</font> ~else` ~if $blank_cells_past eq 1` 0 ~/if` ~/if`</td>
        		~foreach from=$it.DAYWISE_AMOUNT name=info item=da`
				<td width=4% align=center>~if $da neq 0` ~$da` ~else` ~if $blank_cells_past eq 1 or $blank_cells_curr eq 1 and $ddarr[$smarty.foreach.info.index] le $dt_curr` 0 ~/if` ~/if`</td>
        		~/foreach`
			<td width=4% align=center>~if $it.TOTAL_AMOUNT neq 0` ~$it.TOTAL_AMOUNT` ~else` ~if $blank_cells_past eq 1 or $blank_cells_curr eq 1` 0 ~/if` ~/if`</td>
	       </tr>
	       <tr></tr>
	       <tr></tr>
        ~/foreach`
        <tr class=formhead style="background-color:PaleGreen">
		<td width=4% align=center><b>GRAND TOTAL</b></td><td></td>
		<td width=4% align=center>~if $overall.TARGET neq 0` ~$overall.TARGET` ~else` ~if $blank_cells_past eq 1` 0 ~/if` ~/if`</td>
		<td width=4% align=center>~if $overall.SALES neq 0` ~$overall.SALES` ~else` ~if $blank_cells_past eq 1 or $blank_cells_curr eq 1` 0 ~/if` ~/if`</td>
		<td width=4% align=center>~if $overall.ACHIEVEMENT[0] neq 0`<font color=~$overall.ACHIEVEMENT[1]`>~$overall.ACHIEVEMENT[0]`</font> ~else` ~if $blank_cells_past eq 1` 0 ~/if` ~/if`</td>
       		~foreach from=$overall.DAYWISE_AMOUNT name=info item=da`
			<td width=4% align=center>~if $da neq 0` ~$da` ~else` ~if $blank_cells_past eq 1 or $blank_cells_curr eq 1 and $ddarr[$smarty.foreach.info.index] le $dt_curr` 0 ~/if` ~/if`</td>
       		~/foreach`
		<td width=4% align=center>~if $overall.AMOUNT neq 0` ~$overall.AMOUNT` ~else` ~if $blank_cells_past eq 1 or $blank_cells_curr eq 1` 0 ~/if` ~/if`</td>
       </tr>
       </table>
</body>
</html>
