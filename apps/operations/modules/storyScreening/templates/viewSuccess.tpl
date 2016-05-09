~include_partial('global/header')`
<br /><br /><br />
  <table width=760 align="CENTER" >
   <tr>
    <td height="23" class="formhead" align="center">
    ~if $fromPage eq 'SS'`
     You have successfully skipped ~$c` record(s)
     ~else if $fromPage eq 'HS'`
     The mail has been sent successfully
     ~else if $fromPage eq 'AS' || $fromPage eq 'OS'`
     You have successfully uploaded the story
     ~else if $fromPage eq 'RS'`
     You have successfully removed the story
     ~else if $fromPage eq 'SK'`
     This story has already been uploaded,&nbsp;marking story as duplicate.
     ~/if`<br><br>
     <a href="index?user=~$user`&cid=~$cid`">Continue</a>&nbsp;&nbsp;&nbsp;&nbsp;
    </td>
   </tr>
 </table>
~include_partial('global/footer')`
