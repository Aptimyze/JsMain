
<HTML>
  
  <HEAD>
    <TITLE>BMS : Campaign Management System</TITLE>
    <LINK HREF="gifs/style.css" REL="stylesheet" TYPE="text/css">
	<script language="JavaScript">
<!--

function change()
{
 
   var docF=document.addeditzone;
          var c,spec;
       var len_course = docF.zone.options.length;
       for(var m1=0;m1<len_course;m1++) {
       if (docF.zone.options[m1].selected == true) {
           c = docF.zone.options[m1].value;
       }
       }
       if(c!="" && c!="select")
       {   var str=c.split("|X|");
           docF.zonename.value=str[1];   
		   docF.zonedesc.value=str[3];
		   docF.zonemaxbans.value=str[4];
		   docF.zonemaxbansrot.value=str[5];
		   
		   docF.zoneadvbook.value=str[6];
		   docF.zonecncl.value=str[7];
		   docF.zonebanwidth.value=str[10];
		   docF.zonebanheight.value=str[11];
		   
      		if(str[12]=='Y')
               docF.zonepopup.checked=true;
           else
               docF.zonepopup.checked=false;  
			var len_status = docF.zonestatus.options.length;
       		for(var m2=0;m2<len_status;m2++) {
       		if (docF.zonestatus.options[m2].value == str[8]) {
           			docF.zonestatus.options[m2].selected=true;
       }
       }   
	   		var len_status = docF.zonealign.options.length;
			for(var m2=0;m2<len_status;m2++) {
       		if (docF.zonealign.options[m2].value == str[9]) {
           			docF.zonealign.options[m2].selected=true;
       }
       }   
	   var len_status = docF.zonecriteria.options.length;
			for(var m2=0;m2<len_status;m2++) {
       		if (docF.zonecriteria.options[m2].value == str[13]) {
           			docF.zonecriteria.options[m2].selected=true;
       }
       }   

			document.getElementById('addimage').style.visibility ="hidden";
			document.getElementById('editimage').style.visibility ="visible";
			document.getElementById('deleteimage').style.visibility ="hidden";          
       }
       else
       {
          
          
           docF.zonename.value="";   
           docF.zonedesc.value="";
		   docF.zonemaxbans.value="";
		   docF.zonemaxbansrot.value="";
		   docF.zoneadvbook.value="15";
		   docF.zonecncl.value="2";
		   docF.zonebanwidth.value="233";
		   docF.zonebanheight.value="60";
           docF.zonepopup.checked=false;
		   document.getElementById('addimage').style.visibility= "visible";
			document.getElementById('editimage').style.visibility = "hidden";
			document.getElementById('deleteimage').style.visibility = "hidden";   
       }
  }
    
function disableedit()
{
	
	var docF=document.addeditzone;
          var c,spec;
       var len_course = docF.zone.options.length;
       for(var m1=0;m1<len_course;m1++) {
       if (docF.zone.options[m1].selected == true) {
           c = docF.zone.options[m1].value;
       }
       }
	   
       if(c=="" || c=="select")
	   {
	   	document.getElementById('addimage').style.visibility= "visible";
			document.getElementById('editimage').style.visibility= "hidden";
			document.getElementById('deleteimage').style.visibility = "hidden";  
	   }
	   else
	   {
	   	document.getElementById('addimage').style.visibility = "hidden";
			document.getElementById('editimage').style.visibility= "visible";
			document.getElementById('deleteimage').style.visibility=  "hidden";  
	   }
}
function isInteger(iNumber)
{
	var i;
	
	for (i=0;i<iNumber.length;i++)
	{
		var c = iNumber.charAt(i);
	
		if (!isDigit(c))
		{
			return false;
		}
	}
	
  	return true;
}
function isDigit (c)
{
		 return ((c >= "0") && (c <= "9"))
}
function cnfrmDelete()
{
        return(confirm("Do you really want to delete this zone"));
		        
}

function validateForm()
{
    
    var docF=document.addeditzone;
    var error="";
    
    if(docF.zonename.value=="")
    {
     error=error+"\n Please fill in the zone name";
    }
    else if(docF.zonedesc.value=="")
    {
        
       error=error+"\n Please fill in the zone description";
       
    }
	else if(docF.zonemaxbans.value==""|| !isInteger(docF.zonemaxbans.value) || docF.zonemaxbans.value>50) 
    {
        
       error=error+"\n Please fill in the correct value of max banners in zone";
       
    }
	else if(docF.zonebanwidth.value=="" || !isInteger(docF.zonebanwidth.value) || docF.zonebanwidth.value>500)
    {
        
       error=error+"\n Please fill in the correct value of zone banner width";
       
    }
	else if(docF.zonebanheight.value=="" || !isInteger(docF.zonebanheight.value) || docF.zonebanheight.value>500)
    {
        
       error=error+"\n Please fill in the correct value of zone banner height";
       
    }
	else if(docF.zoneadvbook.value=="" || !isInteger(docF.zoneadvbook.value) || docF.zoneadvbook.value>60)
    {
        
       error=error+"\n Please fill in the correct value of advance booking period";
       
    }
	else if(docF.zonecncl.value=="" || !isInteger(docF.zonecncl.value) || docF.zonecncl.value>30)
    {
        
       error=error+"\n Please fill in the correct value of cancellation period";
       
    }
	
    if(error=="")
    {   
        return true;
    }
    else
    {
        alert(error);
        return false;
    }   
}


-->

</script>   

  </HEAD>
  
  <BODY BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#000000" VLINK="#000000" 
  ALINK="#000000" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0" onload="disableedit();" onRefresh="disableedit();">


    ~$bmsheader`
	<DIV CLASS="text-under" STYLE="margin-left:20px;">
  <A HREF="bms_adminindex.php?id=~$id`" TARGET="">MIS Home</A> &gt;&gt; Banner Admin &gt;&gt; <a href="bms_region.php?id=~$id`" target="">Region Details</a> 
  &gt;&gt; <B>Zone Details</B></DIV>
<br>
<DIV CLASS="text"><TABLE WIDTH="100%" BORDER="0">
      <TR>
        <TD WIDTH="20%" ALIGN="RIGHT"><IMG SRC="gifs/zero.gif" ALT="New Clients" BORDER="0" HSPACE="0" VSPACE="0" WIDTH="250" HEIGHT="1"><IMG SRC="gifs/tick.gif" ALT="New Clients" BORDER="0" HSPACE="0" VSPACE="0" WIDTH="13" HEIGHT="11">
        </TD>
        
      <TD WIDTH="80%" CLASS="text">You are currently viewing banner details.</TD>
      </TR>
	  ~if $errormsg`
	  <TR> 
    <TD WIDTH="20%" ALIGN="RIGHT"><img src="gifs/error-2.gif" alt="New Clients" border="0" hspace="0" vspace="0" width="16" height="16"></TD>
    <TD WIDTH="80%" CLASS="error">~$errormsg`</TD>
  </TR>
  ~/if`
  ~if $cnfrmmsg`
  <TR>
    <TD WIDTH="20%" ALIGN="RIGHT"><img src="gifs/action-performed.gif" alt="New Clients" border="0" hspace="0" vspace="0" width="16" height="16"></TD>
    <TD WIDTH="80%" CLASS="text">~$cnfrmmsg`</TD>
  </TR>
  ~/if`
    </TABLE></DIV>
	
    <FORM name="addeditzone" method="POST" action="bms_zone.php" onsubmit="return validateForm();"  >
	<input type="hidden" name="id" value="~$id`" />
	<input type="hidden" name="regionid" value="~$regionid`" />
	<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0" 
    ALIGN="CENTER">
      <TR>
        <TD WIDTH="18" BACKGROUND="gifs/page-bg.gif"><IMG SRC="gifs/lt-curve.gif" ALT="Resdex - naukri.com" WIDTH="18" HEIGHT="31" BORDER="0" HSPACE="0"></TD>
        <TD VALIGN="MIDDLE" HEIGHT="31" WIDTH="100%"><TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0" HEIGHT="31">
          <TR>
            <TD BACKGROUND="gifs/page-bg.gif" WIDTH="70%">&nbsp;</TD>
            <TD WIDTH="30%" BACKGROUND="gifs/page-bg.gif" ALIGN="LEFT">&nbsp;</TD>
          </TR>
        </TABLE></TD>
        <TD ALIGN="RIGHT" WIDTH="18" BACKGROUND="gifs/page-bg.gif"><IMG SRC="gifs/rt-curve.gif" ALT="Resdex - naukri.com" WIDTH="18" HEIGHT="31" BORDER="0"></TD>
      </TR>
    </TABLE><TABLE WIDTH="100%" BORDER="0" VSPACE="0" HSPACE="0" 
    CELLPADDING="0" CELLSPACING="0" ALIGN="CENTER">
      <TR>
        <TD WIDTH="18" BACKGROUND="gifs/lt-line-bg-1.gif"><IMG SRC="gifs/zero.gif" ALT="Account Information" BORDER="0" HSPACE="0" VSPACE="0" WIDTH="18" HEIGHT="1"></TD>
        <TD WIDTH="100%"> <BR><DIV ALIGN="CENTER"><TABLE WIDTH="65%" BORDER="0" VSPACE="0" HSPACE="0" CELLPADDING="4" CELLSPACING="0">
          <TR>
            <TD CLASS="text" WIDTH="50%" HEIGHT="20" VALIGN="TOP"><IMG SRC="gifs/zero.gif" ALT="New Clients" BORDER="0" HSPACE="0" VSPACE="0" WIDTH="130" HEIGHT="1"><BR><B>Select
            Zone</B></TD>
            <TD ALIGN="LEFT" WIDTH="50%"><SELECT NAME="zone" ONCHANGE="change();" CLASS="text-small">
            <option value="" selected >New</option>
							~section  name=i loop=$zones`
							
                       	 <option value="~$zones[i].zoneid`|X|~$zones[i].zonename`|X|~$zones[i].zonereg`|X|~$zones[i].zonedesc`|X|~$zones[i].zonemaxbans`|X|~$zones[i].zonemaxbansrot`|X|~$zones[i].zoneadvbook`|X|~$zones[i].zonecncl`|X|~$zones[i].zonestatus`|X|~$zones[i].zonealign`|X|~$zones[i].zonebanwidth`|X|~$zones[i].zonebanheight`|X|~$zones[i].zonepopup`|X|~$zones[i].criteriaid`" ~if $zoneid eq $zones[i].zoneid` selected ~/if`>~$zones[i].zonename` </option>
                             ~/section`
							 
							 </SELECT></TD>
          </TR>
          <TR>
            <TD CLASS="text" HEIGHT="20" VALIGN="TOP"><B>Zone name</B></TD>
            <TD ALIGN="LEFT"><INPUT TYPE="text" name="zonename"  maxlength="30" value="~$zonename`" SIZE="25" CLASS="textbox">
            </TD>
          </TR>
          <TR>
            <TD CLASS="text" HEIGHT="20" VALIGN="TOP"><B>Description</B></TD>
            <TD ALIGN="LEFT"><INPUT TYPE="text" name="zonedesc"  maxlength="150" value="~$zonedesc`" SIZE="25" CLASS="textbox">
            </TD>
          </TR>
          <TR>
            <TD CLASS="text" HEIGHT="20" VALIGN="TOP"><B>Maximum Banners
            Allowed (max 50)</B></TD>
            <TD ALIGN="LEFT"><INPUT TYPE="text" name="zonemaxbans" maxlength="10" SIZE="10" CLASS="textbox" value="~$zonemaxbans`"></TD>
          </TR>
          <TR>
            <TD CLASS="text" HEIGHT="20" VALIGN="TOP"><B>Maximum Banners in
            rotation</B><BR><SPAN CLASS="text-small-black">(Check only if
            rotation is to be allowed)</SPAN></TD>
            <TD ALIGN="LEFT"><INPUT TYPE="text" name="zonemaxbansrot" maxlength="10"  SIZE="10" CLASS="textbox" value="~$zonemaxbansrot`"></TD>
          </TR>
          <TR>
            <TD CLASS="text" HEIGHT="20" VALIGN="TOP"><B>Advance Booking
            Period (max 60 days)</B></TD>
            <TD ALIGN="LEFT" VALIGN="TOP"><INPUT TYPE="text" name="zoneadvbook" maxlength="10" ~if $zoneadvbook`value="~$zoneadvbook`" ~else` value="15" ~/if` SIZE="10" CLASS="textbox" ></TD>
          </TR>
          <TR>
            <TD CLASS="text" HEIGHT="20" VALIGN="TOP"><B>Cancellation Period
            (max 30 days) </B></TD>
            <TD ALIGN="LEFT" VALIGN="TOP"><INPUT TYPE="text" name="zonecncl"  maxlength="10" ~if $zonecncl` value="~$zonecncl`" ~else` value="2" ~/if` SIZE="10" CLASS="textbox"></TD>
          </TR>
          <TR>
            <TD CLASS="text" HEIGHT="20" VALIGN="TOP"><B>Zone Status</B></TD>
            <TD ALIGN="LEFT" VALIGN="TOP"><SELECT NAME="zonestatus" CLASS="text-small">
            <OPTION VALUE="active" ~if $zonestatus eq "active"` selected ~/if`>Active</OPTION>
            <OPTION VALUE="inactive" ~if $zonestatus eq "inactive"` selected ~/if`>Inactive</OPTION></SELECT> </TD>
          </TR>
          <TR>
            <TD CLASS="text" HEIGHT="20" VALIGN="TOP"><B>Zone Alignment</B></TD>
            <TD ALIGN="LEFT" VALIGN="TOP"><SELECT NAME="zonealign" CLASS="text-small">
            <OPTION VALUE="V" ~if $zonealign eq "V"` selected ~/if`>Vertical</OPTION>
            <OPTION VALUE="H" ~if $zonealign eq "H"` selected ~/if`>Horizontal</OPTION></SELECT> </TD>
          </TR>
          <TR>
            <TD CLASS="text" HEIGHT="20" VALIGN="TOP"><B>Banner Width </B></TD>
            <TD ALIGN="LEFT" VALIGN="TOP"><INPUT TYPE="text" name="zonebanwidth"  maxlength="10" ~if $zonebanwidth` value="~$zonebanwidth`" ~else` value="233" ~/if` SIZE="10" CLASS="textbox"></TD>
          </TR>
          <TR>
            <TD CLASS="text" HEIGHT="20" VALIGN="TOP"><B>Banner Height</B></TD>
            <TD ALIGN="LEFT" VALIGN="TOP"><INPUT TYPE="text" name="zonebanheight"  maxlength="10" ~if $zonebanheight` value="~$zonebanheight`" ~else`  value="60" ~/if` SIZE="10" CLASS="textbox"></TD>
          </TR>
          <TR>
            <TD CLASS="text" HEIGHT="20" VALIGN="TOP"><B>Is the zone a pop up?</B></TD>
            <TD ALIGN="LEFT" VALIGN="TOP"><INPUT TYPE="checkbox" name="zonepopup" value="Y" ~if $zonepopup eq "Y"` checked ~/if` ></TD>
          </TR>
          <TR>
            <TD CLASS="text" HEIGHT="20" VALIGN="TOP"><B>Select Criteria of
            this zone</B></TD>
            <TD ALIGN="LEFT" VALIGN="TOP"><SELECT NAME="zonecriteria" CLASS="text-small">
             ~section  name=i loop=$criteria`        
							
							
                       	 <option value="~$criteria[i].criteriaid`" ~if $criteria[i].criteriaid eq $zonecriteria` selected ~/if`>~$criteria[i].criterianame` </option>
                             ~/section`
							 
							 </SELECT></TD>
          </TR>
        </TABLE></DIV><BR><DIV ALIGN="CENTER"><TABLE WIDTH="55%" BORDER="0">
          <TR>
            <TD><INPUT TYPE="image" NAME="reset" SRC="gifs/reset.gif" BORDER="0"></TD>
            <TD><INPUT TYPE="image" NAME="add" SRC="gifs/add-zone.gif" BORDER="0" id="addimage" ></TD>
			
            <TD><INPUT TYPE="image" NAME="edit" SRC="gifs/edit-zone.gif" BORDER="0" id="editimage" ></TD>
			
            <TD><INPUT TYPE="IMAGE" NAME="deletee" SRC="gifs/del-zone.gif" BORDER="0"  id="deleteimage" onClick="return cnfrmDelete();"></TD>
          </TR>
        </TABLE></DIV><BR>
        <P></P>
        <P></P></TD>
        <TD BACKGROUND="gifs/rt-line-bg-1.gif" WIDTH="18"><IMG SRC="gifs/zero.gif" ALT="Account Information" BORDER="0" HSPACE="0" VSPACE="0" WIDTH="18" HEIGHT="1"></TD>
      </TR>
    </TABLE><TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0" 
    ALIGN="CENTER">
      <TR>
        <TD WIDTH="18" BACKGROUND="gifs/page-bg-1.gif"><IMG SRC="gifs/lt-low-curve.gif" ALT="Resdex - naukri.com" WIDTH="18" HEIGHT="31" BORDER="0"></TD>
        <TD VALIGN="MIDDLE" HEIGHT="31" BACKGROUND="gifs/page-bg-1.gif">&nbsp;</TD>
        <TD ALIGN="RIGHT" WIDTH="18" BACKGROUND="gifs/page-bg-1.gif"><IMG SRC="gifs/lt-rt-curve.gif" ALT="Resdex - naukri.com" WIDTH="18" HEIGHT="31" BORDER="0"></TD>
      </TR>
    </TABLE></FORM>
    
    ~$bmsfooter`
  </BODY>
</HTML>
