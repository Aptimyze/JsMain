~assign var=zedoValue value= $sf_request->getAttribute('zedo')`
~assign var=zedo value= $zedoValue["zedo"]`
<!--start:header-->
	~include_partial('search/JSPC/_jspcViewSimilarProfileHeader')`
<!--end:header--> 
~include_partial('global/JSPC/_jspcContactEngineButtons')`
~include_partial("photoAlbum")`

<!--start:middle part-->
<div class="bg-4">
	<!-- loader -->
	<div id="searchResultsLoaderTop" class="searchResultsLoader disp-none mainwid container" style="padding-top:100px;padding-bottom:100px;text-align:center;">
		<img src="~sfConfig::get('app_img_url')`/images/jspc/commonimg/loader_card.gif" style="vertical-align: middle; margin: 0pt 20px 0pt 0pt;">
	</div>
	<!-- loader -->
	<div class="container mainwid pt35" id="vspMainDiv">
		 ~if $contactedProfileDetails eq "show"`
		 ~if $dontShowBreadcrumb eq 0`
		<p class="pb10 mtn24 color11 f15 fontlig js-limitW cursp" id="vspBackLink">Back to Profile of ~$Username`</p>
		~/if`
		<!--start:div 1-->
      <!--start:photo div 1-->
       <div class="prfbg1 clearfix pos-rel" id="contactedProfileDetailsDiv"> 
        
        <!--start:photo-->
        ~if $arrOutDisplay['pic']['pic_count'] eq "0"`
        <div class="fl pos-rel imgSize" data="~$arrOutDisplay['pic']['pic_count']`,~$arrOutDisplay['about']['username']`,~$arrOutDisplay['page_info']['profilechecksum']`">
        ~else`
         <div class="fl pos-rel imgSize photoClick js-viewTupleImage cursp" data="~$arrOutDisplay['pic']['pic_count']`,~$arrOutDisplay['about']['username']`,~$arrOutDisplay['page_info']['profilechecksum']`">
         ~/if`
          <div class="prfpos2 pos-abs">
            ~if $arrOutDisplay['pic']['pic_count'] neq "0"` <div class="disp-tbl prfclr1 prfdim1 prfrad1 colrw txtc">
             <div class="vmid disp-cell fontlig">~$arrOutDisplay['pic']['pic_count']`</div>
            </div>~/if`
          </div>
           <div class="imgSizeParent bgColorG scrollhid">
            <img src="~$arrOutDisplay['pic']['url']`" class="brdr-0 vtop imgSize" alt=""/>
           </div>
            ~if $arrOutDisplay['pic']['action']`
            <div id="requestphoto" class="pos-abs srppos3 propos6 f14 fontlig fullwid cursp js-hasaction" data='~$arrOutDisplay['page_info']['profilechecksum']`' myaction='~$arrOutDisplay['pic']['action']`'>
              <div class=" bg5 txtc fontlig f14 colrw lh50">~$arrOutDisplay['pic']['label']`</div>
            </div>
            ~else`
            <div id="requestphoto"  class="pos-abs srppos4 propos6 f14 fontlig fullwid js-noaction">
              <div class=" txtc colrw opa80 mauto fullwid pos-abs propos6">~$arrOutDisplay['pic']['label']`</div>
            </div>
            ~/if`
           </div>
        <!--end:photo--> 
        ~include_Partial("search/JSPC/_jspcViewSimilarBasicDetailsSection",["apiData"=>$arrOutDisplay,"contactedProfilechecksum"=>$contactedProfilechecksum,"finalResponse"=>$finalResponse,"actions_buttonsVSP"=>$actions_buttonsVSP])`
        
      </div>
      
        <!--end: div-->
      <!--end:photo div 1-->
      ~/if`
      
		<p class="f20 fontlig color11 pb20 pt50" id="VSPResultsHeading"></p>   
		<!--start:div2-->
			<!--<div id="featuredListing" class="scrollhid">
				<div id="featuredFirstResultsBlock">
				</div>
				<div id="featuredResultsBlock" class="disp-none" style="overflow:hidden;">
				</div>
                <div class="srpbdr5 clearfix cursp disp-none pos-rel" id="featuredProfiles" style="top: -7px;">
					<div class="fr pos-rel labelFeatured" >
						<div class="pos-abs triangle-topright srppos2"></div>
						<div class="bg_pink f12 fontlig colrw txtc wid165 srppad15">Featured Profile  <span id="featuredMoreMsg"></span><span class="showLessFeatured disp-none">(Show Less)</span> </div>
						<div class="pos-abs disp-none triangle-topleft srppos2right"></div>
					</div>
				</div>
			</div>-->
			<!--start:VSP page data-->
			<div class="clearfix"> 			
				<!--start:right-->
				<div class="fl wid725 mtn24" id="mainVSPTupleDiv">
					~include_partial("search/JSPC/_vspZeroResultsSection")` 				
					<!--start:srp box-->
					~include_partial("searchBasicTuple",["defaultImage"=>$defaultImage])`
					<!--end:srp box-->        
				</div>    
				<!--end:right--> 
				<!--start:left-->
				<div class="fr wid230 fontlig">
					<!--start:membership message div-->
						~include_partial("search/JSPC/_VSPMembershipDetails",["MembershipMessage"=>$MembershipMessage])`
					<!--end:membership message div-->

					<!--start:success stories div-->
						~include_partial('search/JSPC/_VSPsuccessStoryTuples')`
					<!--end:success stories div-->	
				</div>    
				<!--end:left--> 
			</div>
			<!--end:VSP page data-->
		<div class='m20 mt25' id='zt_~$zedo["masterTag"]`_searchbottom'> </div>
		<!--end:div2--> 		
	</div>
	<div class="hgt200"></div>
</div>
<!--start:footer-->
	~include_partial('global/JSPC/_jspcCommonFooter')`

<!--end:footer-->

<script type="text/javascript">
	var response = ~$firstResponse|decodevar`;
	var similarPageShow = ~$similarPageShow`;  // =1 if similar results exist otherwise =0
	var totalFeaturedProfiles = 0;  //added to make it consistent with search response
	var NAVIGATOR = "~$NAVIGATOR`";
	var contactedUsername = "~$Username`";  
	var showContactedProfileDetails = "~$contactedProfileDetails`";	
	var viewProfileBackParams = "~$viewProfileBackParams`";
</script>
