~include_partial('global/header')`
~include_partial("storyHeader",["OFFLINE"=>1,user=>$user,cid=>$cid])`
<form name="uploadnew" enctype="multipart/form-data" action="~$SITE_URL`/operations.php/storyScreening/offline" method="post">
<table width=60%  align="center" cellspacing="2" cellpadding="2" >
<tr class="formhead"><td colspan="3" align="center">Please Enter the Follwing Details. Fields Marked with <font color="red">*</font> are compulsory</td><tr>
<tr class="fieldsnew"><td class="label" align="center">USER ID(HUSBAND)</td>
			<td align="center"><input type="text" name="user_h" value="~$user_h`"></td>
			<td align="center">~if $nousername`<font color="red">Plase enter atlease one username!</font>~else if $novalidusername`<font color="red">Plase provide valid usernames!</font>~else`&nbsp;~/if`</td>
</tr>
<tr class="fieldsnew"><td class="label" align="center">USER ID(WIFE)</td>
                        <td align="center"><input type="text" name="user_w" value="~$user_w`"></td>
                        <td align="center">&nbsp;</td></tr>
<tr class="fieldsnew"><td class="label" align="center">NAME(HUSBAND)<font color="red">*</font></td>
                        <td align="center"><input type="text" name="name_h" value="~$name_h`"></td>
                        <td align="center">~if $noname`<font color="red">Please enter atleast one name!</font>~else`&nbsp;~/if`</td></tr>
<tr class="fieldsnew"><td class="label" align="center">NAME(WIFE)<font color="red">*</font></td>
                        <td align="center"><input type="text" name="name_w" value="~$name_w`"></td>
                        <td align="center">&nbsp;</td></tr>
<tr class="fieldsnew"><td class="label" align="center">WEDDING DATE</td>
                        <td align="center"><select name="day">
						~section name="day" start=1 loop=32`
						<option value="~$smarty.section.day.index`" ~if $day eq $smarty.section.day.index`selected~/if`>~$smarty.section.day.index`</option>
						~/section`
						
					    </select> / <select name="month">
						 ~section name="month" start=1 loop=13`
						<option value="~$smarty.section.month.index`" ~if $month eq $smarty.section.month.index`selected~/if`>~$smarty.section.month.index`</option>
						~/section`
					     </select> / <select name="year">
						~assign var=thisYear value=$smarty.now|date_format:"%Y"`
						~section name=years start=2005 loop=$thisYear+1 step=1`
							<option value="~$smarty.section.years.index`" ~if $year eq $smarty.section.years.index or $v.year eq $smarty.section.years.index`selected~/if`>~$smarty.section.years.index`</option>
						~/section`
						</select></td>
                        <td align="center">&nbsp;</td></tr>
<tr class="fieldsnew"><td class="label" align="center">HEADING</td>
                        <td align="center"><input type="text" name="heading" value="~$heading`"></td>
                        <td align="center">&nbsp;</td></tr>
<tr class="fieldsnew"><td class="label" align="center">CONTACT DETAILS</td>
                        <td align="center"><input type="text" name="contact" value="~$contact`"></td>
                        <td align="center">&nbsp;</td></tr>
<tr class="fieldsnew"><td class="label" align="center">EMAIL of Husband</td>
                        <td align="center"><input type="text" name="email_h" value="~$email_h`"></td>
                        <td align="center">&nbsp;</td></tr>
<tr class="fieldsnew"><td class="label" align="center">EMAIL of Wife</td>
                        <td align="center"><input type="text" name="email_w" value="~$email_w`"></td>
                        <td align="center">&nbsp;</td></tr>
<tr class="fieldsnew"><td class="label" align="center">MAIN PHOTO</td>
                        <td align="center"><input type="file" name="fullphoto"></td>
                        <td align="center">~if $nopic`<font color="red">all photos have to be uploaded</font>~else`&nbsp;~/if`</td></tr>
<tr class="fieldsnew"><td class="label" align="center">FRAMED PHOTO</td>
                        <td align="center"><input type="file" name="frame"></td>
                        <td align="center">~if $nopic`<font color="red">all photos have to be uploaded</font>~else`&nbsp;~/if`</td></tr>

<tr class="fieldsnew"><td class="label" align="center">HOME PHOTO</td>
                        <td align="center"><input type="file" name="homephoto"></td>
                        <td align="center">~if $nopic`<font color="red">all photos have to be uploaded</font>~else`&nbsp;~/if`</td></tr>

<tr class="fieldsnew"><td class="label" align="center">NEW HOME PHOTO</td>
                        <td align="center"><input type="file" name="squarephoto"></td>
                        <td align="center">~if $nopic`<font color="red">all photos have to be uploaded</font>~else`&nbsp;~/if`</td></tr>

<tr class="fieldsnew"><td class="label" align="center">STORY<font color="red">*</font></td>
                        <td align="center"><textarea name="story" rows=10 cols=50>~$story`</textarea></td>
                        <td align="center">~if $nostory`<font color="red">Please enter the story!</font>~else`&nbsp;~/if`</td></tr>
</table>
<br>
<input type="hidden" name="cid" value="~$cid`">
<input type="hidden" name="user" value="~$user`">
<input type="hidden" name="offline" value="~$OFFLINE`"> 
<table width=60% align="center" cellspacing="2" cellpadding="2">
<tr class="fieldsnew"><td class="label" align="center"><input type="submit" name="Upload" value="Upload"></td></tr>
</table>
</form>
<br /><br />
~include_partial('global/footer')`
