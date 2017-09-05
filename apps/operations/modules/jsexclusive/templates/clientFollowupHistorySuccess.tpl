~include_partial('global/header',["showExclusiveServicingBack"=>'Y'])`
<div>
	<br>
	<div style="background-color:lightblue;text-align:center;font-size:12px;width:80%;margin-left:131px;">
		<div style="font-weight:bold;"><font size=4px>Client Followup History &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></div>
	</div>
	<br>
	<table align="CENTER" width="100%" table-layout="auto">
		~include_partial("headerSubSection",["columnNamesArr"=>$columnNamesArr])`
                ~foreach from=$dataArray item=value key=clientid`
			<tr align="CENTER" bgcolor="#fbfbfb" id="asd">
				<td height="21" width="10%" align="CENTER">~$value.USERNAME` ~if $value.CLIENT_NAME` <br>(~$value.CLIENT_NAME`) ~/if`</td>
                <td height="21" width="10%" align="CENTER">~$value.ENTRY_DT`</a></td>
                <td height="21" width="20%" align="CENTER">~$value.FOLLOWUP1_DT` ~if $value.STATUS1`||<br>~$value.STATUS1`~/if` ~if $value.FOLLOWUP_1` ||<br>~$value.FOLLOWUP_1` ~/if`</td>
                <td height="21" width="20%" align="CENTER">~$value.FOLLOWUP2_DT` ~if $value.STATUS2`||<br>~$value.STATUS2`~/if` ~if $value.FOLLOWUP_2`||<br>~$value.FOLLOWUP_2` ~/if`</td>
                <td height="21" width="20%" align="CENTER">~$value.FOLLOWUP3_DT` ~if $value.STATUS3`||<br>~$value.STATUS3`~/if` ~if $value.FOLLOWUP_3`||<br>~$value.FOLLOWUP_3` ~/if`</td>
                <td height="21" width="20%" align="CENTER">~$value.FOLLOWUP4_DT` ~if $value.STATUS4`||<br>~$value.STATUS4`~/if` ~if $value.FOLLOWUP_4`||<br>~$value.FOLLOWUP_4` ~/if`</td>
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