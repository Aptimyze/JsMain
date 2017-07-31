~include_partial('global/header')`
<div>
	<br>
	<div style="background-color:lightblue;text-align:center;font-size:12px;width:80%;margin-left:131px;">
		<div style="font-weight:bold;"><font size=4px>Follow Ups&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight:normal;">~$unscreenedClientsCount`</font></span></div>
		~if $infoMsg`
			<br>
			<div>~$infoMsg`</div>
		~/if`
	</div>
	<br>
	<table align="CENTER" width="150%" table-layout="auto">
		~include_partial("headerSubSection",["columnNamesArr"=>$columnNamesArr])`
		
		~foreach from=$followUpsPool item=valued key=sno`
		<tr align="CENTER" bgcolor="#fbfbfb" id="followUp~$sno`">
			<td height="21" align="CENTER">~($sno+1)`</td>
			<td height="21" align="CENTER">~$valued.CLIENT_ID`</td>
		    <td height="21" align="CENTER"><a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$valued.CLIENT_ID`" target="_blank">~$valued.CLIENT_ID`</a></td>	
		    <td height="21" align="CENTER">~$valued.MEMBER_ID`</td>
		    <td height="21" align="CENTER">~$valued.MEMBER_PHONE1`</td>
		    <td height="21" align="CENTER">~$valued.MEMBER_PHONE2`</td>
		    <td height="21" align="CENTER">~$valued.FOLLOWUP_1`</td>
		    <td height="21" align="CENTER">~$valued.FOLLOWUP_2`</td>
		    <td height="21" align="CENTER">~$valued.FOLLOWUP_3`</td>
		    <td height="21" align="CENTER"><div class="jsc-ExAllocate jsc-cursp" data="~$valued.PROFILEID`,~$valued.USERNAME`,~$valued.PHONE_MOB`,~$tabChosenDetails.ACTION`,~$valued.BILL_ID`" style="background-color:lightgrey;color:#d9475c;width: 50%"><b>STATUS</td></b></td>
		</tr>
		~/foreach`
		<tr bgcolor="#fbfbfb">
		    <td colspan="20" height="21">&nbsp; </td>
		</tr>
		<tr>
		    <td colspan="20" height="21">&nbsp; </td>
		</tr>
	</table>
</div>
~include_partial('global/footer')`