~include_partial('global/header',["showExclusiveServicingBack"=>'Y'])`
<div>
	<br>
	<div style="background-color:lightblue;text-align:center;font-size:12px;width:80%;margin-left:131px;">
		<div style="font-weight:bold;"><font size=4px>Active Clients &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight:normal;">~$count`</font></span></div>
	</div>
	<br>
	<table align="CENTER" width="100%" table-layout="auto">
		~include_partial("headerSubSection",["columnNamesArr"=>$columnNamesArr])`
                ~foreach from=$dataArray item=value key=clientid`
			<tr align="CENTER" bgcolor="#fbfbfb" id="asd">
								<td height="21" align="CENTER"><a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$value.CLIENT_ID`" target="_blank">~$value.USERNAME`</td>
                                <td height="21" align="CENTER">~$value.CLIENT_NAME`</a></td>
                                <td height="21" align="CENTER">~$value.ASSIGNED_DT`</td>
                                <td height="21" align="CENTER">~$value.SERVICE_DAY`</td>
                                <td height="21" align="CENTER">~$value.EXPIRY_DT`</td>
			</tr>
                        ~/foreach`
		<tr bgcolor="#fbfbfb">
		    <td colspan="20" height="21">&nbsp;</td>
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