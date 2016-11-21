~include_partial('global/header')`

<form action="~sfConfig::get('app_site_url')`/operations.php/commoninterface//negativeTreatment?cid=~$cid`" method="POST" name="insertForm">
	<input type=hidden name=cid value="~$cid`">
	<table width=900 align=center >
		<tr class="formhead" align=center><td colspan=5>Negative Treatment Process</tr>

			~if $successMessage`
				<tr align=center><td class=fieldsnew colspan=100%><font size=2><b>Negative treatment submission is successful. List will be updated and the profiles deleted within 5 minutes. </b></font></td></tr>
				<tr align=center><td class=fieldsnew colspan=100%><font size=2><b>
					<a href="~sfConfig::get('app_site_url')`/operations.php/commoninterface/negativeTreatment?cid=~$cid`">Add More</a></b>
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
						 <b>Value</b> (phone number (along with ISD) /Email /USERNAME) * 
					</td>
                                        <td class=fieldsnew width=60% colspan=100%>
						<input size=30% type='text' name=dataArr[negativeVal] value="~$dataArr.negativeVal`">
                                        </td>
                                </tr>
                                <tr class="formhead" align=center>
                                        <td class=fieldsnew width=40% >
						~if $errorArr.comment`<font color='red'>~/if`
						<b>Comments</b> *
					</td>
                                        <td class=fieldsnew width=60% colspan=100%>
						<textarea rows=3 cols=35 name=dataArr[comment]>~$dataArr.comment`</textarea>
                                        </td>
                                </tr>
				<tr align=center>
					<td class=fieldsnew colspan=100%>
						<input type=submit name=submit value="Submit">
					</td>
				</tr>
			~/if`
	</table>
</form>
~include_partial('global/footer')`
