~include_partial('global/header')`
<div id="mainContent">
	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		<tr class="formhead" align="center">
			<td colspan="2" style="background-color:lightblue"><font size=3>PENDING VISITS INTERFACE</font></td>
		</tr>
		<tr></tr>
	</table>
	<table width=100% align="CENTER">
		~include_partial("crmAllocation/headerSubSection",["columnNamesArr"=>$columnNamesArr])`
		~foreach from=$result item=valued key=profileid`
		<tr align="CENTER" bgcolor="#fbfbfb" id="FSRow~$valued.USERNAME`">
		    <td height="21" align="CENTER">~$valued.USERNAME`</td>
		    <td height="21">~$valued.PHONE_MOB`</td>
		    <td height="21" align="CENTER">~$valued.EMAIL`</td>
		    <td height="21">~$valued.LOCATION`</td>
		    <td height="21">~$valued.REQUESTED_BY`</td>
		    <td height="21">~$valued.REQUESTED_VISIT_DT`</td>
		    <td height="21"><div class="jsc-FSRemove jsc-cursp" data="~$valued.USERNAME`"><b>Remove</td></b></td>
		</tr>
		~/foreach`
		<tr bgcolor="#fbfbfb">
		    <td colspan="7" height="21">&nbsp; </td>
		</tr>
		<tr>
		    <td colspan="7" height="21">&nbsp; </td>
		</tr>
	</table>
</div>
~include_partial('global/footer')`