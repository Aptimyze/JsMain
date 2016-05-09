~include_partial('global/header')`
<div id="mainExContent">
	~include_partial("exclusiveMemberCommonHeader",["activeExTab"=>$tabChosenDetails.TABID,"tabDetails"=>$tabDetails,user=>$user,cid=>$cid])`

	<table width=100% align="CENTER">
		~include_partial("headerSubSection",["columnNamesArr"=>$columnNamesArr])`
		
		~foreach from=$result item=valued key=profileid`
		<tr align="CENTER" bgcolor="#fbfbfb" id="exRow~$valued.PROFILEID`">
		    <td height="21" align="CENTER">~$valued.USERNAME`</td>
		    <td height="21">~$valued.PHONE_MOB`</td>
		    <td height="21" align="CENTER">~$valued.EMAIL`</td>
		    <td height="21">~$valued.BILLING_DT`</td>
		    ~if $tabChosenDetails.ACTION eq "UNASSIGN"`
		    	<td height="21">~$valued.ASSIGNED_TO`</td>
		    ~else`
		    	<td height="21" id="ASSIGN~$valued.PROFILEID`">
		    		<select name="executiveList_~$valued.PROFILEID`">
						<option value="">Please Select</option>
						~foreach from=$ExPmSrExecutivesList key=dkey item=dvalue`
							<option value="~$dkey`">~$dvalue.USERNAME`</option>
						~/foreach`
					</select>
				</td>
		    ~/if`
		    <td height="21"><div class="jsc-ExAllocate jsc-cursp" data="~$valued.PROFILEID`,~$valued.USERNAME`,~$valued.PHONE_MOB`,~$tabChosenDetails.ACTION`"><b>~$tabChosenDetails.ACTION`</td></b></td>
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
<script>
	var executivesdata = ~$executivesData|decodevar`;
</script>