  <!--start:face Card section-->
<div id="faceCard" class="disp-none">
<li id='{{list_id}}' class="pos-rel">
<div class="pos-abs fullwid myjs-block f17 myjs-pos170 fontlig txtc lh50 sendintr"> <a onClick={{POST_ACTION_1}} class="colr5 cursp fontreg">{{ACTION_1_LABEL}}</a> </div>
<div class="bg-white" >
	<a href ={{DETAILED_PROFILE_LINK}} onClick ={{GA_TRACKING_FOR_PHOTO_VIEW}}>
	<div id={{PROFILE_FACE_CARD_ID}} class="pos-rel">
  <div class="pos-abs fontlig myjs-pos10 cursp  {{albumHide}}">
<div class="disp-tbl opaclr1 myjs-br3 colrw txtc" style="height:30px;width:30px;">
<div class="vmid disp-cell disp-none js-albumCount">{{js-AlbumCount}}</div>
</div>
</div>
	<div class='myjs-dim10 mauto scrollhid'><img class='imageReplace' oncontextmenu="return false;" onmousedown="return false;"  style='width:220px;' {{PHOTO_URL}} src="~$otherPhotoUrl`" ></div></div>
	<div class="myjs-10 color11 fontlig f14 lh22">
  <div class="clearfix">
	<div class="textTru fontreg f15 fl" style="max-width:180px">{{AGE}}, {{HEIGHT}}, {{LOCATION}}</div>
  </div>
  <p class="textTru">{{MTONGUE}}, {{CASTE}} </p>
  <p class="textTru">{{EDUCATION_STR}}</p>
  <p class="textTru">{{INCOME}}, {{OCCUPATION}}</p>
</div>
</a>
</div>
</div>
</li>
</div>
<!--end:face Card section-->

<div id="SMSLoader" class='disp-none'>
<div style="color: #d9475c" class="la-ball-pulse-sync la-sm">
    <div></div>
    <div></div>
    <div></div>
</div>
</div>



<!--start:face Card section-->
<div id="interestReceivedCard" class="disp-none">
<li id='{{list_id}}' class="pos-rel">
<div class="bg-white blockp" >
<div class="pos-abs fullwid myjs-pos11 myjs-z_ind clearfix myjs-block intdisp">
                  <div class="widtabp fl lh50 txtc intbrd1 acceptButton">
                      <div onClick="{{ACCEPT_LINK}}" class="colr5 ctrl fontreg">Accept</div>
                    </div>
                    <div class="widtabp fr lh50 txtc declineButton ">
                      <div onclick="{{DECLINE_LINK}}" class="colr5 ctrl fontreg">Decline</div>
                    </div>
                </div>

  <a href ="{{DETAILED_PROFILE_LINK}}">
  <div id="{{PROFILE_FACE_CARD_ID}}" class="pos-rel">
  <div class="pos-abs myjs-pos10 cursp {{albumHide}}">
<div class="disp-tbl opaclr1 myjs-br3 colrw txtc" style="height:30px;width:30px;">
<div class="vmid disp-cell js-albumCount">{{js-AlbumCount}}</div>
</div>
</div>
  <div class='myjs-dim6 mauto scrollhid'><img style='width:200px;' 
oncontextmenu="return false;" onmousedown="return false;" class='imageReplace' src="~$otherPhotoUrl`" {{PHOTO_URL}}></div></div>
  <div class="myjs-10 color11 fontlig f14 lh22"  style="text-align:left;">
  <div class="clearfix">
  <div class="textTru fontreg f15 fl" style="max-width:180px">{{AGE}}, {{HEIGHT}}, {{LOCATION}}</div>
  </div>
  <p class="textTru">{{MTONGUE}}, {{CASTE}} </p>
  <p class="textTru">{{EDUCATION_STR}}</p>
  <p class="textTru">{{INCOME}}, {{OCCUPATION}}</p>
</div>
</a>
</div>
</li>
</div>
<!--end:face Card section-->

<!--start:small face Card section-->
<div id="smallCard1" class="disp-none">
<li id='{{list_id}}' style="width:72px; height:72px; border-radius:50%">
<a href ={{DETAILED_PROFILE_LINK}}>
<div class='mauto scrollhid' style="width:72px; height:72px; border-radius:50%"><img style='width:72px;' id={{PROFILE_SMALL_CARD1_ID}} src="~$otherthumbnail`" {{PHOTO_URL}} class='imageReplace' 
oncontextmenu="return false;" onmousedown="return false;" /></div></a>
</li>
</div>
<!--end:small face Card section-->

<!--start:small plus face Card section-->
<div id="smallCard2" class="disp-none">
<li id='{{list_id}}' id="{{PROFILE_SMALL_CARD2_ID}}" class="pos-rel">
            <a href="{{LISTING_LINK}}">
            <div class="myjs-ovrlay myjs-dim5 disp-tbl txtc bdr-rad2">
              <div class="disp-cell vmid colrw fontreg f30">+{{COUNT}}</div>
            </div></a>
            <img src="~$otherthumbnail`"  {{PHOTO_URL}} class='imageReplace' 
oncontextmenu="return false;" onmousedown="return false;" /> </li>
</div>
<!--end:small plus face Card section-->



<!--start:View All Face Card section-->
<div id="viewAllCard" class="disp-none">
	<li>
		<div class="pos-rel myjs-dim9">
			<div class="pos-abs z4">
				<div class="disp-tbl myjs-dim9 txtc">
					<div class="disp-cell vmid"><a id ="idForViewAllCard" href="{{LISTING_LINK}}" class="f30 fontreg colrw">View All</a></div>
				</div>
			</div>
			~if $gender eq 'M'`
			<img class="myjs-dim9 blur viewAllImageFemale"/>
			~else`
			<img class="myjs-dim9 blur viewAllImageMale"/>
			~/if`
		</div>
    </li>
</div>




<div id="messageCard" class="disp-none">
<li id='{{list_id}}'>
<div  class="myjs-p8">
<!--start:div-->
<div class="clearfix fontlig">
<!--start:left-->
<a href ={{DETAILED_PROFILE_LINK}}>
<div class="fl txtc">
<div class='myjs-dim8  bdr-rad2 mauto scrollhid'><img style='width:100px;' {{PHOTO_URL}} src="~$otherthumbnail`" class='imageReplace' 
oncontextmenu="return false;" onmousedown="return false;" ></div></div>
</a>
<!--end:left-->
<!--start:right-->
<div class="fr myjs-m1 wid70p">
<div class="clearfix fullwid pb5">
<a href ={{DETAILED_PROFILE_LINK}}>
<div class="fl f20 color11 textTru cursp" style="max-width:95px">{{USERNAME}} </div></a>
<div id="MessageCount" class="fl f20 color11 {{CountShow}}"><b> {{MESSAGE_COUNT}}</b></div>
<div class="fr colr2 f12 pt10">{{MESSAGE_DATE}}</div>
</div>
<p class="colr2 fontlig f13">{{MESSAGE}}
</p>
</div>
<!--end:left-->
</div>
<!--end:div-->
<!--start:text box-->
<form id="{{MSG_ID}}" action="#" class="acctxt {{DISP_1}}">
<div class="fullwid txtclass1 mt30">
<div class="padall-10">

<textarea id = "{{text_area_id}}" class="fontlig fullwid brdr-0 bgnone" onclick="{{POST_ACTION_MSG_ERROR}}" placeholder="{{textPlaceholder}}" {{active}}></textarea>
</div>
</div>
<div class="brdr-0 bgnone fontrobbold f15 {{color}} pt10">
<a id="{{SEND_BUTTON_ID}}" class="{{opa40}} {{color}} cursp" onclick="{{POST_ACTION_MSG}}">{{button}}</a><span id="{{BlankMsg_MSG}}" class="disp-none fontlig fullwid brdr-0 bgnone color11"></span></div>
</form>
<div id="{{MESSAGE_RESPONSE_ID}}" class='fontlig color11 f16 pt55 txtc disp-none'>Message has been sent.</div>
<!--end:text box-->
</div>
</li>
</div>



<div id="acceptanceCard" class="disp-none">
<li id='{{list_id}}'  class="myjs-h5">
<div id={{PROFILE_ACCEPTANCE_CARD_ID}} class="myjs-p8">
<!--start:div-->
<div class="clearfix fontlig">
<!--start:left-->
<div class="fl txtc"> <a href ={{DETAILED_PROFILE_LINK}}><div class='myjs-dim8  bdr-rad2 mauto scrollhid'><img style='width:100px;' {{PHOTO_URL}} src="~$otherPhotoUrl`" class='imageReplace' 
oncontextmenu="return false;" onmousedown="return false;" ></div></a>
<a>
<p class="colr5 f13 cursp" onclick="{{POST_ACTION_VIEWCONTACT}}">View Contact</p></a>
</div>
<!--end:left-->
<!--start:right-->
<div class="fr myjs-m1 wid70p">
<div class="clearfix fullwid brdrg-7 pb5">


  <a href ={{DETAILED_PROFILE_LINK}}><div class="fl f20 color11 textTru" style="max-width:95px">{{USERNAME}} </div></a>
	
  <div class="fl f12 colr2  pt9 pl10">{{ONLINE_STR}}</div>
	
  <div class="fr colr5 f12 pt10">{{SUBSCRIPTION_STATUS}}</div>

</div>


<ul class="hor_list clearfix myjslist2 color11 pt10">
<li>{{AGE}},  {{HEIGHT}}</li>
<li class="textTru">{{EDUCATION_STR}}</li>
<li class="textTru">{{LOCATION}}</li>
<li class="textTru">{{OCCUPATION}}</li>
<li class="textTru">{{CASTE}}</li>
<li class="textTru">{{INCOME}}</li>
<li class="textTru">{{MTONGUE}}</li>
<li class="textTru">{{MARITAL_STATUS}}</li>
</ul>
</div>
<!--end:left-->
</div>
<!--end:div-->
<!--start:text box-->
<form id="{{ACCEPTANCE_ID}}" action="#" class="acctxt">
<div class="fullwid txtclass1 mt30">
<div class="padall-10">

<textarea id = "{{text_area_id}}" class="fontlig fullwid brdr-0 bgnone" onclick="{{POST_ACTION_MSG_ERROR}}" placeholder="{{textPlaceholder}}" {{active}}></textarea>
</div>
</div>
<div class="brdr-0 bgnone fontrobbold f15 {{color}} pt10"><a id="{{SEND_BUTTON_ID}}" class="{{opa40}} {{color}} cursp" onclick="{{POST_ACTION_MSG}}">{{button}}</a><span id="{{BlankMsg_MSG}}" class="disp-none fontlig fullwid brdr-0 bgnone color11"></span></div>
</form>
<div id="{{MESSAGE_RESPONSE_ID}}" class='fontlig color11 f16 pt55 disp-none'>Message has been sent.</div>
<div id="{{contactDivId}}" class="fullwid mt26 clearfix color12 f12 fontlig myjs-brd8 disp-none">
	<div id="{{handled_contact}}" class="fullwid clearfix pt10">
    
		<div class="fl wid90p pt4"><span id="{{postedById}}" class="disp_ib"></span>
      ~if LoggedInProfile::getInstance()->getISD() eq '91'`
    <span  class="SMSContactsDiv fr colr5 disp-none cursp">SMS these details to me</span>~/if`</div> 
		<div class="fr "><a onclick="{{POST_ACTION_VIEWCONTACT_CLOSE}}"><i class="sprite2 myjs-close2 cursp"></i></a></div>
	</div>
	<div id="{{phone_view_Contact}}" class="fullwid clearfix pt20">
		<div class="fl pt2">Phone:</div>
		<div class="fl myjs-wid88 lh18"><span id="{{contact1}}" class="disp_ib pl10"></span><span id="{{contact2}}" class="disp_ib pl10"></span><span id="{{contact3}}" class="disp_ib pl10"></span></div>
	</div>

	<div id="{{email}}" class="fullwid clearfix pt10 ">
	
		<div class="fr wid88p"></div>
	</div>
</div>
<!--end:text box-->
</div>
</li>
</div>

<div id="noSmallCard" class="disp-none">
	<div class="mt15 fullwid">
      <div class="pt25">
                <div class=" disp-tbl">
					<div id="{{ID}}" class="disp-cell"><i class="sprite2 myjs-erric"></i></div>
					<div class="disp-cell vmid f22 fontlig color14">{{NO_PROFILE_TEXT}}</div>
				</div>
      </div>
    </div>
</div>

<div id="noFaceCard" class="disp-none">
	<div id="head{{ID}}" class="myjs-bg3 fullwid">
      <div style='padding:30px 25px;'>
                <div class=" disp-tbl mauto">
					<div id="{{ID}}" class="disp-cell"><i class="sprite2 myjs-erric {{display}}"></i></div>
					<div class="disp-cell vmid pl10 f22 fontlig color14">{{NO_PROFILE_TEXT}}</div>
				</div>
      </div>
    </div>
</div>


<div id="noEngagementCard" class="disp-none">
<div class="myjs-bg2 fullwid pt45">
      <div class="myjs-p9">
                <div class=" disp-tbl mauto wid70p">
					<div id="{{ID}}" class="disp-cell"><i class="sprite2 myjs-erric"></i></div>
					<div class="disp-cell vmid pl10  f24 fontlig color15 txtc">{{NO_PROFILE_TEXT}}</div>
				</div>
      </div>
    </div>
</div>

<div class="fl bg-white myjs-h2 disp-none" id='filteredCard_dummy'>
              <div class="clearfix fullwid">
                <ul class="hor_list clearfix filt">
                <a href="" class="filteredAnchor vishid"><li><div class='myjs-dim7 mauto scrollhid'><img src="" style='width:96px;' class="filteredImage " oncontextmenu="return false;" onmousedown="return false;"></div></li></a>
                <a href=""  class="filteredAnchor vishid"> <li><div class='myjs-dim7 mauto scrollhid'><img src="" style='width:96px;' class=" myjs-dim7 filteredImage " oncontextmenu="return false;" onmousedown="return false;"></div></li></a>
                <a href=""  class="filteredAnchor vishid"> <li><div class='myjs-dim7 mauto scrollhid'><img src="" style='width:96px;' class=" myjs-dim7 filteredImage " oncontextmenu="return false;" onmousedown="return false;"></div></li></a>
                  <li class="pos-rel" style='margin-right:0px;'>
                   <a href='/inbox/12/1'> <div id='filteredMoreCount' class="disp-none myjs-ovrlay2 pos-abs myjs-dim7 txtc">
                      <div class="disp-cell vmid colrw fontreg f30">+<span></span></div>
                    </div></a>
                  <a href='' class="filteredAnchor vishid"><div class='myjs-dim7 mauto scrollhid'><img src="" style='width:96px;' class="myjs-dim7  filteredImage " oncontextmenu="return false;" onmousedown="return false;"></div></a>
                  </li>
                </ul>
                <a href="/inbox/12/1" >
                <div class="txtc pt13 pr5 pl5">
                  <p class="f15 fontreg colr5">Filtered Interests</p>
                  <p class="f13 fontlig color11 pt10">These profiles have sent you interest</p>
                </div>
                </a>
              </div>
        
          </div>



                <div id='interestsNoResultDiv' class='disp-none'>  
      <div class="fl bg-white myjs-h2 myjs-fulwid" id='noInterestsCard'> 
                    <div class="txtc pt100">
                        <p class="f20 colr5">Interests</p>
                        <p class="f15 color11 mauto wid144 pt20">Interests you receive will appear here.</p>
                    </div> 
              </div>
              <!--end:div--> 
              <!--start:div-->
              <div class="fl bg-white myjs-h2 myjs-fulwid" id='noFilteredCard'> 
                    <div class="txtc pt100">
                        <p class="f20 colr5">Filtered Interests</p>
                        <p class="f15 color11 mauto wid177 pt20">These are interests from people who don't match your filters.</p>
                    </div> 
              </div>
              <!--end:div--> 
              <!--start:case 1 div-->
              <div class="fr" id='infoCardDouble'>
                  <div class="myjs-bg5 fullwid myjs-h2" style='height:320px;'>
                    <div class="myjs-p13 clearfix fontreg">
                        <!--start:div-->
                        <div class="fl txtc myjs-wid12 mt20">
                          <p class="color11 f15">~$staticCardArr[0]['head']`</p>
                            <p class="f12 colr2 pt15 mauto wid80p">~$staticCardArr[0]['msg']`</p>
                            <p><a href="~$staticCardArr[0]['url']`" class="f12 myjs-colr2">Know more</a></p>                           
                        </div>
                        <!--end:div-->
                         <!--start:div-->
                        <div class="fl txtc myjs-wid12 mt20">
                          <p class="color11 f15">~$staticCardArr[1]['head']`</p>
                            <p class="f12 colr2 pt15 mauto wid80p">~$staticCardArr[1]['msg']`</p>
                            <p><a href="~$staticCardArr[1]['url']`" class="f12 myjs-colr2">Know more</a></p>                            
                        </div>
                        <!--end:div-->
                        <div class="fl txtc myjs-wid5 pt30 pb30" style='clear:both;'>
                          <div class="mauto myjs-wid13 myjs-bdr5"></div>
                        </div>
                        <div class="fl txtc myjs-wid5 pt30 pb30">
                          <div class="mauto myjs-wid13 myjs-bdr5"></div>
                        </div>
                         <!--start:div-->
                        <div class="fl txtc myjs-wid12">
                          <p class="color11 f15">~$staticCardArr[2]['head']`</p>
                            <p class="f12 colr2 pt15 mauto wid80p">~$staticCardArr[2]['msg']`</p>
                            <p><a href="~$staticCardArr[2]['url']`" class="f12 myjs-colr2">Know more</a></p>
                        </div>
                        <!--end:div-->
                         <!--start:div-->
                        <div class="fl txtc myjs-wid12">
                          <p class="color11 f15">~$staticCardArr[3]['head']`</p><p class="f12 colr2 pt15 mauto wid80p">~$staticCardArr[3]['msg']`</p>
                            <p><a href="~$staticCardArr[3]['url']`" class="f12 myjs-colr2">Know more</a></p>
                            </div>
                            </div></div></div></div>





                          <div class="fl myjs-bg5 disp-none" id='infoCardSingle' style="min-height:320px;">
            <div class="myjs-p7">
              <ul class="fontlig myjsli1">
                <li class='myjs-fulwid'>
               <a href="~$staticCardArr[0]['url']`">   <p>~$staticCardArr[0]['head']`</p> </a>
                </li>
                <li class='myjs-fulwid'>
                  <a href="~$staticCardArr[1]['url']`"><p>~$staticCardArr[1]['head']`</p></a>
                </li>
                <li class='myjs-fulwid'>
                  <a href="~$staticCardArr[2]['url']`"><p>~$staticCardArr[2]['head']`</p></a>
                </li>
                <li class='myjs-fulwid'>
                  <a href="~$staticCardArr[3]['url']`"><p>~$staticCardArr[3]['head']`</p></a>
                </li>
              </ul>
            </div>
          </div>
