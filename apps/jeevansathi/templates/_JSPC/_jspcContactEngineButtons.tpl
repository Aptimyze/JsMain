 <!-- Search htmls-->
<div id="SMSLoader" class='disp-none'>
<div style="color: #d9475c" class="la-ball-pulse-sync la-sm">
    <div></div>
    <div></div>
    <div></div>
</div>
</div>
<div class='disp-none'>



    <ul id="PreFourButtonsSearch" >
              
		<li class='contactEngineIcon'>
			<div class="disp-cell vmid"> <i class="sprite2 cursp buttonImage"></i> </div>
		</li>
     </ul>
     <!-- End of Search Htmls-->

<!-- Profile Detail htmls-->
<ul id="IgnoreDetail" class="ignonediv f15">
  <li class="pos-rel prfdim9 disp-cell vmid">
    <i class="sprite2 pos-abs prfc3"></i>
    <p class="pl60 pt16">You ignored this profile. </p>
    <p class="pl60">Want to re-inititiate contact with her?</p>
  </li>
  <li class="fullwid bg_pink lh40 txtc">
    <a class="colrw fontreg" href="#">Remove From Ignore List</a>
  </li>
</ul>
<div id="ExpectationDetailDiv">
  <div class="bg5 prfdim10 txtc">
    <div class="valignprf pos-rel">
      <i class="sprite2 prfic45"></i>
      <p class="f16 wid150 mauto">{{INFO_MSG_LABEL}}</p>
    </div>
  </div>
</div>
<div id="PreDetailOneButton">
  <div  class="fr prfwid12 colrw fontlig f20"> 
    <div class="bg5 prfdim10 txtc pos-rel">
      <div class="prfdim11">
        <div id="{{ACTION_ID}}_INFO" class="valignprf pos-rel f16">
          {{MessageDisplay}}
        </div>
      </div>
      <div id="{{ACTION_ID}}" class="pos-abs fullwid prflh2 bg_pink {{LOGIN_LOGOUT}}" data="{{params}}" style="bottom:0;">
        <!--start:div-->
        <div class="clearfix cursp wid165 mauto">
          <div class="fl pt2 {{VisibilityClass}}"> <i class="sprite2 {{icon}}"></i> </div>
          <div class="fl prfp21 prfm4">{{ButtonDisplayName}}</div>
        </div>
        <!--end:div--> 
      </div>
    </div>
    
  </div>
</div>
<div id="PreDetailTwoButton">
    <div class="prfdim10 txtc pos-rel">
     <div class="padall-10">
      <div id="ACCEPT_DECLINE_INFO" class="f16 pt20 wid144 mauto">{{InfoMsg}}</div>
      <ul id="cEUlContainer" class="hor_list pt30 pl40 intspog">
        <li  id="{{ACTION_ID}}" class="ml5 cursp {{LOGIN_LOGOUT}}" data="{{params}}">
          <div class="cir1">
           <div class="valignprf pos-rel">
            <i  id="{{ACTION_ID}}_ICON" class="sprite2"></i>
          </div>  
        </div>
        <p id="{{ACTION_ID}}_LABEL" class="pt10">{{Button_label}}</p>        
      </li>
  </ul>                 
</div>                
</div>
</div>
<div id="cEPreFourButtonsDetail" >
	<ul id="PreFourButtonsDetail" class="listnone vprof3">
		<li id="{{ACTION_ID}}" class="{{className}} {{LOGIN_LOGOUT}}" data="{{params}}">
			<div class="fl mt18">
				<div id="{{ACTION_ID}}_ICON" class="sprite2 {{icon}} js-CeIcon"></div>                            
			</div>
			<div id="{{ACTION_ID}}_LABEL" class="fl pl20">{{iconName}}</div>
		</li>
	</ul>
</div>
<!-- End of Profile Detail Htmls-->

<!-- After action display layer for Search and ProfilePage-->
<div id="postViewContactLayer"><div id="contactEngineLayerDiv" class="pos-abs fullwid celyr1 z3 fontlig cepos1 cehgt1 " >
<div class="clearfix cebrd1 cep3 pos-rel">
	<div class="fl f13 pt5 cewid3">
		 <span class="color11 disp_ib cewid2 txtr js-usernameCE">{{USERNAME}}</span> 
		 <span class="disp_ib cewid1 txtc">-</span>     
		 <span class="colr2 disp_ib pos-rel cewid4">{{DETAILED_PROFILE_INFO}}
		 <span id="cEMembershipEvalue" class="disp-none colr5"><a href="/profile/mem_comparison.php" class="color5">, Upgrade to eValue</a> </span>
		  <span id="CONTACTS_LEFT" class="fontlig f17 colr2 pos-abs disp-none" style="right:0; top:-5px;"><span class="colr5">{{CONTACTS_LEFT}}</span> contact left to view</span>
		 
		 </span>
		
		 
	 </div>
	
	 <i class=" closeContactDetailLayer sprite2 sendcross1 cursp pos-abs cepos2"></i>
 </div>
 <!--end:top line-->
 <!--start:content-->
 <div class="cep6">
	<div class="cEcontent ceght1s mCustomScrollbar f13 ">
   ~if LoggedInProfile::getInstance()->getISD() eq '91'`
  <div  class="SMSContactsDiv posabs colr5 disp-none cursp" style='right:16px;top:3px;'>SMS these details to me</div> ~/if`
		<ul id="cEViewContactListing" class="ceviewcontct fontlig">
			<li class="clearfix">
				<div class="fl">{{CONTACT_NAME}}</div>
				<div class="fl">{{CONTACT_VALUE}}<span id ="reportInvalidButton" class="{{DISP_REPORT}} cursp disp_ib pl30 color11 reportInvalid" {{prochecksum}} {{phonetype}}>Report Invalid</span></div>
			
			</li>
		</ul>
	</div>
	</div>                     
 </div>    
 <!--end:content-->
</div>
<div id="postCommonDisplayLayer">
					<div id='contactEngineLayerDiv' class="pos-abs fullwid celyr1 z3 fontlig cepos1 cehgt1">
                    <div class="clearfix cebrd1" style="height:50px">
						<p class="txtc color11 f13 {{VisibilityClass_header}}">{{header}}<span class="color5 pl5 ">{{ViewSimiarProfile}}</span></p>
                    	<i class=" closeContactDetailLayer sprite2 sendcross1 cursp pos-abs cepos2"></i>
                    </div>
                    <div class="disp-tbl fullwid">
							<div class="disp-cell vmid txtc color11 f15" style="height:144px">
						<p class="color11 ptm25 {{VisibilityClass_Error}}">{{ErrorMsg}}</p> 
                        <p class="color5 {{VisibilityClass_Info}}">{{InfoMsg}}</p>
                        <button id={{ACTION_ID}} class=" cursp bg_pink colrw fontreg f15 mt20 brdr-0 cep2 contactEngineIcon  {{ButtonClass}} {{VisibilityClass_Button}}" data="{{paramData}}">{{ButtonLabel}}</button>
                        <div class="colr5 pt10 ">{{ButtonLabelText}}</div>
						</div>
						</div>
                    
                    	
                     </div>
</div>
<div id="postCommonMessageLayer">
<!--start:layer 1-->
<div id="contactEngineLayerDiv" class="pos-abs fullwid celyr1 z3 fontlig cepos1 cehgt1" >
                  <div class="fullwid clearfix">
                  		<!--start:left-->
                        <div class="fl wid80p">
                        	<div class="cebrd1 cep3">
                            	<ul class="hor_list clearfix f13 fontlig color13">
                                	<li class="pr10 colr4 js-newMsgText">Write new message</li>
                                    <li class="cursp disp-none  pl10 js-lastSentText">Last sent</li>
                                </ul>                            
                            </div> 
                            <div class="padalls">
                            	 <textarea id="{{ACTION_ID}}-cEMessageText" class="js-msgBoxForBinding outlineBorderBoxNone wid99p bgnone brdr-0 fontlig f15 color11 cehgta" placeholder="{{MESSAGE_TEXT}}">
							</textarea>
                            </div>                      
                        </div>                        
                        <!--end:left-->
                   		<!--start:right-->
                        <div class="fr wid8p ">
                        	<div  id={{ACTION_ID}} class="cursp hgt110 bg10 txtc" data="{{params}}">
                            	<i class="sprite2 chkicon  aligncen"></i>
                            </div>
                            <div class="hgt110 bg5 cursp txtc closeContactDetailLayer">
                            	<i class="sprite2 sendintcross  aligncen"></i>
                            </div>
                        
                        </div>                        
                        <!--end:right-->
                  
                  </div>
                  </div>
<!--end:layer -->

</div>
<div id="postCommonErrorLayer">
					<div id='contactEngineLayerDiv' class="pos-abs fullwid celyr1 z3 fontlig cepos1 cehgt1">
						 <div class="cep1 clearfix cebrd1 hgt25">
                    	<i class=" closeContactDetailLayer sprite2 sendcross1 cursp pos-abs cepos2"></i>
                    </div>
						<div class="disp-tbl fullwid">
							<div class="disp-cell vmid txtc color11 f15" style="height:144px">
						Something went wrong, Please try again later.
						</div>
						</div>
					</div>

</div>

<!--ContactCenterHtmls    -->
<div id="PreContactCenterTwoButton">

                                	<ul class="listnone ccwid3 f17 cclist3">
                                    	<li id=""  data="" class="lh50 buttonId1 fullwid txtc contactEngineIcon cursp">
                                        	<span class="colrw buttonLabel1"></span>
                                        </li>
                                        <li id="" data="" class="lh50 buttonId2 fullwid txtc contactEngineIcon cursp">
                                        	<span class="colrw buttonLabel2"></span>
                                        </li>
                                    </ul>

</div>
<div id="PreContactCenterOneButton">

                                	<p id="" class="f15 txtc pt14 msgDisplay"></p>
                                    <div id="" data="" class="contactEngineIcon cursp buttonLabel bg_pink lh50 txtc fullwid f17 mt16 colrw"></div>
</div>                                    
<div id="PreContactNoCenterButton">

                                	<p id="" class="f15 txtc pt14 msgDisplay">{{INFO_MSG_LABEL}}</p>

</div> 
<!--End of Contact Center Htmls -->
 
<!--ContactCenter Post Layer Htmls    -->
<div id="postContactCenterCommonLayer">
 <!--start:layer 
                    <div id ="contactEngineLayerDivvvv" class="pos-abs ccdimn4 ccbg1 z3 cErightZero">
                      <div class="disp-tbl fullwid cch2n">
              <span class="disp_ib fr closeContactDetailLayer pos-abs cepos3 icons cursp cmn_close"></span>
                        <div class="disp-cell vmid txtc">
                          <p class="txtc f15 color11">{{ErrorMsg}}</p>
                          <div id={{ACTION_ID}} data="{{paramData}}" class="contactEngineIcon cursp lh30 colrw f15 bg_pink txtc mauto wid45p mt13 {{ButtonClass}} {{VisibilityClass_Button}}">{{ButtonLabel}}</div>
                        </div>
                      </div>
                    </div>
                    <!--end:layer 1--> 


                     <!--start:layer 1-->
                    <div id ="contactEngineLayerDiv" class="pos-abs ccdimn4 ccbg1 z3 cErightZero">
                      <div class="disp-tbl fullwid cch2n">
                      <span class="disp_ib closeContactDetailLayer pos-abs cepos3 icons cursp cmn_close"></span>
                        <div class="disp-cell vmid {{ButtonLabelShiftClass}}">
                          <p class="f15">{{ErrorMsg}}</p>
                          
                          
                          <div class="mauto  mt13"> 
                                <div class="lh30 bg_pink txtc disp_ib wid45p">
                                    <div id={{ACTION_ID}} data="{{paramData}}" class="colrw f15 cursp contactEngineIcon {{ButtonClass}} {{VisibilityClass_Button}}">{{ButtonLabel}}</div> 
                                </div>
                                <div style="max-width:290px; height:21px; vertical-align: middle" class="colr5 disp_ib pl10 textTru f13">{{ButtonLabelText}}</div>
                           </div>
                           
                           
                           
                        </div>
                      </div>
                    </div>
                    <!--end:layer 1--> 



</div>

<div id="postContactCenterCommonMessageLayer">

 <div id ="contactEngineLayerDiv" class="pos-abs ccdimn4 ccbg2 z3 cErightZero">
                      <div class="disp-tbl fullwid cch2n">
                        <div class="disp-cell vmid txtc">
                            <!--start:send message-->
                             <div class="pl30">
                           <form>
                              <div class="mt10 fullwid bg-white ccbrdb2">
                                <div class="ccp3">
                                  <textarea id="{{ACTION_ID}}-cEMessageText" class="brdr-0 ccout color12 f12 fontlig fullwid" placeholder="{{MESSAGE_TEXT}}"></textarea>
                                </div>
                              </div>
                              <div  class="clearfix  bgnone brdr-0 f15 fontrobbold pt10 pl10 txtl"><div id={{ACTION_ID}} data="{{params}}"class="opa40 color11 fl">SEND</div><div class="closeContactDetailLayer cursp color12 fontlig fr" style="">Discard</div></div>
                            </form>   
                            </div>
                                           
                       		<!--end:send message-->                       
                        </div>
                      </div>
                    </div>

</div>


<div id="postCCCommonViewLayer"><div id ="contactEngineLayerDiv" class="pos-abs ccdimn4 ccbg1 z3 cErightZero">
                      <div class="disp-tbl fullwid cch2n">
                        <div class="disp-cell txtc">
                            <!--start:view contact-->
                           <div class="ccp10 f12">
                              <div class="clearfix">
                      				<div class="fl">
                                    	<span class="f17 js-usernameCC"></span> 
                                        <span class="disp_ib f11 pl10 js-onlineStatusCC" style='margin-right:2px;'></span>
                                        <div class="SMSContactsDiv disp_ib colr5 disp-none cursp">SMS these details to me</div>
                              
                                      </div>
                     				<div class="fr ccp5"> 
                                    	<span class="disp_ib color5 js-leftToView1 disp-none"></span> 
                                        <span class="disp_ib color2 pl5 pr10 js-leftToView2 disp-none">contacts left to view</span> 
                                        <span class="disp_ib closeContactDetailLayer icons cursp cmn_close"></span> 
                                    </div>
                    			</div>
                                 <p style='white-space:pre;' class="pt6 txtl js-phoneContactCC disp-none"><span>Phone </span><span class='js-timeToCallCC'></span> :  <span class='js-phoneValuesCC'></span></p>
                    			<p class="pt10 txtl js-emailContactCC disp-none"><span>Email id:     </span>    <span class='js-emailValueCC'></span></p>
                            </div>                          
                       		<!--end:view contact-->                       
                        </div>
                      </div>
                    </div>
	</div>
 

<div id='CCundoLayer'><div id="contactEngineLayerDiv" class="ccbg1 txtc f15 lh61">
                              <span class="opa70 infoMsg"><a class='js-usernameCC'></a> has been removed from Ignored list.</span><span id='' class="cursp contactEngineIcon disp_ib fontreg color11 pl10" undoLayer='1' data=''>Undo</span>
                      </div>
                      
</div>


  



<div id="postCCErrorCommonLayer"><div id ="contactEngineLayerDiv" class="pos-abs ccdimn4 ccbg1 z3 cErightZero"><i class=" closeContactDetailLayer sprite2 sendcross1 cursp pos-abs cepos2"></i>
                      <div class="disp-tbl fullwid cch2n">
                        <div class="disp-cell vmid txtc">
                          <p class="txtc f15 js-genericMsg color11">Something went wrong, please try again later</p>
                        </div>
                      </div>
                    </div>
                    <!--end:layer 1--> 

</div>

<!--End of ContactCenter Post layer Htmls    -->


</div>
<!-- Start of Report Invalid Option Layer -->
 <div id="reportInvalidReason-layer" class="reportAbuse-layer pos_fix layersZ fontlig setshare disp-none">
      <div class="prfwid16 mauto">
          <div class="prfbg6">
              <div class="prfp22">
              <div class="clearfix">
                  <div class="prfrad prfdim8 prfbr6 fl "> <img src="" border="0" class="js-otherProfilePic prfdim5 prfrad prfm2"> </div>
                    <div class="fl ml10 prfbr7 pb10 f13 color11 wid80p pt16" style='white-space:pre;'>
                      <span class='js-username'></span>    -    <span class="colr2"> Report invalid number</span>
                    </div>
                </div>
                <div class="pl12 pt20">
                  <p id='RAReasonHead' class="color12 f13">Select reason</p>
                    <ul id ="reasonCode" class="listnone reportlist fontlig f15 pt10 color2">
                      <li>
                          <input type="radio"  name="report_profile" value = '1'>Switched off / Not reachable</li>
                          <li>
                          <input type="radio"   name="report_profile" value = '2'>Not an account holder's phone</li>
                           <li>
                          <input type="radio"   name="report_profile" value = '3'>Already married / engaged</li>
                            <li>
                          <input type="radio"  name="report_profile" value = '4'>Not picking up</li>
                             <li>
                          <input id='otherOptionBtn' type="radio"  name="report_profile" value = '5'>Others
                        <div id='otherOptionMsgBox' class='disp-none' >
                        <div id='errorText' class="disp-none"><br><div class="errcolr" style="font-size: 11px;">*Please Enter The Comments</div></div>
                        <textarea rows='4' type="radio" style='width:95%;outline:none;' class='brdr-0 bgnone reportAbuse  mt10 fontlig' placeholder='Add Comments'></textarea></div>
                           </li>
                                                  
                        
                    
                    </ul>
                
                
                </div>
            
            </div>        
          </div>
            <div class="fullwid">
              <div id = "reportInvalidReasonLayer" class="fl cursp wid50p bg_pink txtc prfp20">
                  <i class="sprite2 prfic42 "></i>
                </div>
                <div id='reportInvalidCross' class="fr cursp wid50p bg5 txtc prfp20">
                  <i class="sprite2 prfic43"></i>
                </div>
            </div>
       
        </div>    
    </div>

  <!--end:report this profile as Invalid-->

  <!-- Start of Report Invalid Confirmation Layer -->

<div id="reportInvalidConfirmLayer" class="pos_fix layersZ fontlig setshare disp-none">
    
  <div class="prfwid16 fontlig">
<div class="prfbg6">
<!--start:div-->
<div class="">
<div class="clearfix reportInv2">
<div class="prfrad prfdim8 prfbr6 fl"> <img src="" border="0" class="js-otherProfilePic prfdim13 prfrad prfm2"> </div>
<div class=" fl ml10 prfbr7 pb10 f13 color11 wid80p pt16">
<span class="js-username"></span><span class="pl10 pr10">-</span><span class="colr2" id = 'headingReportInvalid'>Phone no. reported as invalid</span>
</div>
</div>

<div class="f13 reportInv1 txtc">
<p id = "invalidConfirmMessage"class="color11 f13 txtl" style="padding-left:70px;">Thank you for helping us.If our team finds this number invalid we will remove this number and credit you with a contact as compensation. </p>
</div>
</div>
</div>
</div>
</div>

<!-- End of Report Invalid Confirmation Layer -->