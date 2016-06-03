        <div id="detailsContactCompleteDiv"  class="fl prfwid17 pos-rel CEParent bg-white">
            <!--start:description-->
            <div class="fl prfwid2 pos-rel ht220">
              <div class="prfp3">
                <div class="clearfix prfbr1 pb2">
                  <div class="fl fontlig color11"><span class="disp_ib f24">~$apiData['about']['username']`</span><span class="disp_ib f12 opa50 pl10">~$apiData['about']['last_active']`</span></div>
                </div>
                <div class="pos-rel mt10 color11 fontlig pos-rel textTru">
                    <div class="pos-abs f12 colr5 prfpos3">~$apiData['about']['subscription_icon']`</div> 
                  <ul class="prfdesc f14 clearfix">
                    <li class="truncate">~$apiData['about']['age']`,   ~$apiData['about']['height']`</li>
                    <li class="truncate">~$apiData['about']['education']`</li>
                    <li class="truncate">~$apiData['about']['location']`</li>
                    <li class="truncate">~$apiData['about']['work_status']['value']`</li>
                    <li class="truncate">~$apiData['about']['religion']`,  ~$apiData['about']['caste']`</li>
                    <li class="truncate">~$apiData['about']['income']`</li>
                    <li class="truncate">~$apiData['about']['mtongue']`</li>
                    <li class="truncate">~$apiData['about']['m_status']`</li>
                    ~if $apiData['about']['have_child'] neq ""`
                    <li class="truncate">~$apiData['about']['have_child']`</li>
                    ~/if`
                  </ul>
                </div>
                ~if $apiData['about']['verification_value'] neq "0"`
                <div class="pt10 fontlig">
                  <div class="f15 colr2 clearfix"> <i class="fl icons prfic7"></i>
                    <div class="fl pt1">Verified by visit</div>
                  </div>
                    ~if $apiData['about']['verification_value'] neq "1"`
                  <div class="color11 opa70 f12 pt5"> Documents provided: ~$apiData['about']['verification_value']` </div>
                  ~/if`
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
            </div>
            
            <!--end:description--> 
            <!--start:link-->
            <div class="fr prfwid12 colrw fontlig f20"> 
              <div id="cEButtonsContainer-~$apiData['page_info']['profilechecksum']`-VSP_VDP" class="bg5">
                   
              </div>          
            </div>
            <!--end:link--> 
        </div>
        
<script>
	var finalResponse= ~$finalResponse|decodevar`;
	//var info={"profileChecksum":"~$apiData['page_info']['profilechecksum']`"};
	var cEObject="";
	$(document).ready(function(){		
		if(finalResponse!=undefined && finalResponse!=null )
		{
			cEObject= new ContactEngineCard("VSP_VDP");
			var cEHtml=cEObject.buttonDisplay(finalResponse.button_details,finalResponse.page_info);
			$("#cEButtonsContainer-"+'~$apiData['page_info']['profilechecksum']`-VSP_VDP').html(cEHtml);
			var actions_buttonsVSP=~$actions_buttonsVSP|decodevar`;
			if(actions_buttonsVSP!=0)
			{
				var buttonObj=new Button($("#cEButtonsContainer-"+'~$apiData['page_info']['profilechecksum']`-VSP_VDP'),1);
				buttonObj.setPostActionData(actions_buttonsVSP);
				buttonObj.post();
			}
			
		}
		cECommonBinding();
	});
</script>
