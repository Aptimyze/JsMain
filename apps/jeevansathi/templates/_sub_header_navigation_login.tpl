		<ul id="css3menu" class="topmenu gnbHeader" style="border-width: 1px 0;float: left;margin: 0;padding: 0;width: 930px;">
				<li class="speratprwhite"></li>
                <li class="topmenu w100 center "><a href="~sfConfig::get('app_site_url')`/profile/mainmenu.php" class="padh cursp bold_gnb" id="homeLoggedIn">Home</a></li>
                <li class="speratprwhite"></li>
                ~if $szNavType eq 'SR' || $szNavType eq 'MA' || $szNavType eq 'KM'`
				<li class="toproot center topRootSelect">
                   	<a href="#" class="layer1">
				~else`
				<li class="toproot center">
                    <a href="~sfConfig::get('app_site_url')`/search/matchalerts" class="layer1  cursp">
				~/if`
                    	<div class="fullwidth padleft22 bold_gnb">
                        	<div class="fl" id="myMatchesLoggedIn">
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
                                  	<a href="~sfConfig::get('app_site_url')`/search/partnermatches" class="fullwidth cursp">
                                    	<div class="paddallfive" id="dpMatchesLoggedIn">Desired Partner Matches</div>
                                     </a>                                        
                                  </div>  
                                </li>
                                                                    
                                    <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a href="~sfConfig::get('app_site_url')`/search/perform?justJoinedMatches=1" class="fullwidth cursp">
                                        <div class="paddallfive" id="JustJoinedLoggedIn">Just Joined Matches</div>                                       
                                     </a>                                        
                                  </div>  
                                </li>
								<li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a href="~sfConfig::get('app_site_url')`/search/matchalerts" class="fullwidth cursp">
                                       <div class="paddallfive" id="MatchAlertsLoggedIn">Daily Recommendations</div>                                       
                                     </a>                                        
                                  </div>  
                                </li>
                                
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a href="~sfConfig::get('app_site_url')`/search/twoway" class="fullwidth cursp">
                                        <div class="paddallfive" id="mutualMatchesLoggedIn">Mutual Matches</div>
                                     </a>                                        
                                  </div>  
                                </li>
                                
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a href="~sfConfig::get('app_site_url')`/search/reverseDpp" class="fullwidth cursp">
                                        <div class="paddallfive" id="memberslookingForMeLoggedIn">Members Looking for Me</div>
                                     </a>                                        
                                  </div>  
                                </li>
                                 
                                
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
									~if $sf_request->getAttribute('kundli_link') eq 2`
										<a href="~sfConfig::get('app_site_url')`/search/kundlialerts" class="fullwidth cursp">
									~elseif $sf_request->getAttribute('kundli_link') eq 1`
										<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?checksum=~$sf_request->getAttribute('checksum')`&profilechecksum=~$sf_request->getAttribute('profilechecksum')`&EditWhatNew=AstroData&nextLayer=C&subheader=1" class="fullwidth cursp">
									~/if`
                                        <div class="paddallfive" id="KundliLoggedIn">Kundli Matches</div>
                                     </a>                                        
                                  </div>  
                                </li>
							</ul>
                        </div>
					 </div>
                </li>
                <li class="speratprwhite"></li>
                <li class="toproot center">
                	<a href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php" class="layer1 cursp">
                    	<div class="fullwidth padleft22">
                        	<div class="fl bold_gnb" id="myContactsloggedIn">
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
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?checksum=~$sf_request->getAttribute('checksum')`&page=eoi&filter=R">
                                    	<div class="paddallfive" id="peopleToRespondLoggedIn">People I Have to Respond to</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?checksum=~$sf_request->getAttribute('checksum')`&page=accept&filter=A">
                                        <div class="paddallfive" id="allAcceptancesLoggedIn">All Acceptances</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?checksum=~$sf_request->getAttribute('checksum')`&page=visitors&filter=R">
                                        <div class="paddallfive" id="recentProfileLoggedIn">Profile Visitors</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?checksum=~$sf_request->getAttribute('checksum')`&page=photo&filter=R">
                                       <div class="paddallfive" id="photoRequestLoggedIn">Photo Requests Received</div>                                       
                                     </a>                                        
                                  </div>  
                                </li>                                
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?checksum=~$sf_request->getAttribute('checksum')`&page=messages&filter=R">
                                        <div class="paddallfive" id="messageReceivedLoggedIn">Messages Received</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?checksum=~$sf_request->getAttribute('checksum')`&page=viewed_contacts_by&filter=R">
                                        <div class="paddallfive" id="viewedMyContactsLoggedIn">People who viewed my contacts</div>
                                     </a>                                        
                                  </div>  
                                </li>
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/contacts_made_received.php?checksum=~$sf_request->getAttribute('checksum')`&page=favorite&filter=M">
                                        <div class="paddallfive" id="shortlistedLoggedIn">Shortlisted Profiles</div>
                                     </a>                                        
                                  </div>  
                                </li>
                                
							</ul>
                        </div>
					 </div>
                </li>
                <li class="speratprwhite"></li>
                ~if sfConfig::get("mod_"|cat:$sf_context->getModuleName()|cat:"_"|cat:$sf_context->getActionName()|cat:"_highlight") eq 'profile'`
                <li class="toproot center topRootSelect">
                	<a href="#" class="layer1">
				~else`
				 <li class="toproot center">
                	<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?checksum=~$sf_request->getAttribute('checksum')`&profilechecksum=~$sf_request->getAttribute('profilechecksum')`" class="layer1 cursp">
				~/if`
                    	<div class="fullwidth padleft22">
                        	<div class="fl bold_gnb" id="myProfileLoggedIn">
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
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?checksum=~$sf_request->getAttribute('checksum')`&profilechecksum=~$sf_request->getAttribute('profilechecksum')`&ownview=1&EditWhatNew=EduOcc">
                                    	<div class="paddallfive" id="editEducationOccLoggedIn">Edit Education and Occupation</div>
                                     </a>                                        
                                  </div>  
                                </li> 
								<li>
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?checksum=~$sf_request->getAttribute('checksum')`&profilechecksum=~$sf_request->getAttribute('profilechecksum')`&ownview=1&EditWhatNew=ContactDetails">
                                    	<div class="paddallfive" id="editContactLoggedIn">Edit Contact Details</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?checksum=~$sf_request->getAttribute('checksum')`&profilechecksum=~$sf_request->getAttribute('profilechecksum')`&ownview=1&EditWhatNew=FamilyDetails">
                                        <div class="paddallfive" id="editFamilyLoggedIn">Edit Family Details</div>
                                     </a>                                        
                                  </div>  
                                </li>  
                                
                                                            
                                 <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/social/addPhotos">
                                        <div class="paddallfive" id="uploadPhotosLoggedIn">Upload Photos</div>
                                     </a>                                        
                                  </div>  
                                </li>  
							</ul>
                        </div>
					 </div>
                </li>
                <li class="speratprwhite"></li>
                ~if sfConfig::get("mod_"|cat:$sf_context->getModuleName()|cat:"_"|cat:$sf_context->getActionName()|cat:"_highlight") eq 'dpp'`
                <li class="toproot center topRootSelect">
                	<a href="#" class="layer1">
				~else`
				<li class="toproot center">
                	<a href="~sfConfig::get('app_site_url')`/profile/dpp" class="layer1 cursp">
				~/if`
                    	<div class="fullwidth padleft22">
                        	<div class="fl bold_gnb" id="dppLoggedIn">
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
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/dpp?EditWhatNew=Dpp_Info">
                                    	<div class="paddallfive" id="editDppDescriptionLoggedIn">Edit Description</div>
                                     </a>                                        
                                  </div>  
                                </li>                                
                                <li style="margin-top:0px;">
                                  <div class="padallsix">
                                  	<a class="fullwidth cursp" href="~sfConfig::get('app_site_url')`/profile/dpp?EditWhatNew=Dpp_Details">
                                        <div class="paddallfive" id="EditDppBasicLoggedIn">Edit Basic Details</div>
                                     </a>                                        
                                  </div>  
                                </li> 
							</ul>
                        </div>
					 </div>
                </li>
                <li class="speratprwhite"></li>
                ~if $sf_context->getModuleName() eq 'membership'`
                <li class="topmenu center topRootSelect">
					~if $sf_request->getAttribute('subscriptionHeader') eq 1`
						<a href="#" class="padh2 bold_gnb" id="membershipLoggedIn">Upgrade Membership</a>
					~else`
						<a href="#" class="padh2 bold_gnb" id="membershipLoggedIn">Membership</a>
					~/if`
				</li>
                ~else`
                <li class="topmenu center">
					~if $sf_request->getAttribute('subscriptionHeader') eq 1`
						<a href="~sfConfig::get('app_site_url')`/profile/mem_comparison.php?from_source=Membership_page_link_just_above_search" class="padh2 cursp bold_gnb" id="membershipLoggedIn">Upgrade Membership</a>
					~else`
						<a href="~sfConfig::get('app_site_url')`/profile/mem_comparison.php?from_source=Membership_page_link_just_above_search" class="padh2 cursp bold_gnb" id="membershipLoggedIn">Membership</a>
					~/if`
				</li>
                ~/if`
                <li class="speratprwhite"></li>
                
                
		</ul>
<script>
if (window.location.protocol == "https:")
	    window.location.href = "http:" + window.location.href.substring(window.location.protocol.length);
</script>
