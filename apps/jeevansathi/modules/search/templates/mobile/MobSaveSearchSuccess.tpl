<div class="perspective" id="perspective">
<div class="pcontainer" id="pcontainer">
<div class="fullwid bg4 fontlig" id="saveSearch"> 
  <!--start:div-->
  <div class="bg1 photoheader">
    <div class="pad1">
         <div class="rem_pad1 posrel fullwid ">
           <div class="posabs" style="left:0;top:18px;">
		<i id ="hamburgerIcon" class="mainsp baricon " hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i>
	   </div>
	   ~if $savedSearches && $savedSearches|@count gt 0`
	     <a  href="javascript:void(0)" class="posabs white fontthin f16 OpenManagelayer" style="right:0;top:18px;">Manage</a>
           ~/if`
	   <div class="white fontthin f19 txtc">Saved Searches <span class="fontlig white opa50 f15 dispibl padl10">~$savedSearches|@count`</span></div>
	 </div>
     </div>
   </div>
         ~if $savedSearches && $savedSearches|@count gt 0`
           <div id="savedSearches">
	      ~include_partial("search/mobSearch/savedSearch",[savedSearches=>$savedSearches,maxSaveSearches=>$maxSaveSearches])`
	   </div>
         ~else`
           <div id="zeroSaved">
	     <div class="svasrc-pada txtc fontreg f16">
	        <div class="color8">Tap on Save icon after performing search</div>
	        <div class="pt10 color1 lh25">Saving searches helps you save time and categorize your matches better</div>
	     </div>
	     <div class="fullwid txtc pad1 ">
	     <div class="posrel">
	        <img src="~sfConfig::get('app_img_url')`/images/jsms/searchImg/0search.png" class="border0 classimg3"/>
	        <div class="posabs fullwid" style="bottom:10px">
			<button class="savsrc-button fontlig f16 white txtc bg7 lh50 fullwid border0 sacsrc-class1" id="performSearch">
				Perform A Search Now
			</button>
	        </div>
	        </div>
	      </div>
	    </div>
		      
         ~/if`
        <div class="clr"></div>
      </div>
    
      
    </div>
    <div id="hamburger" class="hamburgerCommon dn fullwid">	
	~include_component('static', 'newMobileSiteHamburger')`	
      </div>
  </div>
  <!--end:div--> 
