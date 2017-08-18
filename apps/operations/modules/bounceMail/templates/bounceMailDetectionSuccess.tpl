~include_partial('global/header')`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align=center><b><h2>Check for Bounce Mails</h2> </b></div><br></br>
	 <form name="bounceMailForm" id="bounceMailForm" method="POST" >
		<input type=hidden name="cid" id="cid" value="~$cid`">
                <input type=hidden name="execname" value="~$execname`">
	 	<table width="600" border="1" cellspacing="1" cellpadding='3' ALIGN="CENTER" >
		    <tr class=label align=center>
                        <td width=20%>&nbsp;Enter Username/Email to Search:</td>
                        <td width=10%>
				<input name="username" id="user">
				<div id="nouserError" style="display:none">
					<font color="red"> Please enter a Username/Email</font>
				</div>
				<div id="invalidUser" style="display:none">
					<br><font color="red"> Enter a valid Username/Email</font> </br>
				</div>
			</td>	
                        <td id="submitButton" width=10%><input type="submit" name="Submit" value="Submit" onclick="evaluateData();return false;">&nbsp;&nbsp;&nbsp;</td>
                        <td id="deleteButton" style="display:none" width=10%><input type="submit" name="Delete" value="Delete" onclick="DeleteEntry();return false;">&nbsp;&nbsp;&nbsp;</td>
                    </tr>
		 </table>
	</form>



	~include_partial('global/footer')`