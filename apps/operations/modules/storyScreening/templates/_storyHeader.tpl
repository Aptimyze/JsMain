<br>
<table width=760 cellspacing="1" cellpadding="3" ALIGN="CENTER">
<tr class="formhead">
~if $REMOVE`
<td class="formhead" border="1" align="center"><a href="index?cid=~$cid`&user=~$user`">Screen & Upload</a></td><td class="formhead" border="1" align="center"><a href="skip?cid=~$cid`&user`=~$user`&Vskip=1`">View Skipped Stories</a></td><td class="formhead" border="1" align="center"><a href="offline?cid=~$cid`&user=~$user`&offline=1">Add Offline Stories</a></td><td class="formhead" border="1" align="center"><a href="unhold?cid=~$cid`&user=~$user`&unhold=1">Unhold/Edit Success Story</a></td>
~else`
	~if $VSKIP`
	<td class="formhead" border="1" align="center"><a href="remove?cid=~$cid`&user=~$user`&Remove=1`">Remove Uploaded Stories</a></td><td class="formhead" border="1" align="center"><a href="index?cid=~$cid`&user`=~$user`">Screen & Upload</a></td><td class="formhead" border="1" align="center"><a href="offline?cid=~$cid`&user`=~$user`">Add Offline Stories</a></td><td class="formhead" border="1" align="center"><a href="unhold?cid=~$cid`&user=~$user`">Unhold/Edit Success Story</a></td>
	~else`
		~if $OFFLINE`
		 <td class="formhead" border="1" align="center"><a href="remove?cid=~$cid`&user=~$user`">Remove Uploaded Stories</a></td><td class="formhead" border="1" align="center"><a href="index?cid=~$cid`&user=~$user`">Screen & Upload</a></td><td class="formhead" border="1" align="center"><a href="skip?cid=~$cid`&user=~$user`">View Skipped Stories</a></td><td class="formhead" border="1" align="center"><a href="unhold?cid=~$cid`&user=~$user`">Unhold/Edit Success Story</a></td>
		~else`
			~if $UNHOLD`
			<td class="formhead" border="1" align="center"><a href="remove?cid=~$cid`&user=~$user`">Remove Uploaded Stories</a><td class="formhead" border="1" align="center"><td class="formhead" border="1" align="center"><a href="index?cid=~$cid`&user=~$user`">Screen & Upload</a></td><td class="formhead" border="1" align="center"><a href="offline?cid=~$cid`&user=~$user`&offline=1">Add Offline Stories</a></td><td class="formhead" border="1" align="center"><a href="skip?cid=~$cid`&user=~$user`&Vskip=1`">View Skipped Stories</a></td>
			~elseif $SCREEN`
			<td class="formhead" border="1" align="center"><a href="remove?cid=~$cid`&user=~$user`&Remove=1`">Remove Uploaded Stories</a></td><td class="formhead" border="1" align="center"><a href="skip?cid=~$cid`&user=~$user`">View Skipped Stories</a></td><td class="formhead" border="1" align="center"><a href="offline?cid=~$cid`&user=~$user`&offline=1">Add Offline Stories</a></td><td class="formhead" border="1" align="center"><a href="unhold?cid=~$cid`&user=~$user`&unhold=1">Unhold/Edit Success Story</a></td>
			
			~/if`
		~/if`
	~/if`
~/if`
</tr>
</table>
<br >
