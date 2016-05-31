 <br>
 <div bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<br>

<table width=80% align="center" cellspacing=2 cellpadding=1 border=0>
<tr>
<td colspan=2 align=center><br><hr></td></tr>
<tr>
<td colspan=2 align=center>
~$msg`
</td>
</tr>
<tr><td>
<table width="60%"  border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
     <td height="15" colspan="2" width="100%" class="bgbrownL"><span class="mediumblackb"><b>&nbsp;Your profile is ~$profileCompletionScoreArr['PCS']`% complete</b></span></td>
    </tr>
<tr class="bggreyl">
<td class="mediumblack" width="10%">&nbsp;</td>
<td class="mediumblack">
		<div class="space15">&nbsp;</div>
		<div class="baro">
			<div class="barin" style="width:~$profileCompletionScoreArr['PCS']`%"><img src="~JsConstants::$imgUrl`/profile/images/bar_complete.gif" width="1" height="13"></div>
		</div>
		<div class="space15">&nbsp;</div>
</td></tr>
		~if $profileCompletionScoreArr['PCS'] neq 100`
		<tr class="bggreyl">
			<td class="mediumblack" width="10%">&nbsp;</td>
			<td class="mediumblack"><b>&nbsp;Make it 100% complete</b></td>
		</tr>
		~/if`
		~if $profCompScoreArr['havePhoto'] eq "N" || $profCompScoreArr['havePhoto'] eq ""`
		<tr class="bggreyl">
			<td class="mediumblack" width="10%">&nbsp;</td>
			<td class="mediumblack">&nbsp;Add your Photo ~if $contactDetailsArr['CONTACTS']['PHOTO_REQUEST_COUNT'] neq 0`<span class="greyele"> [ ~$contactDetailsArr['CONTACTS']['PHOTO_REQUEST_COUNT']` members requested your photo ]</span>~/if`
			</td>
		</tr>
		~/if`
		~foreach from=$profileCompletionScoreArr['msgDetails'] key=k item=val`
		~if $val neq null`
		<tr class="bggreyl">
			<td class="mediumblack" width="10%">&nbsp;</td>
			<td class="mediumblack">&nbsp;~$val`</td>
		</tr>
		~/if`
		~/foreach`
		
		~if $profCompScoreArr['photoDisplay'] neq 'A' && $profCompScoreArr['photoDisplay'] neq ''`
		<tr class="bggreyl">
			<td class="mediumblack" width="10%">&nbsp;</td>
			<td class="mediumblack">&nbsp;Make your Photo privacy - Visible to All</td>
		</tr>
		~/if`
		~if $yourEducation|count_characters:true lt 200`
		<tr class="bggreyl">
			<td class="mediumblack" width="10%">&nbsp;</td>
			<td class="mediumblack">&nbsp;Write more about your Education (min 200 characters)</td>
		</tr>
		~/if`
		~if $jobInfo|count_characters:true lt 200`
		<tr class="bggreyl">
			<td class="mediumblack" width="10%">&nbsp;</td>
			<td class="mediumblack">&nbsp;Write more about your Career (min 200 characters)</td>
		</tr>
		~/if`
		~if $familyInfo|count_characters:true lt 200`
		<tr class="bggreyl">
			<td class="mediumblack" width="10%">&nbsp;</td>
			<td class="mediumblack">&nbsp;Add more about your Family (min 200 characters)</td>
		</tr>
		~/if`
		~if $contactDetailsArr['CONTACTS']['AWAITING_RESPONSE']`
		<tr class="bggreyl">
			<td class="mediumblack" width="10%">&nbsp;</td>
			<td class="mediumblack">&nbsp;~$contactDetailsArr['CONTACTS']['AWAITING_RESPONSE']` + 0 people awaiting response say yes or no</td>
		</tr>
		~/if`
	<tr class="bggreyl"><td colspan="2" ><div class="space15">&nbsp;</div></td></tr>
</table></td></tr>
~if $profCompScoreArr['incomplete'] eq 'Y'`
<BR><BR>
<table width="48%"  border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
     <td height="15" colspan="2" width="100%" class="bgbrownL"><span class="mediumblackb"><b>&nbsp;Incomplete Profile</b></span></td>
    </tr>

<tr class="bggreyl">
                        <td class="mediumblack" width="10%">&nbsp;</td>
                        <td class="mediumblack">&nbsp;This profile is incomplete</td>
 </tr>

</table>
~/if`
<tr>
<td colspan=2 align=center><br><hr></td></tr>
<tr>
</tr>
<tr><td colspan=2><br></td></tr><br>
</table>
 <br><br>
 </div>
