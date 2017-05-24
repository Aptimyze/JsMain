~include_partial('global/header')`

<form action="~sfConfig::get('app_site_url')`/operations.php/commoninterface//negativeHandler?cid=~$cid`" method="POST" name="insertForm">
	<input type=hidden name=cid value="~$cid`">
	<input type=hidden name=actionType value="~$actionType`">
	<table width=900 align=center >
		<tr class="formhead" align=center><td colspan=5>Negative Treatment Process</tr>

			~if $successMessage`
				<tr align=center><td class=fieldsnew colspan=100%><font size=2>
					~if $msgContent neq ''`	
						~$msgContent`
					~/if`
				</font></td></tr>
				<tr align=center><td class=fieldsnew colspan=100%><font size=2><b>
					<a href="~sfConfig::get('app_site_url')`/operations.php/commoninterface/negativeHandler?cid=~$cid`&actionType=~$actionType`">Back</a></b>
				</font></td></tr>			
			~else`
				<tr class="formhead" align=center>
					<td class=fieldsnew width=40%><b>Select Type</b> (Phone Number/Email/Username) *</td>	
					<td class=fieldsnew width=6% colspan=100%>
						<select name=dataArr[negativeType]>
	                                        	~foreach from=$negativeTypeDropdown key=k item=v`
        	                                        	<option value="~$k`" ~if $dataArr.negativeType eq $k` selected ~/if` >~$v`</option>
        	                                	~/foreach`
						</select>

					</td>
				</tr>
				<br>
                                <tr class="formhead" align=center>
                                        <td class=fieldsnew width=40% >
						~if $errorArr.negativeVal`<font color='red'>~/if`
						 <b>Value</b> (phone number (along with ISD) /Email /Username) * 
					</td>
                                        <td class=fieldsnew width=60% colspan=100%>
						<input size=30% type='text' name=dataArr[negativeVal] value="~$dataArr.negativeVal`">
                                        </td>
                                </tr>
				<tr align=center>
					<td class=fieldsnew colspan=100%>
						~if $actionType eq 'D'`
							<input type=submit name=submit value="Delete">
						~elseif $actionType eq 'F'`
							<input type=submit name=submit value="Fetch">
						~/if`
						
					</td>
				</tr>
			~/if`
	</table>
</form>
~include_partial('global/footer')`
