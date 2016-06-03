~include_partial('global/header')`
~include_partial("storyHeader",["SCREEN"=>1,user=>$user,cid=>$cid])`
~if $SCREEN eq "1"`
		~if $photo neq ""`
		  <form name="screen" enctype="multipart/form-data" action="~$SITE_URL`/operations.php/storyScreening/index" method="POST">
		~else`
  		 <form name ="screen" action="~$SITE_URL`/operations.php/storyScreening/index" method="POST">
		~/if`
		  <table width="90%" border="0" cellspacing="2" cellpadding="2" align=center>
		~if $showformskip neq 1`<tr class=label>
		<td align=center colspan=8>Success Stories left to be screened:<b> ~$row_total`</b>
		</tr>
		~/if`
	        <tr class=label>
	        <td align=center width=10% >Username [H]</td>
        	<td align=center width=10% >Username [W]</td>
		<td align=center width=10% >Name [H]</td>
	        <td align=center width=10% >Name [W]</td>
		<td align=center width=10% >Email</td>
        	<td align=center width=10% >Contact Detail</td>
	        <td align=center width=10% >Wedding Date</td>
		<td align=centre width=10% >Upload Date & Time</td>
		</tr>
		<tr align=center class=fieldsnew>
                <td width=10%>~$user_h`</td>
        	<td width=10%>~$user_w`</td>
	        <td width=10%>~$name_h`</td>
		<td width=10%>~$name_w`</td>
        	<td width=10%>~$email`</td>
	        <td width=10%>~$contact`</td>
	        <td width=10%>~$wedding_date`</td>
                <td width=10%>~$dateandtime`</td>
                </tr>
	        </table>
		~if $NOSTORY`
		<p align="center"><font color="red">Story cant be blank!</font></p>
		~/if`
		~if $NOPIC`
		<p align="center"><font color="red">You have to either upload or delete pictures!</font></p>
		~/if`
        	<table width="50%" border="0" cellspacing="2" cellpadding="2" align=center>
	        <tr align=center class=label><td><b>Success Story</b></td></tr>
        	<tr align=center class=fieldsnew>
	        <td><textarea name="story" cols="50" rows="10">~$comments`</textarea></td>
        	</tr>
                </table>
		<br />
		 <table width="50%" border=0 cellspacing="2" cellpadding="2" align="center">
		~if $photo neq ""`
		<tr class="formhead"><td align="center">Uploaded Photo</td></tr>
		<tr class="fieldsnew"><td align="center"><img src="~PictureFunctions::getCloudOrApplicationCompleteUrl($photo)`"></td></tr>
		<tr class="fieldsnew" height="50px"><td align="center"><input type="checkbox" name="delete" ~if $delete` checked ~/if`>Delete photo</td></tr>
		~else`
                <tr class="formhead"><td align="center">No Photo Uploaded</td></tr>
		~/if`
		</table>
		<br /><table width="50%" align="center" cellspacing="2" cellpadding="2">
		                <tr><td>&nbsp;</td></tr>
                 <tr class="formhead"><td align="center">YEAR</td>
                 <td>
                        <select name="year">
                        ~for $var = date("Y") to $YEAR -5  step -1`
                         <option value="~$var`" ~if $YEAR eq $var`selected~/if`>~$var`</option>
                        ~/for`
                       </select></td>
                </tr>
		</table><br>
		~if $photo neq ""`
		<table width="50%" align="center" cellspacing="2" cellpadding="2">
		<tr class="formhead" align="center"><td colspan="2" align="center">Please Screen Photos and Upload</td></tr>
		<tr class="fieldsnew"><td align="center">Framed Main Page Photo</td>
		<td><input name="frame" type="file"></td>
		</tr>
		 <tr class="fieldsnew"><td align="center">Full Size Photo</td>
                <td><input name="fullphoto" type="file"></td>
                </tr>
		 <tr class="fieldsnew"><td align="center">HOME Photo</td>
                <td><input name="homephoto" type="file"></td>
                </tr>
		<tr class="fieldsnew"><td align="center">NEW HOME Photo</td>
				<td><input name="squarephoto" type="file"></td>
		</tr>       
		</table>
		<br />
		~/if`
		<input type="hidden" name="name_h" value="~$name_h`">
                <input type="hidden" name="name_w" value="~$name_w`">
                <input type="hidden" name="user_h" value="~$user_h`">
                <input type="hidden" name="user_w" value="~$user_w`">
		<input type="hidden" name="username" value="~$username`">
                <input type="hidden" name="cid" value="~$cid`">
                <input type="hidden" name="user" value="~$user`">
                <input type="hidden" name="photo" value="~$photo`">
                <input type="hidden" name="id" value="~$id`">
                <input type="hidden" name="comments" value="~$comments`">
		<input type="hidden" name="email" value="~$email`">
		~if $YEAR eq ''`<input type="hidden" name="year" value="~$year`">~/if`
		~if $showformskip eq "1"`
		<input type="hidden" name="skip" value="1">
		~/if`
		<table align="center" width="50%" cellspacing="2" cellpadding="2">
		<tr class="formhead">
		<td align="center" width="25%"><input type="submit" value="Accept" name="Accept" onclick="submitForm(this.name)"></input></td>
		 <td align="center" width="25%"><input type="submit" value="Hold" name="Hold" onclick="submitForm(this.name)"></input></td>
		 <td align="center" width="25%"><input type="submit" value="Reject" name="Reject" onclick="submitForm(this.name)"></input></td>
		~if $showformskip neq "1"`
		 <td align="center" width="25%"><input type="submit" value="Skip" name="Skip" onclick="submitForm(this.name)"></input></td>
		~/if`
		</tr>
		</table>
</form>
~/if`
~include_partial('global/footer')`
