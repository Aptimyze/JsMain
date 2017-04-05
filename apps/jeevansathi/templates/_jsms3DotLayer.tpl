
<div class="posfix z112 dispnone"  id="reportInvalidContainer">
                    <div class="fullwid fontlig" >
                        <data></data><div class="photoheader">
                            <div class="pad16 brdr_new" style="height:85px;">
                                <div class="rem_pad1 posrel fullwid ">
                                    <img id="photoReportInvalid" class="srp_box3 abs_c" src="">
                                    <div style="position:absolute; top:16px;" class="white fullwid fontthin f19 txtc">Report Invalid</div>
                                    <div id="savedSearchIcon" class="posabs " style="top:18px;right: 0;" onclick="hideReportInvalid()">
                                        <div class="posrel"> <i class="mainsp com_cross"></i>
                                        </div>
                                    </div>
                                    <div class="clr"></div>
                                </div>
                            </div>
                        </div>
                        <div id="reportInvalidMidDiv" style="width:200%;overflow:auto;">
                        
                        <div class="selectOptions reportInvalidScreen fl" id="js-reportInvalidMainScreen" style="height:100%;">
                            <i class="mainsp arow_new dispibl"></i>
                            <ul class="f16 fontthin white">
                                <li class="white fullwid dispibl dashedBorder pad18">Reason for reporting invalid </li>

                                <li id = "reasonCodeOption" class="reportInvalidOption dispibl dashedBorder pad3015 fullwid" value="1">
                                	<div class="fullwid posrel">
                                		Switched off / Not reachable
                                		<img class="RAcorrectImg dispnone" src="/images/jsms/commonImg/correct.png">
                                	</div>
                                </li>
                                <li class="reportInvalidOption dispibl dashedBorder pad3015 fullwid" value="2">
                                	<div class="fullwid posrel">
                                	Not an account holder's phone
                                	<img class="RAcorrectImg dispnone" src="/images/jsms/commonImg/correct.png">
                                	</div>
                                </li>
                                <li class="reportInvalidOption dispibl dashedBorder pad3015 fullwid" value="3">
                                	<div class="fullwid posrel">
                                	 Already married / engaged<img class="RAcorrectImg dispnone" src="/images/jsms/commonImg/correct.png">
                                	</div>
                                </li>

                                <li class="reportInvalidOption dispibl dashedBorder pad3015 fullwid" value="4">
                                	<div class="fullwid posrel">
                                	Not picking up<img class="RAcorrectImg dispnone" src="/images/jsms/commonImg/correct.png">
                                	</div>
                                </li>

                                <li class="reportInvalidOption dispibl dashedBorder pad3015 fullwid" id="js-otherInvalidReasons">
                                	<div class="fullwid posrel">
                                	Other reasons (please specify)<img class="RAcorrectImg dispnone" src="/images/jsms/commonImg/correct.png">
                                	</div>
                                </li>
                            </ul>
                        </div>
                        <div class="reportInvalidScreen" style="height:100%">
                        <textarea class=" pad18 fullheight fullwid f18 fontthin" id="js-otherInvalidReasonsLayer" style="background: none;height: 100%" placeholder="Describe your concern for this number"></textarea>
                        </div>
                        </div>
                        <div class="posfix fullwid scrollhid pos1_c1">
                            <div id="reportInvalidSubmit" class="bg7 white lh30 fullwid dispbl txtc lh50">Report Invalid</div>
                        </div>
                        </div>
                </div> 

<div class="posfix z112 dispnone"  id="reportAbuseContainer">
                    <div class="fullwid fontlig" >
                        <div class="photoheader">
                            <div class="pad16 brdr_new" style="height:85px;">
                                <div class="rem_pad1 posrel fullwid ">
                                    <img id="photoReportAbuse" class="srp_box3 abs_c" src="">
                                    <div style="position:absolute; top:16px;" class="white fullwid fontthin f19 txtc">Report Abuse</div>
                                    <div id="savedSearchIcon" class="posabs " style="top:18px;right: 0;" onclick="hideReportAbuse()">
                                        <div class="posrel"> <i class="mainsp com_cross"></i>
                                        </div>
                                    </div>
                                    <div class="clr"></div>
                                </div>
                            </div>
                        </div>
                        <div id="reportAbuseMidDiv" style="width:200%;overflow:auto;">
                        
                        <div class="selectOptions reportAbuseScreen fl" id="js-reportAbuseMainScreen" style="height:100%;">
                            <i class="mainsp arow_new dispibl"></i>
                            <ul class="f16 fontthin white">
                                <li class="white fullwid dispibl dashedBorder pad18">Please tell us why you are reporting this profile </li>

                                <li class="reportAbuseOption dispibl dashedBorder pad3015 fullwid">
                                	<div class="fullwid posrel">
                                		Looks like fake profile
                                		<img class="RAcorrectImg dispnone" src="/images/jsms/commonImg/correct.png">
                                	</div>
                                </li>
                                <li class="reportAbuseOption dispibl dashedBorder pad3015 fullwid">
                                	<div class="fullwid posrel">
                                	Inappropriate content
                                	<img class="RAcorrectImg dispnone" src="/images/jsms/commonImg/correct.png">
                                	</div>
                                </li>
                                <li class="reportAbuseOption dispibl dashedBorder pad3015 fullwid">
                                	<div class="fullwid posrel">
                                	Spam<img class="RAcorrectImg dispnone" src="/images/jsms/commonImg/correct.png">
                                	</div>
                                </li>
                                <li class="reportAbuseOption dispibl dashedBorder pad3015 fullwid" id="js-otherReasons">
                                	<div class="fullwid posrel">
                                	Other reasons (please specify)<img class="RAcorrectImg dispnone" src="/images/jsms/commonImg/correct.png">
                                	</div>
                                </li>
                            </ul>
                        </div>
                        <div class="reportAbuseScreen">
                        <textarea class="dispnone pad18 fullheight fullwid f18 fontthin" id="js-otherReasonsLayer" style="background: none;" placeholder="Describe your concern for this profile"></textarea>
                        </div>
                        </div>
                        <div class="posfix fullwid scrollhid pos1_c1">
                            <div id="reportAbuseSubmit" class="bg7 white lh30 fullwid dispbl txtc lh50">Report Abuse</div>
                        </div>
                        </div>
                </div>



    <div class="posrel forHide" style="display:none; z-index: 110;" id="commonOverlay">
	<a href class="contact_dialog_overlay" onClick="popBrowserStack();return false;"> </a>
	<!--start:options-->
	<div class="srpoverlay_2 top_r1" id="commonOverlayTop">
		<input type="hidden" id="selIndexId" value="" />

		<!--top section starts here-->
		<div id="3DotProPic" class="txtc">
			<div id = "photoIDDiv" style="border: 1px solid rgb(255,255,255);border: 1px solid rgba(255,255,255,0.2);  overflow:hidden; width: 90px; height: 90px; border-radius: 45px;"><img id="ce_photo"  class="srp_box2 mr6"/></div>
			<div class="f14 white fontlig opa80 pt10 forHide" id="topMsg"></div>
                        <div class="f16 pt10 lh25 fontlig white opa80 forHide" id="topMsg2" style='padding-left:15px; padding-right:15px'></div>

		</div>
		<!--top section ends here-->

		<!--Buttons template starts here-->
		<div class="fullwid forHide" id="buttonsOverlay">
				<div class="clr hgt_r2"></div>
            <div class="wid49p txtc fl forHide" style="display:none;" id="otherbutton0">
				<i class="mainsp msg_srp2" id="otherimage0"></i>
				<div class="f14 white fontlig lh30" id="otherlabel0"></div>
			</div>
			<div class="wid49p txtc fl forHide" style="display:none;" id="otherbutton1">
			<!--div class="wid49p txtc fl" style="display:block;" id="CONTACTDETAIL_1"-->
				<i class="mainsp vcontact" id="otherimage1"></i>
				<div class="f14 white fontlig lh30" id="otherlabel1"></div>
				<!--div class="f14 white fontlig lh30" id="CONTACTDETAIL_LABEL_1">View Contact</div-->
			</div>
			<div class="clr hgt_r2"></div>
			<!--div class="wid49p txtc fl opa50" style="display:none;" id="otherbutton2"-->
			<div class="wid49p txtc fl forHide" style="display:none;" id="otherbutton2">
				<i class="mainsp srtlist" id="otherimage2"></i>
				<div class="f14 white fontlig lh30" id="otherlabel2"></div>
			</div>
			<div class="wid49p txtc fl forHide" style="display:none;" id="otherbutton3">
				<i class="mainsp ignore"></i>
				<div class="f14 white fontlig lh30" id="otherlabel3"></div>
			</div>
			<div class="clr hgt_r2"></div>
			<div class="wid49p txtc fl forHide" style="display:none;" id="otherbutton4">
				<i class=""></i>
				<div class="f14 white fontlig lh30" id="otherlabel4"></div>
			</div>
			<div class="clr hgt_r2"></div>
			<div style="margin-bottom:10px;" class="fullwid txtc"> <a href="#" onClick="popBrowserStack();return false;" class="mainsp srp_close1 dispbl"></a> </div>
		</div>
		<!--Button template ends here-->

                <!--start: loader display -->
                <div class="fullwid pad18 txtc f16 opa80 fontlig white pt10" id="loaderDisplay" style="display:none">
			<img src="/images/jsms/commonImg/loader.gif" class="srp_box2 mr6"/>
                </div>
                <!--end: loader display -->

		<!--Confirmation template-->
		<div class="fullwid pad18 txtc f16 opa80 fontlig white pt10 forHide" id="confirmationOverlay">
			<div class="fontthin f18 forHide" id="confirmMessage0"></div>
			<div class="lh30 top20px forHide" id="confirmMessage1"></div>
			<div  class="lh30 top20px forHide" id="confirmMessage2"></div>
			<div  class="lh30 top20px forHide" id="confirmMessage3"></div>
		</div>
		<!--Confirmation template ends here -->

		<!--start: Error message overlay-->
    		<div class="fullwid pad1 txtc forHide" id="errorMsgOverlay" style="display:none">
         		<div class="pt20 white f18 fontthin" id="errorMsgHead">
		        </div>        
    		</div>
  		<!--end: Error message overlay--> 

		<!--Contact detail template starts-->
		<div class="fullwid fontlig pad1 forHide" id="contactDetailOverlay" style="display:none; overflow-y: auto;">
			<!--start:mobile no.-->
			<div class="pt15" id="mobile" style="display:none">
				<div class="fl white">
					<div class=" f14 lh30 opa50">Mobile no </div>
					<div class="f16 forHide" id="mobileVal" style="display:none"> </div> 
					<div class="pb20 forHide" id="mobileValBlur" style="display:none;"><img src="/images/blurredtext.png"></div>
					<div></div>
				</div>
				<div id="mobileIcon" class="fr pt15 forHide" style="display:none;"><a href=""><i  class="mainsp srp_phnicon" ></i></a></div>
				<div class="clr"></div>
			</div>        
			<!--end:mobile no.-->
			<div class="pt15" id="ViewContactPreLayer" style="padding-top: 20%; display:none;">
    <p id="ViewContactPreLayerText" style="
    color: #fff;
    text-align: center;"></p>
			</div>

			<div class="pt15" id="ViewContactPreLayerNoNumber" style="padding-top: 20%; display:none;">
    <p id="ViewContactPreLayerTextNoNumber" style="
    color: #fff;
    text-align: center;"></p>
			</div>


			<!--start:landline no.-->
			<div class="pt15 forHide" id="landline" style="display:none">
				<div class="fl white">
					<div class=" f14 lh30 opa50" >Landline no</div>
					<div class="f16 forHide" id="landlineVal" style="display:none"> </div>
					<div class="pb20" id="landlineValBlur" style="display:none"><img src="/images/blurredtext.png"></div>
					<div></div>
				</div>
				<div id ="landlineIcon" class="fr pt15 forHide" style="display:none;"><a href=""><i class="mainsp srp_phnicon"></i></a></div>
				<div class="clr"></div>
			</div>        
			<!--end:landline no.-->
			<!--start:mobile no.-->
			<div class="pt15 forHide" id="alternate" style="display:none">
				<div class="fl white">
					<div class=" f14 lh30 opa50">Alternate no</div>
					<div class="f16 forHide" id="alternateVal" style="display:none"></div>
					<div class="pb20 forHide" id="alternateValBlur" style="display:none"><img src="/images/blurredtext.png"></div>
					<div></div>
				</div>
				<div id ="alterIcon" class="fr pt15 forHide" style="display:none;"><a href=""><i class="mainsp srp_phnicon"></i></a></div><div class="clr"></div>
			</div>
			<!--end:Alternate no.-->
			<!--start:Email-->
			<div class="pt15 forHide" id="email" style="display:none">
				<div class="fl white">
					<div class=" f14 lh30 opa50">Email</div>
					<div class="f16 forHide" id="emailVal" style="display:none"></div>
					<div class="pb20 forHide" id="emailValBlur" style="display:none"><img src="/images/blurredtext.png"></div>
					<div></div>
				</div>
				<div class="fr pt15 forHide" id="msgIcon" style="display:none;"><a href=""><i  class="mainsp srp_msg1" ></i></a></div>
				<div class="clr"></div>
			</div>        

			<div class="txtc"><a href="#" class=" pb20 white fontlig f16 forHide opa50 forHide" id="bottomMsg2" style="display:none; margin:20px 9px;"></a></div>
			<!--end:Email-->
		</div>
    		<!--Contact detail template ends here-->


		<!--Footer section-->
		<div class="posfix btmo fullwid" id="bottomElement">
     		<div class="pt15">
    			<div class="txtc"><a href="#" class=" pb20 white fontlig f16 forHide" id="bottomMsg" style="display:none;"></a></div>
    			<a href="#" class="dispbl brdr22 white txtc f16 pad2 fontlig forHide" id="closeLayer" style="display:none;border-top: 1px solid rgb(255, 255, 255);border-top: 1px solid rgba(255, 255, 255, .2);-webkit-background-clip: padding-box; /* for Safari */ background-clip: padding-box; " onClick="popBrowserStack();return false;">Close</a>
        		<a href="#" class="dispbl white txtc f16 pad2 fontlig forHide" id="neverMindLayer" style="display:none;" onClick="popBrowserStack();return false;">Never Mind</a>
        		<a href="javascript:void(0);" class="brdr23_contact dispbl color2 txtc f16 pad2 fontlig forHide" id="membershipMessageCE" style="display:none;"></a>
        		<a href="#" class="dispbl bg7 white txtc f16 pad2 fontlig forHide" id="footerButton" style="display:none"></a>
    		</div>
		</div>
		<!--Footer section ends here-->

	</div>
<img src="/images/jsms/membership_img/revamp_bg1.jpg" class="posfix classimg1 bgset"/>
</div>
<div class="posrel fullwid fullheight overlayPos forHide" id="membershipOverlay" style="display:none;">
    <img src="/images/jsms/membership_img/revamp_bg1.jpg" class="posfix classimg1 bgset">
    <div class="fullheight fullwid layerOpa posrel" style="overflow:auto;">
        <div class="memOverlay app_clrw" style="padding-bottom:50px">
            <div class="txtc">
                <div id="photoIDDiv" class="photoDiv">

                <img id="profilePhoto" class="srp_box2 mr6" src="http://mediacdn.jeevansathi.com/1255/13/25113359-1411734648.jpeg">
                </div>
                <div class="pad2 f16 fontlig" id="newErrMsg"></div>
                <div class="pad20 f16 fontlig mt15" id="membershipheading"></div>
                <ul class=" memList f13 fontlig">
                    <li class="tick pad21" id="subheading1"></li>
                    <li class="tick pad21" id="subheading2"></li>
                    <li class="tick pad21" id="subheading3"></li>
                </ul>
                <div id="MembershipOfferExists" style="display: none">
                    <div class="pad45_0 f16 fontlig" id="membershipOfferMsg1"></div>
                    <div class="f16 pad20 fontmed" id="membershipOfferMsg2"></div>
                </div>

            <div class="f16 fontlig" id="LowestOffer" style="display: none">Lowest Membership starts @<del id="oldPrice" style="display: none"></del>&nbsp;<span id="currency"></span>&nbsp;<span id="newPrice"></span></div>
            </div>
 
        </div>
        
    </div>
    <div id="footerDiv" class="posfix fullwid btmo" style="background:black">
            <a href="#" id="skipLayer" class="f16 fontmed app_clrw txtc posSkip" onClick="popBrowserStack();return false;">Skip</a>
            <div class="bg7">

            <a href="#" id="footerButtonNew" class="fullwid dispbl lh50 txtc f17 fontlig white"></a>
            </div>
    </div> 


</div>

<!-- start :Write Message Overlay -->
<div class="forHide" style="display:none; z-index:110;position:relative;" id="writeMessageOverlay">
	<a href class="contact_dialog_overlay" onClick="popBrowserStack();return false;"> </a>
        <!--start:options-->
        <div class="srpoverlay_2" style="top:0px">
    		<!--start:top header -->
    		<div class="pad18 brdr4" id="comm_headerMsg">
      			<div class="posrel clearfix fontthin hdrHght_con">
        			<div class="posabs com_left1"> 
					<img id="imageId" src="" class="com_brdr_radsrp" style="width:50px; height:50px;"/>
				</div>
        			<div class="posabs com_right1"> 
					<i class="mainsp com_cross" onClick="popBrowserStack();return false;"></i>
				</div>
        			<div class="txtc f19 white pt10" id="usernameId">Akansha</div>
      			</div>
    		</div>
    		<!--end: top header --> 
    		<div class="message_con" id="msgId">
        		<!--start:right align preset message -->
          		<div class="forHide" id="presetMessageId"> 
            			<div class="padl30_contact fontlig f16 white com_pad1" id="presetMessageDispId">  
					<span id="presetMessageTxtId">
						Interest sent. You may send a personalized message with the interest.	
					</span> 
					<span class="dispbl f12 color1 pt5 white" id="presetMessageStatusId"></span>
				</div>
          		</div>
        		<!--end:right align preset message-->
        		<!--start:left align write message -->
			<div class="forHide" id= "writeMsgDisplayId"  style="display:none">
                            
			</div>
                        
			<div style="height:1px" id="setMsgHght"></div>
        		<!--end:left align write message--> 
    		</div>
 		<!--start:footer message box -->
 		<div class="forHide fullwid white lineH_con posabs" id="freeMsgId" style="display:none">
                		Become a paid member to connect further	        
                	</div>
		<div id="parentFootId">
                	<div class="forHide white color2 lineH_con brdr23_contact" id="CEmembershipMessage2" style="display:none"></div>
	                <div class="fullwid clearfix forHide" id="comm_footerMem">
	                        <a href="/profile/mem_comparison.php" class="dispbl bg7 white txtc f16 pad20_con fontlig forHide" id="memTxtId"></a>
	                </div>          
	 		<div class="fullwid clearfix brdr23_contact btmsend txtAr_bg1 posfix btmo" id="comm_footerMsg">
	    			<div class="fl wid80p com_pad3">
	        			<textarea id="writeMessageTxtId" class="fullwid lh15 inp_1 white" onClick="setTextAreaHgt()" /></textarea>
	        		</div>
	        		<div class="fr com_pad4">
	        			<a href="#" class="color2 f16 fontlig" onClick="updateReminder('writeMessageTxtId')">Send</a>
	        		</div>
	    		</div>
	                <div class="forHide posfix btmo fullwid" id="crossButId" style="display:none;">
        	        	<a href="#" class="dispbl brdr22 white txtc f16 pad2 fontlig" id="closeLayer" onClick="popBrowserStack();return false;">Close</a>
        	        </div>
		</div>
    		<!--end: footer message box -->
	</div>
	<img src="/images/jsms/membership_img/revamp_bg1.jpg" class="classimg1 bgset"/>
</div>
<!--end: Write Message Overlay  -->
<div id="contactLoader" class="tapoverlayContact posabs" style="display:none;width:100%;">
    <img src="/images/jsms/commonImg/loader.gif"/>
</div>
    <div class="posrel forHide" style="display:none; z-index: 115;" id="loaderOverlay">
        <a href class="loaderOverlayDialog" onClick="return false;"> </a>
    </div>

