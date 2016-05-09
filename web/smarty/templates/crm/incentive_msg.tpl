<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>JeevanSathi</title>
  <script>
   function print_bill()
   {
        window.open("../billing/bluedart_bill.php?cid=~$cid`&air=~$airway_number`","printpage","dependent=yes,width=775,height=600,screenX=300,screenY=400,titlebar=no,scrollbars=yes,maximize=no");
   }
  </script>

  <link rel="stylesheet" href="jeevansathi.css" type="text/css">
  <link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
 </head>
 ~include file="head.htm"`
 <br>
 <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <br><br>
  <table width=760 align="CENTER" >
   <tr>
    <td height="23" class="formhead" align="center">
     ~$MSG`
    </td>
   </tr>      
  </table>
  <br><br><br><br><br><br><br><br><br><br><br>
  ~include file="foot.htm"`
 </body>
</html>
