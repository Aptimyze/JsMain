~include_partial('global/header')`
~include_partial("storyHeader",["UNHOLD"=>1,user=>$user,cid=>$cid])`
	~if $showformunhold`
	~foreach from=$story key=k item=v`
	 <form name="unhold" action="~$SITE_URL`/operations.php/storyScreening/unhold" enctype="multipart/form-data" method="post">
	~if $v.id eq $screenid`
		~if $NONAME || $NOSTORY || $NOPIC`
		<p align="center"> <font color="red">Please enter all the details again!</font></p>
		~/if`
	~/if`
	<table align="center" width="50%" cellspacing="2" cellpadding="2">
	<tr class="formhead" align="center"><td align="center">STATUS</td><td colspan="2">~$v.status`</td></tr>
        <tr class="fieldsnew" align=center>
          <TD align=center class="label">
          STORY ID
         </TD>
          <TD align="center"><input type="text" name="STORY_ID" value="~$v.sid`">
         </TD><td></td>
        </tr>
        <tr class="fieldsnew" align="center"><td align="center" class="label">USER ID(HUSBAND)</td>
						<td align="center"><input type="text" name="user_h" value="~$v.user_h`"></td>
						<td align="center">&nbsp;</td>
	</tr>
        
        <tr class="fieldsnew" align="center"><td align="center" class="label">USER ID(WIFE)</td>
						<td align="center"><input type="text" name="user_w" value="~$v.user_w`"></td>
						<td align="center">&nbsp;</td>
	</tr>
	 <tr class="fieldsnew" align="center"><td align="center" class="label">NAME (HUSBAND)</td>
                                                <td align="center"><input type="text" name="name_h" value="~$v.name_h`"></td>
                                                <td align="center">~if $v.id eq $screenid`~if $NONAME`<font color="red">Please enter atleast one name!</font>~/if`~else`&nbsp;~/if`</td>
        </tr>
	 <tr class="fieldsnew" align="center"><td align="center" class="label">NAME (WIFE)</td>
                                                <td align="center"><input type="text" name="name_w" value="~$v.name_w`"></td>
                                                <td align="center">&nbsp;</td>
        </tr>
	 <tr class="fieldsnew" align="center"><td align="center" class="label">HEADING</td>
                                               <td align="center"><input type="text" name="heading" value="~$v.heading`"></td>
                                                <td align="center">&nbsp;</td>
        </tr>
	 <tr class="fieldsnew" align="center"><td align="center" class="label">CONTACT</td>
                                                <td align="center"><input type="text" name="contact" value="~$v.contact`"></td>
                                                <td align="center">&nbsp;</td>
        </tr>
	 <tr class="fieldsnew" align="center"><td align="center" class="label">EMAIL</td>
                                                <td align="center"><input type="text" name="email" value="~$v.email`"></td>
                                                <td align="center">&nbsp;</td>
        </tr>
	 <tr class="fieldsnew" align="center"><td align="center" class="label">UPLOAD DATE & TIME</td>
                                                <td align="center">~$v.datetime`</td>
                                                <td align="center">&nbsp;</td>
        </tr>
	~if $v.photo eq '1'`
		~if $v.status eq 'UPLOADED' || $v.status eq 'REMOVED'`
	 		<tr class="fieldsnew" align="center"><td align="center" class="label">MAIN PHOTO</td>
                        		                     <td align="center"><img src="~PictureFunctions::getCloudOrApplicationCompleteUrl($v.photo_m)`" height="200" width="150"></img></td>
                                                	     <td align="center">&nbsp;</td>
		        </tr>   
			 <tr class="fieldsnew" align="center"><td align="center" class="label">FRAMED PHOTO</td>
                		                                <td align="center"><img src="~PictureFunctions::getCloudOrApplicationCompleteUrl($v.photo_f)`" height="200" width="150"></img></td>
                                		                <td align="center">&nbsp;</td>
		        </tr>
			<tr class="fieldsnew" align="center"><td align="center" class="label">HOME PHOTO</td>
                        		                     <td align="center"><img src="~PictureFunctions::getCloudOrApplicationCompleteUrl($v.photo_h)`" height="200" width="150"></img></td>
                                                	     <td align="center">&nbsp;</td>
		        </tr>
			<tr class="fieldsnew" align="center"><td align="center" class="label">NEW HOME PHOTO</td>
												 <td align="center"><img src="~PictureFunctions::getCloudOrApplicationCompleteUrl($v.photo_sq)`" height="200" width="150"></img></td>
													 <td align="center">&nbsp;</td>
			</tr>    
		~else`
			 <tr class="fieldsnew" align="center"><td align="center" class="label">MAIN PHOTO</td>
                                                             <td align="center"><img src="~PictureFunctions::getCloudOrApplicationCompleteUrl($v.photo_s)`" height="200" width="150"></img></td>
                                                             <td align="center">&nbsp;</td>
                        </tr>
		~/if`
		<tr class="fieldsnew"><td class="label" align="center">Delete photos</td>
					<td align="center"><input type="checkbox" name="delete"></td>
					<td align="center">&nbsp;</td>
	
	~else`
	 <tr class="fieldsnew" align="center"><td align="center" class="label">PHOTO</td>
                                                <td align="center">NO PHOTO UPLOADED</td>
                                                <td align="center">&nbsp;</td>
        </tr>
	~if $v.sid`
		~if $v.photo_ss`
			<tr class="fieldsnew" align="center"><td align="center" class="label">SUBMITED PHOTO</td>
                                                             <td align="center"><img src="~PictureFunctions::getCloudOrApplicationCompleteUrl($v.photo_s)`" height="200" width="150"></img></td>
                                                             <td align="center">&nbsp;</td>
                        </tr>
		~/if`
	~/if`
	~/if`
	 <tr class="fieldsnew" align="center"><td align="center" class="label">MAIN PHOTO</td>
                                                <td align="center"><input type="file" name="fullphoto"></td>
                                                <td align="center">~if $v.id eq $screenid`~if $NOPIC`<font color="red">All photos have to be uploaded simultaneously!</font>~/if`~else`&nbsp;~/if`</td>
        </tr>
	 <tr class="fieldsnew" align="center"><td align="center" class="label">FRAMED PHOTO</td>
                                                <td align="center"><input type="file" name="frame"></td>
                                                <td align="center">~if $v.id eq $screenid`~if $NOPIC`<font color="red">All photos have to be uploaded simultaneously!</font>~/if`~else`&nbsp;~/if`</td>
        </tr>




	 <tr class="fieldsnew" align="center"><td align="center" class="label">HOME PHOTO</td>
                                                <td align="center"><input type="file" name="homephoto"></td>
                                                <td align="center">~if $v.id eq $screenid`~if $NOPIC`<font color="red">All photos have to be uploaded simultaneously!</font>~/if`~else`&nbsp;~/if`</td>
        </tr>
	
	 <tr class="fieldsnew" align="center"><td align="center" class="label">NEW HOME PHOTO</td>
                                                <td align="center"><input type="file" name="squarephoto"></td>
                                                <td align="center">~if $v.id eq $screenid`~if $NOPIC`<font color="red">All photos have to be uploaded simultaneously!</font>~/if`~else`&nbsp;~/if`</td>
        </tr>




	 <tr class="fieldsnew" align="center"><td align="center" class="label">STORY</td>
                                                <td align="center"><textarea name="textstory" align="center" cols="50" rows=10">~$v.story`</textarea></td>
                                              <td align="center">~if $v.id eq $screenid`~if $NOSTORY`<font color="red">There has to be a story!</font>~/if`~else`&nbsp;~/if`</td>
        </tr>
	 <tr class="fieldsnew" align="center"><td align="center" class="label">WEDDING DATE</td>
                                                <td align="center"><select name="day">
												~section name="day" start=1 loop=32`
												<option value="~$smarty.section.day.index`" ~if $v.day eq $smarty.section.day.index`selected~/if`>~$smarty.section.day.index`</option>
												~/section`
                                                
                                            </select> / <select name="month">
						 <option value="1" ~if $v.month eq "01"`selected~/if`>1</option>
                                                ~section name="month" start=1 loop=13`
												<option value="~$smarty.section.month.index`" ~if $v.month eq $smarty.section.month.index`selected~/if`>~$smarty.section.month.index`</option>
												~/section`
                                             </select> / <select name="year">

						~assign var=thisYear value=$smarty.now|date_format:"%Y"`
                                                ~section name=years start=2005 loop=$thisYear+1 step=1`
                                                        <option value="~$smarty.section.years.index`" ~if $v.year eq $smarty.section.years.index`selected~/if`>~$smarty.section.years.index`</option>
                                                ~/section`
                                                </select></td>
        			             <td align="center">&nbsp;</td>
        </tr>
	</table>
	<input type="hidden" name="cid" value="~$cid`">
        <input type="hidden" name="user" value="~$user`">
        <input type="hidden" name="unhold" value="~$UNHOLD`">
	~if $v.status eq 'UPLOADED' || $v.status eq 'REMOVED'`
	<input type="hidden" name="sid" value="~$v.sid`">
		~if $v.photo_ss`
		<input type="hidden" name="photo_ss" value="~$v.photo_ss`">
		~/if`
	~/if`
	<input type="hidden" name="id" value="~$v.id`">
	<input type="hidden" name="sid" value="~$v.sid`">
	<input type="hidden" name="mail_name_h" value="~$v.name_h`">
	<input type="hidden" name="mail_name_w" value="~$v.name_w`">
	<input type="hidden" name="email" value="~$v.email`">
	<input type="hidden" name="photo" value="~$v.photo`">
	<input type="hidden" name="datetime" value="~$v.datetime`">
	~if $search_user_h`
	<input type="hidden" name="search_user_h" value="~$search_user_h`">
	~/if`
	~if $search_user_w`
	<input type="hidden" name="search_user_w" value="~$search_user_w`">
	~/if`
	~if $search_name_h`
	<input type="hidden" name="search_name_h" value="~$search_name_h`">
	~/if`
	~if $search_name_w`
	<input type="hidden" name="search_name_w" value="~$search_name_w`">
	~/if`
	<table width="50%" align="center">
	~if $v.status eq 'UPLOADED'`
	
	<tr class="formhead" align="center"><td align="center"><input type="submit" name="remove" value="REMOVE"></td>
                                           <td align="center"><input type="submit" name="accept" value="UPLOAD"></td>
                                                <td align="center">&nbsp;</td>
	~else`
		~if $v.status eq 'REMOVED'`
		<tr class="formhead" align="center"><td align="center"><input type="submit" name="accept" value="UPLOAD"></td>
					   <td align="center">&nbsp;</td>
                                           <td align="center">&nbsp;</td>
		~else`
			~if $v.status eq 'REJECTED'`
				 <tr class="formhead" align="center"><td align="center"><input type="submit" name="accept" value="UPLOAD" ></td>
			                        
                        			                     <td align="center"><input type="submit" name="hold" value="HOLD"></td>


			~else`	
				<tr class="formhead" align="center"><td align="center"><input type="submit" name="accept" value="UPLOAD" ></td>
                                				    <td align="center"><input type="submit" name="reject" value="REJECT"></td>
                                           			    <td align="center"><input type="submit" name="hold" value="HOLD"></td>
			~/if`
		
		~/if`
	~/if`				
	</table>
	<br />
	</form>
	~/foreach`
	<br />
	<form action="~$SITE_URL`/operations.php/storyScreening/index" method="post">
	<table width="50%" align="center">
	<tr class="formhead" align="center"><td align="center"><input type="submit" name="cancelunhold" value="Cancel"></td></tr>
	<input type="hidden" name="cid" value="~$cid`">
	<input type="hidden" name="user" value="~$user`">
	<input type="hidden" name="unhold" value="~$UNHOLD`">
	</table>
	</form>
	~else`
		~if $UNHOLDMAIL eq "1"`
		<form action="~$SITE_URL`/operations.php/storyScreening/unhold" method="post">
 <table border="0" cellspacing="2" cellpadding="2" align=center><tr><td class="fieldsnew" align="center">Mail to be sent to ~$mail_name_h` and ~$mail_name_w` specifying reason for rejection</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td align="center">
                <textarea name="mail" value ="" cols="50" rows="10" >
                Dear Jeevansathi member,

                Thank you for sending you success story.

                We would request you to send a couple photograph of the wedding and your complete address in India (with phone number) so that we can send you a surprise gift as a token of appreciation from our side.

                Wishing you a happy married life ahead.

                Thanks and regards
                Jeevansathi Team
                </textarea>
                </td></tr>
                <tr><td>&nbsp;</td></tr>
                <input type="hidden" name="cid" value="~$cid`">
                <input type="hidden" name="user" value="~$user`">
                <input type="hidden" name="id" value="~$id`">
		<input type="hidden" name="unhold" value="~$UNHOLD`">
                <input type="hidden" name="email" value="~$email`">
                <tr><td align="center"><input type ="submit" value="send" name="send"></td></tr>
                </table>
                </form>
		~else`
		 <form name="unhold" action="~$SITE_URL`/operations.php/storyScreening/unhold">
	        ~if $NODATA`<p align="center"><font color="red">You have to enter at least one field!</font>~/if`
        	<table width="50%" align="center" cellspacing="2" cellpadding="2">
	        <tr class="formhead"><td colspan="3" align="center">PLEASE ENTER ONE OR MORE FIELDS</td></tr>
	        <tr class="fieldsnew"><td class="label" align="center">Story Id</td>
        	        <td align="center"><input type="text" name="STORY_ID" value="~$sid`"></td>
	        </tr>

        	<tr class="fieldsnew"><td class="label" align="center">User ID of Husband</td>
                <td align="center"><input type="text" name="search_user_h" value="~$search_user_h`"></td>
	        </tr>
        	<tr class="fieldsnew"><td class="label" align="center">User ID of Wife</td>
                <td align="center"><input type="text" name="search_user_w" value="~$search_user_w`"></td>
	        </tr>
        	<tr class="fieldsnew"><td class="label" align="center">Husband's name</td>
                <td align="center"><input type="text" name="search_name_h" value="~$search_name_h`"></td>
	        </tr>
        	<tr class="fieldsnew"><td class="label" align="center">Wife's name</td>
                 <td align="center"><input type="text" name="search_name_w" value="~$search_name_w`"></td>
	        </tr>
        	<tr class="fieldsnew"><td class="label" colspan="3" align="center"><input type="submit" value="Search" name="unsearch"></td></tr>
	        </table>
        	<input type="hidden" name="cid" value="~$cid`">
	        <input type="hidden" name="user" value="~$user`">
	        <input type="hidden" name="unhold" value="~$UNHOLD`">
	        </form>
        ~if $NOSTORY`<p align="center"><font color="red"><b>No story with given entries exists!</b></font></p>~/if`
		~/if`
	~/if`
~include_partial('global/footer')`	
