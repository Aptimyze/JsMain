        <div id="detailsContactCompleteDiv"  class="fl prfwid17 pos-rel CEParent">
            <!--start:description-->
            <div class="fl prfwid2 pos-rel ht220">
          ~if $apiData['about']['introCallData']['OFFLINE_CALL_PROGRESS']`
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


                  <div class="clearfix fontlig color11">
		               
              			<div class="fl f24 wd300">
                       ~if $nameOfUser`
              				<span class="disp_ib textTru fixWidthOverflow">~$nameOfUser`</span>
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
              			</div>		             
		               
              			<div class="fr f12 opa50 pl10 mt10">~$apiData['about']['last_active']`</div>
    		           

                    </div>



              </div>
                <div class="pos-rel mt10 color11 fontlig pos-rel textTru">
                  <div class="pos-abs f12 colr5 prfpos3">~$apiData['about']['subscription_icon']`</div>
                  <ul class="prfdesc f14 clearfix">
                    <li class="textTru">~$apiData['about']['age']`,   ~$apiData['about']['height']`</li>
                    <li class="textTru">~$apiData['about']['educationOnSummary']`</li>
                    <li class="textTru">~$apiData['about']['location']`</li>
                    <li class="textTru">~$apiData['about']['work_status']['value']`</li>
                    <li class="textTru">~$apiData['about']['religion']`,  ~$apiData['about']['caste']`</li>
                    <li class="textTru">~$apiData['about']['income']`</li>
                    <li class="textTru">~$apiData['about']['mtongue']`</li>
                    <li class="textTru">~$apiData['about']['m_status']`</li>
                    ~if $apiData['about']['have_child'] neq ""`
                    <li class="textTru">~$apiData['about']['have_child']`</li>
                    ~/if`
                  </ul> 
                ~if $apiData['about']['verification_value'] neq "0"`
                <div class="pt10 fontlig">
                  <a href="/static/agentinfo">
                    <div class="f15 colr2 clearfix"> <i class="fl icons prfic7"></i>
                      <div class="fl pt1">Verified by visit</div>
                    </div>
                      ~if $apiData['about']['verification_value'] neq "1"`
                    <div class="color11 opa70 f12 pt5"> Documents provided: ~$apiData['about']['verification_value']` </div>
                    ~/if`
                  </a>
                </div>
                ~/if`
                ~if $apiData['about']['verification_value'] eq "0"`
                <div class="pt10 fontlig">
                  <div class="f15 colr2 clearfix">
                    <div class="fl pt1"></div>
                  </div>
                </div>
                ~/if`
                </div>
                  <div class="mt26"> 

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

                  <span class="disp_ib shareParent pos-rel pl10">
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
                  </div>
              </div>
            </div>
            
            <!--end:description--> 
            <!--start:link-->
            <div class="fr prfwid12 colrw fontlig f20"> 
              <div id="cEButtonsContainer-~$apiData['page_info']['profilechecksum']`-VDP" class="bg5">
                   
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
