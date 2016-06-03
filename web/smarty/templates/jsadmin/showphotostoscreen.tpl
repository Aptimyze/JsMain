<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>JeevanSathi</title>
  <link rel="stylesheet" href="jeevansathi.css" type="text/css">
  <link rel="stylesheet" href="../profile/images/styles.css" type="text/css">
<script language="javascript">
<!--
function MM_openBrWindow(theURL,winName,features)
{
        window.open(theURL,winName,features);
}
//-->
</script>
 </head>
 ~include file="head.htm"`
 <br>
 <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table width=760 cellspacing="1" cellpadding='0' ALIGN="CENTER" >
   <tr width=100% border=1>
    <td width="3%" class="formhead" height="23">&nbsp;</td>
    <td width="47%" class="formhead" height="23"><font><b>Welcome :~$username`</b></font></td>
    <td width="6%" class="formhead" align='RIGHT' height="23">
     <a href="logout.php?cid=~$cid`">Logout</a>
    </td>
    <td width="3%" class="formhead" height="23">&nbsp;
    </td>
   </tr>
  </table>
<table width="600" border="0" cellspacing="1" cellpadding='3' ALIGN="CENTER" >
    <tr class=label align=center>
        <td width=10%>&nbsp;Gender</td>
        <td width=10%>&nbsp;Age</td>
        <td width=10%>&nbsp;Country</td>
        <td width=10%>&nbsp;City</td>
        <td width=10%>&nbsp;Marital Status</td>
        <td width=10%>&nbsp;Ethinicty (State of Origin)</td>
        <td width=10%>&nbsp;Religion</td>
        <td width=10%>&nbsp;Caste</td>
        <td width=10%>&nbsp;Country of Birth</td>
        <td width=10%>&nbsp;City of Birth</td>
    </tr>
    <tr class=fieldsnew align=center>
        <td>&nbsp;~$SHOW_GENDER`</td>
        <td>&nbsp;~$SHOW_AGE`</td>
        <td>&nbsp;~$SHOW_COUNTRY[0]`</td>
        <td>&nbsp;~if $SHOW_CITYRES[0] neq ''` ~$SHOW_CITYRES[0]` ~else` Outside India ~/if`</td>
        <td>&nbsp;~$SHOW_MSTATUS`</td>
        <td>&nbsp;~$SHOW_MTONGUE[0]`</td>
        <td>&nbsp;~$SHOW_RELIGION[0]`</td>
        <td>&nbsp;~$SHOW_CASTE[0]`</td>
        <td>&nbsp;~$SHOW_COUNTRY_BIRTH[0]`</td>
        <td>&nbsp;~if $SHOW_CITY_BIRTH neq ''` ~$SHOW_CITY_BIRTH` ~else` Not Specified ~/if`</td>
    </tr>
</table>
  <form enctype="multipart/form-data" action="uploadphoto.php" method="POST">
   <input type=hidden name="profileid" value="~$profileid`">
   <input type=hidden name="cid" value="~$cid`">
   <input type=hidden name="username" value="~$username`">
   <input type=hidden name="count_photos" value="~$count_photos`"> 
   <table width=760 align="CENTER" >
     <tr width=100%>
      <td width=25%>&nbsp;</td>
      <td width=25%>&nbsp;</td>
      <td width=50%>&nbsp;</td>
     </tr> 
     <tr class="formhead">
      <td>Username : ~$USERNAME`</td>
      <td>Gender : <font class="red">~$GENDER`</font></td>
<!--      <td><a href="#" onClick="MM_openBrWindow('../profile/photocheck.php?profilechecksum=~$PROFILECHECKSUM`&seq=1&jsadmin=yes','','width=400,height=500,scrollbars=yes')">Click here for album photos</a></td>-->
      <td><a href="show_album_photos.php?cid=~$cid`&profileid=~$profileid`" target="_blank">Click here for album photos</a></td>
     </tr>    	
     <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
     </tr>    	
    ~if $mainphoto eq "Y"`    
     <tr align="CENTER" class="fieldsnew">
      <td>Main Photo</td>      
      <td>	
       <img src="../profile/photo_serve_jsadmin.php?profileid=~$CHECKSUM`&photo=MAINPHOTO&jsval=T&cid=~$cid`" height="200" width="150"></img>
      </td>
      <td>
		<input type="checkbox" name="delete_main_photo">Delete<br>
		<input name="mainphotofile" type="file">		      		       		
      </td>
     </tr>
    ~/if`
    ~if $albumphoto1 eq "Y"`
     <tr align="CENTER" class="fieldsnew">
      <td>Album Photo 1</td>
      <td>
       <img src="../profile/photo_serve_jsadmin.php?profileid=~$CHECKSUM`&photo=ALBUMPHOTO1&jsval=T&cid=~$cid`" height="200" width="150"></img>
      </td>
      <td>
		<input type="checkbox" name="delete_album_photo1">Delete<br>
		<input name="albumphoto1file" type="file">      	
      </td>    
     </tr>
    ~/if`
    ~if $albumphoto2 eq "Y"`
     <tr align="CENTER" class="fieldsnew">
      <td>Album Photo 2</td>	
      <td>
       <img src="../profile/photo_serve_jsadmin.php?profileid=~$CHECKSUM`&photo=ALBUMPHOTO2&jsval=T&cid=~$cid`" height="200" width="150"></img>
      </td>
      <td>
		<input type="checkbox" name="delete_album_photo2">Delete<br>
		<input name="albumphoto2file" type="file">		             	
      </td>        
     </tr>
    ~/if`    
    ~if $thumbnail eq "Y"`    
     <tr align="CENTER" class="fieldsnew">
      <td>Thumbnail (60x60 size)</td>	
      <td>&nbsp;</td>
      <td><input name="thumbnailfile" type="file"></td>        
     </tr>
    ~/if`
    ~if $profilephoto eq "Y"`        
     <tr align="CENTER" class="fieldsnew">
      <td>Profile photo (150x200 size)</td>	
      <td>&nbsp;</td>
      <td><input name="profilephotofile" type="file"></td>        
     </tr>    	
    ~/if`
     <tr>
      <td colspan="3">&nbsp;</td>
     </tr>    
    ~if $profilephoto eq "Y"`        
     <tr align="CENTER" class="fieldsnew">
      <td>Photo Grade</td>	
      <td>&nbsp;</td>
      <td><select name="photograde">
		<!--option>A</option-->
		<option selected>B</option>
		<option>C</option>
	  </select>
      </td>        
     </tr>    	
    ~/if`
     <tr>
      <td colspan="3">&nbsp;</td>
     </tr>    	
   ~if $count_photos neq "0"`
     <tr align="CENTER" class="fieldsnew">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" name="Upload" value="Upload">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="submit" name="Delete" value="Delete"></td>
     </tr>    	
   ~else`
     <tr>
      <td colspan="3">&nbsp;</td>
     </tr>    	
   ~/if`
   </table>
   <br><br><br>
  </form>
  <br><br><br><br>
  ~include file="foot.htm"`
 </body>
</html>
