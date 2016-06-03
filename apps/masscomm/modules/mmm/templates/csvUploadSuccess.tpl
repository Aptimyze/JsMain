~include_partial('global/header')`
<br>
<br>

<form action="csvUpload" method=post enctype="multipart/form-data">
	<table width=80% align=center>
		~if !$noError`
			<tr>
				<td>
					<span style="color:blue">CSV FORMAT: PROFILEID*, EMAIL*, NAME, PHONE </span>
				</td>
			</tr>

			<tr class=label>
				<td>Select Mailer</td>
				<td>
					<select name=mailer_id>
					~foreach from =$mailers item =i key = k`
						<option value = ~$k` ~if mail_id eq $k` selected ~/if`> ~$k`. ~$i` </option>
					~/foreach` 
					</select>
				</td>
			</tr>

			<tr class=label>
				<td><input type="file" name="csv"></td>
				<td><input type="submit" name="submit" value="Submit"></td>
			</tr>

			~if $msgToDisplay`
				<tr class=label>
					<td>
						<span style="color:red">~$msgToDisplay`</span>
					</td>
				</tr>
			~/if`

			<tr><td><br></td></tr>

			<tr>
				<td>*Necessary Fields</td>
			</tr>

		~else`
			<tr class=label>
				<td>
					<span style="color:green">
						~$msgToDisplay`
					</span>
				</td>
			</tr>

			<tr class=label>
				<td>Total Number of Rows Inserted: ~$insertedRows`</td>
			</tr>
		~/if`
	</table>
</form>

