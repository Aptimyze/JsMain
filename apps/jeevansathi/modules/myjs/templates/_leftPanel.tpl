<div class="pt10_v1">
	<div class="lpbrder_v1 lpbg1_v1 wid242_v1">
        	<nav>
	              	<!--start:inbox-->
                        <div class="padall_10_v1 probrdrbtm_v1">
        	       		<a href="#" class="txtlpcolor_v1 fntsize16_v1 txtdeco_v1 ftbld_v1">Inbox</a>
                       	</div> 
                        <div class="inboxlist_v1">
                             	<ul>
                                   	<li>
                                        	<a class="txt5color_v1" href="~$SITE_URL`/inbox/1/1">
                                            	<div class="fl_v1 wid200_v1 fntsize14_v1 colorh padright5_v1 ">Interests Received</div>
						~if $countObj["INTEREST_RECEIVED"] gt 0`
                                                	<div class="fl_v1 wid16_v1 txtalign_c_v1">
                                        			<div class="brdr1_v1 bg1 fnt12_v1">~$countObj["INTEREST_RECEIVED"]`</div>
                                                	</div>
						~/if`
                                                <div class="clr_v1"></div>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="txt5color_v1" href="~$SITE_URL`/inbox/2/1">
                                                <div class="fl_v1 wid200_v1 fntsize14_v1 colorh padright5_v1">Acceptances</div>
						~if $countObj["ACCEPTANCES_RECEIVED"] gt 0`
	                                                <div class="fl_v1 wid16_v1 txtalign_c_v1">
        	                                            <div class="brdr1_v1 bg1 fnt12_v1">~$countObj["ACCEPTANCES_RECEIVED"]`</div>
                	                                </div>
                        			~/if`
			                        <div class="clr_v1"></div>
                                            </a>
                                        </li>   
                                        <li>
                                            <a class="txt5color_v1" href="~$SITE_URL`/inbox/4/1">
                                                <div class="fl_v1 wid200_v1 fntsize14_v1 colorh padright5_v1">Messages Received</div>
						~if $countObj["MESSAGE_RECEIVED"] gt 0`
							<div class="fl_v1 wid16_v1 txtalign_c_v1">
                                                	    <div class="brdr1_v1 bg1 fnt12_v1">~$countObj["MESSAGE_RECEIVED"]`</div>
                                                	</div>
						~/if`
                                                <div class="clr_v1"></div>
                                            </a>
                                        </li> 
                                        <li>
                                            <a class="txt5color_v1">
                                                <div class="fl_v1 wid200_v1 fntsize14_v1 colorh padright5_v1">Requests Received</div>
                                                ~if ($countObj["HOROSCOPE_REQUEST_RECEIVED"]+ $countObj["PHOTO_REQUEST_RECEIVED"]) gt 0`
							<div class="fl_v1 wid16_v1 txtalign_c_v1">
								<div class="brdr1_v1 bg1 fnt12_v1">~$countObj["HOROSCOPE_REQUEST_RECEIVED"]+$countObj["PHOTO_REQUEST_RECEIVED"]`</div>
                                                	</div>
						~/if`
                                                <div class="clr_v1"></div>
                                            </a>
                                        </li>  
                                        <li>
                                            <a class="txt5color_v1" href="~$SITE_URL`/inbox/10/1">
                                                <div class="fl_v1 wid200_v1 fntsize14_v1 colorh padright5_v1">Decline Received</div>
						~if $countObj["DECLINE_RECEIVED"] gt 0`
	                                                <div class="fl_v1 wid16_v1 txtalign_c_v1">
								<div class="bg2 fnt12_v1">~$countObj["DECLINE_RECEIVED"]`</div>
                                                	</div>
						~/if`
                                                <div class="clr_v1"></div>
                                            </a>
                                        </li>  
                        	</ul>                                
                	</div>                               
                        <!--end:inbox-->
                        <!--start:matches-->
                        <div class="padall_10_v1 probrdrbtm_v1">
                        	<a href="#" class="txtlpcolor_v1 fntsize16_v1 txtdeco_v1 ftbld_v1">Matches</a>
                        </div>
                        <div class="inboxlist_v1">
                        	<ul>
                                	<li>
                                            <a>
                                                <div class="fl_v1 wid200_v1 fntsize14_v1 colorh_v1 padright5_v1">2-way Matches</div>
                                                <div class="fl_v1 wid16_v1 txtalign_c_v1">
                                                    
                                                </div>
                                                <div class="clr_v1"></div>
                                            </a>
                                        </li>
                                        <li>
                                            <a>
                                                <div class="fl_v1 wid200_v1 fntsize14_v1 colorh_v1 padright5_v1">Recommended Matches</div>
                                                <div class="fl_v1 wid16_v1 txtalign_c_v1">
                                                    <div class="bg2 fnt12_v1">1</div>
                                                </div>
                                                <div class="clr_v1"></div>
                                            </a>
                                        </li> 
                                         <li>
                                            <a>
                                                <div class="fl_v1 wid200_v1 fntsize14_v1 colorh_v1 padright5_v1">Kundli Matches</div>
                                                <div class="fl_v1 wid16_v1 txtalign_c_v1">
                                                    <div class="bg2 fnt12_v1">1</div>
                                                </div>
                                                <div class="clr_v1"></div>
                                            </a>
                                        </li>  
                                         <li>
                                            <a>
                                                <div class="fl_v1 wid200_v1 fntsize14_v1 colorh_v1 padright5_v1">Members I shortlisted</div>
                                                <div class="fl_v1 wid16_v1 txtalign_c_v1">
                                                  
                                                </div>
                                                <div class="clr_v1"></div>
                                            </a>
                                        </li>                     
                                </ul>
                        </div>                            
                        <!--end:matches-->
                        <!--start:activity-->
                        <div class="padall_10_v1 probrdrbtm_v1">
                                    <a href="#" class="txtlpcolor_v1 fntsize16_v1 txtdeco_v1 ftbld_v1">Activity</a>
                        </div>
                        <div class="inboxlist_v1">
                        	<ul>
                                      
                                        <li>
                                            <a>
                                                <div class="fl_v1 wid200_v1 fntsize14_v1 colorh_v1 padright5_v1">Members who visited my profile</div>                                           		 <div class="fl_v1 wid16_v1 txtalign_c_v1">
                                                    <div class="bg2 fnt12_v1">1</div>
                                                </div>
                                                <div class="clr_v1"></div>
                                            </a>
                                        </li>    
                                        <li>
                                            <a>
                                                <div class="fl_v1 wid200_v1 fntsize14_v1 colorh_v1 padright5_v1">Profile Updates of contacted members</div>
                                                 <div class="fl_v1 wid16_v1 txtalign_c_v1">
                                                    <div class="bg2 fnt12_v1">1</div>
                                                </div>
                                                <div class="clr_v1"></div>
                                            </a>
                                        </li>  
                                                                  
                                </ul>
                        </div>                            
                        <!--end:activity-->
		</nav>                        
	</div>                    
</div>
