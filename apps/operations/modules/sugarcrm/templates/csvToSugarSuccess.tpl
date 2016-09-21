~include_partial('global/header')`
~if $result neq true`		
                <form  id="submitForm" action="" method="post" enctype="multipart/form-data">
		  <table width=760 align="CENTER" >
                        <tr class="formhead" align="CENTER">
                                <td colspan=3>UPLOAD CSV FOR SUGARCRM LEAD:</td>
                        </tr>
			<tr>
				<td colspan="3"></td>
			</tr>
			<tr>
				<td colspan="3"></td>
			</tr>
                        <tr align="CENTER">
				<td colspan=3>
					<input type="file" name="csv" id="file" accept=".csv"/>
				</td>
                        </tr>
			<tr>
				<td colspan="3"></td>
			</tr>
			<tr>
				<td colspan="3"></td>
			</tr>
                        <tr align="CENTER">
				<td colspan=3>
					<input type="submit" name="submit">
				</td>
                        </tr>
		  </table>
                </form>
~else`
<br/>
<br/>
	<center>~$message`</center>
<br/>
<br/>
~/if`
~include_partial('global/footer')`
