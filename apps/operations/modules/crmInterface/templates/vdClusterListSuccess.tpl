~include_partial('global/header')`

<form action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/preProcessMiniVd?cid=~$cid`" method="POST">
	<input type=hidden name=cid value="~$cid`">
	<table width=900 align=center >
		<tr class="formhead" align=right><td colspan=5>	
			<a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/preProcessMiniVd?cid=~$cid`">Add Offer</a></td>
		</tr>
		<tr class="formhead" align=center><td colspan=5>Customized Variable Discount Offer Submission Screen</td></tr><p/>

			<tr class="formhead" align=center>
				<td width=40% >Cluster Name</td>
				<td width=10% >Discount Value</td>
				<td width=20% >Discount Start Date</td>
				<td width=20% >Discount End Date</td>
				<td width=10% >Delete</td>
			</tr><p/>
			~foreach from=$dataArr key=k item=val`
			<tr class="formhead" align=center>
				<td class=fieldsnew width=40%>~$val.CLUSTER`</td>
				<td class=fieldsnew width=10%>~$val.DISCOUNT`</td>
				<td class=fieldsnew width=20%>~$val.START_DT`</td>
				<td class=fieldsnew width=20%>~$val.END_DT`</td>
				<td class=fieldsnew width=10%><a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/preProcessMiniVd?cid=~$cid`&clusterName=~$val.CLUSTER`&submit=delete" >Delete</a></td>

			</tr>
			~/foreach`
			<tr class="formhead" align=center>
				<td class=fieldsnew colspan=100%>
					~if $successMessage`
						<font color=blue><b>Process Started Successfully</b></font>
					~else`
						<input type=submit name=submit value="Upload Offer Now">	
					~/if`
				</td>
			</tr>

	</table>
</form>
~include_partial('global/footer')`
