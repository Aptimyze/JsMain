<script type="text/javascript">
    var userGender="~$apiData.gender`",siteUrl="~sfConfig::get('app_site_url')`";
    var myjsdata = ~$jsonData|decodevar`;
    var responseTrackingno="~JSTrackingPageType::MYJS_EOI_JSMS`",awaitingResponseNext=~if $apiData.interest_received.show_next eq ''`null~else`~$apiData.interest_received.show_next`~/if`, completionScore="~$apiData.my_profile.completion`";
    var hamJs= '~$hamJs`';
    var showExpiring=~$showExpiring`;
    var showMatchOfTheDay=~$showMatchOfTheDay`;
    var pageMyJs=~$pageMyJs`;   
    var myJsCacheTime = 60000;//in microseconds
</script>
<!--start:div-->
<div class="perspective" id="perspective">
<div class="" id="pcontainer">
<div class="fullwid bg1">
	<div class="pad1">
		<div class="rem_pad1">
			<div class="fl wid20p">                             
                            <div id="hamburgerIcon" hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1">
                                <i class="loaderSmallIcon dn"></i>
                            <svg id="hamIc" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><defs><style>.cls-1{fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.2px;}</style></defs><title>icons</title><line class="cls-1" x1="2" y1="3.04" x2="18" y2="3.04"/><line class="cls-1" x1="2.29" y1="10" x2="18.29" y2="10"/><line class="cls-1" x1="2" y1="16.96" x2="18" y2="16.96"/></svg>
                            </div>
                        </div>
                        <div id='myJsHeadingId' class="fl wid60p txtc color5  fontthin f19">Home</div>
                                                <div class="fr">
				<div class="fullwid">
					<div class="fl padr15 posrel">
						<a href="#" id="notificationView" onClick="setNotificationView();return false;"><i class="mainsp bellicon"></i>
            <div class="posabs pos1" style="top:-6px;">
              <div class="posrel"> ~if $apiData.BELL_COUNT.TOTAL_NEW>0`
                <div class="disptbl oval"> 
                	<div class="dispcell vertmid color6 f11 txtc">~$apiData.BELL_COUNT.TOTAL_NEW`</div>
                </div>~/if`
		
</div>
</div>
</a>
					</div>
                <a id="calltopSearch" href="~$SITE_URL`/search/topSearchBand?isMobile=Y"><div class="fl"> <i class="mainsp srchicon"></i> </div></a>
				</div>

			</div>
			<div class="clr"></div>
		</div>
	</div>
	<div class="clr"></div>
</div>
<!--end:div-->
<div class="bg4" id="notificationBellView" style="display:none">
    
    <a href="~$SITE_URL`/search/perform?justJoinedMatches=1">
    <div class="fullwid fontthin f14 color3 pad18 brdr1">
		<div class="fl wid92p">
			<div class="fullwid txtc">Just Joined Matches</div>
		</div>
		~if $apiData.BELL_COUNT.NEW_MATCHES>0`
		<div class="fr wid8p">
			<div class="bg7 brdr50p white f12 wid25 hgt25 pt4 txtc">~$apiData.BELL_COUNT.NEW_MATCHES`</div>
		</div>
		~/if`
		<div class="clr"></div>
	</div>
    </a>
<a href="~$SITE_URL`/inbox/4/1">	<div class="fullwid fontthin f14 color3 pad18 brdr1">
		<div class="fl wid92p">
			<div class="fullwid txtc">Messages</div>
		</div>
		~if $apiData.BELL_COUNT.MESSAGE_NEW>0`
		<div class="fr wid8p">
			<div class="bg7 brdr50p white f12 wid25 hgt25 pt4 txtc">~$apiData.BELL_COUNT.MESSAGE_NEW`</div>
		</div>
		~/if`
		<div class="clr"></div>
	</div>
</a>
<a href="~$SITE_URL`/inbox/9/1">	<div class="fullwid fontthin f14 color3 pad18 brdr1">
		<div class="fl wid92p">
			<div class="fullwid txtc">Photo Requests</div>
		</div>
		~if $apiData.BELL_COUNT.PHOTO_REQUEST_NEW>0`
		<div class="fr wid8p">
			<div class="bg7 brdr50p white f12 wid25 hgt25 pt4 txtc">~$apiData.BELL_COUNT.PHOTO_REQUEST_NEW`</div>
		</div>
		~/if`
		<div class="clr"></div>
	</div>
</a>
                
                <a href="~$SITE_URL`/inbox/1/1">
	<div class="fullwid fontthin f14 color3 pad18 brdr1">
		<div class="fl wid92p">
			<div class="fullwid txtc">Interests Received</div>
		</div>
		~if $apiData.BELL_COUNT.AWAITING_RESPONSE_NEW>0`
		<div class="fr wid8p">
			<div class="bg7 brdr50p white f12 wid25 hgt25 pt4 txtc">~$apiData.BELL_COUNT.AWAITING_RESPONSE_NEW`</div>
		</div>
		~/if`
		<div class="clr"></div>
	</div>
                </a>
                <a href="~$SITE_URL`/inbox/2/1">
	<div class="fullwid fontthin f14 color3 pad18 brdr1">
		<div class="fl wid92p">
			<div class="fullwid txtc">Members who Accepted me</div>
		</div>
		~if $apiData.BELL_COUNT.ACC_ME_NEW>0`
		<div class="fr wid8p">
			<div class="bg7 brdr50p white f12 wid25 hgt25 pt4 txtc">~$apiData.BELL_COUNT.ACC_ME_NEW`</div>
		</div>
		~/if`
		<div class="clr"></div>
	</div>
                </a>
                  <a href="~$SITE_URL`/inbox/10/1">	<div class="fullwid fontthin f14 color3 pad18 brdr1">
		<div class="fl wid92p">
			<div class="fullwid txtc">Declined/Cancelled</div>
		</div>
		~if $apiData.BELL_COUNT.DEC_ME_NEW>0`
		<div class="fr wid8p">
			<div class="bg7 brdr50p white f12 wid25 hgt25 pt4 txtc">~$apiData.BELL_COUNT.DEC_ME_NEW`</div>
		</div>
		~/if`
		<div class="clr"></div>
	</div>
</a>
           
           <a href="~$SITE_URL`/inbox/12/1">
	<div class="fullwid fontthin f14 color3 pad18 brdr1">
		<div class="fl wid92p">
			<div class="fullwid txtc">Filtered Interests</div>
		</div>
		~if $apiData.BELL_COUNT.FILTERED_NEW>0`
		<div class="fr wid8p">
			<div class="bg7 brdr50p white f12 wid25 hgt25 pt4 txtc">~$apiData.BELL_COUNT.FILTERED_NEW`</div>
		</div>
		~/if`
		<div class="clr"></div>
	</div>
                </a>

              

</div>

<!--start:div-->
<input type="hidden" id="awaitingResponseCount" value="~$apiData.interest_received.tuples|@count`">
<input type="hidden" id="visitorCount" value="~$apiData.visitors.new_count`">
<input type="hidden" id="matchalertCount" value="~$apiData.match_alert.tuples|@count`">
<input type="hidden" id="matchOfDayCount" value="~$apiData.match_of_the_day.tuples|@count`">
<a href="#" onClick="setNotificationView();" id="darkSection"></a>
<div class="pad1 preload" id="profileDetailSection" style="overflow-x:scroll; width:100% ;white-space: nowrap; background-color: #e4e4e4; overflow-y: hidden;">
	<div class="row" style=" width:250%;">
        
           	<div class="cell brdr6" style="width:16%;">
                     <div class="fullwid pad12" id="jsmsProfilePic">
				<div style="position:relative; float:left;">


					<div class="hold hold1">
						<div class="pie pie1">

						</div>
					</div>

					<div class="hold hold2">
						<div class="pie pie2"></div>
					</div>

					<div class="bg"> </div>

                                      
                                        <img class="image" src="~$apiData.my_profile.photo`" border="0" />
                                      
				</div>

				<div class="fl  color7 fontlig padl10 pt16" id="percent"></div>
				<div class="clr"></div>
			</div>
		</div>
                                       		~foreach from=$apiData.my_profile.incomplete item=element key=id`

		 <div class="cell brdr6 vtop pad13" style="width:10.75%; ">
			<div class="txtc ">
		<a href="~$element.url`">		<div style="height:35px;"><i class="mainsp ~$element.cssClass`"></i>
				</div>
                                                </a>

                        <div class="f12 color7 fontlig">~$element.title`</div>
			</div>
		</div>
		~/foreach`




	</div>
</div>
<!--end:div-->
<!--start:div-->
<!--MembershipMessageStarts-->
~if $apiData.membership_message neq ''`
<a href="~$IMG_URL`~$apiData.membership_message_link`">
    <div class="posrel pt20 pb20 newBgBand">
    <div class="posrel fullwid" style="top:0px; left:0px;">
    	<div class="clearfix" style="padding:0 30px 0;">
        	<div class="fl fontlig wid88p">
            	<div class="f24 white">~$apiData.membership_message.top|decodevar`</div>
            	~if $apiData.membership_message.extra && $apiData.membership_message.extra neq ""`
                	<div class="f14 white">~$apiData.membership_message.extra|decodevar`</div>
                ~/if`
                <div class="f14 white">~$apiData.membership_message.bottom|decodevar`</div>
            </div>
            <div class="fr wid10p">
            	<div style="padding-top:26px"><i class="mainsp" style="background-position: -323px -168px;width: 17px;height: 27px;"></i></div>
            </div>
        
        </div>
    </div>
  </div>
                </a>
~/if`
<!--MembershipMessageEnds-->
<div class="bg4 pad1" id="acceptanceCountSection">
	<div class="fullwid pad2">
	<a href="~$SITE_URL`/inbox/2/1">	
            <div class="fl wid49p txtc">
			~if $apiData.all_acceptance.view_all_count neq 0`
				
                        <div class="row bg7 wid75 hgt75 brdr50p posrel" id="acceptedMe">
			    <div class="cell vmid white fullwid myjs_f30 fontlig">~$apiData.all_acceptance.view_all_count`</div>
				~if $apiData.all_acceptance.new_count neq 0`
				<div class="posabs pos3">
					<div class="bg10 txtc wid20 hgt20 brdr50p">
						<div class="white f12 fontlig pt1">~$apiData.all_acceptance.new_count`</div>
					</div>
				</div>
				~/if`
			</div>
			~else`
			<div class="row bg6 wid75 hgt75 brdr50p">
                            <div class="cell vmid white">--</div>
                            </div>
			~/if`
			<div class="f12 fontlig color7 pt10">
				<p>All</p>
				<p> Acceptances</p>
			</div>
		</div>
                                                 </a>
	
                        <a href="~$SITE_URL`/search/perform?justJoinedMatches=1">
                       <div class="fl wid49p txtc">
			~if $apiData.just_joined_matches.view_all_count neq 0`
			     <div class="row bg7 wid75 hgt75 brdr50p posrel" id="iAccepted">
                            
					<div class="cell vmid white myjs_f30 fontlig">~$apiData.just_joined_matches.view_all_count`</div> 
					~if $apiData.just_joined_matches.new_count neq 0`
					<div class="posabs pos3">
						<div class="bg10 txtc wid20 hgt20 brdr50p">
							<div class="white f12 fontlig pt1"> 
							~if $apiData.just_joined_matches.new_count gt 99`
							99+
							~else`
							~$apiData.just_joined_matches.new_count`
							~/if`
							</div>
						</div>
					</div>
					~/if`
			</div>
                       
			~else`
                            <div class="row bg6 wid75 hgt75 brdr50p">
                                    <div class="cell vmid white">--</div>
			</div>
                           
                        ~/if`
			<div class="f12 fontlig color7 pt10">
				<p>Just</p>
				<p>Joined Matches</p>
			</div>
                        </div> </a>
                        
		<div class="clr"></div>
	</div>
</div>

<!-- Interest Expiring section -->
~if $apiData.interest_expiring.view_all_count > 0`
	~include_partial("myjs/jsmsInterestExpiringSection",[expiringData=>$apiData.interest_expiring])`
~/if`
<!--end:div-->
<!--eoi section-->
<span class="setWidth" id="awaitingResponsePresent" style="display:block;background-color: #e4e4e4; margin-top:15px;">
	~include_partial("myjs/jsmsAwaitingResponseSection",[eoiData=>$apiData.interest_received,gender=>$apiData.gender])`
</span>

<span id="matchOfDayPresent"  class="setWidth" style="display:block;background-color: #e4e4e4; margin-top:15px;">
	~if $showMatchOfTheDay eq 1`
		~include_partial("myjs/jsmsMatchOfTheDaySection",[matchOfDay=>$apiData.match_of_the_day,gender=>$apiData.gender])`
	~/if`
</span>

<span class="setWidth"  id="visitorPresent" style="background-color: #e4e4e4; margin-top:15px;">
	~include_partial("myjs/jsmsVistorsSection",[visitorData=>$apiData.visitors])`
</span>
<span id="matchalertPresent"  class="setWidth" style="display:block;background-color: #e4e4e4; margin-top:15px;">
	~include_partial("myjs/jsmsMatchalertSection",[matchalertData=>$apiData.match_alert,gender=>$apiData.gender])`
</span>

<span id="browseMyMatchBand" style="display:block; background-color: #e4e4e4;">
	~include_partial("myjs/jsmsBrowseMyMatchesBand")`
</span>
<span id="awaitingResponseAbsent" style="background-color: #e4e4e4;display:none;">
	~include_partial("myjs/jsmsAwaitingResponseSectionAbsent",[eoiData=>$apiData.interest_received])`
</span>
<span id="visitorAbsent" style="display:none;background-color: #e4e4e4;">
	~include_partial("myjs/jsmsVistorsSection",[visitorData=>$apiData.visitors])`
</span>
<span id="matchalertAbsent" style="display:none;background-color: #e4e4e4;">
	~include_partial("myjs/jsmsMatchalertSection",[matchalertData=>$apiData.match_alert])`
</span>
~include_component('common', 'notificationLayerJsms')`	
</div>
</div>
<script>~$pixelcode|decodevar`</script>


