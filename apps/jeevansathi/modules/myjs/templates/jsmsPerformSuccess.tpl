<script type="text/javascript">
    var userGender="~$apiData.gender`",siteUrl="~$SITE_URL`";
	var tupleObject,tupleObject2,responseTrackingno="~JSTrackingPageType::MYJS_EOI_JSMS`",awaitingResponseNext=~if $apiData.interest_received.show_next eq ''`null~else`~$apiData.interest_received.show_next`~/if`,
                matchAlertNext=0,
		full_loaded = 0;
	$(window).load(function() {
		profile_completion("~$apiData.my_profile.completion`");

	});
        

	$(document).ready(function() {
		jsmsMyjsReady();
		var d = new Date();
		var hrefVal = $("#calltopSearch").attr("href")+"&stime="+d.getTime();
		$("#calltopSearch").attr("href",hrefVal);
                
    });


	function setNotificationView() {
                    $("#darkSection").toggleClass("posabs");
		$("#darkSection").toggleClass("tapoverlay");
		$("#notificationBellView").toggle();
        if ($("#mainContent").css("overflow")=="hidden") 
                   scrollOn();
               else scrollOff();
		
	};


	function onnewtuples(_parent) {
		if (_parent.page >= 0) {
                        if (_parent._isRequested) return ;
                        ++_parent.page;
			loadnew(_parent.page,_parent);
                        
		}
	};
      
</script>
<!--start:div-->
<div class="perspective" id="perspective">
<div class="" id="pcontainer">
<div class="fullwid bg1">
	<div class="pad1">
		<div class="rem_pad1">
			<div class="fl wid20p"> <i id ="hamburgerIcon" class="mainsp baricon " hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i> </div>
                        <div class="fl wid60p txtc color5  fontthin f19">Home</div>
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
<a href="~$SITE_URL`/profile/contacts_made_received.php?page=messages">	<div class="fullwid fontthin f14 color3 pad18 brdr1">
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
<a href="~$SITE_URL`/profile/contacts_made_received.php?page=photo&filter=R">	<div class="fullwid fontthin f14 color3 pad18 brdr1">
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
                
                <a href="~$SITE_URL`/profile/contacts_made_received.php?page=eoi&filter=R">
	<div class="fullwid fontthin f14 color3 pad18 brdr1">
		<div class="fl wid92p">
			<div class="fullwid txtc">People to Respond to</div>
		</div>
		~if $apiData.BELL_COUNT.AWAITING_RESPONSE_NEW>0`
		<div class="fr wid8p">
			<div class="bg7 brdr50p white f12 wid25 hgt25 pt4 txtc">~$apiData.BELL_COUNT.AWAITING_RESPONSE_NEW`</div>
		</div>
		~/if`
		<div class="clr"></div>
	</div>
                </a>
                <a href="~$SITE_URL`/profile/contacts_made_received.php?page=accept&filter=R">
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

</div>

<!--start:div-->
<input type="hidden" id="awaitingResponseCount" value="~$apiData.interest_received.tuples|@count`">
<input type="hidden" id="visitorCount" value="~$apiData.visitors.new_count`">
<input type="hidden" id="matchalertCount" value="~$apiData.match_alert.tuples|@count`">
<a href="#" onClick="setNotificationView();" id="darkSection"></a>
<div class="pad1 preload" id="profileDetailSection" style="overflow-x:scroll; width:100% ;white-space: nowrap; background-color: #e4e4e4; overflow-y: hidden;">
	<div class="row" style=" width:250%;">
        
           	<div class="cell brdr6" style="width:14%;">
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
    <div class="posrel pt20 pb20" style='background-image: url("~$IMG_URL`/images/band-image.jpg");'>
    <div class="posrel fullwid" style="top:0px; left:0px;">
    	<div class="clearfix" style="padding:0 30px 0;">
        	<div class="fl fontlig wid88p">
            	<div class="f24 white">~$apiData.membership_message.top|decodevar`</div>
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
	<a href="~$SITE_URL`/profile/contacts_made_received.php?page=accept&filter=R">	
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
<!--end:div-->
<!--eoi section-->
<span class="setWidth" id="awaitingResponsePresent" style="display:block;background-color: #e4e4e4; margin-top:15px;">
	~include_partial("myjs/jsmsAwaitingResponseSection",[eoiData=>$apiData.interest_received,gender=>$apiData.gender])`
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

<div id="hamburger" class="hamburgerCommon dn fullwid">	
	~include_component('static', 'newMobileSiteHamburger')`	
</div>
</div>
<script>~$pixelcode|decodevar`</script>
