<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	        <td valign="top" width="40%" align="center"><img src="~sfConfig::get('app_img_url')`/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0"></td>
	</tr>
	<tr>
                <td align="center" class="label"><font size=2>
                        <a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?name=~$agentName`&cid=~$cid`">Mainpage</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </font></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr class="formhead" align="center">
                <td style="background-color:lightblue"><font size=3>Field Sales Executive Performance MIS</font></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr class="formhead" align="center">
                <td style="background-color:lightGray">
			<font size=2>
				~$header`
			</font>
		</td>
        </tr>
        <tr><td>&nbsp;</td></tr>
</table>
<br />
~if $details eq 'VD' || $details eq 'ALL'`
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
                <td align="center" class="label"><font size=2>
                        Executive Name: <b>~$exec`</b>
                </font></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
	<tr>
                <td align="center" class="label"><font size=2>
                        	Date of Fresh Visit: <b>~$currentDateRange`</b>
                </font></td>
        </tr>
</table>
<br />
        <table width=100%>
        <tr class=formhead style="background-color:LightSteelBlue">
                <td width=4% align=center>Sl.No.</td>
                <td width=4% align=center>Username</td>
                <td width=4% align=center>Date of Allocation</td>
                <td width=4% align=center ~if $details eq 'VD' || $details eq 'ALL'`style="background-color:PaleGreen"~/if`>Date of Fresh Visit</td>
                <td width=4% align=center ~if $details eq 'PP'`style="background-color:PaleGreen"~/if`>Date of Payment</td>
                <td width=4% align=center ~if $details eq 'SL'`style="background-color:PaleGreen"~/if`>Amount</td>
        </tr>
	
	~foreach from=$execDetailsArr1 item=execDetails name=info`
		<tr style="background-color:Moccasin">
                        <td width=4% align=center>~$smarty.foreach.info.index+1`</td>
			<td width=4% align=center>~$execDetails.USERNAME`</td>
                        <td width=4% align=center>~$execDetails.ALLOCATION_DATE`</td>
                        <td width=4% align=center>~$execDetails.FRESH_VISIT_DATE`</td>
                        <td width=4% align=center>~$execDetails.PAYMENT_DATE`</td>
                        <td width=4% align=center>~$execDetails.AMOUNT`</td>
		</tr>
	~/foreach`
	</table>
<br />
~/if`
~if $details eq 'PP' || $details eq 'SL' || $details eq 'ALL'`
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
                <td align="center" class="label"><font size=2>
                        Executive Name: <b>~$exec`</b>
                </font></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
                <td align="center" class="label"><font size=2>
                                Date of Payment: <b>~$currentDateRange`</b>
                </font></td>
        </tr>
</table>
<br />
        <table width=100%>
        <tr class=formhead style="background-color:LightSteelBlue">
                <td width=4% align=center>Sl.No.</td>
                <td width=4% align=center>Username</td>
                <td width=4% align=center>Date of Allocation</td>
                <td width=4% align=center ~if $details eq 'VD'`style="background-color:PaleGreen"~/if`>Date of Fresh Visit</td>
                <td width=4% align=center ~if $details eq 'PP' || $details eq 'ALL'`style="background-color:PaleGreen"~/if`>Date of Payment</td>
                <td width=4% align=center ~if $details eq 'SL'`style="background-color:PaleGreen"~/if`>Amount</td>
        </tr>
	
	~foreach from=$execDetailsArr2 item=execDetails name=info`
		<tr style="background-color:#F0F0F0">
                        <td width=4% align=center>~$smarty.foreach.info.index+1`</td>
                        <td width=4% align=center>~$execDetails.USERNAME`</td>
                        <td width=4% align=center>~$execDetails.ALLOCATION_DATE`</td>
                        <td width=4% align=center>~$execDetails.FRESH_VISIT_DATE`</td>
                        <td width=4% align=center>~$execDetails.PAYMENT_DATE`</td>
                        <td width=4% align=center>~$execDetails.AMOUNT`</td>
		</tr>
	~/foreach`
	</table>
<br />
~/if`
</html>
