~include_partial('global/header')`
<form name="form1" action="/operations.php/crmAllocation/processExclusiveServicingIISubmit" method="post">
	<input type="hidden" name="agentName" value="~$agentName`">
	<input type="hidden" name="cid" value="~$cid`">
	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		<tr class="formhead" align="center">
			<td colspan="2" style="background-color:lightblue"><font size=3>Exclusive Servicing Platform II</font></td>
		</tr>
		<tr></tr>
	</table>
	~if $successMsg`
	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		<tr height="10"></tr>
		<tr align="center">
		    <td class="label">
			<font size=3>~$successMsg`,<a href="/operations.php/crmAllocation/exclusiveServicingII">Click</a> here to submit new request</font>
		    </td>
		</tr>
	</table>
	~elseif $errorMsg`
	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		<tr height="10"></tr>
		<tr align="center">
		    <td class="label" style="background-color:orange">
			<font size=3>~$errorMsg`,Please resubmit form with correct details</font>
		    </td>
		</tr>
	</table>
	~/if`
	~if $successMsg eq ""`
	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		<tr height="10"></tr>
		<tr align="center">
			<td class="label" width=50%>Email-id of exclusive customer <font style="color:red">*</font>
			</td>
			<td class="fieldsnew" align="center"><input type="text" id='exclusiveEmail' name="exclusiveEmail" value="~$exclusiveEmail`" >
			</td>		
		</tr>
		<tr align="center">
			<td class="label" width=50%>Username of profiles <font style="color:red">*</font>
			</td>
			<td class="fieldsnew" align="center">
				<div class="brdrbtm_new fullwid btm0 pos-abs bg-white"><textarea cols="23" style="width: 220px;height:123px;" id="profileUsernameList" class="inputText lh20 brdr-0 padall-10 colorGrey hgt18 fontlig" placeholder="Enter username list separated by newline" value="~$profileUsernameList`" name="profileUsernameList"></textarea></div>
			</td>		
		</tr>
		<tr height="10"><input type="hidden" id='profileUsernameListParsed' name="profileUsernameListParsed" value="~$profileUsernameListParsed`" ></tr>
		<tr align="center">
			<td class="label" colspan="2" style="background-color:Moccasin">
			<input type="submit" name="submit" value="SUBMIT" onclick="return validateFormInputs();">
			</td>
		</tr>
	</table>
	~/if`

</form>
~include_partial('global/footer')`