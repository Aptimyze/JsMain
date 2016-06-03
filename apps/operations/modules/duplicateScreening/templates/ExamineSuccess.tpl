<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link rel="stylesheet" type="text/css" href="~sfConfig::get('app_img_url')`/profile/css/pd_screen.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Untitled Document</title>
	</head>
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
						<input type="checkbox" ~if strstr($pair->getReason(), 'PHONE')`checked="checked"~/if` DISABLED/>Phone number matching<br />
						<input type="checkbox" ~if strstr($pair->getReason(), 'CRAWLER')`checked="checked"~/if` DISABLED/>Profile information matching<br />
						<input type="checkbox" ~if strstr($pair->getReason(), 'TEXT')`checked="checked"~/if` DISABLED/>Text information matching            
					</div>
					<div class="fl options2">           
						<input type="checkbox" ~if strstr($pair->getReason(), 'EMAIL') or strstr($pair->getReason(), 'MESSENGER')`checked="checked"~/if` DISABLED/>eMail or Messenger ID matching <br /> 
						<input type="checkbox" ~if strstr($pair->getReason(), 'PHOTO')`checked="checked"~/if`/ DISABLED>Photo matching <br />
						<input type="checkbox" ~if strstr($pair->getReason(), 'ID_PROOF')`checked="checked"~/if`/ DISABLED>ID proof matching           
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
			<div class="white-heading2 fl">
				~foreach from=$album1 item=photo1`
					~if $photo1->getProfilePicUrl() neq ''`
						<div class=" fs20  contentdivleft2  "><img src="~$photo1->getProfilePicUrl()`" width="232px" height="248px"/></div>
					~/if`
				~/foreach`
				~foreach from=$album2 item=photo2`
					~if $photo1->getProfilePicUrl() neq ''`
						<div class=" fs20 contentdivright2"><img src="~$photo2->getProfilePicUrl()`" width="232px" height="248px"/></div>
					~/if`
				~/foreach`
			</div>
			<div class="clr">&nbsp;</div>
			<!--DUPLICATE DECISION BOX STARTS-->           
			<div class="box-grey" >
				<textarea placeholder="Enter text" required>~$pair->getComments()`</textarea>
				<div class="sp10">&nbsp;</div>           
				<input type="button" class="btn-red fs16 white b" value="Duplicate" />
				<input type="button" class="btn-green fs16 white b"  value="Not Duplicate"/>
				<input type="button" class="btn-orange fs16 white b" value="Can't say" />
			</div>
			<!--DUPLICATE DECISION BOX FINISH-->
			<div class="clr">&nbsp;</div>
			<div class="brown-heading2">
				<div class="fl div1">&nbsp;</div><div  class="fl orange fs20 div2">Basic Info</div>
				<div class=" fs20  contentdivleft">~$profile1->getUSERNAME()`</div>    	
				<div class=" fs20 contentdivright">~$profile2->getUSERNAME()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Age</div>
				<div class="contentdivleft ~if $profile1->getAGE() eq $profile2->getAGE()`red~/if`">~$profile1->getAGE()`</div>
				<div class="  contentdivright ~if $profile1->getAGE() eq $profile2->getAGE()`red~/if`">~$profile2->getAGE()`</div>
			</div>
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Height</div>
				<div class="contentdivleft ~if $profile1->getDecoratedHeight() eq $profile2->getDecoratedHeight()`red~/if`">~$profile1->getDecoratedHeight()`</div>
				<div class="  contentdivright ~if $profile1->getDecoratedHeight() eq $profile2->getDecoratedHeight()`red~/if`">~$profile2->getDecoratedHeight()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Gender</div>
				<div class="contentdivleft ~if $profile1->getDecoratedGender() eq $profile2->getDecoratedGender()`red~/if`">~$profile1->getDecoratedGender()`</div>
				<div class="  contentdivright ~if $profile1->getDecoratedGender() eq $profile2->getDecoratedGender()`red~/if`">~$profile2->getDecoratedGender()`</div>
			</div>
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Religion</div>
				<div class="contentdivleft ~if $profile1->getDecoratedReligion() eq $profile2->getDecoratedReligion()`red~/if`">~$profile1->getDecoratedReligion()`</div>    
				<div class="  contentdivright ~if $profile1->getDecoratedReligion() eq $profile2->getDecoratedReligion()`red~/if`">~$profile2->getDecoratedReligion()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Mother Tongue</div>
				<div class="  contentdivleft ~if $profile1->getDecoratedCommunity() eq $profile2->getDecoratedCommunity()`red~/if`">~$profile1->getDecoratedCommunity()`</div>
				<div class="  contentdivright ~if $profile1->getDecoratedCommunity() eq $profile2->getDecoratedCommunity()`red~/if`">~$profile2->getDecoratedCommunity()`</div>
			</div>
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Caste</div>
				<div class="contentdivleft ~if $profile1->getDecoratedCaste() eq $profile2->getDecoratedCaste()`red~/if`">~$profile1->getDecoratedCaste()`</div>    	
				<div class="  contentdivright ~if $profile1->getDecoratedCaste() eq $profile2->getDecoratedCaste()`red~/if`">~$profile2->getDecoratedCaste()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Marital status</div>
				<div class="contentdivleft ~if $profile1->getDecoratedMaritalStatus() eq $profile2->getDecoratedMaritalStatus()`red~/if`">~$profile1->getDecoratedMaritalStatus()`</div>    	
				<div class="  contentdivright ~if $profile1->getDecoratedMaritalStatus() eq $profile2->getDecoratedMaritalStatus()`red~/if`">~$profile2->getDecoratedMaritalStatus()`</div>
			</div>
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Education</div>
				<div class="contentdivleft ~if $profile1->getDecoratedEducation() eq $profile2->getDecoratedEducation()`red~/if`">~$profile1->getDecoratedEducation()`</div>
				<div class="  contentdivright ~if $profile1->getDecoratedEducation() eq $profile2->getDecoratedEducation()`red~/if`">~$profile2->getDecoratedEducation()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Occupation</div>
				<div class="contentdivleft ~if $profile1->getDecoratedOccupation() eq $profile2->getDecoratedOccupation()`red~/if`">~$profile1->getDecoratedOccupation()`</div>
				<div class="  contentdivright ~if $profile1->getDecoratedOccupation() eq $profile2->getDecoratedOccupation()`red~/if`">~$profile2->getDecoratedOccupation()`</div>
			</div>
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Location</div>
				<div class="contentdivleft ~if $profile1->getDecoratedCity() eq $profile2->getDecoratedCity()`red~/if`">~$profile1->getDecoratedCity()`</div>
				<div class="  contentdivright ~if $profile1->getDecoratedCity() eq $profile2->getDecoratedCity()`red~/if`">~$profile2->getDecoratedCity()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Annual Income</div>
				<div class="contentdivleft ~if $profile1->getDecoratedIncomeLevel() eq $profile2->getDecoratedIncomeLevel()`red~/if`">~$profile1->getDecoratedIncomeLevel()`</div>
				<div class="  contentdivright ~if $profile1->getDecoratedIncomeLevel() eq $profile2->getDecoratedIncomeLevel()`red~/if`">~$profile2->getDecoratedIncomeLevel()`</div>
			</div>
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Date of birth</div>
				<div class="contentdivleft ~if $profile1->getDTOFBIRTH() eq $profile2->getDTOFBIRTH()`red~/if`">~$profile1->getDTOFBIRTH()`</div>    	
				<div class="  contentdivright ~if $profile1->getDTOFBIRTH() eq $profile2->getDTOFBIRTH()`red~/if`">~$profile2->getDTOFBIRTH()`</div>
			</div>
			<div class="clr">&nbsp;</div>
			<!--DUPLICATE DECISION BOX STARTS-->
			<div class="box-grey" >
				<textarea placeholder="Enter text" required>~$pair->getComments()`</textarea>
				<div class="sp10">&nbsp;</div>           
				<input type="button" class="btn-red fs16 white b" value="Duplicate" />
				<input type="button" class="btn-green fs16 white b"  value="Not Duplicate"/>
				<input type="button" class="btn-orange fs16 white b" value="Can't say" />
			</div>
			<!--DUPLICATE DECISION BOX FINISH-->
			<div class="clr">&nbsp;</div>
			<div class="brown-heading2">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange fs20 div2">Text Fields</div><div class=" fs20  contentdivleft">~$profile1->getUSERNAME()`</div>    	
				<div class=" fs20 contentdivright">~$profile2->getUSERNAME()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">About Myself</div>
				<div class="contentdivleft ~if strpos(str_replace(" ","",$profile1->getDecoratedYourInfo()),str_replace(" ","",$profile2->getDecoratedYourInfo()))`red~/if`">~$profile1->getDecoratedYourInfo()`</div>
				<div class="  contentdivright ~if strpos(str_replace(" ","",$profile2->getDecoratedYourInfo()),str_replace(" ","",$profile1->getDecoratedYourInfo()))`red~/if`">~$profile2->getDecoratedYourInfo()`</div>
			</div>
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">About Family</div>
				<div class="contentdivleft ~if strpos(str_replace(" ","",$profile1->getDecoratedFamilyInfo()),str_replace(" ","",$profile2->getDecoratedFamilyInfo()))`red~/if`">~$profile1->getDecoratedFamilyInfo()`</div>
				<div class="  contentdivright ~if strpos(str_replace(" ","",$profile2->getDecoratedFamilyInfo()),str_replace(" ","",$profile1->getDecoratedFamilyInfo()))`red~/if`">~$profile2->getDecoratedFamilyInfo()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">About Education</div>
				<div class="contentdivleft ~if strpos(str_replace(" ","",$profile1->getDecoratedEducationInfo()),str_replace(" ","",$profile2->getDecoratedEducationInfo()))`red~/if`">~$profile1->getDecoratedEducationInfo()`</div>
				<div class="  contentdivright ~if strpos(str_replace(" ","",$profile2->getDecoratedEducationInfo()),str_replace(" ","",$profile1->getDecoratedEducationInfo()))`red~/if`">~$profile2->getDecoratedEducationInfo()`</div>
			</div>
			<div class="grey-heading fl">
				    <div class="fl div1">&nbsp;</div>
				    <div  class="fl orange  div2">About Occupation</div>
				    <div class="contentdivleft ~if strpos(str_replace(" ","",$profile1->getDecoratedJobInfo()),str_replace(" ","",$profile2->getDecoratedJobInfo()))`red~/if`">~$profile1->getDecoratedJobInfo()`</div>    	<div class="  contentdivright ~if strpos(str_replace(" ","",$profile2->getDecoratedJobInfo()),str_replace(" ","",$profile1->getDecoratedJobInfo()))`red~/if`">~$profile2->getDecoratedJobInfo()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">About Desired Partner</div>
				<div class="  contentdivleft ~if strpos(str_replace(" ","",$profile1->getDecoratedSpouseInfo()),str_replace(" ","",$profile2->getDecoratedSpouseInfo()))`red~/if`">~$profile1->getDecoratedSpouseInfo()`</div>    	<div class="  contentdivright ~if strpos(str_replace(" ","",$profile2->getDecoratedSpouseInfo()),str_replace(" ","",$profile1->getDecoratedSpouseInfo()))`red~/if`">~$profile2->getDecoratedSpouseInfo()`</div>
			</div>
			<div class="clr">&nbsp;</div>
			<!--DUPLICATE DECISION BOX STARTS-->           
			<div class="box-grey" >
				<textarea placeholder="Enter text" required>~$pair->getComments()`</textarea>
				<div class="sp10">&nbsp;</div>           
				<input type="button" class="btn-red fs16 white b" value="Duplicate" />
				<input type="button" class="btn-green fs16 white b"  value="Not Duplicate"/>
				<input type="button" class="btn-orange fs16 white b" value="Can't say" />
			</div>
			<!--DUPLICATE DECISION BOX FINISH-->
			<div class="clr">&nbsp;</div>   
			<div class="brown-heading2">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange fs20 div2">More Information</div>
				<div class=" fs20  contentdivleft">~$profile1->getUSERNAME()`</div>    	<div class=" fs20 contentdivright">~$profile2->getUSERNAME()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Manglik/Chewai Dosh</div>
				<div class="contentdivleft ~if $profile1->getDecoratedManglik() eq $profile2->getDecoratedManglik()`red~/if`">~$profile1->getDecoratedManglik()`</div>
				<div class="  contentdivright ~if $profile1->getDecoratedManglik() eq $profile2->getDecoratedManglik()`red~/if`">~$profile2->getDecoratedManglik()`</div>
			</div>
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Rashi/Moon Sign</div>
				<div class="contentdivleft ~if $profile1->getRASHI() eq $profile2->getRASHI()`red~/if`">~$profile1->getRASHI()`</div>    	<div class="  contentdivright ~if $profile1->getRASHI() eq $profile2->getRASHI()`red~/if`">~$profile2->getRASHI()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Nakshatra</div>
				<div class="contentdivleft ~if $profile1->getNAKSHATRA() eq $profile2->getNAKSHATRA()`red~/if`">~$profile1->getNAKSHATRA()`</div>    	<div class="  contentdivright ~if $profile1->getNAKSHATRA() eq $profile2->getNAKSHATRA()`red~/if`">~$profile2->getNAKSHATRA()`</div>
			</div>
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Horoscope Match</div>
				<div class="contentdivleft ~if $profile1->getDecoratedHoroscopeMatch() eq $profile2->getDecoratedHoroscopeMatch()`red~/if`">~$profile1->getDecoratedHoroscopeMatch()`</div>    	<div class="  contentdivright ~if $profile1->getDecoratedHoroscopeMatch() eq $profile2->getDecoratedHoroscopeMatch()`red~/if`">~$profile2->getDecoratedHoroscopeMatch()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Family Values</div>
				<div class="  contentdivleft ~if $profile1->getDecoratedFamilyValues() eq $profile2->getDecoratedFamilyValues()`red~/if`">~$profile1->getDecoratedFamilyValues()`</div>    	<div class="  contentdivright ~if $profile1->getDecoratedFamilyValues() eq $profile2->getDecoratedFamilyValues()`red~/if`">~$profile2->getDecoratedFamilyValues()`</div>
			</div>  
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Family Type</div>
				<div class="contentdivleft ~if $profile1->getDecoratedFamilyType() eq $profile2->getDecoratedFamilyType()`red~/if`">~$profile1->getDecoratedFamilyType()`</div>    	<div class="  contentdivright ~if $profile1->getDecoratedFamilyType() eq $profile2->getDecoratedFamilyType()`red~/if`">~$profile2->getDecoratedFamilyType()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Family Status</div>
			<div class="  contentdivleft ~if $profile1->getDecoratedFamilyStatus() eq $profile2->getDecoratedFamilyStatus()`red~/if`">~$profile1->getDecoratedFamilyStatus()`</div>    	<div class="  contentdivright ~if $profile1->getDecoratedFamilyStatus() eq $profile2->getDecoratedFamilyStatus()`red~/if`">~$profile2->getDecoratedFamilyStatus()`</div>
			</div> 
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Father</div>
				<div class="contentdivleft ~if $profile1->getDecoratedFamilyBackground() eq $profile2->getDecoratedFamilyBackground()`red~/if`">~$profile1->getDecoratedFamilyBackground()`</div>    	<div class="  contentdivright ~if $profile1->getDecoratedFamilyBackground() eq $profile2->getDecoratedFamilyBackground()`red~/if`">~$profile2->getDecoratedFamilyBackground()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Mother</div>
				<div class="  contentdivleft ~if $profile1->getDecoratedMotherOccupation() eq $profile2->getDecoratedMotherOccupation()`red~/if`">~$profile1->getDecoratedMotherOccupation()`</div>    	<div class="  contentdivright ~if $profile1->getDecoratedMotherOccupation() eq $profile2->getDecoratedMotherOccupation()`red~/if`">~$profile2->getDecoratedMotherOccupation()`</div>
			</div> 
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Brothers(s)</div>
				<div class="contentdivleft ~if $profile1->getT_BROTHER() eq $profile2->getT_BROTHER()`red~/if`">~$profile1->getT_BROTHER()`</div>    	<div class="  contentdivright ~if $profile1->getT_BROTHER() eq $profile2->getT_BROTHER()`red~/if`">~$profile2->getT_BROTHER()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Sisters(s)</div>
				<div class="  contentdivleft ~if $profile1->getT_SISTER() eq $profile2->getT_SISTER()`red~/if`">~$profile1->getT_SISTER()`</div>    	<div class="  contentdivright ~if $profile1->getT_SISTER() eq $profile2->getT_SISTER()`red~/if`">~$profile2->getT_SISTER()`</div>
			</div> 
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Living with parents</div>
				<div class="contentdivleft ~if $profile1->getDecoratedLiveWithParents() eq $profile2->getDecoratedLiveWithParents()`red~/if`">~$profile1->getDecoratedLiveWithParents()`</div>    	<div class="  contentdivright ~if $profile1->getDecoratedLiveWithParents() eq $profile2->getDecoratedLiveWithParents()`red~/if`">~$profile2->getDecoratedLiveWithParents()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Diet</div>
				<div class="  contentdivleft ~if $profile1->getDecoratedDiet() eq $profile2->getDecoratedDiet()`red~/if`">~$profile1->getDecoratedDiet()`</div>    	<div class="  contentdivright ~if $profile1->getDecoratedDiet() eq $profile2->getDecoratedDiet()`red~/if`">~$profile2->getDecoratedDiet()`</div>
			</div> 
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Smoke</div>
				<div class="contentdivleft ~if $profile1->getDecoratedSmoke() eq $profile2->getDecoratedSmoke()`red~/if`">~$profile1->getDecoratedSmoke()`</div>    	<div class="  contentdivright ~if $profile1->getDecoratedSmoke() eq $profile2->getDecoratedSmoke()`red~/if`">~$profile2->getDecoratedSmoke()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Drink</div>
				<div class="  contentdivleft ~if $profile1->getDecoratedDrink() eq $profile2->getDecoratedDrink()`red~/if`">~$profile1->getDecoratedDrink()`</div>    	<div class="  contentdivright ~if $profile1->getDecoratedDrink() eq $profile2->getDecoratedDrink()`red~/if`">~$profile2->getDecoratedDrink()`</div>
			</div>  
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Complexion</div>
				<div class="contentdivleft ~if $profile1->getDecoratedComplexion() eq $profile2->getDecoratedComplexion()`red~/if`">~$profile1->getDecoratedComplexion()`</div>    	<div class="  contentdivright ~if $profile1->getDecoratedComplexion() eq $profile2->getDecoratedComplexion()`red~/if`">~$profile2->getDecoratedComplexion()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Body Type</div>
				<div class="  contentdivleft ~if $profile1->getDecoratedBodytype() eq $profile2->getDecoratedBodytype()`red~/if`">~$profile1->getDecoratedBodytype()`</div>    	<div class="  contentdivright ~if $profile1->getDecoratedBodytype() eq $profile2->getDecoratedBodytype()`red~/if`">~$profile2->getDecoratedBodytype()`</div>
			</div> 
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Challenged</div>
				<div class="contentdivleft ~if $profile1->getDecoratedHandicapped() eq $profile2->getDecoratedHandicapped()`red~/if`">~$profile1->getDecoratedHandicapped()`</div>    	<div class="  contentdivright ~if $profile1->getDecoratedHandicapped() eq $profile2->getDecoratedHandicapped()`red~/if`">~$profile2->getDecoratedHandicapped()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Blood Group	</div>
				<div class="  contentdivleft ~if $profile1->getDecoratedBloodGroup() eq $profile2->getDecoratedBloodGroup()`red~/if`">~$profile1->getDecoratedBloodGroup()`</div>    	<div class="  contentdivright ~if $profile1->getDecoratedBloodGroup() eq $profile2->getDecoratedBloodGroup()`red~/if`">~$profile2->getDecoratedBloodGroup()`</div>
			</div> 
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Residential Status</div>
				<div class="contentdivleft ~if $profile1->getDecoratedRstatus() eq $profile2->getDecoratedRstatus()`red~/if`">~$profile1->getDecoratedRstatus()`</div>    	<div class="  contentdivright ~if $profile1->getDecoratedRstatus() eq $profile2->getDecoratedRstatus()`red~/if`">~$profile2->getDecoratedRstatus()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Spoken Language</div>
			<div class="  contentdivleft ~if $profile1->getHobbies() eq $profile2->getHobbies()`red~/if`">~$profile1->getHobbies()->LANGUAGE`</div>    	<div class="  contentdivright ~if $profile1->getHobbies() eq $profile2->getHobbies()`red~/if`">~$profile2->getHobbies()->LANGUAGE`</div>
			</div> 
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">HIV+</div>
				<div class="contentdivleft ~if $profile1->getDecoratedHiv() eq $profile2->getDecoratedHiv()`red~/if`">~$profile1->getDecoratedHiv()`</div>    	<div class="  contentdivright ~if $profile1->getDecoratedHiv() eq $profile2->getDecoratedHiv()`red~/if`">~$profile2->getDecoratedHiv()`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
			    <div  class="fl orange  div2">Hobbies</div>
			    <div class="  contentdivleft ~if $profile1->getHobbies()->HOBBY eq $profile2->getHobbies()->HOBBY`red~/if`">~$profile1->getHobbies()->HOBBY`</div>    	<div class="  contentdivright ~if $profile1->getHobbies()->HOBBY eq $profile2->getHobbies()->HOBBY`red~/if`">~$profile2->getHobbies()->HOBBY`</div>
			    </div> 
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Interests</div>
				<div class="contentdivleft ~if $profile1->getHobbies()->INTEREST eq $profile2->getHobbies()->INTEREST`red~/if`">~$profile1->getHobbies()->INTEREST`</div>    	<div class="  contentdivright ~if $profile1->getHobbies()->INTEREST eq $profile2->getHobbies()->INTEREST`red~/if`">~$profile2->getHobbies()->INTEREST`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Favourite Music</div>
				<div class="  contentdivleft ~if $profile1->getHobbies()->MUSIC eq $profile2->getHobbies()->MUSIC`red~/if`">~$profile1->getHobbies()->MUSIC`</div>    	<div class="  contentdivright ~if $profile1->getHobbies()->MUSIC eq $profile2->getHobbies()->MUSIC`red~/if`">~$profile2->getHobbies()->MUSIC`</div>
			</div> 
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Favourite Read</div>
				<div class="contentdivleft ~if $profile1->getHobbies()->BOOK eq $profile2->getHobbies()->BOOK`red~/if`">~$profile1->getHobbies()->BOOK`</div>    	<div class="  contentdivright ~if $profile1->getHobbies()->BOOK eq $profile2->getHobbies()->BOOK`red~/if`">~$profile2->getHobbies()->BOOK`</div>
			</div>
			<div class="white-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Favourite Books</div>
				<div class="  contentdivleft ~if $profile1->getHobbies()->FAV_BOOK eq $profile2->getHobbies()->FAV_BOOK`red~/if`">~$profile1->getHobbies()->FAV_BOOK`</div>    	<div class="  contentdivright ~if $profile1->getHobbies()->FAV_BOOK eq $profile2->getHobbies()->FAV_BOOK`red~/if`">~$profile2->getHobbies()->FAV_BOOK`</div>
			</div> 
			<div class="grey-heading fl">
				<div class="fl div1">&nbsp;</div>
				<div  class="fl orange  div2">Favourite Vacation</div>
				<div class="contentdivleft ~if $profile1->getHobbies()->FAV_VAC_DEST eq $profile2->getHobbies()->FAV_VAC_DEST`red~/if`">~$profile1->getHobbies()->FAV_VAC_DEST`</div>    	<div class="  contentdivright ~if $profile1->getHobbies()->FAV_VAC_DEST eq $profile2->getHobbies()->FAV_VAC_DEST`red~/if`">~$profile2->getHobbies()->FAV_VAC_DEST`</div>
			</div>              
			<div class="clr">&nbsp;</div>
			 <!--DUPLICATE DECISION BOX STARTS-->           
			<div class="box-grey" >
				<textarea placeholder="Enter text" required>~$pair->getComments()`</textarea>
				<div class="sp10">&nbsp;</div>           
				<input type="button" class="btn-red fs16 white b" value="Duplicate" />
				<input type="button" class="btn-green fs16 white b"  value="Not Duplicate"/>
				<input type="button" class="btn-orange fs16 white b" value="Can't say" />
			</div>
			<!--DUPLICATE DECISION BOX FINISH-->
			<div class="clr">&nbsp;</div>   
			<!--CONTENT FINISH -->
		</div>
	</body>
</html>
