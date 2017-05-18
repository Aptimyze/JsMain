        <div id="detailsContactCompleteDiv"  class="fl prfwid17 pos-rel CEParent">
            <!--start:description-->
            <div class="fl prfwid2 pos-rel ht220">
          ~if $apiData['about']['introCallData']['OFFLINE_CALL_PROGRESS'] || $apiData['button_details']['contactType'] eq 'E' || $apiData['button_details']['contactType'] eq 'D' || $apiData['button_details']['contactType'] eq 'C' || $SAMEGENDER`
          ~elseif ($apiData['about']['introCallData']['OFFLINE_ASSISTANT_ADD']||$apiData['about']['introCallData']['OFFLINE_ASSISTANT_REM']) && $apiData['about']['introCallData']['introCallDetail']['PURCHASED']`
            <!--start:we talk for you added-->  
            <div class="pos-abs" style="top:80%; right:30px;">
              <div class="pos-rel wetalk z1">
                  <div class="sprite2 ~if $apiData['about']['introCallData']['OFFLINE_ASSISTANT_ADD']`prfic40 js-txt1 cursp~else`prfic41~/if` pos-rel z2 js-div1"></div>
                    <div class="pos-abs z1 wetlk2 prfc1 js-div2">
                        ~if $apiData['about']['introCallData']['OFFLINE_ASSISTANT_ADD']`
                        <div class="colrw f13 fontlig prflh1 pl20 pr30 bg5 prfrad2 cursp js-txt1 js-div3">Add to 'we talk for you' list</div>
                        ~else`
                        <div class="colrw f13 fontlig prflh1 pl20 pr30 bg5 prfrad2 prfbg7 js-div3">Added to 'we talk for you' list</div>
                        ~/if`
                    </div>
              </div>
                
          </div>          
          <!--end:we talk for you added-->
          ~/if`
              <div class="prfp3">
                
                <div class="clearfix prfbr1 pb2">


                                <div class="fl fontlig color11 fullwid">
			       ~if $nameOfUser`	
					<span class="disp_ib fixWidthOverflow f24">~$nameOfUser`</span>
				       <span class="f15 vertSuper">(~$apiData['about']['username']`)</span>
				~else`
					<span class="disp_ib f24">
					 ~$apiData['about']['username']`
				       </span>
                        ~if $loginProfileId`
                                <span class="verified">
                                        <i class="quesIcon"></i>
                                        <span class="hoverDiv">
                                                <div class="f14 fontreg blueColor">~$dontShowNameReason`
                                                </div>
                                        </span>
                                </span>
                        ~/if`
                       ~/if`
                 ~if $apiData['about']['verification_value'] neq "0"`
                                    <span class="verified3" style="position:relative;">

                                    	<i class="verIcon js-verificationPage "></i>

                                    	<span class="hoverDiv3 js-verificationPage">

                                    		<div class="f14 fontreg blueColor">Verified by visit</div>
                       ~if $apiData['about']['verification_value'] neq "1"`
                                   			<div class="f12 pt10 fontreg lightgrey ">Documents provided:</div>
                                    		<ul id="appendDocs" class="f12 fontreg lightgrey ">
							~assign var=docCount value=$apiData['about']['verification_value_arr']|@count-1`
							~foreach from=$apiData['about']['verification_value_arr'] item=val key=k`
								~if $k neq $docCount`
								<li>~$val`,</li>
								~else`
								<li>~$val`</li>
								~/if`
							~/foreach`
                                    		</ul>
			~/if`

                                    		<a href="/static/agentinfo" class="f11 fontreg blueColor z999 cursp pt10 verKnowMore">Know More</a>

                                    	</span>

                                    </span>
		~/if`



                                    <span class="disp_ib fr f12 opa50 pl10 mt10">~$apiData['about']['last_active']`</span></div>


              </div>
                <div class="pos-rel mt10 color11 fontlig pos-rel textTru">
                  <div class="pos-abs f12 colr5 prfpos3">~$apiData['about']['subscription_icon']`</div>
                  <ul class="prfdesc f14 clearfix">
                    <li class="textTru">~$apiData['about']['age']`,   ~$apiData['about']['height']`</li>
                    <li class="textTru">~$apiData['about']['educationOnSummary']`</li>
                    <li class="textTru">~$apiData['about']['location']`</li>
                    <li class="textTru">~$apiData['about']['work_status']['value']`</li>
                    
                    ~if $apiData["lifestyle"]["religion_value"] eq "2" && $apiData["family"]["caste"] neq ""`
                     <li class="textTru">~$apiData['about']['religion']`,  ~$apiData['about']['caste']`, ~$apiData["family"]["caste"]`</li>
                     ~else`
                    <li class="textTru">~$apiData['about']['religion']`,  ~$apiData['about']['caste']`</li>
                    ~/if`
                    <li class="textTru">~$apiData['about']['income']`</li>
                    <li class="textTru">~$apiData['about']['mtongue']`</li>
                    <li class="textTru">~$apiData['about']['m_status']`</li>
                    ~if $apiData['about']['have_child'] neq ""`
                    <li class="textTru">~$apiData['about']['have_child']`</li>
                    ~/if`
                  </ul> 
                </div>
                  <div class="pt20 wid50p disp_ib fl"> 

                                          <span class="disp_ib pos-rel communicationToolTip">
                    ~if !$loginProfileId`
                        <span class="disp_ib sprite2 prfic5 cursp loginLayerJspc"></span> 
                    ~else`
                    <span class="disp_ib sprite2 prfic5 cursp communicationParent"></span> 
                    ~/if`
                    <!--start:tooltip-->
                    <div class="communicationChild">
                      <div class="boxtip3 colrw fontlig prfp8 wd125">
                        Communication History
                      </div>                                    
                    </div>
                    <!--end:tooltip--> 
                  </span>

                  <span class="disp_ib shareParent pos-rel pl15">
                  ~if !$loginProfileId`
                    <span class="disp_ib sprite2 prfic6 cursp loginLayerJspc"></span>
                  ~else`
                    <span class="disp_ib sprite2 prfic6 cursp share js-action"></span> 
                  ~/if`
                    <!--start:tooltip-->
                    <div class="shareChild">
                      <div class="boxtip4 colrw fontlig prfp8 wd70">
                        Share Profile
                      </div>                                    
                    </div>
                    <!--end:tooltip--> 
                  </span>

                  <span class="disp_ib ignoreParent pos-rel pl15">
                  ~if $apiData['page_info']['is_ignored']`
                                <span id="IGNORE-~$apiData['page_info']['profilechecksum']`-VDP-IGNORE" class=" cEIgnoreDetailProfile" data="&ignore=0" data-chat="~$apiData['other_profileid']`,UNBLOCK">
                ~else`
                  <span id="IGNORE-~$apiData['page_info']['profilechecksum']`-VDP-IGNORE" class=" ~if $loginProfileId`cEIgnoreDetailProfile~/if`" data="&ignore=1" data-chat="~$apiData['other_profileid']`,BLOCK">
                ~/if`
                  ~if !$loginProfileId`
                    <span class="disp_ib spriteIgnoreReport prfic51 cursp loginLayerJspc"></span>
                  ~else`
                    <span class="disp_ib spriteIgnoreReport prfic51 cursp ignore"></span> 
                  ~/if`
                    <!--start:tooltip-->
                    <div class="ignoreChild">
                      <div id="ignoreProfileToolTip" class="boxtip6 txtc colrw fontlig prfp8">
                        ~if $apiData['page_info']['is_ignored']`
                        Unblock Profile 
                        ~else`
                        Block Profile
                        ~/if`
                      </div>                                    
                    </div>
                    <!--end:tooltip--> 
                  </span>
                  </span>

                  <span class="disp_ib reportParent pos-rel pl15">
                  ~if !$loginProfileId`
                    <span class="disp_ib spriteIgnoreReport prfic52 cursp loginLayerJspc"></span>
                  ~else`
                    <span class="disp_ib spriteIgnoreReport prfic52 cursp report js-action"></span> 
                  ~/if`
                    <!--start:tooltip-->
                    <div class="reportChild">
                      <div class="boxtip5 colrw fontlig prfp8 wd74">
                        Report Profile
                      </div>                                    
                    </div>
                    <!--end:tooltip--> 
                  </span>


                  </div>
                  ~if $showIdfy`
                   <div  id="idfyDiv" class="idfyDiv2 disp_ib fr wid223 mt16 fl"><span class="color5 f13 fontlig idfyText ml3">Get details of this user verified</span><i class="idfyIcon"></i></div>
                  ~/if`
              </div>
            </div>
            ~if $apiData['about']['gender'] eq "Male"`
              ~assign var=userGender value="M"`
            ~else`
              ~assign var=userGender value="F"`
            ~/if`
            <!--end:description--> 
            <!--start:link-->
            <div class="fr prfwid12 colrw fontlig f20"> 
              <div id="cEButtonsContainer-~$apiData['page_info']['profilechecksum']`-VDP" class="bg5 pcChatHelpData" data-pcChat="~$apiData['about']['username']`,~$apiData['page_info']['profilechecksum']`,~$userGender`">
                   
              </div>          
            </div>
            <!--end:link--> 
        </div>
        
~include_partial('global/JSPC/_jspcContactEngineButtons')`
<script>
	var finalResponse= ~$finalResponse|decodevar`;
	var cEObject="";
	var isIgnored=finalResponse.page_info.is_ignored;
	$(document).ready(function(){		
		if(finalResponse!=="undefined")
		{
			cEObject= new ContactEngineCard("VDP");
			var cEHtml=cEObject.buttonDisplay(finalResponse.button_details,finalResponse.page_info);
			$("#cEButtonsContainer-"+finalResponse.page_info.profilechecksum+'-VDP').html(cEHtml);
		}
		cECommonBinding();
	});
</script>
