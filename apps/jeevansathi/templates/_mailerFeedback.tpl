<table border="0" cellspacing="0" cellpadding="0" width="93%"  align="center" style="text-align:left">
	<tr>
		<td colspan="3" height="27" align="left" style="font-size:14px;"> Do you find the matches shown in this Email relevant?</td>
	</tr>
	<tr>
		<td height="27" align="left" width="500" style="font-size:14px;"> <a href= "~$mailerLinks['MATCHALERT_FEEDBACK']`~$commonParamaters`?matchAlertLink=~if $fromVsp eq 1`~$mailerLinks['HOME_PAGE_MYJS']`~else`~$mailerLinks['MATCH_ALERT']`~/if`&stype=~$stype`&mailSentDate=~$mailSentDate`&feedbackValue=Y" target="_blank" style="text-decoration:none;">Yes, I find them relevant</a></td>
		<td height="27" align="left" width="500" style="font-size:14px;"><a href= "~$mailerLinks['MATCHALERT_FEEDBACK']`~$commonParamaters`?~if $fromVsp eq 1`matchAlertLink=~$mailerLinks['HOME_PAGE_MYJS']`&~/if`stype=~$stype`&mailSentDate=~$mailSentDate`&feedbackValue=N" target="_blank" style="text-decoration:none;">No, they are not relevant</a></td>
		<tr height="15">
		</tr>
	</tr>
</table>