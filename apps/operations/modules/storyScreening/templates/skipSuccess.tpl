~include_partial('global/header')`
~include_partial("storyHeader",["VSKIP"=>1,user=>$user,cid=>$cid])`
~if $VSKIP eq "1"`
	<table width=100% align="CENTER">
	<tr align="CENTER"><td class="formhead" colspan="9" height="23"><b><font size="4" color="blue">Skipped Stories </font></b></td></tr>
	<tr align="CENTER">
        <td class="label" width=15% height="20"><b>Name (Husband)</b></td>
        <td class="label" width=15% height="21"><b>Name (Wife)</b></td>
        <td class="label" width=15% height="21"><b>User ID (Husband)</b></td>
        <td class="label" width=15% height="21"><b>User ID (Wife)</b></td>
        <td class="label" width=15% height="21"><b>Receive Date</b></td>
	<td class="label" width=15% height="21"><b>Skip Comments</b></td>
        <td class="label" width=10% height="21"><b>View </b></td>
	</tr>
	~foreach from=$values item=valued key=index`
	<tr align="CENTER" bgcolor="#fbfbfb" >
        <td height="21" align="CENTER">~$valued.NAME_H`</td>
        <td height="21">~$valued.NAME_W`</td>
        <td height="21" align="CENTER">~$valued.USERNAME_H`</td>
        <td height="21">~$valued.USERNAME_W`</td>
        <td height="21">~$valued.DATETIME`</td>
	<td height="21">~$valued.SKIP_COMMENTS`</td>
        <td height="21"><b><a href="index?screenskip=1&id=~$valued.ID`&cid=~$cid`&user=~$user`">view</a></td> </b></td>
	</tr>
	~/foreach`
	<tr bgcolor="#fbfbfb">
        <td colspan="7" height="21">&nbsp; </td>
	</tr>
	<tr>
        <td colspan="7" height="21">&nbsp; </td>
	</tr>
	</table>
~/if`
~include_partial('global/footer')`
