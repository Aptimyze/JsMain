~assign var=zedoValue value= $sf_request->getAttribute('zedo')`
~assign var=zedo value= $zedoValue["zedo"]`
~include_Partial("search/photoAlbum")`

<div id='commHistoryOverlay-layer' class="pos_fix layersZ setshare disp-none">
    <li id='commDiv' class="commDiv disp-none clearfix">
                                  <div class="prfwid18 prfm2" style='margin-top:0px;'><img src="" border="0" class="js-profilePic prfdim14 prfrad vtop"></div>
                                    <div class="pl10 prfwid19">
                                      <p class="f15 js-commHeading"></p>
                                        <p class="js-commMessage disp-none pt7 color2 f13"></p>
                                        <p class="js-commTime pt10 f12 color12"></p>
                                    </div>
                                </li>
      <div class="prfwid16 fontlig">
        <div class="prfbg6">
                <!--start:div-->          
              <div class="">
                <div class="clearfix shrp1">
                    <div class="prfrad prfdim8 prfbr6 fl"> <img src="" border="0" class="otherProfilePic prfdim13 prfrad prfm2"> </div>
                      <div class=" fl ml10 prfbr7 pb10 f13 color11 wid80p pt16">
                       <span class='js-usernameCC'></span><span class='pl10 pr10'>-</span><span class="colr2">Communication History</span>
                      </div>
                      <i class="sprite2 sendcross2 cursp pos-abs cepos4 closeCommLayer"></i>
                  </div>

                  <div id='commHistoryAbsent' class="f13 comhisp1 txtc">
                      <p class="color5 f22">No Communication history</p>
                        <p class="color2 f13 pt15">Send an interest to connect with <span class='js-usernameCC'></span></p>            
                    </div>

                     <!--start:form-->
                     <img id='commHistoryLoader' src="/images/jspc/commonimg/loader.gif" style="visibility:hidden;margin: 0 auto;height: 22px;display: block;">
                     <div id='commHistory' class="f13 comhp1">
                        
                          <div id='commLayerScroller' class="cEcontent">                        
                            <ul id='mainDiv' class="listnone comhis fontlig color11">
                              
                                      
                                                         
                            </ul>
                            </div>
                       
                    </div>
                    <!--end:form-->
              </div>        
            <!--end:div-->
               
            </div>    
      </div>  
     </div>

    <!--start:we talk for you layer-->
    <div id="we-talk-layer" class="pos_fix layersZ disp-none">
        <i id="cls-we-talk" class="sprite2 close pos_fix closepos cursp"></i>
        <div class="pos_fix setshare"> 
         
         <div class="mauto prfbg6 fontlig prfwid15">
         	<div class="prfp16">
            	<div class="clearfix">
                	<div class="fl">
                    	<div class="prfrad prfdim8 prfbr6"><div id="addPhotoLabel"></div><img src="~$arrOutDisplay['pic']['url']`" border="0" class="prfdim5 prfrad prfm2"/> </div>
                    </div>
                    <div class="fl ml10 wid85p pt20">
                    	<p class="f15 prfbr7 pb5 txtind">Profile is added to 'we talk for you' list</p>
                    </div>                    
                </div>
                <div class="prflist2 colr2 f15 fontlig prfp17">
                    	<ul>
                        	<li>
                            	<p>Total Purchased</p>
                                <p>~$arrOutDisplay['about']['introCallData']['introCallDetail']['PURCHASED']`</p>
                            </li>
                            <li>
                            	<p>Added to 'we talk for you'</p>
                                <p>~$arrOutDisplay['about']['introCallData']['introCallDetail']['TO_BE_CALLED']`</p>
                            </li>
                            <li>
                            	<p>of which call complete</p>
                                <p>~$arrOutDisplay['about']['introCallData']['introCallDetail']['CALLED']`</p>
                            </li>
                            <li>
                            	<p>More profile that can be added</p>
                                <p>~$arrOutDisplay['about']['introCallData']['introCallDetail']['TO_BE_ADDED']`</p>
                            </li>
                        
                        </ul>                    
                    </div>
            </div>
         </div>
    
    </div>  
</div>
    <!--end:we talk for you layer-->
        <!--start:share this profile-->
      ~include_Partial("profile/jspcViewProfile/_jspcShareProfileSection",["apiData"=>$arrOutDisplay,"loginProfileId"=>$loginProfile->getPROFILEID()])`
    <!--end:share profile layer-->
    <!--start:ignore profile layer-->
    <div id="ignore-layer" class="pos_fix fullwid layersZ disp-none setshare" style="width:700px">
     <div class="prfbg6">
       <div class="prfp18">
         <ul class="hor_list clearfix fontlig f13">
           <li><div class="prfrad prfdim8 prfbr6"> <img src="~$arrOutDisplay['pic']['url']`" border="0" class="prfdim5 prfrad prfm2"/> </div></li>
           <li class="prfwid10 pl10">
             <p class="color11 pt10">~$arrOutDisplay["about"]["username"]`</p>
             <p id="ignoredText" class="pt4 colr2"></p>
           </li>
           ~if $arrOutDisplay['page_info']['is_ignored']`
           <li id="IGNORE-~$arrOutDisplay['page_info']['profilechecksum']`-VDP-UNDO" class="cursp pl10 colr5 pt28 cEUndoIgnoreLayerDetail " data="&ignore=1">Undo</li>
           ~else`
           <li id="IGNORE-~$arrOutDisplay['page_info']['profilechecksum']`-VDP-UNDO" class="cursp colr5 pl10 pt28 cEUndoIgnoreLayerDetail " data="&ignore=0">Undo</li>
           ~/if`
         </ul>            
       </div>        
     </div>
   </div>
   <!--end:ignore profile layer-->

<!--start:report this profile-->
<div id="reportAbuseConfirmLayer" class="pos_fix layersZ fontlig setshare disp-none">
    
  <div class="prfwid16 fontlig">
<div class="prfbg6">
<!--start:div-->
<div class="">
<div class="clearfix reportInv2">
<div class="prfrad prfdim8 prfbr6 fl"> <img src="" border="0" class="js-otherProfilePic prfdim13 prfrad prfm2"> </div>
<div class=" fl ml10 prfbr7 pb10 f13 color11 wid80p pt16">
<span class="js-username"></span><span id ="hiphenForConfirm" class="pl10 pr10">-</span><span id = "reportAbuseConfirmHeading"class="colr2">Profile reported for Abuse</span>
</div>
</div>

<div class="f13 reportInv1 txtc">
<p id ="messageForReportAbuse" class="color11 f13 txtl" style="padding-left:70px;">
  Thank you for helping us. This profile will be removed if the content or behaviour is found to be inappropriate.
</p>
</div>
</div>
</div>
</div>
</div>

    <div id="reportAbuse-layer" class="reportAbuse-layer pos_fix layersZ fontlig setshare disp-none">
      <div class="prfwid16 mauto">
          <div class="prfbg6">
              <div class="prfp22">
              <div class="clearfix">
                  <div class="prfrad prfdim8 prfbr6 fl "> <img src="" border="0" class="js-otherProfilePic prfdim5 prfrad prfm2"> </div>
                    <div class="fl ml10 prfbr7 pb10 f13 color11 wid80p pt16" style='white-space:pre;'>
                      <span class='js-username'></span>    -    <span class="colr2"> Report Abuse</span>
                    </div>
                </div>
                <div class="pl12 pt20">
                  <p id='RAReasonHead' class="color12 f13">Select reason</p>
                     <ul id="reportAbuseList" class="listnone hgt200 reportlist fontlig f15 pt10 color2 mb20">
    <li class="openBox">
        <input id='openBox' type="radio" name="report_profile" /><span class="reason">One or more of Profile Details are incorrect</span>
        <div class='otherOptionMsgBox disp-none'>
            <div id='errorText' class="disp-none">
                <br>
                <div class="errcolr" style="font-size: 11px;">*Please Enter The Comments</div>
            </div>
            <textarea maxlength="225" rows='4' type="radio" style='width:90%;outline:none;' class='brderinp brdr-0 bgnone reportAbuse  mt10 fontlig padall-10' placeholder='Please elaborate further in your own words about the issue. Please be as detailed as possible....'></textarea>
        </div>
    </li>
    <li class="openBox">
        <input id='openBox' type="radio" name="report_profile"><span class="reason">Photo on profile doesn't belong to the person</span>
        <div class='otherOptionMsgBox disp-none'>
            <div id='errorText' class="disp-none">
                <br>
                <div class="errcolr" style="font-size: 11px;">*Please Enter The Comments</div>
            </div>
            <textarea maxlength="225" rows='4' type="radio" style='width:90%;outline:none;' class='brderinp brdr-0 bgnone reportAbuse  mt10 fontlig padall-10' placeholder='Please elaborate further in your own words about the issue. Please be as detailed as possible....'></textarea>
        </div>
    </li>
    <li class="openBox">
        <input id='openBox' type="radio" name="report_profile"><span class="reason">User is using abusive/indecent language</span>
        <div class='otherOptionMsgBox disp-none'>
            <div id='errorText' class="disp-none">
                <br>
                <div class="errcolr" style="font-size: 11px;">*Please Enter The Comments</div>
            </div>
            <textarea maxlength="225" rows='4' type="radio" style='width:90%;outline:none;' class='brderinp brdr-0 bgnone reportAbuse  mt10 fontlig padall-10' placeholder='Please elaborate further in your own words about the issue. Please be as detailed as possible....'></textarea>


        </div>
    </li>
    <li class="openBox">
        <input id='openBox' type="radio" name="report_profile"><span class="reason">User is stalking me with messages/calls</span>
        <div class='otherOptionMsgBox disp-none'>
            <div id='errorText' class="disp-none">
                <br>
                <div class="errcolr" style="font-size: 11px;">*Please Enter The Comments</div>
            </div>
            <textarea maxlength="225" rows='4' type="radio" style='width:90%;outline:none;' class='brderinp brdr-0 bgnone reportAbuse  mt10 fontlig padall-10' placeholder='Please elaborate further in your own words about the issue. Please be as detailed as possible....'></textarea>


        </div>
    </li>
    <li class="openBox">
        <input id='openBox' type="radio" name="report_profile"> <span class="reason">User is asking for money</span>
        <div class='otherOptionMsgBox disp-none'>
            <div id='errorText' class="disp-none">
                <br>
                <div class="errcolr" style="font-size: 11px;">*Please Enter The Comments</div>
            </div>
            <textarea maxlength="225" rows='4' type="radio" style='width:90%;outline:none;' class='brderinp brdr-0 bgnone reportAbuse  mt10 fontlig padall-10' placeholder='Please elaborate further in your own words about the issue. Please be as detailed as possible....'></textarea>
        </div>


    </li>
    <li class="openBox">
        <input id='openBox' type="radio" name="report_profile"><span class="reason">User has no intent to marry</span>
        <div class=' otherOptionMsgBox disp-none'>
            <div id='errorText' class="disp-none">
                <br>
                <div class="errcolr" style="font-size: 11px;">*Please Enter The Comments</div>
            </div>
            <textarea maxlength="225" rows='4' type="radio" style='width:90%;outline:none;' class='brderinp brdr-0 bgnone reportAbuse  mt10 fontlig padall-10' placeholder='Please elaborate further in your own words about the issue. Please be as detailed as possible....'></textarea>


        </div>
    </li>
    <li class="openBox">
        <input id='openBox' type="radio" name="report_profile"><span class="reason">User is already married / engaged</span>
        <div class=' otherOptionMsgBox disp-none'>
            <div id='errorText' class="disp-none">
                <br>
                <div class="errcolr" style="font-size: 11px;">*Please Enter The Comments</div>
            </div>
            <textarea maxlength="225" rows='4' type="radio" style='width:90%;outline:none;' class='brderinp brdr-0 bgnone reportAbuse  mt10 fontlig padall-10' placeholder='Please elaborate further in your own words about the issue. Please be as detailed as possible....'></textarea>


        </div>
    </li>
    <li>
        <input type="radio" name="report_profile"><span class="reason">User is not picking up phone calls</span></li>
    <li class="openBox">
        <input id='openBox' type="radio" name="report_profile"><span class="reason">Person on Phone denied owning this profile</span>
        <div class=' otherOptionMsgBox disp-none'>
            <div id='errorText' class="disp-none">
                <br>
                <div class="errcolr" style="font-size: 11px;">*Please Enter The Comments</div>
            </div>
            <textarea maxlength="225" rows='4' type="radio" style='width:90%;outline:none;' class='brderinp brdr-0 bgnone reportAbuse  mt10 fontlig padall-10' placeholder='Please elaborate further in your own words about the issue. Please be as detailed as possible....'></textarea>
        </div>

    </li>
    <li>
        <input type="radio" name="report_profile" /><span class="reason">User's phone is switched off/not reachable</span>
    </li>
    <li class="openBox">
        <input id='openBox' type="radio" name="report_profile"><span class="reason">User's phone is invalid</span>
        <div class=' otherOptionMsgBox disp-none'>
            <div id='errorText' class="disp-none">
                <br>
                <div class="errcolr" style="font-size: 11px;">*Please Enter The Comments</div>
            </div>
            <textarea maxlength="225" rows='4' type="radio" style='width:90%;outline:none;' class='brderinp brdr-0 bgnone reportAbuse  mt10 fontlig padall-10' placeholder='Please elaborate further in your own words about the issue. Please be as detailed as possible....'></textarea>
        </div>

    </li>

    <li class="openBox">
        <input id='openBox' type="radio" name="report_profile"><span class="reason">Other reasons</span>
        <div class=' otherOptionMsgBox disp-none'>
            <div id='errorText' class="disp-none">
                <br>
                <div class="errcolr" style="font-size: 11px;">*Please Enter The Comments</div>
            </div>
            <textarea maxlength="225" rows='4' type="radio" style='width:90%;outline:none;' class='brderinp brdr-0 bgnone reportAbuse  mt10 fontlig padall-10' placeholder='Please elaborate further in your own words about the issue. Please be as detailed as possible....'></textarea>


        </div>
    </li>

</ul>
                
                
                </div>
            
            </div>        
          </div>
            <div class="fullwid">
              <div onclick='reportAbuse();' class="fl cursp wid50p bg_pink txtc prfp20">
                  <i class="sprite2 prfic42 "></i>
                </div>
                <div id='reportAbuseCross' class="fr cursp wid50p bg5 txtc prfp20">
                  <i class="sprite2 prfic43"></i>
                </div>
            </div>
       
        </div>    
    </div>
    <!--end:report this profile-->
<div class="pos-rel fullwid">   
  <!--start:top part-->
  <div class="prf-cover1" 
style="height:387px; background-image: url('~$arrOutDisplay["about"]["coverPhoto"]`')">
    <div class="container mainwid pt35"> 
      <!--start:top nav case logged in--> 
        <!--start:logo-->
        ~include_partial('global/JSPC/_jspcCommonTopNavBar')`
        <!--end:logo--> 
      </div>
      <!--end:top nav case logged in--> 
      
    </div>
  </div>
  <!--end:top part-->
~if $SHOW_NEXT_PREV && $STYPE !='MOD'`
  <!--start:next/previous button-->
  ~if $SHOW_PREV`
  	<!--start:prv-->
    <a id="show_prevListingProfile" ~if isset($prevLink)`href ="/profile/viewprofile.php?~$prevLink`&stype=~$STYPE`&responseTracking=~$responseTracking`~$other_params`&~$NAVIGATOR`"~else`href ="/profile/viewprofile.php?show_profile=prev&total_rec=~$total_rec`&actual_offset=~$actual_offset`&j=~$j`&responseTracking=~$responseTracking`&searchid=~$searchid`~$other_params`&~$NAVIGATOR`&tupleId=~$preTupleId`"~/if`>
    <div class="pos-abs prfpos5 cursp z1">
    	<div class="disp-tbl prfdim6 prfb10 txtc">
    		<div class="disp-cell vmid"><i class="sprite2 prfic33"></i></div>
        </div>
    </div>    
    </a>
    <!--start:prv-->
  ~/if`
  ~if $SHOW_NEXT`
    <!--start:next-->


    <a id="show_nextListingProfile" ~if isset($nextLink)`href ="/profile/viewprofile.php?~$nextLink`&stype=~$STYPE`&responseTracking=~$responseTracking`~$other_params`&~$NAVIGATOR`"~else`href ="/profile/viewprofile.php?show_profile=next&total_rec=~$total_rec`&actual_offset=~$actual_offset`&j=~$j`&responseTracking=~$responseTracking`&searchid=~$searchid`~$other_params`&~$NAVIGATOR`&tupleId=~$nextTupleId`"~/if`>
    <div class="pos-abs prfpos6 cursp z1">
    	<div class="disp-tbl prfdim6 prfb10 txtc">
    		<div class="disp-cell vmid"><i class="sprite2 prfic34"></i></div>
        </div>
    </div>    
   </a>
    <!--start:next-->  
  ~/if`
  <!--end:next/previous button-->
~/if`
  
  <div class="bg-4">
  
    <div class="pos-rel container mainwid settop1"> 
      
      <!--start:scrolling menu-->	
      <div class="pos_fix prfbar1fix z3 disp-none js-barscroll">
            <div class="fullwid clearfix">
            	<div class="fl prfbg4 prfwid14">
                    <!--start:div-->
                    <div class="fl prfp13">
                        <img id='profilePicScrollBar' src="~$arrOutDisplay['about']['thumbnailPic']`" class="prfdim5 prfrad vtop"/>
                    </div>
                    <!--end:div-->
                    <!--start:div-->
                    <div class="fl pt22" id="menu-center">
                        <ul class="listn scrollm clearfix fontlig f15 pos-rel">
                            <li class="fisrt"><a class="active" href="#section-about">About ~if $arrOutDisplay["about"]["gender"] eq "Female"`Her~else`Him~/if`</a></li>
                            <li><a href="#section-career">Education & Career</a></li>
                            <li><a href="#section-family">Family Details</a></li>
                            <li><a href="#section-d">Looking For</a></li>
                            <li class="pos-abs" id="barmov1" style="border:1px solid #000; top:20px; margin:0"></li>
                        </ul>                
                    </div>
                    <!--end:div-->
                 </div>
                    
                
                <!--start:div-->
                <div class="fl bg5 prfwid13 disp-none" >
                		<div class="disp-tbl">
                        	<div class="disp-cell vmid hov1">
                            	<div class="sprite2 prfic1 mauto"></div>
                            </div>
                            <div class="disp-cell vmid hov1">
                            	<div class="sprite2 prfic2 mauto"></div>
                            </div>
                            <div class="disp-cell vmid hov1">
                            	<div class="sprite2 prfic3 mauto"></div>
                            </div>
                            <div class="disp-cell vmid hov1">
                            	<div class="sprite2 prfic4 mauto"></div>
                            </div>
                        
                        </div>
                          
                </div>
                <!--end:div-->
            
            </div>      
      </div>      
      <!--end:scrolling menu-->
      
      
      
      ~if !$loginProfile->getPROFILEID()`
      <!--start:breadcrumb-->
      <p id="breadcrumbs" class="fontlig f13 prfcolr1 opa70 lh30" ><span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="~sfConfig::get('app_site_url')`" itemprop="url" class="colrw"><span itemprop="title">Home</span></a></span> &rsaquo; ~if $profileLinkArr['REL_LINK'] neq ''`<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="~sfConfig::get('app_site_url')`~$profileLinkArr['REL_LINK']`" itemprop="url" class="colrw" title="~$religionSelf` Matrimonial"><span itemprop="title">~$religionSelf` Matrimony</span></a></span> &rsaquo; ~/if`~if $profileLinkArr['MTNG_LINK'] neq ''`<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="~sfConfig::get('app_site_url')`~$profileLinkArr['MTNG_LINK']`" class="colrw" itemprop="url" title="~FieldMap::getFieldLabel("community_small",$profile->getMTONGUE())` Matrimonial"><span itemprop="title">~FieldMap::getFieldLabel("community_small",$profile->getMTONGUE())` Matrimony</span></a></span> &rsaquo; ~/if`~if $profileLinkArr['CASTE_LINK'] neq ''`<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="~sfConfig::get('app_site_url')`~$profileLinkArr['CASTE_LINK']`" class="colrw" itemprop="url" title="~$CASTE` Matrimonial"><span itemprop="title">~$CASTE` Matrimony</span></a></span> &rsaquo; ~/if`~if $profileLinkArr['BRIDE_GROOM_LINK'] neq ''`<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="~sfConfig::get('app_site_url')`~$profileLinkArr['BRIDE_GROOM_LINK']`" itemprop="url" class="colrw" title="~FieldMap::getFieldLabel("community_small",$profile->getMTONGUE())` ~if $PROFILEGENDER eq 'Male'`Grooms~else`Brides~/if`"><span itemprop="title">~FieldMap::getFieldLabel("community_small",$profile->getMTONGUE())`~if $PROFILEGENDER eq 'Male'` Grooms~else` Brides~/if`</span></a></span> &rsaquo; ~/if`~$TopUsername`</p>
~/if`
<!--Breadcrumb ends here-->


      <!--start:photo div 1-->
       <div class="prfbg1 clearfix pos-rel"> 
        
        <!--start:photo-->
        ~if $arrOutDisplay['pic']['pic_count'] eq "0"`
        <div class="fl pos-rel imgSize" data="~$arrOutDisplay['pic']['pic_count']`,~$arrOutDisplay['about']['username']`,~$arrOutDisplay['page_info']['profilechecksum']`">
        ~else`
         <div class="fl pos-rel imgSize photoClick js-searchTupleImage cursp ~if !$loginProfile->getPROFILEID()` loginLayerJspc loginAlbumSearch ~/if`" data="~$arrOutDisplay['pic']['pic_count']`,~$arrOutDisplay['about']['username']`,~$arrOutDisplay['page_info']['profilechecksum']`">
         ~/if`
          <div class="prfpos2 pos-abs">
            ~if $arrOutDisplay['pic']['pic_count'] neq "0"` <div class="disp-tbl prfclr1 prfdim1 prfrad1 colrw txtc">
             <div class="vmid disp-cell fontlig">~$arrOutDisplay['pic']['pic_count']`</div>
            </div>~/if`
          </div>
           <div class="imgSizeParent bgColorG scrollhid">
            <img src="~$arrOutDisplay['pic']['url']`" class="brdr-0 vtop imgSize" oncontextmenu="return false;" onmousedown="return false;"  alt=""/>
           </div>
            ~if $arrOutDisplay['pic']['action']`
            <div id="requestphoto" class="pos-abs propos6 fullwid f14 fontlig cursp ~if $loginProfile->getPROFILEID()`js-hasaction~else`loginLayerJspc~/if`" data='~$arrOutDisplay['page_info']['profilechecksum']`' myaction='~$arrOutDisplay['pic']['action']`'>
              <div class=" bg5 txtc fontlig f14 colrw lh50">~$arrOutDisplay['pic']['label']`</div>
            </div>
            ~else`
            <div id="requestphoto"  class="pos-abs srppos4 f14 fontlig fullwid js-noaction">
              <div class=" txtc colrw opa80 mauto fullwid pos-abs propos10">~$arrOutDisplay['pic']['label']`</div>
            </div>
            ~/if`
           </div>
        <!--end:photo--> 
        ~include_Partial("profile/jspcViewProfile/_jspcViewProfileBasicDetailsSection",["apiData"=>$arrOutDisplay,"finalResponse"=>$finalResponse,"loginProfileId"=>$loginProfile->getPROFILEID(),"nameOfUser"=>$nameOfUser,"dontShowNameReason"=>$dontShowNameReason,"SAMEGENDER"=>$SAMEGENDER,"showIdfy"=>$arrOutDisplay["showIdfy"]])`
        
      </div>
      <!--end:photo div 1-->
      <div class="mt13">
        <div class="fullwid clearfix"> 
          <!--start:left div-->
          <div class="fl prfwid3 bg-white"> 
            
            <!--start:top nav section-->
            <div class="tabs-style-prf fontlig f15 prfp4 prfbr2 pos-rel">
              <div class="moveline"></div>
              <nav>
                <ul class="clearfix ">
                  <li data-attr="1"> <a href="#section-a">
                    <div class="sprite2 prfic8 mauto"></div>
                    <div class="pt10">About ~if $arrOutDisplay["about"]["gender"] eq "Female"`Her ~else`Him~/if`</div>
                    </a> </li>
                  <li data-attr="2"> <a href="#section-career">
                    <div class="sprite2 prfic9 mauto"></div>
                    <div class="pt10">Education & Career</div>
                    </a> </li>
                  <li  data-attr="3"> <a href="#section-family">
                    <div class="sprite2 prfic10 mauto"></div>
                    <div class="pt10">Family Details</div>
                    </a> </li>
                  <li data-attr="4"> <a href="#section-d">
                    <div class="sprite2 prfic11 mauto mt4"></div>
                    <div class="pt11">Desired Partner</div>
                    </a> </li>
                </ul>
              </nav>
            </div>
            <!--end:top nav section-->
            <!--start:content section-->
            <div class="f15 fontlig  color11 prfcont"> 
              <!--start:about us-->
              ~include_Partial("profile/jspcViewProfile/_jspcViewProfileAboutPersonSection",["apiData"=>$arrOutDisplay])`
              <!--end:about us--> 
              <!--start:Education-->
              ~include_Partial("profile/jspcViewProfile/_jspcViewProfileEducationSection",["apiData"=>$arrOutDisplay])`
              <!--end:Education--> 
              <!--start:Family Details-->
              ~include_Partial("profile/jspcViewProfile/_jspcViewProfileFamilySection",["apiData"=>$arrOutDisplay])`
              <!--end:Family Details--> 
               <!--start:Lifestyle-->
              ~include_Partial("profile/jspcViewProfile/_jspcViewProfileLifestyleSection",["apiData"=>$arrOutDisplay])`

              <!--end:Lifestyle--> 
              <!--start:She Likes-->
              ~include_Partial("profile/jspcViewProfile/_jspcViewProfileHobbiesSection",["apiData"=>$arrOutDisplay])`
              <!--end:She Likes--> 
              <!--start:Desired Partner-->
              ~include_Partial("profile/jspcViewProfile/_jspcViewProfileDesiredPartnerSection",['apiData'=>$arrOutDisplay,'matchingFields'=>$CODEDPP,'loginProfile'=>$loginProfile])`
              <!--end:Desired Partner--> 
              <!--start:like her profile-->
              <!-- <div class="prfp10">
              	<div class="mauto prfwid11">
                	<div class="fullwid clearfix">                    	
                        <div class="fl">
                        	<div class="prfbr5 prfdim3 prfrad">                            	
                        		<img src="~sfConfig::get('app_img_url')`/images/jspc/viewProfileImg/srch_image1.jpg" class="prfdim2 prfrad prfm2"/>
                         	</div>
                        </div>
                        ~if $arrOutDisplay["about"]["gender"] eq "Female"`
                        <div class="fl fontlig f15 color11 prfp9">
                        	Like her profile? Send her Interest
                        </div>
                        ~else`
                        <div class="fl fontlig f15 color11 prfp9">
                          Like his profile? Send him Interest
                        </div>
                        ~/if`
                        <div class="fl">
                        	<div class="bdr-rad2 prfdim3 prfbr5">
                        		<img src="~sfConfig::get('app_img_url')`/images/jspc/commonimg/no-img-m.jpg" class="prfdim2 bdr-rad2 prfm2"/>
                            </div>
                        </div>                    
                    </div>                
                </div>              
              </div>               -->
              <!--end:like her profile-->
              <!--start:bottom bar-->
              <div class="prfbg2">
              	<div class="prfp11">
                	<div class="fullwid clearfix">
                    	<div class="fl fontlig">
                        	<p class="f15 color11">Profile managed by ~$arrOutDisplay['about']['profile_posted']`</p>
                            <p class="f13 prfcolr2">Last updated on ~$arrOutDisplay['about']['last_mod']`</p>
                        </div>
                    </div>
                </div>              
              </div>              
              <!--end:bottom bar-->
              
            </div>
            <!--end:content section--> 
          </div>
          <!--end:left div--> 
          <!--start:right div-->
          <div class="fr">
          	<div class="prfscroll1 pos-rel">
                <!--start:gunna match-->
                ~include_Partial("profile/jspcViewProfile/_jspcViewProfileAstroSection",["apiData"=>$arrOutDisplay,myProChecksum=>$myProfileChecksum,"loginProfileId"=>$loginProfile->getPROFILEID()])`
                <!--end:gunna match-->
                <!-- start: document provided section -->
                ~include_Partial("profile/jspcViewProfile/_jspcViewProfileDocumentProvidedSection",["apiData"=>$arrOutDisplay])`
                <!-- end: document provided section -->
                <!--start:similar profile-->
                 ~include_Partial("profile/jspcViewProfile/_jspcViewProfileSimilarProfile",["apiData"=>$arrOutDisplay])`
                
                <!--endl:similar profile-->
                <div class="txtc prfwid12 mt20" id="zt_~$zedo['masterTag']`_side"> </div>
            </div>
          </div>
          <!--end:right div--> 
        </div>
      </div>
    </div>
  </div>

<!--start:footer-->
<footer> 
  <!--start:footer band 1-->      
     ~include_partial('global/JSPC/_jspcCommonFooter')`
    </div>
  </div>
  <!--endt:footer band 1--> 
  
</footer>
<!--end:footer--> 
  </div>
</div>
<script>
    var ProCheckSum = "~$arrOutDisplay["page_info"]["profilechecksum"]`";
    var ViewedUserName = "~$arrOutDisplay["about"]["username"]`";
    var sameGender = "~$arrOutDisplay["about"]["sameGender"]`";
    var photoArr = {};
    photoArr = "~$arrOutDisplay["pic"]|decodevar`";
    var senderEmail = "~$loggedInEmail`";
    var viewedProfileUsername = "~$arrOutDisplay["about"]["username"]`";
    var selfProChecksum = "~$myProfileChecksum`";
    var searchId = "~$searchid`";
    var selfUsername='~$loginProfile->getUSERNAME()`';
    var selfEmail='~$loginProfile->getEMAIL()`';
    var hideUnimportantFeatureAtPeakLoad='~JsConstants::$hideUnimportantFeatureAtPeakLoad`';
</script>
