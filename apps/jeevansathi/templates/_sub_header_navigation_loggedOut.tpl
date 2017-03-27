<ul id="css3menu" class="topmenu gnbHeader" style="border-width: 1px 0;float: left;margin: 0;padding: 0;width: 930px;">
				~if $pageName eq 'SearchPage' || $pageName eq 'membership'`
				<li class="speratprwhite"></li>
                <li class="topmenu w100 center"><a href="~sfConfig::get('app_site_url')`/profile/mainmenu.php" onclick="return sub_header_fn(0,'~$pageName`',1);" id="homeLoggedOut" class="padh cursp bold_gnb">Home</a></li>
                <li class="speratprwhite"></li>
				<li class="toproot center">
                <a href="~sfConfig::get('app_site_url')`/search/matchalerts" onclick="return sub_header_fn(0,'~$pageName`',6);" class="layer1 cursp">
                    	<div class="fullwidth padleft22">
                        	<div class="fl bold_gnb" id="myMatchesLoggedOut">
                            	Desired Partner Matches
                            </div>
                            <div class="fl droparw">
                            	<div class="droparrow1"></div>
                            </div>
                            <div class="clr"></div>                        
                        </div>
                    </a>
					<div class="submenu boxshadow w170">
						<div class="column fullwidth">
                        	<ul class="fullwidth">
                                    
                                <li>
                                  <div class="padallsix">
                                  	<a href="~sfConfig::get('app_site_url')`/search/partnermatches" onclick="return sub_header_fn(0,'~$pageName`',6);" class="fullwidth cursp">
                                    	<div class="paddallfive" id="dpMatchesLoggedOut">Desired Partner Matches</div>
                                     </a>                                        
                                  </div>  
                                </li>
                                
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a href="~sfConfig::get('app_site_url')`/search/perform?justJoinedMatches=1" onclick="return sub_header_fn(0,'~$pageName`',23);" class="fullwidth cursp">
                                       <div class="paddallfive" id="JustJoinedLoggedOut">Just Joined Matches</div>                                       
                                     </a>                                        
                                  </div>  
                                </li>
								 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a href="~sfConfig::get('app_site_url')`/search/matchalerts" onclick="return sub_header_fn(0,'~$pageName`',16);"class="fullwidth cursp">
                                       <div class="paddallfive" id="MatchAlertsLoggedOut">Daily Recommendations</div>                                       
                                     </a>                                        
                                  </div>  
                                </li>
                        	
								
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a href="~sfConfig::get('app_site_url')`/search/twoway" onclick="return sub_header_fn(0,'~$pageName`',8);" class="fullwidth cursp">
                                        <div class="paddallfive" id="mutualMatchesLoggedOut">Mutual Matches</div>
                                     </a>                                        
                                  </div>  
                                </li>
                                
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a href="~sfConfig::get('app_site_url')`/search/reverseDpp" onclick="return sub_header_fn(0,'~$pageName`',7);" class="fullwidth cursp">
                                        <div class="paddallfive" id="memberslookingForMeLoggedOut">Members Looking for Me</div>
                                     </a>                                        
                                  </div>  
                                </li>
                               
                                
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?checksum=~$sf_request->getAttribute('checksum')`&page=kundli&filter=R" onclick="return sub_header_fn(0,'~$pageName`',17);" class="fullwidth cursp">
                                        <div class="paddallfive" id="KundliLoggedOut">Kundli Matches</div>
                                     </a>                                        
                                  </div>  
                                </li>
							</ul>
                        </div>
					 </div>
                </li>
                <li class="speratprwhite"></li>
                <li class="toproot center">
                	<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php" onclick="return sub_header_fn(0,'~$pageName`',2);"  class="layer1 cursp">
                    	<div class="fullwidth padleft22">
                        	<div class="fl bold_gnb" id="myContactsLoggedOut">
                            	Contacts
                            </div>
                            <div class="fl droparw">
                            	<div class="droparrow1"></div>
                            </div>
                            <div class="clr"></div>                        
                        </div>
                    </a>
					<div class="submenu boxshadow w210">
						<div class="column fullwidth">
                        	<ul class="fullwidth">
								<li>
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?checksum=~$sf_request->getAttribute('checksum')`&page=eoi&filter=R" onclick="return sub_header_fn(0,'~$pageName`',9);" >
                                    	<div class="paddallfive" id="peopleToRespondLoggedOut">People I Have to Respond to</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?checksum=~$sf_request->getAttribute('checksum')`&page=accept&filter=A" onclick="return sub_header_fn(0,'~$pageName`',10);" >
                                        <div class="paddallfive" id="allAcceptancesLoggedOut">All Acceptances</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?checksum=~$sf_request->getAttribute('checksum')`&page=visitors&filter=R" onclick="return sub_header_fn(0,'~$pageName`',11);" >
                                        <div class="paddallfive" id="recentProfileLoggedOut">Profile Visitors</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?checksum=~$sf_request->getAttribute('checksum')`&page=photo&filter=R" onclick="return sub_header_fn(0,'~$pageName`',12);">
                                       <div class="paddallfive" id="photoRequestLoggedOut">Photo Requests Received</div>                                       
                                     </a>                                        
                                  </div>  
                                </li>                                
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?checksum=~$sf_request->getAttribute('checksum')`&page=messages&filter=R" onclick="return sub_header_fn(0,'~$pageName`',13);" >
                                        <div class="paddallfive" id="messageReceivedLoggedOut">Messages Received</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?checksum=~$sf_request->getAttribute('checksum')`&page=viewed_contacts_by&filter=R" onclick="return sub_header_fn(0,'~$pageName`',14);" >
                                        <div class="paddallfive" id="viewedMyContactsLoggedOut">People who viewed my contacts</div>
                                     </a>                                        
                                  </div>  
                                </li>
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?checksum=~$sf_request->getAttribute('checksum')`&page=favorite&filter=M" onclick="return sub_header_fn(0,'~$pageName`',15);" >
                                        <div class="paddallfive" id="shortlistedLoggedOut">Shortlisted Profiles</div>
                                     </a>                                        
                                  </div>  
                                </li>
                                
							</ul>
                        </div>
					 </div>
                </li>
                <li class="speratprwhite"></li>
				 <li class="toproot center">
                	<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?ownview=1" onclick="return sub_header_fn(0,'~$pageName`',3);"class="layer1 cursp">
                    	<div class="fullwidth padleft22">
                        	<div class="fl bold_gnb" id="myProfileLoggedOut">
                            	My Profile
                            </div>
                            <div class="fl droparw">
                            	<div class="droparrow1"></div>
                            </div>
                            <div class="clr"></div>                        
                        </div>
                    </a>
					<div class="submenu boxshadow w210">
						<div class="column fullwidth">
                        	<ul class="fullwidth">
								 <li>
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?checksum=~$sf_request->getAttribute('checksum')`&profilechecksum=~$sf_request->getAttribute('profilechecksum')`&ownview=1&EditWhatNew=EduOcc"  onclick="return sub_header_fn(0,'~$pageName`',18);" >
                                    	<div class="paddallfive" id="editEducationOccLoggedOut">Edit Education and Occupation</div>
                                     </a>                                        
                                  </div>  
                                </li>  
								<li>
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?checksum=~$sf_request->getAttribute('checksum')`&profilechecksum=~$sf_request->getAttribute('profilechecksum')`&ownview=1&EditWhatNew=ContactDetails"  onclick="return sub_header_fn(0,'~$pageName`',18);" >
                                    	<div class="paddallfive" id="editContactLoggedOut">Edit Contact Details</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?checksum=~$sf_request->getAttribute('checksum')`&profilechecksum=~$sf_request->getAttribute('profilechecksum')`&ownview=1&EditWhatNew=FamilyDetails"  onclick="return sub_header_fn(0,'~$pageName`',19);" >
                                        <div class="paddallfive" id="editFamilyLoggedOut">Edit Family Details</div>
                                     </a>                                        
                                  </div>  
                                </li>    
                                                             
                                                    
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/social/addPhotos"  onclick="return sub_header_fn(0,'~$pageName`',20);">
                                        <div class="paddallfive" id="uploadPhotosLoggedOut">Upload Photos</div>
                                     </a>                                        
                                  </div>  
                                </li>  
							</ul>
                        </div>
					 </div>
                </li>
                <li class="speratprwhite"></li>
				<li class="toproot center">
                	<a href="~sfConfig::get('app_site_url')`/profile/dpp" onclick="return sub_header_fn(0,'~$pageName`',21);" class="layer1 cursp">
                    	<div class="fullwidth padleft22">
                        	<div class="fl bold_gnb" id="dppLoggedOut">
                            	Desired Partner
                            </div>
                            <div class="fl droparw">
                            	<div class="droparrow1"></div>
                            </div>
                            <div class="clr"></div>                        
                        </div>
                    </a>
					<div class="submenu boxshadow w210">
						<div class="column fullwidth">
                        	<ul class="fullwidth">
								<li>
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/dpp?EditWhatNew=Dpp_Info"  onclick="return sub_header_fn(0,'~$pageName`',22);" >
                                    	<div class="paddallfive" id="editDppDescriptionLoggedOut">Edit Description</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/dpp?EditWhatNew=Dpp_Details"  onclick="return sub_header_fn(0,'~$pageName`',23);" >
                                        <div class="paddallfive" id="EditDppBasicLoggedOut">Edit Basic Details</div>
                                     </a>                                        
                                  </div>  
                                </li> 
							</ul>
                        </div>
					 </div>
                </li>
                <li class="speratprwhite"></li>
                ~else`
                <li class="speratprwhite"></li>
                <li class="topmenu w100 center"><a href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=1" class="padh thickbox cursp bold_gnb" id="homeLoggedOut">Home</a></li>
                <li class="speratprwhite"></li>
				<li class="toproot center">
                <a href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=6" class="layer1 thickbox cursp">
                    	<div class="fullwidth padleft22">
                        	<div class="fl bold_gnb" id="myMatchesLoggedOut">
                            	Matches
                            </div>
                            <div class="fl droparw">
                            	<div class="droparrow1"></div>
                            </div>
                            <div class="clr"></div>                        
                        </div>
                    </a>
					<div class="submenu boxshadow w170">
						<div class="column fullwidth">
                        	<ul class="fullwidth">
								<li>
                                  <div class="padallsix">
                                  	<a href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=6" class="fullwidth cursp thickbox">
                                    	<div class="paddallfive" id="dpMatchesLoggedOut">Desired Partner Matches</div>
                                     </a>                                        
                                  </div>  
                                </li>
                                
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=8" class="fullwidth cursp thickbox">
                                        <div class="paddallfive" id="mutualMatchesLoggedOut">Mutual Matches</div>
                                     </a>                                        
                                  </div>  
                                </li>
                                
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=7" class="fullwidth cursp thickbox">
                                        <div class="paddallfive" id="memberslookingForMeLoggedOut">Members Looking for Me</div>
                                     </a>                                        
                                  </div>  
                                </li>
							</ul>
                        </div>
					 </div>
                </li>
                <li class="speratprwhite"></li>
                <li class="toproot center">
                	<a href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=2" class="layer1 thickbox cursp">
                    	<div class="fullwidth padleft22">
                        	<div class="fl bold_gnb" id="myContactsLoggedOut">
                            	Contacts
                            </div>
                            <div class="fl droparw">
                            	<div class="droparrow1"></div>
                            </div>
                            <div class="clr"></div>                        
                        </div>
                    </a>
					<div class="submenu boxshadow w210">
						<div class="column fullwidth">
                        	<ul class="fullwidth">
								<li>
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp thickbox" href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=9">
                                    	<div class="paddallfive" id="peopleToRespondLoggedOut">People I Have to Respond to</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp thickbox" href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=10">
                                        <div class="paddallfive"  id="allAcceptancesLoggedOut">All Acceptances</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp thickbox" href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=11">
                                        <div class="paddallfive" id="recentProfileLoggedOut">Profile Visitors</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp thickbox" href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=12">
                                       <div class="paddallfive" id="photoRequestLoggedOut">Photo Requests Received</div>                                       
                                     </a>                                        
                                  </div>  
                                </li>                                
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp thickbox" href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=13">
                                        <div class="paddallfive" id="messageReceivedLoggedOut">Messages Received</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp thickbox" href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=14">
                                        <div class="paddallfive" id="viewedMyContactsLoggedOut">People who viewed my contacts</div>
                                     </a>                                        
                                  </div>  
                                </li>
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp thickbox" href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=15">
                                        <div class="paddallfive" id="shortlistedLoggedOut">Shortlisted Profiles</div>
                                     </a>                                        
                                  </div>  
                                </li>
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=16" class="fullwidth cursp thickbox">
                                       <div class="paddallfive" id="MatchAlertsLoggedOut">Daily Recommendations</div>                                       
                                     </a>                                        
                                  </div>  
                                </li>
                                
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=17" class="fullwidth cursp thickbox">
                                        <div class="paddallfive" id="KundliLoggedOut">Kundli Matches</div>
                                     </a>                                        
                                  </div>  
                                </li>
							</ul>
                        </div>
					 </div>
                </li>
                <li class="speratprwhite"></li>
				 <li class="toproot center">
                	<a href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=3" class="layer1 thickbox cursp">
                    	<div class="fullwidth padleft22">
                        	<div class="fl bold_gnb" id="myProfileLoggedOut">
                            	Profile
                            </div>
                            <div class="fl droparw">
                            	<div class="droparrow1"></div>
                            </div>
                            <div class="clr"></div>                        
                        </div>
                    </a>
					<div class="submenu boxshadow w210">
						<div class="column fullwidth">
                        	<ul class="fullwidth">
								<li>
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=18" >
                                    	<div class="paddallfive" id="editEducationOccLoggedOut">Edit Education and Occupation</div>
                                     </a>                                        
                                  </div>  
                                </li>  
								
								<li>
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp thickbox" href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=18">
                                    	<div class="paddallfive" id="editContactLoggedOut">Edit Contact Details</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp thickbox" href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=19">
                                        <div class="paddallfive" id="editFamilyLoggedOut">Edit Family Details</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp thickbox" href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=20">
                                        <div class="paddallfive" id="uploadPhotosLoggedOut">Upload Photos</div>
                                     </a>                                        
                                  </div>  
                                </li>  
							</ul>
                        </div>
					 </div>
                </li>
                <li class="speratprwhite"></li>
				<li class="toproot center">
                	<a href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=5"  class="layer1 thickbox cursp">
                    	<div class="fullwidth padleft22">
                        	<div class="fl bold_gnb" id="dppLoggedOut">
                            	Desired Partner
                            </div>
                            <div class="fl droparw">
                            	<div class="droparrow1"></div>
                            </div>
                            <div class="clr"></div>                        
                        </div>
                    </a>
					<div class="submenu boxshadow w210">
						<div class="column fullwidth">
                        	<ul class="fullwidth">
								<li>
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp thickbox" href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=21">
                                    	<div class="paddallfive" id="editDppDescriptionLoggedOut">Edit Description</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp thickbox" href="~sfConfig::get('app_site_url')`/profile/before_log.php?page=22">
                                        <div class="paddallfive" id="EditDppBasicLoggedOut">Edit Basic Details</div>
                                     </a>                                        
                                  </div>  
                                </li> 
							</ul>
                        </div>
					 </div>
                </li>
                <li class="speratprwhite"></li>
                ~/if`
                ~if $sf_context->getModuleName() eq 'membership'`
                <li class="topmenu center topRootSelect"><a href="#" class="padh2 bold_gnb" id="membershipLoggedOut">Upgrade Membership</a></li>
                ~else`
                <li class="topmenu center"><a href="~sfConfig::get('app_site_url')`/profile/mem_comparison.php?from_source=Membership_page_link_just_above_search" class="padh2 cursp bold_gnb" id="membershipLoggedOut">Upgrade Membership</a></li>
                ~/if`
                <li class="speratprwhite"></li>
                
                
            </ul>
<script>
if (window.location.protocol == "https:")
	    window.location.href = "http:" + window.location.href.substring(window.location.protocol.length);
</script>
