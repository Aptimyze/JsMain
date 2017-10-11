~include_partial('global/header')`
~include_partial("storyHeader",["REMOVE"=>1,user=>$user,cid=>$cid])`
~if $showformremove eq "1"`
	<form name="removeaction" action="~$SITE_URL`/operations.php/storyScreening/remove">
	~foreach from=$story key=k item=v`
	 <table width="90%" border="0" cellspacing="2" cellpadding="2" align=center>
                <tr class=label>
                <td align=center width=20% >Username [H]</td>
                <td align=center width=20% >Username [W]</td>
                <td align=center width=20% >Name [H]</td>
                <td align=center width=20% >Name [W]</td>
		 <td align=center width=20% >Heading</td>
		</tr>
                <tr align=center class=fieldsnew>
                <td width=20%>~$v.user_h`</td>
                <td width=20%>~$v.user_w`</td>
                <td width=20%>~$v.name_h`</td>
                <td width=20%>~$v.name_w`</td>
                 <td width=20%>~$v.heading`</td>
                 </tr>
                </table>
                <br />
                <table width="50%" border="0" cellspacing="2" cellpadding="2" align=center>
                <tr align=center class=label><td><b>Success Story</b></td></tr>
                <tr align=center class=fieldsnew>
                <td align="center">~$v.story`</td>
                </tr>
                </table>
                <br />
		  <table width="50%" border=0 cellspacing="2" cellpadding="2" align="center">
                ~if $v.photo eq "1"`
                <tr class="formhead"><td colspan="2" align="center">Uploaded Photos</td></tr>
                <tr class="fieldsnew"><td width="25%" align="center">Full Photo</td><td width="75%" align="center"><img src="~PictureFunctions::getCloudOrApplicationCompleteUrl($v.photo_m)`" height="200" width="150"></img></td></tr>
		<tr class="fieldsnew"><td width="25%" align="center">Framed Photo</td><td align="center"><img src="~PictureFunctions::getCloudOrApplicationCompleteUrl($v.photo_f)`" height="200" width="150"></img><td></tr>
                ~else`
                <tr class="formhead"><td align="center">No Photo Uploaded</td></tr>
                ~/if`
                </table>
        	<br />
		 <input type="hidden" name="sid" value="~$v.sid`">
                <input type="hidden" name="id" value="~$v.id`">
                 <input type="hidden" name="cid" value="~$cid`">
                <input type="hidden" name="user" value="~$user`">
                <input type="hidden" name="Remove" value="~$REMOVE`">
		 <table align="center" width="50%" cellspacing="2" cellpadding="2">
                <tr class="formhead">
                <td align="center"><input type="submit" value="Remove" name="doremove"></input></td>
                </tr>
                </table>
		<br /><br />
		~/foreach`
		 <table align="center" width="50%" cellspacing="2" cellpadding="2">
                <tr class="formhead">
                <td align="center"><input type="submit" value="Cancel" name="cancelremove"></input></td>
                </tr>
                </table>
		</form>
		~else`	
	<form name="remove" action="~$SITE_URL`/operations.php/storyScreening/remove">
	~if $NODATA`<p align="center"><font color="red">You have to enter atleast one field!</font>~/if`
	<table width="50%" align="center" cellspacing="2" cellpadding="2">
	<tr class="formhead"><td colspan="3" align="center">PLEASE ENTER ONE OR MORE FIELDS</td></tr>
        <tr class="fieldsnew"><td class="label" align="center">Story Id</td>
                <td align="center"><input type="text" name="STORY_ID" value="~$sid`"></td>
        </tr>

	<tr class="fieldsnew"><td class="label" align="center">User ID of Husband</td>
		<td align="center"><input type="text" name="user_h" value="~$user_h`"></td>
	</tr>
	<tr class="fieldsnew"><td class="label" align="center">User ID of Wife</td>
                <td align="center"><input type="text" name="user_w" value="~$user_w`"></td>
	</tr>
	<tr class="fieldsnew"><td class="label" align="center">Enter Husband's name</td>
                       <td align="center"><input type="text" name="name_h" value="~$name_h`"></td>
	</tr>
	<tr class="fieldsnew"><td class="label" align="center">Enter Wife's name</td>
        	       <td align="center"><input type="text" name="name_w" value="~$name_w`"></td>
	</tr>
	<tr class="fieldsnew"><td class="label" colspan="3" align="center"><input type="submit" value="Search" name="search"></td
	></tr>
	</table>
	<input type="hidden" name="cid" value="~$cid`">
	<input type="hidden" name="user" value="~$user`">
	<input type="hidden" name="Remove" value="~$REMOVE`">
	</form>
	~if $NOTUP`<p align="center"><font color="red"><b>This story is not uploaded!</b></font></p>~/if`
	~if $NOSTORY`<p align="center"><font color="red"><b>No story with given entries exists!</b></font></p>~/if`
	~/if`
~include_partial('global/footer')`		

