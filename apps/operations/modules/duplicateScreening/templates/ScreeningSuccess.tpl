<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link rel="stylesheet" type="text/css" href="~sfConfig::get('app_img_url')`/profile/css/pd_screen.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>OPS Screening Module</title>
	</head>
	<script language="JavaScript">
        function comment()
        {
                var comments = document.getElementById("comments").value;
		var comments_bi = document.getElementById("comments_bi").value;
		var comments_ti = document.getElementById("comments_ti").value;
		var comments_mi = document.getElementById("comments_mi").value;
                comments = comments.replace(/^\s*|\s*$/,"");
		comments_bi = comments_bi.replace(/^\s*|\s*$/,"");
		comments_ti = comments_ti.replace(/^\s*|\s*$/,"");
		comments_mi = comments_mi.replace(/^\s*|\s*$/,"");
                if(comments=='' && comments_bi=='' && comments_ti=='' && comments_mi=='')
                {
                        alert("Comments are mandatory for marking Can't Say");
                        return false;
                }
		else{ 
			var check_msg =showMessage('cant_say');
			if(check_msg!=true)
				return false;
		}
        }
	function showMessage(msg)
	{
		if(msg=='duplicate')
			var confirm_msg =confirm("Mark the profile pair as Duplicate ?");
		else if(msg=='not_duplicate')
			var confirm_msg =confirm("Mark the profile pair as Not Duplicate ?");
		else if(msg=='cant_say')	
			var confirm_msg =confirm("Mark the profile pair as Can't Say ?");
		if(confirm_msg==true)
			return true;
		else
			return false;
	}
	</script>

	~if $page eq 'Examine'`
	<form action="~sfConfig::get('app_site_url')`/operations.php/duplicateScreening/Examine?cid=~$cid`" method="POST">
	~else`
	<form action="~sfConfig::get('app_site_url')`/operations.php/duplicateScreening/Screening?cid=~$cid`" method="POST">
	~/if`
		<input type=hidden name="profileid1" value="~$profile1->getPROFILEID()`">
		<input type=hidden name="profileid2" value="~$profile2->getPROFILEID()`">
		<input type=hidden name="reason" value="~$pair->getReason()`">
		<input type=hidden name="identified_on" value="~$pair->getExtension('IDENTIFIED_ON')`">
		<input type=hidden name="screened_by" value="~$pair->getScreenedBy()`">
	<body>
		<div id="wrapper">
			<!--HEADER STARTS -->
			<div id="header">
				<div class="fl">
					<div class="logo">&nbsp;<font class="fl">We Match Better</font></div>
				</div>
				<div class="title">Probable Duplicate Profile Screening</div>
				<div class="box fl">
					<div class="heading">Profile suspected to be duplicate on the basis of</div>
					<div class="clear"></div>
					<div class="fl options">
						<input type="checkbox" ~if stristr($pair->getReason(), 'PHONE')`checked="checked"~/if` DISABLED/>Phone number matching<br />
						<input type="checkbox" ~if stristr($pair->getReason(), 'CRAWLER')`checked="checked"~/if` DISABLED/>Profile information matching<br />
						<input type="checkbox" ~if stristr($pair->getReason(), 'TEXT')`checked="checked"~/if` DISABLED/>Text information matching            
					</div>
					<div class="fl options2">           
						<input type="checkbox" ~if stristr($pair->getReason(), 'EMAIL') or stristr($pair->getReason(), 'MESSENGER')`checked="checked"~/if` DISABLED/>eMail or Messenger ID matching <br /> 
						<input type="checkbox" ~if stristr($pair->getReason(), 'PHOTO')`checked="checked"~/if`/ DISABLED>Photo matching <br />
						<input type="checkbox" ~if stristr($pair->getReason(), 'ID_PROOF')`checked="checked"~/if`/ DISABLED>ID proof matching           
					</div>
				</div>
			</div>
			<!--HEADER FINISH -->
		    
			<!--CONTENT STARTS -->
			<div id="content">
				<div class="brown-heading fl">
					<div class=" fs20  contentdivleft ">Profile of ~$profile1->getUSERNAME()`</div>    	
					<div class=" fs20 contentdivright">Duplicate with ~$profile2->getUSERNAME()`</div>
				</div>
			</div>
			~if $album1|@count>0 && $album2|@count>0`
			<div id="photo">
				<div class="white-heading2 fl">
					<div class="contentdivleft2">			
					~foreach from=$album1 item=photo1`
						~if $photo1->getMainPicUrl() neq ''`
							<div><img src="~$photo1->getMainPicUrl()`" width="232px" height="248px"/></div>
						~/if`
					~/foreach`
					</div>
					<div class="contentdivright2">
					~foreach from=$album2 item=photo2`
						~if $photo2->getMainPicUrl() neq ''`
							<div><img src="~$photo2->getMainPicUrl()`" width="232px" height="248px"/></div>
						~/if`
					~/foreach`
					</div>
				</div>
			</div>
			~/if`

			<div class="clr">&nbsp;</div>
			<!--DUPLICATE DECISION BOX STARTS-->           
			<div class="box-grey" >
				<textarea placeholder="Enter text" name=comments id=comments>~$comments`</textarea>
				<div class="sp10">&nbsp;</div>           
				<input type="Submit" name="marked" class="btn-red fs16 white b" value="Duplicate" onclick="return showMessage('duplicate')"/>
				<input type="Submit" name="marked" class="btn-green fs16 white b"  value="Not Duplicate" onclick="return showMessage('not_duplicate')"/>
				<input type="Submit" name="marked" class="btn-orange fs16 white b" value="Cant Say" onclick="return comment()"/>
			</div>
			<!--DUPLICATE DECISION BOX FINISH-->
			<div class="clr">&nbsp;</div>
			<div class="brown-heading2">
				<div class="fl div1">&nbsp;</div><div  class="fl orange fs20 div2">Basic Info</div>
				<div class=" fs20  contentdivleft">~$profile1->getUSERNAME()`</div>    	
				<div class=" fs20 contentdivright">~$profile2->getUSERNAME()`</div>
			</div>
			<!--  BASIC INFO STARTS -->				
			~if ($archiveInfo1.USERNAME && $archiveInfo2.USERNAME) || ($profile1->getDecoratedPersonHandlingProfile() && $profile2->getDecoratedPersonHandlingProfile())`	
			<div class="white-heading fl">
            			<div class="fl div1">&nbsp;</div>
            			<div  class="fl orange  div2">Name</div>
                                <div class="contentdivleft">
					~if $archiveInfo1.USERNAME && $archiveInfo2.USERNAME`
						<font ~if stristr(str_replace(" ","",$archiveInfo1.USERNAME),str_replace(" ","",$archiveInfo2.USERNAME)) || stristr(str_replace(" ","",$archiveInfo2.USERNAME),str_replace(" ","",$archiveInfo1.USERNAME))`  class="red" ~/if`>
	                                                ~$archiveInfo1.USERNAME`
						</font>
					~/if`
					~if $profile1->getDecoratedPersonHandlingProfile() && $profile2->getDecoratedPersonHandlingProfile()`
        	                                        <font ~if stristr(str_replace(" ","",$profile1->getDecoratedPersonHandlingProfile()),str_replace(" ","",$profile2->getDecoratedPersonHandlingProfile())) || stristr(str_replace(" ","",$profile2->getDecoratedPersonHandlingProfile()),str_replace(" ","",$profile1->getDecoratedPersonHandlingProfile()))`  class="red" ~/if`>
							~if $archiveInfo1.USERNAME && $archiveInfo2.USERNAME`
                	                                , 
							~/if`
							~$profile1->getDecoratedPersonHandlingProfile()`
                        	                        </font>
					~/if`
                                </div>
                                <div class="contentdivright">
                                        ~if $archiveInfo1.USERNAME && $archiveInfo2.USERNAME`
                                                <font ~if stristr(str_replace(" ","",$archiveInfo1.USERNAME),str_replace(" ","",$archiveInfo2.USERNAME)) || stristr(str_replace(" ","",$archiveInfo2.USERNAME),str_replace(" ","",$archiveInfo1.USERNAME))`  class="red" ~/if`>
                                                        ~$archiveInfo2.USERNAME`
                                                </font>
					~/if`
					~if $profile1->getDecoratedPersonHandlingProfile() && $profile2->getDecoratedPersonHandlingProfile()`
        	                                        <font ~if stristr(str_replace(" ","",$profile2->getDecoratedPersonHandlingProfile()),str_replace(" ","",$profile1->getDecoratedPersonHandlingProfile())) || stristr(str_replace(" ","",$profile1->getDecoratedPersonHandlingProfile()),str_replace(" ","",$profile2->getDecoratedPersonHandlingProfile()))`  class="red" ~/if`>
							~if $archiveInfo1.USERNAME && $archiveInfo2.USERNAME`
                	                                ,
							~/if`
							 ~$profile2->getDecoratedPersonHandlingProfile()`
                        	                        </font>
					~/if`
                                </div>
            		</div>
			~/if`
			~if ($profile1->getPhoneNumber() || $profile1->getPHONE_MOB() || $profile1->getExtendedContacts()->ALT_MOBILE || $archiveInfo1.ALTERNATE_NUM || $archiveInfo1.CONTACT.PHONE_RES || $archiveInfo1.CONTACT.PHONE_MOB || $archiveInfo1.CONTACT.ALT_MOBILE) && ($profile2->getPhoneNumber() || $profile2->getPHONE_MOB() || $profile2->getExtendedContacts()->ALT_MOBILE || $archiveInfo2.ALTERNATE_NUM || $archiveInfo2.CONTACT.PHONE_RES || $archiveInfo2.CONTACT.PHONE_MOB || $archiveInfo2.CONTACT.ALT_MOBILE)`
			<div class="grey-heading fl">
            			<div class="fl div1">&nbsp;</div>
            			<div  class="fl orange  div2">Phone Numbers</div>
            			<div class="contentdivleft">
					~if $profile1->getPhoneNumber()`
							<font ~if $phoneFlagArr1.PHONE_RES eq 'Y'`  class="red"~/if`>
							~$profile1->getPhoneNumber()`
							</font>
					~/if`
					~if $profile1->getPHONE_MOB()`
							~if $profile1->getPhoneNumber()`
							,
							~/if`
							<font ~if $phoneFlagArr1.PHONE_MOB eq 'Y'`  class="red"~/if`>	
							~$profile1->getPHONE_MOB()`
							</font>
					~/if`
                                        ~if $profile1->getExtendedContacts()->ALT_MOBILE`
                                                        ~if $profile1->getPhoneNumber() || $profile1->getPHONE_MOB()`
                                                        ,
                                                        ~/if`
							<font ~if $phoneFlagArr1.ALT_MOBILE eq 'Y'`  class="red"~/if`>
                                                         ~$profile1->getExtendedContacts()->ALT_MOBILE`
                                                        </font>
                                        ~/if`
                                        ~if $archiveInfo1.ALTERNATE_NUM`
							~if $profile1->getPhoneNumber() || $profile1->getPHONE_MOB() || $profile1->getExtendedContacts()->ALT_MOBILE` 
                                                        ,
							~/if`
							<font ~if $phoneFlagArr1.ALTERNATE_NUM eq 'Y'`  class="red"~/if`> 
							~$archiveInfo1.ALTERNATE_NUM`
                                                        </font>
                                        ~/if`
					<!--  Display Archived Numbers Residence -->
					~if $archiveInfo1.CONTACT.PHONE_RES`	
						~if $profile1->getPhoneNumber() || $profile1->getPHONE_MOB() || $profile1->getExtendedContacts()->ALT_MOBILE || $archiveInfo1.ALTERNATE_NUM`
						<br> 
						~/if`
						<font ~if $phoneFlagArr1.CONTACT_PHONE_RES eq 'Y'`  class="red"~/if`>
						~$archiveInfo1.CONTACT.PHONE_RES`
						</font>
					~/if`	

					<!--  Display Archived Numbers Mobile -->
					~if $archiveInfo1.CONTACT.PHONE_MOB`		
						~if $profile1->getPhoneNumber() || $profile1->getPHONE_MOB() || $profile1->getExtendedContacts()->ALT_MOBILE || $archiveInfo1.ALTERNATE_NUM || $archiveInfo1.CONTACT.PHONE_RES`
						<br> 
						~/if`
						<font ~if $phoneFlagArr1.CONTACT_PHONE_MOB eq 'Y'`  class="red"~/if`>
						~$archiveInfo1.CONTACT.PHONE_MOB`
						</font>
					~/if`

					<!--  Display Archived Numbers Alternate Num -->
					~if $archiveInfo1.CONTACT.ALT_MOBILE`
                                        	~if $profile1->getPhoneNumber() || $profile1->getPHONE_MOB() || $profile1->getExtendedContacts()->ALT_MOBILE || $archiveInfo1.ALTERNATE_NUM || $archiveInfo1.CONTACT.PHONE_RES || $archiveInfo1.CONTACT.PHONE_MOB`
                                        	<br>
                                        	~/if`
						<font ~if $phoneFlagArr1.CONTACT_ALT_MOBILE eq 'Y'`  class="red"~/if`>
                                        	~$archiveInfo1.CONTACT.ALT_MOBILE`
                                        	</font>
					~/if`
				</div>
                                <div class="contentdivright">
                                        ~if $profile2->getPhoneNumber()`
							<font ~if $phoneFlagArr2.PHONE_RES eq 'Y'`  class="red"~/if`>
                                                        ~$profile2->getPhoneNumber()`
                                                        </font>
                                        ~/if`
                                        ~if $profile2->getPHONE_MOB()`
                                                        ~if $profile2->getPhoneNumber()`
                                                        ,
                                                        ~/if`
							<font ~if $phoneFlagArr2.PHONE_MOB eq 'Y'`  class="red"~/if`>	
                                                        ~$profile2->getPHONE_MOB()`
                                                        </font>
                                        ~/if`
                                        ~if $profile2->getExtendedContacts()->ALT_MOBILE`
                                                        ~if $profile2->getPhoneNumber() || $profile2->getPHONE_MOB()`
                                                        ,
                                                        ~/if`
							<font ~if $phoneFlagArr2.ALT_MOBILE eq 'Y'`  class="red"~/if`>
                                                         ~$profile2->getExtendedContacts()->ALT_MOBILE`
                                                        </font>
                                        ~/if`
                                        ~if $archiveInfo2.ALTERNATE_NUM`
                                                        ~if $profile2->getPhoneNumber() || $profile2->getPHONE_MOB() || $profile2->getExtendedContacts()->ALT_MOBILE`
                                                        ,
                                                        ~/if`
							<font ~if $phoneFlagArr2.ALTERNATE_NUM eq 'Y'`  class="red"~/if`>
                                                        ~$archiveInfo2.ALTERNATE_NUM`
                                                        </font>
                                        ~/if`
                                        <!--  Display Archived Numbers Residence -->
					~if $archiveInfo2.CONTACT.PHONE_RES`
                                        	~if $profile2->getPhoneNumber() || $profile2->getPHONE_MOB() || $profile2->getExtendedContacts()->ALT_MOBILE || $archiveInfo2.ALTERNATE_NUM`
                                       		<br>	
                                        	~/if`
						<font ~if $phoneFlagArr2.CONTACT_PHONE_RES eq 'Y'`  class="red"~/if`>
                                       	 	~$archiveInfo2.CONTACT.PHONE_RES`
                                        	</font>
					~/if`

                                        <!--  Display Archived Numbers Mobile -->
					~if $archiveInfo2.CONTACT.PHONE_MOB`
                                        	~if $profile2->getPhoneNumber() || $profile2->getPHONE_MOB() || $profile2->getExtendedContacts()->ALT_MOBILE || $archiveInfo2.ALTERNATE_NUM || $archiveInfo2.CONTACT.PHONE_RES`
                                        	<br>
                                        	~/if`
						<font ~if $phoneFlagArr2.CONTACT_PHONE_MOB eq 'Y'`  class="red"~/if`>
                                        	~$archiveInfo2.CONTACT.PHONE_MOB`
                                        	</font>
					~/if`

                                        <!--  Display Archived Numbers Alternate Num -->
					~if $archiveInfo2.CONTACT.ALT_MOBILE`
                                        	~if $profile2->getPhoneNumber() || $profile2->getPHONE_MOB() || $profile2->getExtendedContacts()->ALT_MOBILE || $archiveInfo2.ALTERNATE_NUM || $archiveInfo2.CONTACT.PHONE_RES || $archiveInfo2.CONTACT.PHONE_MOB`
                                        	<br>
                                        	~/if`
						<font ~if $phoneFlagArr2.CONTACT_ALT_MOBILE eq 'Y'`  class="red"~/if`>
                                        	~$archiveInfo2.CONTACT.ALT_MOBILE`
                                        	</font>
					~/if`
                                </div>
            		</div>
			~/if`

			~if ($profile1->getEMAIL() || $profile1->getMESSENGER_ID() || $profile1->getExtendedContacts()->ALT_MESSENGER_ID) && ($profile2->getEMAIL() || $profile2->getMESSENGER_ID() || $profile2->getExtendedContacts()->ALT_MESSENGER_ID)`	
			<div class="white-heading fl">
            			<div class="fl div1">&nbsp;</div>
            			<div  class="fl orange  div2">EMail/Messenger IDs</div>

				<div class="contentdivleft">		
					~if $profile1->getEMAIL()`
							<font ~if $emailFlagArr1.EMAIL`  class="red" ~/if`>
                	                                ~$profile1->getEMAIL()`
                        	                        </font>
					~/if`
					~if $profile1->getMESSENGER_ID()`
							~if $profile1->getEMAIL()`
							,
							~/if`
							<font ~if $emailFlagArr1.MESSENGER_ID` class="red" ~/if`>
                	                                ~$profile1->getMESSENGER_ID()`
                        	                        </font>
					~/if`
                                        ~if $profile1->getExtendedContacts()->ALT_MESSENGER_ID`
                                                        ~if $profile1->getEMAIL() || $profile1->getMESSENGER_ID()`
                                                        ,
                                                        ~/if`
							<font ~if $emailFlagArr1.ALT_MESSENGER_ID`  class="red" ~/if`>
                                                        ~$profile1->getExtendedContacts()->ALT_MESSENGER_ID`
                                                        </font>
                                        ~/if`

					~if $archiveInfo1.CONTACT.EMAIL`
                                        	~if $profile1->getEMAIL() || $profile1->getMESSENGER_ID() || $profile1->getExtendedContacts()->ALT_MESSENGER_ID`
                                       		<br> 
                                        	~/if`
						<font ~if $emailFlagArr1.CONTACT_EMAIL`  class="red" ~/if`>
						~$archiveInfo1.CONTACT.EMAIL`
						</font>
					~/if`			

					~if $archiveInfo1.CONTACT.MESSENGER_ID`
						~if $profile1->getEMAIL() || $profile1->getMESSENGER_ID() || $profile1->getExtendedContacts()->ALT_MESSENGER_ID || $archiveInfo1.CONTACT.EMAIL`
                                       		<br> 
                                        	~/if`
						<font ~if $emailFlagArr1.CONTACT_MESSENGER_ID`  class="red" ~/if`>	
						 ~$archiveInfo1.CONTACT.MESSENGER_ID`
						</font>
					~/if`

					~if $archiveInfo1.CONTACT.ALT_MESSENGER_ID`
                                        	~if $profile1->getEMAIL() || $profile1->getMESSENGER_ID() || $profile1->getExtendedContacts()->ALT_MESSENGER_ID || $archiveInfo1.CONTACT.EMAIL || $archiveInfo1.CONTACT.MESSENGER_ID`
                                        	<br>
                                        	~/if`
						<font ~if $emailFlagArr1.CONTACT_ALT_MESSENGER_ID`  class="red" ~/if`>
                                        	 ~$archiveInfo1.CONTACT.ALT_MESSENGER_ID`
                                        	</font>
					~/if`
                                </div>
                                <div class="contentdivright">
					~if $profile2->getEMAIL()`
							<font ~if $emailFlagArr2.EMAIL`  class="red" ~/if`>
                	                                ~$profile2->getEMAIL()`
                        	                        </font>
					~/if`
					~if $profile2->getMESSENGER_ID()`
							~if $profile2->getEMAIL()`
        	        	                       ,
							~/if`
							<font ~if $emailFlagArr2.MESSENGER_ID`  class="red" ~/if`> 
							~$profile2->getMESSENGER_ID()`
                	                                </font>
					~/if`
                                        ~if $profile2->getExtendedContacts()->ALT_MESSENGER_ID`
                                                        ~if $profile2->getEMAIL() || $profile2->getMESSENGER_ID()`
                                                        ,
                                                        ~/if`
							<font ~if $emailFlagArr2.EMAIL`  class="red" ~/if`>
                                                        ~$profile2->getExtendedContacts()->ALT_MESSENGER_ID`
                                                        </font>
                                        ~/if`

					~if $archiveInfo2.CONTACT.EMAIL`
						~if $profile2->getEMAIL() || $profile2->getMESSENGER_ID() || $profile2->getExtendedContacts()->ALT_MESSENGER_ID`
						<br>	
						~/if`
						<font ~if $emailFlagArr2.CONTACT_EMAIL`  class="red" ~/if`>
                                        	~$archiveInfo2.CONTACT.EMAIL`
						</font>
					~/if`

					~if $archiveInfo2.CONTACT.MESSENGER_ID`
						~if $profile2->getEMAIL() || $profile2->getMESSENGER_ID() || $profile2->getExtendedContacts()->ALT_MESSENGER_ID || $archiveInfo2.CONTACT.EMAIL`
						<br>
						~/if`
						<font ~if $emailFlagArr2.CONTACT_MESSENGER_ID`  class="red" ~/if`>
                                        	~$archiveInfo2.CONTACT.MESSENGER_ID`
						</font>
					~/if`

					~if $archiveInfo2.CONTACT.ALT_MESSENGER_ID`
                                        	~if $profile2->getEMAIL() || $profile2->getMESSENGER_ID() || $profile2->getExtendedContacts()->ALT_MESSENGER_ID || $archiveInfo2.CONTACT.EMAIL || $archiveInfo2.CONTACT.MESSENGER_ID`
                                        	<br>
                                        	~/if`
						<font ~if $emailFlagArr2.CONTACT.ALT_MESSENGER_ID`  class="red" ~/if`>
                                        	 ~$archiveInfo2.CONTACT.ALT_MESSENGER_ID`
                                        	</font>
					~/if`
                                </div>
        		</div>
			~/if`

			~if $profile1->getPASSWORD() && $profile2->getPASSWORD()`
			<div class="grey-heading fl">
            			<div class="fl div1">&nbsp;</div>
            			<div  class="fl orange  div2">Password</div>
            			<div class="contentdivleft ~if $profile1->getPASSWORD() eq $profile2->getPASSWORD()`red~/if` ">~$profile1->getPASSWORD()`</div>
			    	<div class="  contentdivright ~if $profile1->getPASSWORD() eq $profile2->getPASSWORD()`red~/if` ">~$profile2->getPASSWORD()`</div>
            		</div>
			~/if`

			~if ($profile1->getDecoratedCity() && $profile2->getDecoratedCity()) || ($profile1->getDecoratedCountry() && $profile2->getDecoratedCountry())`
			<div class="white-heading fl">
            			<div class="fl div1">&nbsp;</div>
            			<div  class="fl orange  div2">Current Location, Country</div>
                                <div class="contentdivleft">
					~if $profile1->getDecoratedCity() && $profile2->getDecoratedCity()`
                                        	        <font ~if $profile1->getDecoratedCity() eq $profile2->getDecoratedCity()`class="red" ~/if`>
                                        	        ~$profile1->getDecoratedCity()`
                                        	        </font>
					~/if`
					~if $profile1->getDecoratedCountry() && $profile2->getDecoratedCountry()`
        	                                        <font ~if $profile1->getDecoratedCountry() eq $profile2->getDecoratedCountry()`class="red" ~/if`>
							~if $profile1->getDecoratedCity() && $profile2->getDecoratedCity()`
							,
							~/if`
        	                                        ~$profile1->getDecoratedCountry()`
        	                                        </font>
					~/if`
                                </div>
                                <div class="contentdivright">
					~if $profile1->getDecoratedCity() && $profile2->getDecoratedCity()`
                                        	        <font ~if $profile1->getDecoratedCity() eq $profile2->getDecoratedCity()`class="red" ~/if`>
                                        	        ~$profile2->getDecoratedCity()`
                                        	        </font>
					~/if`
					~if $profile1->getDecoratedCountry() && $profile2->getDecoratedCountry()`
        	                                        <font ~if $profile1->getDecoratedCountry() eq $profile2->getDecoratedCountry()`class="red" ~/if`>
							 ~if $profile1->getDecoratedCity() && $profile2->getDecoratedCity()`
        	                                        ,
							~/if`	
							~$profile2->getDecoratedCountry()`
                	                                </font>
					~/if`
                                </div>
            		</div>
			~/if`

			~if ($profile1->getCONTACT() || $profile1->getPARENTS_CONTACT() || $profile1->getPINCODE() || $profile1->getPARENTS_CONTACT()) && ($profile2->getCONTACT() || $profile2->getPARENTS_CONTACT() || $profile2->getPINCODE() || $profile2->getPARENTS_CONTACT())`
			<div class="grey-heading fl">
            			<div class="fl div1">&nbsp;</div>
            			<div  class="fl orange  div2">Address</div>
                                <div class="contentdivleft">
                                        ~if $profile1->getCONTACT()`
							<font ~if $addFlagArr1.CONTACT eq 'Y'` class="red" ~/if`>
                                                        ~$profile1->getCONTACT()`
							</font>
					~/if`
                                        ~if $profile1->getPINCODE()`
							~if $profile1->getCONTACT()`,~/if`
							<font ~if ($profile1->getPINCODE() eq $profile2->getPINCODE()) || ($profile1->getPINCODE() eq $profile2->getPARENT_PINCODE())` class="red" ~/if`>
                                               	        ~$profile1->getPINCODE()`
							</font>
                                        ~/if`
                                        ~if $profile1->getPARENTS_CONTACT()`
							~if $profile1->getCONTACT() || $profile1->getPINCODE()`
                                                        <br>
							~/if`
							<font ~if $addFlagArr1.PARENTS_CONTACT eq 'Y'` class="red" ~/if`>
							~$profile1->getPARENTS_CONTACT()`
                                                        </font>
					~/if`
                                        ~if $profile1->getPARENT_PINCODE()`
							~if $profile1->getPARENTS_CONTACT()`, ~/if`	
							<font ~if ($profile1->getPARENT_PINCODE() eq $profile2->getPARENT_PINCODE()) || ($profile1->getPARENT_PINCODE() eq $profile2->getPINCODE())` class="red" ~/if`>
                                                       	~$profile1->getPARENT_PINCODE()`
							</font>
                                        ~/if`
                                </div>
                                <div class="contentdivright">
                                        ~if $profile2->getCONTACT()`
							<font ~if $addFlagArr2.CONTACT eq 'Y'` class="red" ~/if`>
                                                        ~$profile2->getCONTACT()`
							</font>
					~/if`
                                       	~if $profile2->getPINCODE()`
							~if $profile2->getCONTACT()` ,~/if`
	                                              	<font ~if ($profile2->getPINCODE() eq $profile1->getPINCODE()) || ($profile2->getPINCODE() eq $profile1->getPARENT_PINCODE())`class="red" ~/if`>
                                                       	~$profile2->getPINCODE()`
							</font>
                                       	~/if`
                                        ~if $profile2->getPARENTS_CONTACT()`
							~if $profile2->getCONTACT() || $profile2->getCONTACT()`
                                                        <br>
							~/if`
							<font ~if $addFlagArr2.PARENTS_CONTACT eq 'Y'` class="red" ~/if`>
							~$profile2->getPARENTS_CONTACT()`
                                                        </font>
					~/if`
                                    	~if $profile2->getPARENT_PINCODE()`
							~if $profile2->getPARENT_PINCODE()` ,~/if`
                                               	        <font ~if ($profile2->getPARENT_PINCODE() eq $profile1->getPARENT_PINCODE()) || ($profile2->getPARENT_PINCODE() eq $profile1->gePINCODE())` class="red" ~/if`>
                                               	        ~$profile2->getPARENT_PINCODE()`
							</font>
                                       	~/if`
                                </div>
            		</div>
			~/if`

			~if $profile1->getDecoratedGender() && $profile2->getDecoratedGender()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Gender</div>
                                <div class="contentdivleft ~if $profile1->getDecoratedGender() eq $profile2->getDecoratedGender()`red~/if`">~$profile1->getDecoratedGender()`</div>
                                <div class="contentdivright ~if $profile1->getDecoratedGender() eq $profile2->getDecoratedGender()`red~/if`">~$profile2->getDecoratedGender()`</div>
                        </div>
			~/if`

			~if $profile1->getAGE() && $profile2->getAGE()`
			~math equation="a-1" a=$profile1->getAGE() assign=lAge1`
			~math equation="a+1" a=$profile1->getAGE() assign=hAge1`
			~math equation="a-1" a=$profile2->getAGE() assign=lAge2`
			~math equation="a+1" a=$profile2->getAGE() assign=hAge2`
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Age</div>
				<div class="contentdivleft ~if ($profile1->getAGE() >= $lAge2) && ($profile1->getAGE() <= $hAge2) || ($profile2->getAGE() >=$lAge1) && ($profile2->getAGE() <=$hAge1)` red~/if`">~$profile1->getAGE()`
				</div>
				<div class="  contentdivright ~if ($profile1->getAGE() >= $lAge2) && ($profile1->getAGE() <= $hAge2) || ($profile2->getAGE() >=$lAge1) && ($profile2->getAGE() <=$hAge1)` red~/if`">~$profile2->getAGE()`
				</div>
			</div>
			~/if`

			~if $profile1->getDecoratedHeight() && $profile2->getDecoratedHeight()`
                        ~math equation="a-1" a=$profile1->getHEIGHT() assign=lHeight1`
                        ~math equation="a+1" a=$profile1->getHEIGHT() assign=hHeight1`
                        ~math equation="a-1" a=$profile2->getHEIGHT() assign=lHeight2`
                        ~math equation="a+1" a=$profile2->getHEIGHT() assign=hHeight2`

			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Height</div>
                                <div class="contentdivleft ~if ($profile1->getHEIGHT() >= $lHeight2) && ($profile1->getHEIGHT() <= $hHeight2) || ($profile2->getHEIGHT() >=$lHeight1) && ($profile2->getHEIGHT() <=$hHeight1)` red~/if`">~htmlspecialchars_decode($profile1->getDecoratedHeight())`

				</div>
                                <div class="  contentdivright ~if ($profile1->getHEIGHT() >= $lHeight2) && ($profile1->getHEIGHT() <= $hHeight2) || ($profile2->getHEIGHT() >=$lHeight1) && ($profile2->getHEIGHT() <=$hHeight1)` red~/if`">~htmlspecialchars_decode($profile2->getDecoratedHeight())`
				</div>
			</div>
			~/if`

			~if $profile1->getDTOFBIRTH() && $profile2->getDTOFBIRTH()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Date and Time of Birth</div>
					<div class="contentdivleft">
						<font ~if ($profile1->getDTOFBIRTH() eq $profile2->getDTOFBIRTH())` class="red" ~/if`">
							~$profile1->getDTOFBIRTH()`
						</font>	
						<font ~if ($profile1->getDTOFBIRTH() eq $profile2->getDTOFBIRTH()) && ($profile1->getBTIME() eq $profile2->getBTIME())` class="red" ~/if`">
							, ~$profile1->getBTIME()`	
						</font>
					</div>				
                                        <div class="contentdivright">
                                                <font ~if ($profile1->getDTOFBIRTH() eq $profile2->getDTOFBIRTH())` class="red" ~/if`">
                                                        ~$profile2->getDTOFBIRTH()`
                                                </font> 
                                                <font ~if ($profile1->getDTOFBIRTH() eq $profile2->getDTOFBIRTH()) && ($profile1->getBTIME() eq $profile2->getBTIME())` class="red" ~/if`">
                                                        , ~$profile2->getBTIME()`
                                                </font>
                                        </div>       
                        </div>
			~/if`

			~if ($profile1->getDecoratedBirthCity() && $profile2->getDecoratedBirthCity()) || ($profile1->getDecoratedCountry() && $profile2->getDecoratedCountry())`
			<div class="white-heading fl">
            			<div class="fl div1">&nbsp;</div>
            			<div  class="fl orange  div2">Birth City, Country</div>
                                <div class="contentdivleft">
					~if $profile1->getDecoratedBirthCity() && $profile2->getDecoratedBirthCity()`
                                        	        <font ~if stristr(str_replace(" ","",$profile1->getDecoratedBirthCity()),str_replace(" ","",$profile2->getDecoratedBirthCity())) || stristr(str_replace(" ","",$profile2->getDecoratedBirthCity()),str_replace(" ","",$profile1->getDecoratedBirthCity()))` class="red" ~/if`>
                                                	~$profile1->getDecoratedBirthCity()`
                                                	</font>
					~/if`
					~if $profile1->getDecoratedCountry() && $profile2->getDecoratedCountry()`
        	                                        <font ~if $profile1->getDecoratedCountry() eq $profile2->getDecoratedCountry()`class="red" ~/if`>
							~if $profile1->getDecoratedBirthCity() && $profile2->getDecoratedBirthCity()`
							,	
							~/if`
                	                                ~$profile1->getDecoratedCountry()`
                        	                        </font>
					~/if`
                                </div>
                                <div class="contentdivright">
					~if $profile1->getDecoratedBirthCity() && $profile2->getDecoratedBirthCity()`
        	                                        <font ~if stristr(str_replace(" ","",$profile2->getDecoratedBirthCity()),str_replace(" ","",$profile1->getDecoratedBirthCity())) || stristr(str_replace(" ","",$profile1->getDecoratedBirthCity()),str_replace(" ","",$profile2->getDecoratedBirthCity()))` class="red" ~/if`>
                	                                ~$profile2->getDecoratedBirthCity()`
                        	                        </font>
					~/if`
					~if $profile1->getDecoratedCountry() && $profile2->getDecoratedCountry()`
        	                                        <font ~if $profile1->getDecoratedCountry() eq $profile2->getDecoratedCountry()`class="red" ~/if`>
							~if $profile1->getDecoratedBirthCity() && $profile2->getDecoratedBirthCity()`
							,
							~/if`	
                	                                ~$profile2->getDecoratedCountry()`
                        	                        </font>
					~/if`
                                </div>
            		</div>
			~/if`

			~if $profile1->getDecoratedCommunity() && $profile2->getDecoratedCommunity()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Mother Tongue</div>
                                <div class="  contentdivleft ~if ($mTongueProfile1 && $mTongueProfile2) || ($profile1->getDecoratedCommunity() eq $profile2->getDecoratedCommunity())` red~/if`">~$profile1->getDecoratedCommunity()`</div>
                                <div class="  contentdivright ~if ($mTongueProfile1 && $mTongueProfile2) || ($profile1->getDecoratedCommunity() eq $profile2->getDecoratedCommunity())` red~/if`">~$profile2->getDecoratedCommunity()`</div>
                        </div>
			~/if`

			~if $profile1->getRELIGION() && $profile2->getRELIGION()`
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Religion</div>
				<div class="contentdivleft ~if $profile1->getRELIGION() eq $profile2->getRELIGION()`red~/if`">~$profile1->getDecoratedReligion()`</div>    
				<div class="  contentdivright ~if $profile1->getRELIGION() eq $profile2->getRELIGION()`red~/if`">~$profile2->getDecoratedReligion()`</div>
			</div>
			~/if`

			~if $profile1->getSECT() && $profile2->getSECT()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Sect</div>
                                <div class="contentdivleft ~if $profile1->getSECT() eq $profile2->getSECT()`red~/if`">~$profile1->getDecoratedSect()`</div>
                                <div class="  contentdivright ~if $profile1->getSECT() eq $profile2->getSECT()`red~/if`">~$profile2->getDecoratedSect()`</div>
                        </div>
			~/if`

			~if $profile1->getCASTE() && $profile2->getCASTE()`
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Caste</div>
				<div class="contentdivleft ~if $profile1->getCASTE() eq $profile2->getCASTE()`red~/if`">~$profile1->getDecoratedCaste()`</div>    	
				<div class="  contentdivright ~if $profile1->getCASTE() eq $profile2->getCASTE()`red~/if`">~$profile2->getDecoratedCaste()`</div>
			</div>
			~/if`

			~if $profile1->getDecoratedSubcaste() && $profile2->getDecoratedSubcaste()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Sub-caste</div>
                                ~if stristr(str_replace(" ","",$profile1->getDecoratedSubcaste()),str_replace(" ","",$profile2->getDecoratedSubcaste())) || stristr(str_replace(" ","",$profile2->getDecoratedSubcaste()),str_replace(" ","",$profile1->getDecoratedSubcaste()))`
                                                ~assign var="sub_caste_info" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $sub_caste_info`red~/if`">~$profile1->getDecoratedSubcaste()`</div>
                                <div class="  contentdivright ~if $sub_caste_info`red~/if`">~$profile2->getDecoratedSubcaste()`</div>
                        </div>
			~/if`
		
			~if $profile1->getDecoratedGothra() && $profile2->getDecoratedGothra()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Gothra (paternal)</div>
                                ~if stristr(str_replace(" ","",$profile1->getDecoratedGothra()),str_replace(" ","",$profile2->getDecoratedGothra())) || stristr(str_replace(" ","",$profile2->getDecoratedGothra()),str_replace(" ","",$profile1->getDecoratedGothra()))`
                                                ~assign var="gothra_paternal" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $gothra_paternal`red~/if`">~$profile1->getDecoratedGothra()`</div>
                                <div class="  contentdivright ~if $gothra_paternal`red~/if`">~$profile2->getDecoratedGothra()`</div>
                        </div>
			~/if`
		
			~if $profile1->getDecoratedGothraMaternal() && $profile2->getDecoratedGothraMaternal()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Gothra (maternal) </div>
                                ~if stristr(str_replace(" ","",$profile1->getDecoratedGothraMaternal()),str_replace(" ","",$profile2->getDecoratedGothraMaternal())) || stristr(str_replace(" ","",$profile2->getDecoratedGothraMaternal()),str_replace(" ","",$profile1->getDecoratedGothraMaternal()))`
                                                ~assign var="gothra_maternal" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $gothra_maternal`red~/if`">~$profile1->getDecoratedGothraMaternal()`</div>
                                <div class="  contentdivright ~if $gothra_maternal`red~/if`">~$profile2->getDecoratedGothraMaternal()`</div>
                        </div>
			~/if`

			~if $profile1->getSUNSIGN() && $profile2->getSUNSIGN()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Sun Sign</div>
                                <div class="contentdivleft ~if $profile1->getSUNSIGN() eq $profile2->getSUNSIGN()`red~/if`">~$profile1->getDecoratedSunsign()`</div>
                                <div class="  contentdivright ~if $profile1->getSUNSIGN() eq $profile2->getSUNSIGN()`red~/if`">~$profile2->getDecoratedSunsign()`</div>
                        </div>
			~/if`

			~if $profile1->getRASHI() && $profile2->getRASHI()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Rashi</div>
                                <div class="contentdivleft ~if $profile1->getRASHI() eq $profile2->getRASHI()`red~/if`">~$profile1->getRASHI()`</div>           <div class="  contentdivright ~if $profile1->getRASHI() eq $profile2->getRASHI()`red~/if`">~$profile2->getRASHI()`</div>
                        </div>
			~/if`

			~if $profile1->getNAKSHATRA() && $profile2->getNAKSHATRA()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Nakshatra</div>
                                <div class="contentdivleft ~if $profile1->getNAKSHATRA() eq $profile2->getNAKSHATRA()`red~/if`">~$profile1->getNAKSHATRA()`</div>       <div class="  contentdivright ~if $profile1->getNAKSHATRA() eq $profile2->getNAKSHATRA()`red~/if`">~$profile2->getNAKSHATRA()`</div>
                        </div>
			~/if`

			~if $profile1->getDecoratedManglik() && $profile2->getDecoratedManglik()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Manglik Status</div>
                                <div class="contentdivleft ~if $profile1->getDecoratedManglik() eq $profile2->getDecoratedManglik()`red~/if`">~$profile1->getDecoratedManglik()`</div>
                                <div class="  contentdivright ~if $profile1->getDecoratedManglik() eq $profile2->getDecoratedManglik()`red~/if`">~$profile2->getDecoratedManglik()`</div>
                        </div>
			~/if`

			~if $profile1->getMSTATUS() && $profile2->getMSTATUS()`
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Marital status</div>
				<div class="contentdivleft ~if $profile1->getMSTATUS() eq $profile2->getMSTATUS()`red~/if`">~$profile1->getDecoratedMaritalStatus()`</div>    	
				<div class="  contentdivright ~if $profile1->getMSTATUS() eq $profile2->getMSTATUS()`red~/if`">~$profile2->getDecoratedMaritalStatus()`</div>
			</div>
			~/if`
			<!--  BASIC INFO ENDS -->

			<div class="clr">&nbsp;</div>
			<!--DUPLICATE DECISION BOX STARTS-->
			<div class="box-grey" >
				<textarea placeholder="Enter text" name="comments_bi" id="comments_bi">~$comments_bi`</textarea>
				<div class="sp10">&nbsp;</div>           
				<input type="Submit" name="marked" class="btn-red fs16 white b" value="Duplicate" onclick="return showMessage('duplicate')"/>
				<input type="Submit" name="marked" class="btn-green fs16 white b"  value="Not Duplicate" onclick="return showMessage('not_duplicate')"/>
				<input type="Submit" name="marked" class="btn-orange fs16 white b" value="Cant Say" onclick="return comment()" />
			</div>
			<!--DUPLICATE DECISION BOX FINISH-->
			<div class="clr">&nbsp;</div>
			<div class="brown-heading2">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange fs20 div2">Text Fields</div><div class=" fs20  contentdivleft">~$profile1->getUSERNAME()`</div>    	
				<div class=" fs20 contentdivright">~$profile2->getUSERNAME()`</div>
			</div>
			<!--  TEXTFIELD INFO STARTS  -->
			~if $profile1->getDecoratedFamilyInfo() && $profile2->getDecoratedFamilyInfo()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">About Family</div>
				~if stristr(str_replace(" ","",$profile1->getDecoratedFamilyInfo()),str_replace(" ","",$profile2->getDecoratedFamilyInfo())) || stristr(str_replace(" ","",$profile2->getDecoratedFamilyInfo()),str_replace(" ","",$profile1->getDecoratedFamilyInfo()))`
					~assign var="family_info" value="1"`
				~/if`
                                <div class="contentdivleft ~if $family_info` red~/if`">~$profile1->getDecoratedFamilyInfo()`</div>
                                <div class="  contentdivright ~if $family_info` red~/if`">~$profile2->getDecoratedFamilyInfo()`</div>
                        </div>
			~/if`

			~if $profile1->getDecoratedYourInfo() && $profile2->getDecoratedYourInfo()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">About Him</div>
				~if stristr(str_replace(" ","",$profile1->getDecoratedYourInfo()),str_replace(" ","",$profile2->getDecoratedYourInfo())) || stristr(str_replace(" ","",$profile2->getDecoratedYourInfo()),str_replace(" ","",$profile1->getDecoratedYourInfo()))`
				~assign var="your_info" value="1"`	
				~/if`
                                <div class="contentdivleft ~if $your_info`red~/if`">~$profile1->getDecoratedYourInfo()`</div>
                                <div class="  contentdivright ~if $your_info`red~/if`">~$profile2->getDecoratedYourInfo()`</div>
                        </div>
			~/if`	

			~if $profile1->getDecoratedJobInfo() && $profile2->getDecoratedJobInfo()`
                        <div class="white-heading fl">
                                    <div class="fl div1">&nbsp;</div>
                                    <div  class="fl orange  div2">About Occupation</div>
					~if stristr(str_replace(" ","",$profile1->getDecoratedJobInfo()),str_replace(" ","",$profile2->getDecoratedJobInfo())) || stristr(str_replace(" ","",$profile2->getDecoratedJobInfo()),str_replace(" ","",$profile1->getDecoratedJobInfo()))`
						~assign var="job_info" value="1"`
					~/if`
                                   <div class="contentdivleft ~if $job_info`red~/if`">~$profile1->getDecoratedJobInfo()`</div>
			           <div class="  contentdivright ~if $job_info`red~/if`">~$profile2->getDecoratedJobInfo()`</div>
                        </div>
			~/if`

			~if $profile1->getDecoratedEducationInfo() && $profile2->getDecoratedEducationInfo()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">About Education</div>
				~if stristr(str_replace(" ","",$profile1->getDecoratedEducationInfo()),str_replace(" ","",$profile2->getDecoratedEducationInfo())) || stristr(str_replace(" ","",$profile2->getDecoratedEducationInfo()),str_replace(" ","",$profile1->getDecoratedEducationInfo()))`
					~assign var="education_info" value="1"`
				~/if`	
                                <div class="contentdivleft ~if $education_info`red~/if`">~$profile1->getDecoratedEducationInfo()`</div>
                                <div class="  contentdivright ~if $education_info`red~/if`">~$profile2->getDecoratedEducationInfo()`</div>
                        </div>
			~/if`
		
			~if $profile1->getEducationDetail()->SCHOOL && $profile2->getEducationDetail()->SCHOOL`	
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">School Name </div>
                                <div class="contentdivleft ~if $profile1->getEducationDetail()->SCHOOL eq $profile2->getEducationDetail()->SCHOOL`red~/if`">~$profile1->getEducationDetail()->SCHOOL`</div>     
                                <div class="  contentdivright ~if $profile1->getEducationDetail()->SCHOOL eq $profile2->getEducationDetail()->SCHOOL`red~/if`">~$profile2->getEducationDetail()->SCHOOL`</div>
                        </div>
			~/if`

			~if $profile1->getEducationDetail()->COLLEGE && $profile2->getEducationDetail()->COLLEGE`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">College Name</div>
                                <div class="contentdivleft ~if $profile1->getEducationDetail()->COLLEGE eq $profile2->getEducationDetail()->COLLEGE`red~/if`">~$profile1->getEducationDetail()->COLLEGE`</div>    
                                <div class="  contentdivright ~if $profile1->getEducationDetail()->COLLEGE eq $profile2->getEducationDetail()->COLLEGE`red~/if`">~$profile2->getEducationDetail()->COLLEGE`</div>
                        </div>
			~/if`

			~if $profile1->getEducationDetail()->UG_DEGREE && $profile2->getEducationDetail()->UG_DEGREE`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Graduation Degree</div>
                                <div class="contentdivleft ~if $profile1->getEducationDetail()->UG_DEGREE eq $profile2->getEducationDetail()->UG_DEGREE`red~/if`">~$profile1->getEducationDetail()->UG_DEGREE`</div>    
                                <div class="  contentdivright ~if $profile1->getEducationDetail()->UG_DEGREE eq $profile2->getEducationDetail()->UG_DEGREE`red~/if`">~$profile2->getEducationDetail()->UG_DEGREE`</div>
                        </div>
			~/if`

			~if $profile1->getEducationDetail()->PG_COLLEGE && $profile2->getEducationDetail()->PG_COLLEGE`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">PG College</div>
                                <div class="contentdivleft ~if $profile1->getEducationDetail()->PG_COLLEGE eq $profile2->getEducationDetail()->PG_COLLEGE`red~/if`">~$profile1->getEducationDetail()->PG_COLLEGE`</div>    
                                <div class="  contentdivright ~if $profile1->getEducationDetail()->PG_COLLEGE eq $profile2->getEducationDetail()->PG_COLLEGE`red~/if`">~$profile2->getEducationDetail()->PG_COLLEGE`</div>
                        </div>
			~/if`	

			~if $profile1->getEducationDetail()->PG_DEGREE && $profile2->getEducationDetail()->PG_DEGREE`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">PG Degree</div>
                                <div class="contentdivleft ~if $profile1->getEducationDetail()->PG_DEGREE eq $profile2->getEducationDetail()->PG_DEGREE`red~/if`">~$profile1->getEducationDetail()->PG_DEGREE`</div>    
                                <div class="  contentdivright ~if $profile1->getEducationDetail()->PG_DEGREE eq $profile2->getEducationDetail()->PG_DEGREE`red~/if`">~$profile2->getEducationDetail()->PG_DEGREE`</div>
                        </div>
			~/if`

			~if $profile1->getEducationDetail()->OTHER_PG_DEGREE && $profile2->getEducationDetail()->OTHER_PG_DEGREE`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Highest Degree</div>
                                <div class="contentdivleft ~if $profile1->getEducationDetail()->OTHER_PG_DEGREE eq $profile2->getEducationDetail()->OTHER_PG_DEGREE`red~/if`">~$profile1->getEducationDetail()->OTHER_PG_DEGREE`</div>    
                                <div class="  contentdivright ~if $profile1->getEducationDetail()->OTHER_PG_DEGREE eq $profile2->getEducationDetail()->OTHER_PG_DEGREE`red~/if`">~$profile2->getEducationDetail()->OTHER_PG_DEGREE`</div>
                        </div>
			~/if`			

			<!--  DUPLICATE FIELD -->

			~if $profile1->getDecoratedEducationInfo() && $profile2->getDecoratedEducationInfo()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">About Education</div>
				~if stristr(str_replace(" ","",$profile1->getDecoratedEducationInfo()),str_replace(" ","",$profile2->getDecoratedEducationInfo())) || stristr(str_replace(" ","",$profile2->getDecoratedEducationInfo()),str_replace(" ","",$profile1->getDecoratedEducationInfo()))`
					~assign var="education_info_dup" value="1"`
				~/if`
                                <div class="contentdivleft ~if $education_info_dup`red~/if`">~$profile1->getDecoratedEducationInfo()`</div>
                                <div class="  contentdivright ~if $education_info_dup`red~/if`">~$profile2->getDecoratedEducationInfo()`</div>
                        </div>
			~/if`		
	
			<!--  DUPLICATE FIELD -->
			~if $profile1->getWORK_STATUS() && $profile2->getWORK_STATUS()`	
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Work Status</div>
                                <div class="contentdivleft ~if $profile1->getWORK_STATUS() eq $profile2->getWORK_STATUS()`red~/if`">~$profile1->getDecoratedWorkStatus()`</div>
                                <div class="  contentdivright ~if $profile1->getWORK_STATUS() eq $profile2->getWORK_STATUS()`red~/if`">~$profile2->getDecoratedWorkStatus()`</div>
                        </div>
			~/if`

			~if $profile1->getOCCUPATION() && $profile2->getOCCUPATION()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Occupation</div>
                                <div class="contentdivleft ~if $profile1->getOCCUPATION() eq $profile2->getOCCUPATION()`red~/if`">~$profile1->getDecoratedOccupation()`</div>
                                <div class="  contentdivright ~if $profile1->getOCCUPATION() eq $profile2->getOCCUPATION()`red~/if`">~$profile2->getDecoratedOccupation()`</div>
                        </div>
			~/if`

			~if $profile1->getINCOME() && $profile2->getINCOME()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Annual Income</div>
                                <div class="contentdivleft ~if $profile1->getINCOME() eq $profile2->getINCOME()`red~/if`">~$profile1->getDecoratedIncomeLevel()`</div>
                                <div class="  contentdivright ~if $profile1->getINCOME() eq $profile2->getINCOME()`red~/if`">~$profile2->getDecoratedIncomeLevel()`</div>
                        </div>
			~/if`

			~if $profile1->getDecoratedCompany() && $profile2->getDecoratedCompany()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Organization Name</div>
				~if stristr(str_replace(" ","",$profile1->getDecoratedCompany()),str_replace(" ","",$profile2->getDecoratedCompany())) || stristr(str_replace(" ","",$profile2->getDecoratedCompany()),str_replace(" ","",$profile1->getDecoratedCompany()))`
					~assign var="company_name" value="1"`
				~/if`
                                <div class="contentdivleft ~if $company_name`red~/if`">~$profile1->getDecoratedCompany()`</div>
                                <div class="  contentdivright ~if $company_name`red~/if`">~$profile2->getDecoratedCompany()`</div>
                        </div>
			~/if`	
	
			<!-- DUPLICATE FIELD  -->

			~if $profile1->getDecoratedJobInfo() && $profile2->getDecoratedJobInfo()`
                        <div class="grey-heading fl">
                                    <div class="fl div1">&nbsp;</div>
                                    <div  class="fl orange  div2">About Occupation</div>
				    ~if stristr(str_replace(" ","",$profile1->getDecoratedJobInfo()),str_replace(" ","",$profile2->getDecoratedJobInfo())) || stristr(str_replace(" ","",$profile2->getDecoratedJobInfo()),str_replace(" ","",$profile1->getDecoratedJobInfo()))`
					~assign var="job_info_dup" value="1"`
				    ~/if` 	

                                    <div class="contentdivleft ~if $job_info_dup`red~/if`">~$profile1->getDecoratedJobInfo()`</div>
			           <div class="  contentdivright ~if $job_info_dup`red~/if`">~$profile2->getDecoratedJobInfo()`</div>
                        </div>
			~/if`
			<!-- DUPLICATE FIELD  -->

			 ~if $profile1->getDecoratedFamilyType() && $profile2->getDecoratedFamilyType()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Family Type</div>
                                <div class="contentdivleft ~if $profile1->getDecoratedFamilyType() eq $profile2->getDecoratedFamilyType()`red~/if`">~$profile1->getDecoratedFamilyType()`</div>
                                <div class="  contentdivright ~if $profile1->getDecoratedFamilyType() eq $profile2->getDecoratedFamilyType()`red~/if`">~$profile2->getDecoratedFamilyType()`</div>
                        </div>
                        ~/if`

			~if $profile1->getDecoratedFamilyValues() && $profile2->getDecoratedFamilyValues()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Family Values</div>
                                <div class="  contentdivleft ~if $profile1->getDecoratedFamilyValues() eq $profile2->getDecoratedFamilyValues()`red~/if`">~$profile1->getDecoratedFamilyValues()`</div>
			        <div class="  contentdivright ~if $profile1->getDecoratedFamilyValues() eq $profile2->getDecoratedFamilyValues()`red~/if`">~$profile2->getDecoratedFamilyValues()`</div>
                        </div>
			~/if`

			~if $profile1->getDecoratedFamilyStatus() && $profile2->getDecoratedFamilyStatus()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Family Status</div>
                        <div class="  contentdivleft ~if $profile1->getDecoratedFamilyStatus() eq $profile2->getDecoratedFamilyStatus()`red~/if`">~$profile1->getDecoratedFamilyStatus()`</div>
			        <div class="  contentdivright ~if $profile1->getDecoratedFamilyStatus() eq $profile2->getDecoratedFamilyStatus()`red~/if`">~$profile2->getDecoratedFamilyStatus()`</div>
                        </div>
			~/if`

			~if $profile1->getFAMILY_INCOME() && $profile2->getFAMILY_INCOME()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Family Income</div>
	                        <div class="  contentdivleft ~if $profile1->getFAMILY_INCOME() eq $profile2->getFAMILY_INCOME()`red~/if`">~$profile1->getDecoratedFamilyIncome()`</div>
			         <div class="  contentdivright ~if $profile1->getFAMILY_INCOME() eq $profile2->getFAMILY_INCOME()`red~/if`">~$profile2->getDecoratedFamilyIncome()`</div>
                        </div>
			~/if`
	
                        ~if $profile1->getFATHER_INFO() && $profile2->getFATHER_INFO()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Father’s Occupation</div>
				~if stristr(str_replace(" ","",$profile1->getFATHER_INFO()),str_replace(" ","",$profile2->getFATHER_INFO())) || stristr(str_replace(" ","",$profile2->getFATHER_INFO()),str_replace(" ","",$profile1->getFATHER_INFO()))`
					~assign var="father_info" value="1"`
				~/if`
			
                                <div class="contentdivleft ~if $father_info`red~/if`">~$profile1->getFATHER_INFO()`</div>
                                <div class="contentdivright ~if $father_info`red~/if`">~$profile2->getFATHER_INFO()`</div>
                        </div>
                        ~/if`
	
			~if $profile1->getDecoratedMotherOccupation() && $profile2->getDecoratedMotherOccupation()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Mother’s Occupation</div>
                                <div class="  contentdivleft ~if $profile1->getDecoratedMotherOccupation() eq $profile2->getDecoratedMotherOccupation()`red~/if`">~$profile1->getDecoratedMotherOccupation()`</div>     <div class="  contentdivright ~if $profile1->getDecoratedMotherOccupation() eq $profile2->getDecoratedMotherOccupation()`red~/if`">~$profile2->getDecoratedMotherOccupation()`</div>
                        </div>
			~/if`
		
			~if $profile1->getT_BROTHER() && $profile2->getT_BROTHER()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Brothers</div>
                                <div class="contentdivleft">
                                        <font ~if $profile1->getM_BROTHER() eq $profile2->getM_BROTHER()`class="red"~/if`>
                                        	~$profile1->getM_BROTHER()`
                                        </font>
					Married,
					~math equation="a-b" a=$profile1->getT_BROTHER() b=$profile1->getM_BROTHER() assign=unmarriedP1_BROTHER`
					~math equation="a-b" a=$profile2->getT_BROTHER() b=$profile2->getM_BROTHER() assign=unmarriedP2_BROTHER`
					<font ~if $unmarriedP1_BROTHER eq $unmarriedP2_BROTHER` class="red" ~/if`>
						~$unmarriedP1_BROTHER`
					</font>
					Unmarried
                                </div>
                                <div class="contentdivright">
                                        <font ~if $profile1->getM_BROTHER() eq $profile2->getM_BROTHER()`class="red"~/if`>
                                        	~$profile2->getM_BROTHER()`
                                        </font>
					Married,
					<font ~if $unmarriedP2_BROTHER eq $unmarriedP1_BROTHER` class="red" ~/if`>
                                               ~$unmarriedP2_BROTHER`
					</font>
					Unmarried	
				</div>
                        </div>
			~/if`
		
			~if $profile1->getT_SISTER() && $profile2->getT_SISTER()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Sisters</div>
                                <div class="contentdivleft">
                                        <font ~if $profile1->getM_SISTER() eq $profile2->getM_SISTER()`class="red"~/if`>
                                        	~$profile1->getM_SISTER()`
                                        </font>
					Married,	
					~math equation="a-b" a=$profile1->getT_SISTER() b=$profile1->getM_SISTER() assign=unmarriedP1_SISTER`
					~math equation="a-b" a=$profile2->getT_SISTER() b=$profile2->getM_SISTER() assign=unmarriedP2_SISTER`
					<font ~if $unmarriedP1_SISTER eq $unmarriedP2_SISTER` class="red" ~/if`>
                                               ~$unmarriedP1_SISTER`
					</font>
					Unmarried
                                </div>
                                <div class="contentdivright">
                                        <font ~if $profile1->getM_SISTER() eq $profile2->getM_SISTER()`class="red"~/if`>
                                        	~$profile2->getM_SISTER()`
                                        </font>
					Married,
                                        ~math equation="a-b" a=$profile2->getT_SISTER() b=$profile2->getM_SISTER() assign=unmarriedP2_SISTER`
					<font ~if $unmarriedP2_SISTER eq $unmarriedP1_SISTER` class="red" ~/if`>
                                               ~$unmarriedP2_SISTER`
					</font>
					Unmarried
                                </div>
                        </div>
			~/if`

			<!--  DUPLICATE FIELDS -->

			~if $profile1->getDecoratedFamilyInfo() && $profile2->getDecoratedFamilyInfo()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">About Family</div>
				~if stristr(str_replace(" ","",$profile1->getDecoratedFamilyInfo()),str_replace(" ","",$profile2->getDecoratedFamilyInfo())) || stristr(str_replace(" ","",$profile2->getDecoratedFamilyInfo()),str_replace(" ","",$profile1->getDecoratedFamilyInfo()))`	
					~assign var="family_info_dup" value="1"`
				~/if`
                                <div class="contentdivleft ~if $family_info_dup`red~/if`">~$profile1->getDecoratedFamilyInfo()`</div>
                                <div class="  contentdivright ~if $family_info_dup`red~/if`">~$profile2->getDecoratedFamilyInfo()`</div>
                        </div>
			~/if`

			~if $profile1->getDecoratedYourInfo() && $profile2->getDecoratedYourInfo()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">About Him</div>
				~if stristr(str_replace(" ","",$profile1->getDecoratedYourInfo()),str_replace(" ","",$profile2->getDecoratedYourInfo())) || stristr(str_replace(" ","",$profile2->getDecoratedYourInfo()),str_replace(" ","",$profile1->getDecoratedYourInfo()))`
					~assign var="your_info_dup" value="1"`
				~/if`
                                <div class="contentdivleft ~if $your_info_dup`red~/if`">~$profile1->getDecoratedYourInfo()`</div>
                                <div class="  contentdivright ~if $your_info_dup`red~/if`">~$profile2->getDecoratedYourInfo()`</div>
                        </div>
			~/if`
			<!--  TEXTFIELD INFO ENDS -->	

			<div class="clr">&nbsp;</div>
			<!--DUPLICATE DECISION BOX STARTS-->           
			<div class="box-grey" >
				<textarea placeholder="Enter text" name="comments_ti" id="comments_ti">~$comments_ti`</textarea>
				<div class="sp10">&nbsp;</div>           
				<input type="Submit" name="marked" class="btn-red fs16 white b" value="Duplicate" onclick="return showMessage('duplicate')"/>
				<input type="Submit" name="marked" class="btn-green fs16 white b"  value="Not Duplicate" onclick="return showMessage('not_duplicate')"/>
				<input type="Submit" name="marked" class="btn-orange fs16 white b" value="Cant Say" onclick="return comment()"/>
			</div>
			<!--DUPLICATE DECISION BOX FINISH-->
			<div class="clr">&nbsp;</div>   
			<div class="brown-heading2">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange fs20 div2">More Information</div>
				<div class=" fs20  contentdivleft">~$profile1->getUSERNAME()`</div>
			    	<div class=" fs20 contentdivright">~$profile2->getUSERNAME()`</div>
			</div>
			<!--  MORE INFORMATION STARTS -->
			~if ($profile1->getIPADD() || $archiveInfo1.PAYMENT_IP || $archiveInfo1.CONTACT_IP) && ($profile2->getIPADD() || $archiveInfo2.PAYMENT_IP || $archiveInfo2.CONTACT_IP)`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">IP Addresses</div>
                                <div class="contentdivleft">
					~if $profile1->getIPADD()`
						<font ~if $ipFlagArr1.IPADD`  class="red" ~/if`>
						~$profile1->getIPADD()`
						</font>
					~/if`

					~if $archiveInfo1.PAYMENT_IP`
						~if $profile1->getIPADD()` ,~/if`
						<font ~if $ipFlagArr1.PAYMENT_IP`  class="red" ~/if`>
						~$archiveInfo1.PAYMENT_IP`
						</font>
					~/if`	

					~if $archiveInfo1.CONTACT_IP`
						~if $archiveInfo1.PAYMENT_IP || $profile1->getIPADD()` ,~/if`
						<font ~if $ipFlagArr1.CONTACT_IP` class="red" ~/if`>
						~$archiveInfo1.CONTACT_IP`
						</font>
					~/if`
				</div>
                                <div class=" contentdivright">
					~if $profile2->getIPADD()`
						<font ~if $ipFlagArr2.IPADD`  class="red" ~/if`>
						~$profile2->getIPADD()`
						</font>
					~/if`

					~if $archiveInfo2.PAYMENT_IP`
						~if $profile2->getIPADD()` ,~/if`
						<font ~if $ipFlagArr2.PAYMENT_IP`  class="red" ~/if`>
                                        	~$archiveInfo2.PAYMENT_IP`
						</font>
					~/if`		

					~if $archiveInfo2.CONTACT_IP`	
						 ~if $archiveInfo2.PAYMENT_IP || $profile2->getIPADD()`,~/if`
						<font ~if $ipFlagArr2.CONTACT_IP`  class="red" ~/if`>	
                                        	~$archiveInfo2.CONTACT_IP`
						</font>
					~/if`
				</div>
                        </div>
			~/if`

			~if $profile1->getDecoratedAncestralOrigin() && $profile2->getDecoratedAncestralOrigin()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Native Place</div>
				~if stristr(str_replace(" ","",$profile1->getDecoratedAncestralOrigin()),str_replace(" ","",$profile2->getDecoratedAncestralOrigin())) || stristr(str_replace(" ","",$profile2->getDecoratedAncestralOrigin()),str_replace(" ","",$profile1->getDecoratedAncestralOrigin()))`
					~assign var="ancestral_origin" value="1"`	
				~/if`	
                                <div class="contentdivleft ~if $ancestral_origin`red~/if`">~$profile1->getDecoratedAncestralOrigin()`</div>
                                <div class="  contentdivright ~if $ancestral_origin`red~/if`">~$profile2->getDecoratedAncestralOrigin()`</div>
                        </div>
			~/if`

			~if $profile1->getRELATION() && $profile2->getRELATION()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Posted By</div>
                                <div class="contentdivleft ~if $profile1->getRELATION() eq $profile2->getRELATION()`red~/if`">~$profile1->getDecoratedRelation()`</div>
                                <div class="  contentdivright ~if $profile1->getRELATION() eq $profile2->getRELATION()`red~/if`">~$profile2->getDecoratedRelation()`</div>
                        </div>
			~/if`

			~if $profile1->getDecoratedPersonHandlingProfile() && $profile2->getDecoratedPersonHandlingProfile()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Name of person handling profile</div>
				~if stristr(str_replace(" ","",$profile1->getDecoratedPersonHandlingProfile()),str_replace(" ","",$profile2->getDecoratedPersonHandlingProfile())) || stristr(str_replace(" ","",$profile2->getDecoratedPersonHandlingProfile()),str_replace(" ","",$profile1->getDecoratedPersonHandlingProfile()))`
					~assign var="person_handling_profile" value="1"`
				~/if`
                                <div class="contentdivleft ~if $person_handling_profile`red~/if`">~$profile1->getDecoratedPersonHandlingProfile()`</div>
                                <div class="contentdivright ~if $person_handling_profile`red~/if`">~$profile2->getDecoratedPersonHandlingProfile()`</div>
                        </div>
			~/if`

			~if $profile1->getDIET() && $profile2->getDIET()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Diet</div>
                                <div class="  contentdivleft ~if $profile1->getDIET() eq $profile2->getDIET()`red~/if`">~$profile1->getDecoratedDiet()`</div>         <div class="  contentdivright ~if $profile1->getDIET() eq $profile2->getDIET()`red~/if`">~$profile2->getDecoratedDiet()`</div>
                        </div>
			~/if`

			~if $profile1->getDRINK() && $profile2->getDRINK()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Drink</div>
                                <div class="  contentdivleft ~if $profile1->getDRINK() eq $profile2->getDRINK()`red~/if`">~$profile1->getDecoratedDrink()`</div>      <div class="  contentdivright ~if $profile1->getDRINK() eq $profile2->getDRINK()`red~/if`">~$profile2->getDecoratedDrink()`</div>
                        </div>
			~/if`

			~if $profile1->getSMOKE() && $profile2->getSMOKE()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Smoke</div>
                                <div class="contentdivleft ~if $profile1->getSMOKE() eq $profile2->getSMOKE()`red~/if`">~$profile1->getDecoratedSmoke()`</div>        <div class="  contentdivright ~if $profile1->getSMOKE() eq $profile2->getSMOKE()`red~/if`">~$profile2->getDecoratedSmoke()`</div>
                        </div>
			~/if`	

			~if $profile1->getCOMPLEXION() && $profile2->getCOMPLEXION()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Complexion</div>
                                <div class="contentdivleft ~if $profile1->getCOMPLEXION() eq $profile2->getCOMPLEXION()`red~/if`">~$profile1->getDecoratedComplexion()`</div>         
				<div class="  contentdivright ~if $profile1->getCOMPLEXION() eq $profile2->getCOMPLEXION()`red~/if`">~$profile2->getDecoratedComplexion()`</div>
                        </div>
			~/if`

			~if $profile1->getBTYPE() && $profile2->getBTYPE()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Body Type</div>
                                <div class="  contentdivleft ~if $profile1->getBTYPE() eq $profile2->getBTYPE()`red~/if`">~$profile1->getDecoratedBodytype()`</div>
				<div class="  contentdivright ~if $profile1->getBTYPE() eq $profile2->getBTYPE()`red~/if`">~$profile2->getDecoratedBodytype()`</div>
                        </div>
			~/if`

			~if $profile1->getWEIGHT() && $profile2->getWEIGHT()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Weight</div>
                                <div class="contentdivleft ~if $profile1->getWEIGHT() eq $profile2->getWEIGHT()`red~/if`">~$profile1->getDecoratedWeight()`</div>
                                <div class="  contentdivright ~if $profile1->getWEIGHT() eq $profile2->getWEIGHT()`red~/if`">~$profile2->getDecoratedWeight()`</div>
                        </div>
			~/if`

			~if $profile1->getBLOOD_GROUP() && $profile2->getBLOOD_GROUP()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Blood Group       </div>
                                <div class="  contentdivleft ~if $profile1->getBLOOD_GROUP() eq $profile2->getBLOOD_GROUP()`red~/if`">~$profile1->getDecoratedBloodGroup()`</div>       <div class="  contentdivright ~if $profile1->getBLOOD_GROUP() eq $profile2->getBLOOD_GROUP()`red~/if`">~$profile2->getDecoratedBloodGroup()`</div>
                        </div>
			~/if`

			~if $profile1->getTHALASSEMIA() && $profile2->getTHALASSEMIA()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Thalassemia</div>
                                <div class="contentdivleft ~if $profile1->getTHALASSEMIA() eq $profile2->getTHALASSEMIA()`red~/if`">~$profile1->getDecoratedThalassemia()`</div>
                                <div class="  contentdivright ~if $profile1->getTHALASSEMIA() eq $profile2->getTHALASSEMIA()`red~/if`">~$profile2->getDecoratedThalassemia()`</div>
                        </div>
			~/if`

			~if $profile1->getHIV() && $profile2->getHIV()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">HIV+</div>
                                <div class="contentdivleft ~if $profile1->getHIV() eq $profile2->getHIV()`red~/if`">~$profile1->getDecoratedHiv()`</div>      
				<div class="  contentdivright ~if $profile1->getHIV() eq $profile2->getHIV()`red~/if`">~$profile2->getDecoratedHiv()`</div>
                        </div>
			~/if`

			~if $profile1->getHANDICAPPED() && $profile2->getHANDICAPPED()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Challenged</div>
                                <div class="contentdivleft ~if $profile1->getHANDICAPPED() eq $profile2->getHANDICAPPED()`red~/if`">~$profile1->getDecoratedHandicapped()`</div>      
				<div class="  contentdivright ~if $profile1->getHANDICAPPED() eq $profile2->getHANDICAPPED()`red~/if`">~$profile2->getDecoratedHandicapped()`</div>
                        </div>
			~/if`

			~if $profile1->getHOROSCOPE_MATCH() && $profile2->getHOROSCOPE_MATCH()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Horoscope Match needed</div>
                                <div class="contentdivleft ~if $profile1->getHOROSCOPE_MATCH() eq $profile2->getHOROSCOPE_MATCH()`red~/if`">~$profile1->getDecoratedHoroscopeMatch()`</div>     
				<div class="  contentdivright ~if $profile1->getHOROSCOPE_MATCH() eq $profile2->getHOROSCOPE_MATCH()`red~/if`">~$profile2->getDecoratedHoroscopeMatch()`</div>
                        </div>
			~/if`

			~if $profile1->getRES_STATUS() && $profile2->getRES_STATUS()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Residential Status</div>
                                <div class="contentdivleft ~if $profile1->getRES_STATUS() eq $profile2->getRES_STATUS()`red~/if`">~$profile1->getDecoratedRstatus()`</div>          
				<div class="  contentdivright ~if $profile1->getRES_STATUS() eq $profile2->getRES_STATUS()`red~/if`">~$profile2->getDecoratedRstatus()`</div>
                        </div>
			~/if`

			~if $profile1->getPARENT_CITY_SAME() && $profile2->getPARENT_CITY_SAME()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Living with parents</div>
                                <div class="contentdivleft ~if $profile1->getPARENT_CITY_SAME() eq $profile2->getPARENT_CITY_SAME()`red~/if`">~$profile1->getDecoratedLiveWithParents()`</div>          
				<div class="  contentdivright ~if $profile1->getPARENT_CITY_SAME() eq $profile2->getPARENT_CITY_SAME()`red~/if`">~$profile2->getDecoratedLiveWithParents()`</div>
                        </div>
			~/if`

			~if $profile1->getOWN_HOUSE() && $profile2->getOWN_HOUSE()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Own House</div>
                                <div class="contentdivleft ~if $profile1->getOWN_HOUSE() eq $profile2->getOWN_HOUSE()`red~/if`">~$profile1->getDecoratedOwnHouse()`</div>
                                <div class="contentdivright ~if $profile1->getOWN_HOUSE() eq $profile2->getOWN_HOUSE()`red~/if`">~$profile2->getDecoratedOwnHouse()`</div>
                        </div>
			~/if`

			~if $profile1->getHAVE_CAR() && $profile2->getHAVE_CAR()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Own Car</div>
                                <div class="contentdivleft ~if $profile1->getHAVE_CAR() eq $profile2->getHAVE_CAR()`red~/if`">~$profile1->getDecoratedHaveCar()`</div>
                                <div class="  contentdivright ~if $profile1->getHAVE_CAR() eq $profile2->getHAVE_CAR()`red~/if`">~$profile2->getDecoratedHaveCar()`</div>
                        </div>
			~/if`

			~if $profile1->getOPEN_TO_PET() && $profile2->getOPEN_TO_PET()`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Open to pets</div>
                                <div class="contentdivleft ~if $profile1->getOPEN_TO_PET() eq $profile2->getOPEN_TO_PET()`red~/if`">~$profile1->getDecoratedOpenToPet()`</div>
                                <div class="  contentdivright ~if $profile1->getOPEN_TO_PET() eq $profile2->getOPEN_TO_PET()`red~/if`">~$profile2->getDecoratedOpenToPet()`</div>
                        </div>
			~/if`

			~if $profile1->getHobbies()->LANGUAGE && $profile2->getHobbies()->LANGUAGE`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Spoken Language</div>
                                ~if stristr(str_replace(" ","",$profile1->getHobbies()->LANGUAGE),str_replace(" ","",$profile2->getHobbies()->LANGUAGE)) || stristr(str_replace(" ","",$profile2->getHobbies()->LANGUAGE),str_replace(" ","",$profile1->getHobbies()->LANGUAGE))`
                                        ~assign var="h_language" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $h_language`red~/if`">~$profile1->getHobbies()->LANGUAGE`</div>
                                <div class="contentdivright ~if $h_language`red~/if`">~$profile2->getHobbies()->LANGUAGE`</div>
                        </div>
			~/if`

			~if $profile1->getHobbies()->DRESS && $profile2->getHobbies()->DRESS`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Dress Style</div>
                                ~if stristr(str_replace(" ","",$profile1->getHobbies()->DRESS),str_replace(" ","",$profile2->getHobbies()->DRESS)) || stristr(str_replace(" ","",$profile2->getHobbies()->DRESS),str_replace(" ","",$profile1->getHobbies()->DRESS))`
                                        ~assign var="h_dress" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $h_dress`red~/if`">~$profile1->getHobbies()->DRESS`</div>
                                <div class="contentdivright ~if $h_dress`red~/if`">~$profile2->getHobbies()->DRESS`</div>
                        </div>
			~/if`

			~if $profile1->getHobbies()->HOBBY && $profile2->getHobbies()->HOBBY`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                            	<div  class="fl orange  div2">Hobbies</div>
                                ~if stristr(str_replace(" ","",$profile1->getHobbies()->HOBBY),str_replace(" ","",$profile2->getHobbies()->HOBBY)) || stristr(str_replace(" ","",$profile2->getHobbies()->HOBBY),str_replace(" ","",$profile1->getHobbies()->HOBBY))`
                                        ~assign var="h_hobby" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $h_hobby`red~/if`">~$profile1->getHobbies()->HOBBY`</div>
                                <div class="contentdivright ~if $h_hobby`red~/if`">~$profile2->getHobbies()->HOBBY`</div>
			~/if`

			~if $profile1->getHobbies()->INTEREST && $profile2->getHobbies()->INTEREST`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Interests</div>
                                ~if stristr(str_replace(" ","",$profile1->getHobbies()->INTEREST),str_replace(" ","",$profile2->getHobbies()->INTEREST)) || stristr(str_replace(" ","",$profile2->getHobbies()->INTEREST),str_replace(" ","",$profile1->getHobbies()->INTEREST))`
                                        ~assign var="h_interest" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $h_interest`red~/if`">~$profile1->getHobbies()->INTEREST`</div>
                                <div class="contentdivright ~if $h_interest`red~/if`">~$profile2->getHobbies()->INTEREST`</div>
                        </div>
			~/if`

			~if $profile1->getHobbies()->BOOK && $profile2->getHobbies()->BOOK`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Favourite Read</div>
                                ~if stristr(str_replace(" ","",$profile1->getHobbies()->BOOK),str_replace(" ","",$profile2->getHobbies()->BOOK)) || stristr(str_replace(" ","",$profile2->getHobbies()->BOOK),str_replace(" ","",$profile1->getHobbies()->BOOK))`
                                        ~assign var="h_book" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $h_book`red~/if`">~$profile1->getHobbies()->BOOK`</div>
                                <div class="contentdivright ~if $h_book`red~/if`">~$profile2->getHobbies()->BOOK`</div>
                        </div>
			~/if`
	
			~if $profile1->getHobbies()->FAV_BOOK && $profile2->getHobbies()->FAV_BOOK`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Favourite Books</div>
                                ~if stristr(str_replace(" ","",$profile1->getHobbies()->FAV_BOOK),str_replace(" ","",$profile2->getHobbies()->FAV_BOOK)) || stristr(str_replace(" ","",$profile2->getHobbies()->FAV_BOOK),str_replace(" ","",$profile1->getHobbies()->FAV_BOOK))`
                                        ~assign var="fav_book" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $fav_book`red~/if`">~$profile1->getHobbies()->FAV_BOOK`</div>
                                <div class="contentdivright ~if $fav_book`red~/if`">~$profile2->getHobbies()->FAV_BOOK`</div>
                        </div>
			~/if`
			
			~if $profile1->getHobbies()->MUSIC && $profile2->getHobbies()->MUSIC`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Favourite Music</div>
                                ~if stristr(str_replace(" ","",$profile1->getHobbies()->MUSIC),str_replace(" ","",$profile2->getHobbies()->MUSIC)) || stristr(str_replace(" ","",$profile2->getHobbies()->MUSIC),str_replace(" ","",$profile1->getHobbies()->MUSIC))`
                                        ~assign var="h_music" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $h_music`red~/if`">~$profile1->getHobbies()->MUSIC`</div>
                                <div class="contentdivright ~if $h_music`red~/if`">~$profile2->getHobbies()->MUSIC`</div>
                        </div>
			~/if`

			~if $profile1->getHobbies()->FAV_TVSHOW && $profile2->getHobbies()->FAV_TVSHOW`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Favourite TV Shows</div>
                                ~if stristr(str_replace(" ","",$profile1->getHobbies()->FAV_TVSHOW),str_replace(" ","",$profile2->getHobbies()->FAV_TVSHOW)) || stristr(str_replace(" ","",$profile2->getHobbies()->FAV_TVSHOW),str_replace(" ","",$profile1->getHobbies()->FAV_TVSHOW))`
                                        ~assign var="fav_tvshow" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $fav_tvshow`red~/if`">~$profile1->getHobbies()->FAV_TVSHOW`</div>
                                <div class="contentdivright ~if $fav_tvshow`red~/if`">~$profile2->getHobbies()->FAV_TVSHOW`</div>
                        </div>
			~/if`

			~if $profile1->getHobbies()->MOVIE && $profile2->getHobbies()->MOVIE`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Preferred Movies</div>
                                ~if stristr(str_replace(" ","",$profile1->getHobbies()->MOVIE),str_replace(" ","",$profile2->getHobbies()->MOVIE)) || stristr(str_replace(" ","",$profile2->getHobbies()->MOVIE),str_replace(" ","",$profile1->getHobbies()->MOVIE))`
                                        ~assign var="h_movie" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $h_movie`red~/if`">~$profile1->getHobbies()->MOVIE`</div>
                                <div class="contentdivright ~if $h_movie`red~/if`">~$profile2->getHobbies()->MOVIE`</div>
                        </div>
			~/if`	

			~if $profile1->getHobbies()->FAV_MOVIE && $profile2->getHobbies()->FAV_MOVIE`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Favourite Movies</div>
                                ~if stristr(str_replace(" ","",$profile1->getHobbies()->FAV_MOVIE),str_replace(" ","",$profile2->getHobbies()->FAV_MOVIE)) || stristr(str_replace(" ","",$profile2->getHobbies()->FAV_MOVIE),str_replace(" ","",$profile1->getHobbies()->FAV_MOVIE))`
                                        ~assign var="fav_movie" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $fav_movie`red~/if`">~$profile1->getHobbies()->FAV_MOVIE`</div>
                                <div class="contentdivright ~if $fav_movie`red~/if`">~$profile2->getHobbies()->FAV_MOVIE`</div>
                        </div>
			~/if`

			~if $profile1->getHobbies()->SPORTS && $profile2->getHobbies()->SPORTS`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Sports/ Fitness</div>
                                ~if stristr(str_replace(" ","",$profile1->getHobbies()->SPORTS),str_replace(" ","",$profile2->getHobbies()->SPORTS)) || stristr(str_replace(" ","",$profile2->getHobbies()->SPORTS),str_replace(" ","",$profile1->getHobbies()->SPORTS))`
                                        ~assign var="h_sports" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $h_sports`red~/if`">~$profile1->getHobbies()->SPORTS`</div>
                                <div class="contentdivright ~if $h_sports`red~/if`">~$profile2->getHobbies()->SPORTS`</div>
                        </div>
			~/if`

			~if $profile1->getHobbies()->CUISINE && $profile2->getHobbies()->CUISINE`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Favourite Cuisine</div>
                                ~if stristr(str_replace(" ","",$profile1->getHobbies()->CUISINE),str_replace(" ","",$profile2->getHobbies()->CUISINE)) || stristr(str_replace(" ","",$profile2->getHobbies()->CUISINE),str_replace(" ","",$profile1->getHobbies()->CUISINE))`
                                        ~assign var="h_cuisine" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $h_cuisine`red~/if`">~$profile1->getHobbies()->CUISINE`</div>
                                <div class="contentdivright ~if $h_cuisine`red~/if`">~$profile2->getHobbies()->CUISINE`</div>
                        </div>
			~/if`

			~if $profile1->getHobbies()->FAV_FOOD && $profile2->getHobbies()->FAV_FOOD`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Food I Cook</div>
                                ~if stristr(str_replace(" ","",$profile1->getHobbies()->FAV_FOOD),str_replace(" ","",$profile2->getHobbies()->FAV_FOOD)) || stristr(str_replace(" ","",$profile2->getHobbies()->FAV_FOOD),str_replace(" ","",$profile1->getHobbies()->FAV_FOOD))`
                                        ~assign var="fav_food" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $fav_food`red~/if`">~$profile1->getHobbies()->FAV_FOOD`</div>
                                <div class="contentdivright ~if $fav_food`red~/if`">~$profile2->getHobbies()->FAV_FOOD`</div>
                        </div>
			~/if`

			~if $profile1->getHobbies()->FAV_VAC_DEST && $profile2->getHobbies()->FAV_VAC_DEST`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Favourite Vacation Destination</div>
                                ~if stristr(str_replace(" ","",$profile1->getHobbies()->FAV_VAC_DEST),str_replace(" ","",$profile2->getHobbies()->FAV_VAC_DEST)) || stristr(str_replace(" ","",$profile2->getHobbies()->FAV_VAC_DEST),str_replace(" ","",$profile1->getHobbies()->FAV_VAC_DEST))`
                                        ~assign var="fav_vac_dest" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $fav_vac_dest`red~/if`">~$profile1->getHobbies()->FAV_VAC_DEST`</div>
                                <div class="contentdivright ~if $fav_vac_dest`red~/if`">~$profile2->getHobbies()->FAV_VAC_DEST`</div>
                        </div>
			~/if`

			~if $profile1->getDecoratedSettlingAbroad() && $profile2->getDecoratedSettlingAbroad()`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Interested in Settling Abroad?</div>
                                <div class="contentdivleft ~if $profile1->getDecoratedSettlingAbroad() eq $profile2->getDecoratedSettlingAbroad()`red~/if`">~$profile1->getDecoratedSettlingAbroad()`</div>
                                <div class="  contentdivright ~if $profile1->getDecoratedSettlingAbroad() eq $profile2->getDecoratedSettlingAbroad()`red~/if`">~$profile2->getDecoratedSettlingAbroad()`</div>
                        </div>
			~/if`	

			~if $profile1->getExtendedContacts()->LINKEDIN_URL && $profile2->getExtendedContacts()->LINKEDIN_URL`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Linkedin Profile ID/URL</div>
                                ~if stristr(str_replace(" ","",$profile1->getExtendedContacts()->LINKEDIN_URL),str_replace(" ","",$profile2->getExtendedContacts()->LINKEDIN_URL)) || stristr(str_replace(" ","",$profile2->getExtendedContacts()->LINKEDIN_URL),str_replace(" ","",$profile1->getExtendedContacts()->LINKEDIN_URL))`
                                        ~assign var="linkedin_url" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $linkedin_url`red~/if`">~$profile1->getExtendedContacts()->LINKEDIN_URL`</div>
                                <div class="contentdivright ~if $linkedin_url`red~/if`">~$profile2->getExtendedContacts()->LINKEDIN_URL`</div>
                        </div>
			~/if`

			~if $profile1->getExtendedContacts()->FB_URL && $profile2->getExtendedContacts()->FB_URL`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Facebook Profile ID/URL</div>
                                ~if stristr(str_replace(" ","",$profile1->getExtendedContacts()->FB_URL),str_replace(" ","",$profile2->getExtendedContacts()->FB_URL)) || stristr(str_replace(" ","",$profile2->getExtendedContacts()->FB_URL),str_replace(" ","",$profile1->getExtendedContacts()->FB_URL))`
                                        ~assign var="fb_url" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $fb_url`red~/if`">~$profile1->getExtendedContacts()->FB_URL`</div>
                                <div class="contentdivright ~if $fb_url`red~/if`">~$profile2->getExtendedContacts()->FB_URL`</div>
                        </div>
			~/if`

			~if $profile1->getExtendedContacts()->BLACKBERRY && $profile2->getExtendedContacts()->BLACKBERRY`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Blackberry Pin</div>
                                ~if stristr(str_replace(" ","",$profile1->getExtendedContacts()->BLACKBERRY),str_replace(" ","",$profile2->getExtendedContacts()->BLACKBERRY)) || stristr(str_replace(" ","",$profile2->getExtendedContacts()->BLACKBERRY),str_replace(" ","",$profile1->getExtendedContacts()->BLACKBERRY))`
                                        ~assign var="blackberry" value="1"`
                                ~/if`
                                <div class="contentdivleft ~if $blackberry`red~/if`">~$profile1->getExtendedContacts()->BLACKBERRY`</div>
                                <div class="contentdivright ~if $blackberry`red~/if`">~$profile2->getExtendedContacts()->BLACKBERRY`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->AMRITDHARI && $profile2->getReligionInfo()->AMRITDHARI`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Are you a Amritdhari? </div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->AMRITDHARI eq $profile2->getReligionInfo()->AMRITDHARI`red~/if`">~$profile1->getReligionInfo()->AMRITDHARI`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->AMRITDHARI eq $profile2->getReligionInfo()->AMRITDHARI`red~/if`">~$profile2->getReligionInfo()->AMRITDHARI`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->CUT_HAIR && $profile2->getReligionInfo()->CUT_HAIR`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Do you cut your hair?</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->CUT_HAIR  eq $profile2->getReligionInfo()->CUT_HAIR`red~/if`">~$profile1->getReligionInfo()->CUT_HAIR`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->CUT_HAIR  eq $profile2->getReligionInfo()->CUT_HAIR`red~/if`">~$profile2->getReligionInfo()->CUT_HAIR`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->TRIM_BEARD && $profile2->getReligionInfo()->TRIM_BEARD`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Do you trim your beard?</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->TRIM_BEARD  eq $profile2->getReligionInfo()->TRIM_BEARD` red~/if`">~$profile1->getReligionInfo()->TRIM_BEARD`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->TRIM_BEARD  eq $profile2->getReligionInfo()->TRIM_BEARD`red~/if`">~$profile2->getReligionInfo()->TRIM_BEARD`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->WEAR_TURBAN && $profile2->getReligionInfo()->WEAR_TURBAN`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Do you wear turban?</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->WEAR_TURBAN eq $profile2->getReligionInfo()->WEAR_TURBAN` red~/if`">~$profile1->getReligionInfo()->WEAR_TURBAN`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->WEAR_TURBAN eq $profile2->getReligionInfo()->WEAR_TURBAN` red~/if`">~$profile2->getReligionInfo()->WEAR_TURBAN`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->CLEAN_SHAVEN && $profile2->getReligionInfo()->CLEAN_SHAVEN`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Are you clean-shaven?</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->CLEAN_SHAVEN  eq $profile2->getReligionInfo()->CLEAN_SHAVEN` red~/if`">~$profile1->getReligionInfo()->CLEAN_SHAVEN`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->CLEAN_SHAVEN eq $profile2->getReligionInfo()->CLEAN_SHAVEN`red~/if`">~$profile2->getReligionInfo()->CLEAN_SHAVEN`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->MATHTHAB && $profile2->getReligionInfo()->MATHTHAB`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Ma'thab</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->MATHTHAB eq $profile2->getReligionInfo()->MATHTHAB` red~/if`">~$profile1->getReligionInfo()->MATHTHAB`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->MATHTHAB eq $profile2->getReligionInfo()->MATHTHAB` red~/if`">~$profile2->getReligionInfo()->MATHTHAB`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->SPEAK_URDU && $profile2->getReligionInfo()->SPEAK_URDU`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Speak Urdu</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->SPEAK_URDU eq $profile2->getReligionInfo()->SPEAK_URDU` red~/if`">~$profile1->getReligionInfo()->SPEAK_URDU`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->SPEAK_URDU eq $profile2->getReligionInfo()->SPEAK_URDU`red~/if`">~$profile2->getReligionInfo()->SPEAK_URDU`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->NAMAZ && $profile2->getReligionInfo()->NAMAZ`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Namaz</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->NAMAZ eq $profile2->getReligionInfo()->NAMAZ`red~/if`">~$profile1->getReligionInfo()->NAMAZ`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->NAMAZ eq $profile2->getReligionInfo()->NAMAZ`red~/if`">~$profile2->getReligionInfo()->NAMAZ`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->ZAKAT && $profile2->getReligionInfo()->ZAKAT`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Zakat</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->ZAKAT eq $profile2->getReligionInfo()->ZAKAT`red~/if`">~$profile1->getReligionInfo()->ZAKAT`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->ZAKAT eq $profile2->getReligionInfo()->ZAKAT` red~/if`">~$profile2->getReligionInfo()->ZAKAT`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->FASTING && $profile2->getReligionInfo()->FASTING`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Fasting</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->FASTING eq $profile2->getReligionInfo()->FASTING`red~/if`">~$profile1->getReligionInfo()->FASTING`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->FASTING eq $profile2->getReligionInfo()->FASTING`red~/if`">~$profile2->getReligionInfo()->FASTING`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->UMRAH_HAJJ && $profile2->getReligionInfo()->UMRAH_HAJJ`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Umrah/Hajj</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->UMRAH_HAJJ eq $profile2->getReligionInfo()->UMRAH_HAJJ` red~/if`">~$profile1->getReligionInfo()->UMRAH_HAJJ`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->UMRAH_HAJJ eq $profile2->getReligionInfo()->UMRAH_HAJJ` red~/if`">~$profile2->getReligionInfo()->UMRAH_HAJJ`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->QURAN && $profile2->getReligionInfo()->QURAN`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Do you read the Quran?</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->QURAN eq $profile2->getReligionInfo()->QURAN`red~/if`">~$profile1->getReligionInfo()->QURAN`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->QURAN eq $profile2->getReligionInfo()->QURAN` red~/if`">~$profile2->getReligionInfo()->QURAN`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->SUNNAH_BEARD && $profile2->getReligionInfo()->SUNNAH_BEARD`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Sunnah Beard</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->SUNNAH_BEARD eq $profile2->getReligionInfo()->SUNNAH_BEARD` red~/if`">~$profile1->getReligionInfo()->SUNNAH_BEARD`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->SUNNAH_BEARD eq $profile2->getReligionInfo()->SUNNAH_BEARD` red~/if`">~$profile2->getReligionInfo()->SUNNAH_BEARD`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->SUNNAH_CAP && $profile2->getReligionInfo()->SUNNAH_CAP`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Sunnah Cap</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->SUNNAH_CAP eq $profile2->getReligionInfo()->SUNNAH_CAP` red~/if`">~$profile1->getReligionInfo()->SUNNAH_CAP`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->SUNNAH_CAP eq $profile2->getReligionInfo()->SUNNAH_CAP`red~/if`">~$profile2->getReligionInfo()->SUNNAH_CAP`</div>
                        </div>
			~/if`
	
			~if $profile1->getReligionInfo()->HIJAB && $profile2->getReligionInfo()->HIJAB`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Hijab</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->HIJAB eq $profile2->getReligionInfo()->HIJAB` red~/if`">~$profile1->getReligionInfo()->HIJAB`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->HIJAB eq $profile2->getReligionInfo()->HIJAB`red~/if`">~$profile2->getReligionInfo()->HIJAB`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->WORKING_MARRIAGE && $profile2->getReligionInfo()->WORKING_MARRIAGE`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Can the girl work after marriage?</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->WORKING_MARRIAGE eq $profile2->getReligionInfo()->WORKING_MARRIAGE`red~/if`">~$profile1->getReligionInfo()->WORKING_MARRIAGE`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->WORKING_MARRIAGE eq $profile2->getReligionInfo()->WORKING_MARRIAGE`red~/if`">~$profile2->getReligionInfo()->WORKING_MARRIAGE`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->DIOCESE && $profile2->getReligionInfo()->DIOCESE`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Diocese</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->DIOCESE eq $profile2->getReligionInfo()->DIOCESE`red~/if`">~$profile1->getReligionInfo()->DIOCESE`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->DIOCESE eq $profile2->getReligionInfo()->DIOCESE`red~/if`">~$profile2->getReligionInfo()->DIOCESE`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->BAPTISED && $profile2->getReligionInfo()->BAPTISED`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Baptised</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->BAPTISED eq $profile2->getReligionInfo()->BAPTISED`red~/if`">~$profile1->getReligionInfo()->BAPTISED`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->BAPTISED eq $profile2->getReligionInfo()->BAPTISED`red~/if`">~$profile2->getReligionInfo()->BAPTISED`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->READ_BIBLE && $profile2->getReligionInfo()->READ_BIBLE`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Do you read Bible Everyday?</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->READ_BIBLE eq $profile2->getReligionInfo()->READ_BIBLE`red~/if`">~$profile1->getReligionInfo()->READ_BIBLE`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->READ_BIBLE eq $profile2->getReligionInfo()->READ_BIBLE`red~/if`">~$profile2->getReligionInfo()->READ_BIBLE`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->OFFER_TITHE && $profile2->getReligionInfo()->OFFER_TITHE`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Do you offer Tithe regularly?</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->OFFER_TITHE eq $profile2->getReligionInfo()->OFFER_TITHE`red~/if`">~$profile1->getReligionInfo()->OFFER_TITHE`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->OFFER_TITHE eq $profile2->getReligionInfo()->OFFER_TITHE`red~/if`">~$profile2->getReligionInfo()->OFFER_TITHE`</div>
                        </div>
			~/if`
	
			~if $profile1->getReligionInfo()->SPREADING_GOSPEL && $profile2->getReligionInfo()->SPREADING_GOSPEL`
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Interested in spreading the Gospel?</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->SPREADING_GOSPEL eq $profile2->getReligionInfo()->SPREADING_GOSPEL`red~/if`">~$profile1->getReligionInfo()->SPREADING_GOSPEL`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->SPREADING_GOSPEL eq $profile2->getReligionInfo()->SPREADING_GOSPEL`red~/if`">~$profile2->getReligionInfo()->SPREADING_GOSPEL`</div>
                        </div>
			~/if`	

			~if $profile1->getReligionInfo()->ZARATHUSHTRI && $profile2->getReligionInfo()->ZARATHUSHTRI`
                        <div class="grey-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Are you a Zarathushtri?</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->ZARATHUSHTRI eq $profile2->getReligionInfo()->ZARATHUSHTRI` red~/if`">~$profile1->getReligionInfo()->ZARATHUSHTRI`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->ZARATHUSHTRI eq $profile2->getReligionInfo()->ZARATHUSHTRI` red~/if`">~$profile2->getReligionInfo()->ZARATHUSHTRI`</div>
                        </div>
			~/if`

			~if $profile1->getReligionInfo()->PARENTS_ZARATHUSHTRI && $profile2->getReligionInfo()->PARENTS_ZARATHUSHTRI`	
                        <div class="white-heading fl">
                                <div class="fl div1">&nbsp;</div>
                                <div  class="fl orange  div2">Are both parents Zarathushtri?</div>
                                <div class="contentdivleft ~if $profile1->getReligionInfo()->PARENTS_ZARATHUSHTRI eq $profile2->getReligionInfo()->PARENTS_ZARATHUSHTRI`red~/if`">~$profile1->getReligionInfo()->PARENTS_ZARATHUSHTRI`</div>
                                <div class="  contentdivright ~if $profile1->getReligionInfo()->PARENTS_ZARATHUSHTRI eq $profile2->getReligionInfo()->PARENTS_ZARATHUSHTRI` red~/if`">~$profile2->getReligionInfo()->PARENTS_ZARATHUSHTRI`</div>
                        </div>
			~/if`
			<!--  MORE INFORMATION ENDS   -->
              
			<div class="clr">&nbsp;</div>
			 <!--DUPLICATE DECISION BOX STARTS-->           
			<div class="box-grey" >
				<textarea placeholder="Enter text" name="comments_mi" id="comments_mi">~$comments_mi`</textarea>
				<div class="sp10">&nbsp;</div>           
				<input type="Submit" name="marked" class="btn-red fs16 white b" value="Duplicate" onclick="return showMessage('duplicate')"/>
				<input type="Submit" name="marked" class="btn-green fs16 white b"  value="Not Duplicate" onclick="return showMessage('not_duplicate')"/>
				<input type="Submit" name="marked" class="btn-orange fs16 white b" value="Cant Say" onclick="return comment()"/>
			</div>
			<!--DUPLICATE DECISION BOX FINISH-->
			<div class="clr">&nbsp;</div>   
			<!--CONTENT FINISH -->
		</div>
	</body>
	</form>
</html>
