~include_partial('global/header')`
<div>
	<br>
	<div style="background-color:lightblue;text-align:center;font-size:12px;width:80%;margin-left:131px;">
		<div style="font-weight:bold;"><font size=4px>Pending Concalls &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight:normal;">~$followUpsCount`</font></span></div>
		~if $infoMsg`
			<br>
			<div>~$infoMsg`</div>
		~/if`
	</div>
	<br>
	<table align="CENTER" width="150%" table-layout="auto">
		~include_partial("headerSubSection",["columnNamesArr"=>$columnNamesArr])`
		
		~if $finalFollowUpsPool && $finalFollowUpsPool.followUpData`
			~foreach from=$finalFollowUpsPool.followUpData item=valued key=sno`
			<tr align="CENTER" bgcolor="#fbfbfb" id="followUp~$sno`">
				<td height="21" align="CENTER">~($sno+1)`</td>
				<td height="21" align="CENTER">
				~if $finalFollowUpsPool.clientsData[$valued.CLIENT_ID]`<a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$valued.CLIENT_ID`" target="_blank">~$finalFollowUpsPool.clientsData[$valued.CLIENT_ID].USERNAME`</a>~/if`</td>
			    <td height="21" align="CENTER">
			    ~if $finalFollowUpsPool.clientsData[$valued.CLIENT_ID]`~$finalFollowUpsPool.clientsData[$valued.CLIENT_ID].NAME`~/if`</td>	
			    <td height="21" align="CENTER">~if $finalFollowUpsPool.membersData[$valued.MEMBER_ID]`<a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$valued.MEMBER_ID`" target="_blank">~$finalFollowUpsPool.membersData[$valued.MEMBER_ID].USERNAME`</a>~/if`</td>
			    <td height="21" align="CENTER">~if $finalFollowUpsPool.membersData[$valued.MEMBER_ID]`~$finalFollowUpsPool.membersData[$valued.MEMBER_ID].PHONE_MOB`~/if`</td>
			    <td height="21" align="CENTER">~if $finalFollowUpsPool.membersData[$valued.MEMBER_ID]`~$finalFollowUpsPool.membersData[$valued.MEMBER_ID].ALT_MOBILE`~/if`</td>
			    <td height="21" align="CENTER">~$valued.AGENT_USERNAME`</td>
			    <td height="21" align="CENTER">~$valued.FOLLOWUP_1`</td>
			    <td height="21" align="CENTER">~$valued.FOLLOWUP_2`</td>
			    <td height="21" align="CENTER">~$valued.FOLLOWUP_3`</td>
			    <td height="21" align="CENTER"><div class="jsc-ExStatus jsc-cursp" data="~$valued.ID`" style="background-color:lightgrey;color:#d9475c;width:50%"><b>STATUS</td></b></td>
			</tr>
			~/foreach`
		~/if`
		<tr bgcolor="#fbfbfb">
		    <td colspan="20" height="21">&nbsp; </td>
		</tr>
		<tr>
		    <td colspan="20" height="21">&nbsp; </td>
		</tr>
	</table>
</div>
~include_partial('global/footer')`