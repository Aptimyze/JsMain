~include_partial('global/header')`

<form name="insertForm" id = "formForReportAbuse" onsubmit="reportAbuseForUserFun();return false;">
	<table width=900 align=center >
		<tr class="formhead" align=center><td colspan=5>Report Abuse For User Process</tr>			
				<br>

					<tr class="formhead" align=center>
                                        <td class=fieldsnew width=40% >
						 <b>Reporter Profile ID</b> * 
					</td>
                                        <td class='fieldsnew formfield' width=60% colspan=100%>
						<input size=30% type='text' name='reporter' id = 'reporterProfileId'">
                                        </td>
                                       <td class=fieldsnew colspan=100%><font size=2 color="red"><b style="display: none" id ="reporterNp">Incorrect Reporter Id.  </b></font></td>
                                </tr>

                                <tr class="formhead" align=center>
                                        <td class=fieldsnew width=40% >
						 <b>Reportee Profile ID</b> * 
					</td>
                                        <td class='fieldsnew formfield' width=60% colspan=100%>
						<input size=30% type='text' name='reportee' id = 'reporteeProfileId' >
                                        </td>
                                        <td class=fieldsnew colspan=100%><font size=2 color="red"><b style="display: none" id ="reporteeNp">Incorrect Reportee Id.  </b></font></td>
                                </tr>

                                <tr class="formhead" align=center>
                                        <td class=fieldsnew width=40% >
						<b>Reason</b> *
					</td>
                                        <td class='fieldsnew formfield' width=60% colspan=100%>
						<textarea rows=3 cols=35 name='reason' id ='reasonId'></textarea>
                                        </td>
                                </tr>

                                   <tr class="formhead" align=center>
                                        <td class='fieldsnew formfield' width=60% colspan=100%>
						<input size=30% type='hidden' name='crmUser' id = 'crmUserId' value = ~$crmUser`>
                                        </td>
                                </tr>
				<tr align=center>
					<td class=fieldsnew colspan=100% align="right">
						<button type="submit" >Report Abuse</button>
					</td>
				</tr>
	</table>
</form>

<tr align=center><td class=fieldsnew colspan=100%><font size=2><b style="display: none" align = "CENTER" id ="successfullDisplay">The Person has been reported abuse successfully.  </b></font></td></tr>

<tr align="center"><td class=fieldsnew colspan=100%><font size=2><b style="display: none" align = "CENTER" id ="invalidEntries">Either Entry already exists for this profile or Internal Server Error.  </b></font></td></tr>

~include_partial('global/footer')`
