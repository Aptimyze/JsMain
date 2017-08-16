~include_partial('global/header',["showExclusiveServicingBack"=>'Y'])`
<div>
	<br>
	<div style="background-color:lightblue;text-align:center;font-size:12px;width:80%;margin-left:131px;">
		<div style="font-weight:bold;"><font size=4px>Pending Concalls &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight:normal;">~$totalCount`</font></span></div>
		~if $infoMsg`
			<br>
			<div style="align:center;">~$infoMsg`</div>
		~/if`
	</div>
	<br>
	<table align="CENTER" width="100%" table-layout="auto">
		~include_partial("headerSubSection",["columnNamesArr"=>$columnNamesArr])`
                ~foreach from=$displayData item=value key=sno`
			
			<tr align="CENTER" bgcolor="#fbfbfb" id="asd">
				<td height="21" align="CENTER">~$value.SNO`</td>
				<td height="21" align="CENTER">~$value.CONCALL_SCH_DT`</td>
                                <td height="21" align="CENTER"><a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$value.CLIENT_ID`" target="_blank">~$value.CLIENT_USERNAME`</a></td>
                                <td height="21" align="CENTER">~$value.CLIENT_NAME`</td>
                                <td height="21" align="CENTER">~$value.CLIENT_PH1`</td>
                                <td height="21" align="CENTER">~$value.CLIENT_PH2`</td>
                                <td height="21" align="CENTER"><a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$value.MEMBER_ID`" target="_blank">~$value.MEMBER_USERNAME`</a></td>
                                <td height="21" align="CENTER">~$value.MEMBER_NAME`</td>
                                <td height="21" align="CENTER">~$value.MEMBER_PH1`</td>
                                <td height="21" align="CENTER">~$value.MEMBER_PH2`</td>
                                <td height="21" align="CENTER"><input type="button" onclick="if(confirm('Are you sure the call was completed?')){location.href='/operations.php/jsexclusive/pendingConcalls?name=~$name`&cid=~$cid`&executedFor=~$value.ID`'}" value="Concall Executed"></td>
			</tr>
                        ~/foreach`
		<tr bgcolor="#fbfbfb">
		    <td colspan="20" height="21">&nbsp; </td>
		</tr>
            
	</table>
        <table align="center">
        <tr class="formhead" align="center" width="100%">
                <td colspan="3"  height="30">
                    <font size=1><input type="button" onclick="location.href='/operations.php/jsexclusive/menu'" value="Back"></font>
                </td>
        </tr>
        </table>
</div>
~include_partial('global/footer')`