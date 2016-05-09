        <!--start:share this profile-->
  <div id="share-layer" class="pos_fix layersZ setshare disp-none">
      <div class="prfwid16 fontlig">
        <div class="prfbg6">
                <!--start:div-->          
              <div class="">
                <div id="shareProfileTopSection" class="clearfix shrp1">
                    <div class="prfrad prfdim8 prfbr6 fl">
                        <img src="~$apiData['about']['thumbnailPic']`" border="0" class="prfdim5 prfrad prfm2"/> 
                    </div>
                      <div class="fl ml10 prfbr7 pb10 f13 color11 wid80p pt16">
                        ~$apiData['about']['username']`    -    <span class="colr2"> Share ~if $apiData["about"]["gender"] eq "Female"`her~else`his~/if` profile</span>
                      </div>
                  </div>
                     <!--start:form-->
                    <div id="shareProfileDiv" class="f13">
                        <div id="share">
                            <form>
                              <div class="shrp3">
                                <label>Email     <span id="errorText" class="color5 disp-ib pl10"></span></label>   
                                <input id="receiverEmail" type="text" placeholder="Enter receiver’s email" class="mt5 fullwid brdr-0 bgnone color11 fontlig"/>                 
                                </div>
                                <div class="shrp4">
                                 <label>Your name</label>   
                                <input id="senderName" type="text" placeholder="Enter name" class="mt5 fullwid brdr-0 bgnone color11 fontlig"/>                 
                                </div>
                                 <div class="shrp4">
                                 <label>Say something</label>   
                                <textarea id="message" type="text" placeholder="Your message" class="mt5 fullwid brdr-0 bgnone color11 fontlig hgt102 outlineBorderBoxNone"></textarea>                 
                                </div>
                                <div class="pt30 clearfix">
                                  <div class="fl bg_pink wid50p shhgt1 txtc pos-rel cursp js-validateEmail" id="validateSenderEmail"><i class="sprite2 chkicon setshare pos-abs"></i></div>
                                    <div class="fl bg5 wid50p shhgt1 txtc pos-rel cursp undoShare js-undoAction"><i class="sprite2 shcross setshare pos-abs"></i></div>
                                
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--end:form-->
                    <div id="confirmationMessage" class="f15">
                      <div class="clearfix shrp1">
                        <div class="prfrad prfdim8 prfbr6 fl"> <img src="~$apiData['pic']['url']`" border="0" class="prfdim5 prfrad prfm2"/> </div>
                        <div class="fl ml10 prfbr7 pb10 f15 color11 wid80p pt16"> ~$apiData['about']['username']`
                        </div>
                      </div>
                  <div class="shrp3">
                    <div id="addConfirmationMessage"class="mt5 fullwid brdr-0 bgnone color11 fontlig"></div>
                    <div class="txtc pt40 pb20">
                    <a id="close-layer" class="color5 f13 fontlig js-undoAction close cursp">Close</a>
                    </div>
                    </div>
                    </div>
              </div>        
            <!--end:div-->
               
            </div>    
      </div>  
     </div> 
    <!--end:share this profile-->