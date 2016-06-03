~include_partial('global/header',[timedout=>1])`
 <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
~if $cid`  
  <table width=760 cellspacing="1" cellpadding='0' ALIGN="CENTER" >
   <tr width=100% border=1>
    <td width=30% class="formhead" align='CENTER' height="23">
     <a href="../jsadmin/mainpage.php?cid=~$cid`">Click here to go to mainpage</a>
    </td>
    <td width=30% class="formhead" align='CENTER' height="23">
     <a href="../jsadmin/logout.php?cid=~$cid`">Logout</a>
    </td>
   </tr>
  </table>
~/if`
<br /><br /><br />
  <table width=760 align="CENTER" >
   <tr>
    <td height="23" class="formhead" align="center">
     ~$MSG`
    </td>
   </tr>      
  </table>
  <br><br><br><br><br><br><br><br><br><br><br>
~include_partial('global/footer')`
 </body>
