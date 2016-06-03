~include_partial('global/header')`
<form name="form1" action="/operations.php/profileVerification/saveFieldVisitRequest" method="post">
	<input type="hidden" name="agentName" value="~$agentName`">
	<input type="hidden" name="cid" value="~$cid`">
	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		<tr class="formhead" align="center">
			<td colspan="2" style="background-color:lightblue"><font size=3>FIELD VISIT REQUEST INTERFACE</font></td>
		</tr>
		<tr></tr>
	</table>
	~if $successMsg`
	<table border="0" align="center" width="60%" cellpadding="4" cellspacing="4" border="0">
		<tr height="10"></tr>
		<tr align="center">
		    <td class="label">
			<font size=3>~$successMsg`,<a href="/operations.php/profileVerification/requestFieldSalesVisit">Click</a> here to submit new request</font>
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
			<td class="label" width=50%>Enter Username of profile requesting visit <font style="color:red">*</font>
			</td>
			<td class="fieldsnew" align="center"><input type="text" id='username' name="username" value="~$username`" >
			</td>		
		</tr>
		<tr align="center">
			<td class="label" width=50%>
				Date of visit
			</td>
			<td class="fieldsnew" align="center">
				<input id="visit_date" type="text" value="">
			</td>		
		</tr>
		<tr height="10"></tr>
		<tr align="center">
			<td class="label" colspan="2" style="background-color:Moccasin">
			<input type="submit" name="submit" value="SUBMIT" onclick="return validateInputs();">
			</td>
		</tr>
	</table>
	~/if`

</form>
~include_partial('global/footer')`
<script>
	var startYear = "~$startYear`";
	var endYear = "~$endYear`";
</script>