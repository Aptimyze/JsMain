~include_partial('global/header')`
~include_partial("storyHeader",["SCREEN"=>1,user=>$user,cid=>$cid])`
<form action="~$SITE_URL`/operations.php/storyScreening/view" method="post">
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name=c value="~$c`">
<input type="hidden" name=user value="~$user`">
<input type="hidden" name=FROM value="~$FROM`">

<!-- carry on variable for success story screening"-->
<input type="hidden" name="id" value="~$id`">
<!-- end of code for success story screening-->

<table width="50%" border="0" cellspacing="1" cellpadding="4" align="center">
          <tr>
            <td class="formhead" valign="middle" colspan="2">&#155; Skip ~$c` records</td>
          </tr>
	~if $MSG`
	  <tr>
	    <td colspan=2 bgcolor="#F9F9F9" class="label" align="center"><font color="red">~$MSG`</font></td>						
	  </tr>	
	~/if`
          <tr>
            <td width="30%" class="label" bgcolor="#F9F9F9">Comments</td>

            <td width="70%" bgcolor="#F9F9F9">
        <textarea name="comments" class="testbox" cols="40" rows="2"></textarea>    
            </td>
          </tr>
          <tr valign="middle" align="Right">
              <td colspan="2"><input type="submit" name="Skip" value="  Skip  " class="textboxes1"></td></tr>
        </table>

</form>
~include_partial('global/footer')`
