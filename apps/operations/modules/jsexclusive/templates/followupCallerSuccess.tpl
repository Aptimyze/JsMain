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
		
		~foreach from=$result item=valued key=id`
		<tr align="CENTER" bgcolor="#fbfbfb" id="exRow~$billid`">
			<td height="21" align="CENTER">~$key`+1</td>
			<td height="21" align="CENTER">~$valued.PROFILE_NAME`</td>
		    <td height="21" align="CENTER"><a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$valued.PROFILEID`" target="_blank">~$valued.USERNAME`</a></td>	
		    <td height="21" align="CENTER">~$valued.AGE`</td>
		    <td height="21" align="CENTER">~$valued.GENDER`</td>
		    <td height="21" align="CENTER">~$valued.MSTATUS`</td>
		    <td height="21" align="CENTER">~$valued.HEIGHT`</td>
		    <td height="21" align="CENTER">~$valued.RELIGION` : ~$valued.CASTE`</td>
		    <td height="21" align="CENTER">~$valued.INCOME`</td>
		    <!-- <td height="21" align="CENTER">~$valued.MATCHES`</td> -->
		    <td height="21" align="CENTER">~$valued.PHONE_MOB`</td>
		    <td height="21" align="CENTER">~$valued.EMAIL`</td>
		    <td height="21" align="CENTER" id="EXCLUSIVE_~$valued.BILL_ID`">~$valued.BILLING_DT`</td>
		    <td height="21" align="CENTER">~$valued.SERVICE_DURATION`</td>
		    <td height="21" align="CENTER">~$valued.EXPIRY_DT`</td>
		    <td height="21" align="CENTER">~$valued.SALES_PERSON`</td>
		    ~if $tabChosenDetails.ACTION eq "UNASSIGN"`
		    	<td height="21" align="CENTER" id="UNASSIGN~$valued.BILL_ID`">~$valued.ASSIGNED_TO`</td>
		    ~else`
		    	<td height="21" align="CENTER" id="ASSIGN~$valued.BILL_ID`" width=20%>
		    		<select name="executiveList_~$valued.BILL_ID`">
						<option value="">Please Select</option>
						~foreach from=$ExPmSrExecutivesList key=dkey item=dvalue`
							<option value="~$dkey`">~$dvalue.USERNAME`</option>
						~/foreach`
					</select>
				</td>
		    ~/if`
		    <td height="21" align="CENTER"><div class="jsc-ExAllocate jsc-cursp" data="~$valued.PROFILEID`,~$valued.USERNAME`,~$valued.PHONE_MOB`,~$tabChosenDetails.ACTION`,~$valued.BILL_ID`"><b>STATUS</td></b></td>
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