~include_partial('global/header')`
~include_partial("storyHeader",["SCREEN"=>1,user=>$user,cid=>$cid])`
<form action="~$SITE_URL`/operations.php/storyScreening/view" method="post">
	<table border="0" cellspacing="2" cellpadding="2" align=center><tr><td class="fieldsnew" align="center">Mail to be sent to ~$name_h` and ~$name_w` specifying reason for rejection</td></tr>
		<tr><td>&nbsp;</td></tr>
        <tr><td align="center">
            	<textarea name="mail" cols="50" rows="10" >
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
		<input type="hidden" name="id" value="~$id`">
		<input type="hidden" name="user" value="~$user`">	
		<input type="hidden" name="email" value="~$email`">
		<input type="hidden" name=FROM value="~$FROM`">
		~if $skip eq "1"`
		<input type="hidden" name="skip" value="~$skip`">
		~/if`
        	<tr><td align="center"><input type ="submit" value="Send" name="Send"></td></tr>
	        </table>
        	</form>
~include_partial('global/footer')`