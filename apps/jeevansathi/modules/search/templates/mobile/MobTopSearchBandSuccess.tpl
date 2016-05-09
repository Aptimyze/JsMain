<script type="text/javascript">
      var t_jsstart = new Date().getTime();
      var DualHamburger=0;
      AndroidPromotion=0;
    </script>
    <div class="perspective" id="perspective">
<div class="pcontainer" id="pcontainer">
<div class="fullwid bg4 fontlig" id="searchMainForm"> 
  <!--start:div-->
  <div class="bg1 photoheader">
    <div class="pad1">
      <div class="rem_pad1 posrel fullwid ">
      <div class="posabs" style="left:0;top:18px;">
      <i id ="hamburgerIcon" class="mainsp baricon " hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i>
      </div>
        <div class="white fontthin f19 txtc">Search Your Match</div>
         ~if $savedSearches && $savedSearches|@count gt 0`
	    <div id="savedSearchIcon" class="posabs savsrc-pos1">
                <div class="posrel"> <i class="savsrc-sp savsrc-icon1"></i> 
                  <!--start:number-->
                  <div class="posabs savsrc-pos2">
                    <div class="posrel"> <i class="mainsp savsrc-circle"></i>
                      <div class="posabs color6 f12 savsrc-pos3">~$savedSearches|@count`</div>
                    </div>
                  </div>
                  <!--end:number--> 
                </div>
             </div>
           ~/if`
        <div class="clr"></div>
      </div>
    </div>
  </div>
  <!--end:div--> 
  <!--start:tab-->
  <div id="search_gender" data="~$gender`">
  ~if !$loggedIn`
  <div class="pad3 brdr1">
    <div class="brdr12 fullwid">
     <div data="F" id="searchform_gender1" class='wid49p txtc fl dispbl ~if $gender neq "M"`bg7 ~/if` pad21 f13'>
      <div class='txtc ~if $gender neq "M"` white ~else` color2 ~/if` wid140'>
        <div class="lh30">Bride</div>
      </div>
      </div>
      <div data="M" id="searchform_gender2" class='wid49p txtc  fr dispbl pad21 f13 ~if $gender eq "M"`bg7 ~/if`'>
      <div class='txtc ~if $gender eq "M"` white ~else` color2 ~/if` wid140'>
        <div class="lh30">Groom</div>
      </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  ~/if`
  </div>
  <!--end:tab--> 
  <!--start:age-->
  ~append var='minArray' value='LAGE' index=0`
  ~append var='minArray' value='LHEIGHT' index=1`
  ~append var='minArray' value='LINCOME' index=2`
  ~append var='maxArray' value='HAGE' index=0`
  ~append var='maxArray' value='HHEIGHT' index=1`
  ~append var='maxArray' value='HINCOME' index=2`
  ~foreach from=$dropdowns item=value key=kk`
  ~if !in_array($kk,$maxArray)`
	~if !in_array($kk,$minArray)`
		<div class="brdr1" id="search_~$kk`" ~if $value["dd"]["dropdownmenu"] eq 1` dropdownmenu=~$value["dd"]["dropdownmenu"]` dmove=~$value["dd"]["dmove"]` dshow=~$value["dd"]["dshow"]` dhide=~$value["dd"]["dhide"]` dselect=~$value["dd"]["dselect"]` ~if $value["dd"]["dependant"]` dependant=~$value["dd"]["dependant"]` dcallback="UpdateSectionWithDependant" ~else` dcallback="UpdateSection"~/if` haveSearch=~$value["dd"]["haveSearch"]` ~/if`>
	~else`
		<div class="brdr1">
	~/if`
  <div class="pad18">
   ~/if`
	  ~if in_array($kk,$minArray) || in_array($kk,$maxArray)`
		<div class="wid40p ~if in_array($kk,$maxArray)` fr mrr5 ~else` fl ~/if`">
        <div class="fullwid" id="search_~$kk`" ~if $value["dd"]["dropdownmenu"] eq 1` dropdownmenu=~$value["dd"]["dropdownmenu"]` dmove=~$value["dd"]["dmove"]` dshow=~$value["dd"]["dshow"]` dhide=~$value["dd"]["dhide"]` dselect=~$value["dd"]["dselect"]` ~if $value["dd"]["dependant"]` dependant=~$value["dd"]["dependant"]` dcallback="UpdateSectionWithDependant" ~else` dcallback="UpdateSection"~/if` haveSearch=~$value["dd"]["haveSearch"]` ~/if`>

	  ~/if`
          <div class="fl wid94p srfrm_wrap">
            <div class="color8 f12">~$value["dd"]["mylabel"]`</div>
            <div class="color8 f17 pt10" data=~$value["value"]`><span class="label wid70p">~$value["label"]`</span>
             ~if $kk eq "LAGE" || $kk eq "HAGE"`
				Years
             ~/if`
            ~if $value["valueDependant"]`
				<span class="dependant f13 color7" data=~$value["valueDependant"]["data"]`>~$value["valueDependant"]["value"]`</span>
			~/if`
			</div>	
          </div>
          <div class="fr wid4p pt8"> <i class="mainsp arow1"></i> </div>
          
       ~if in_array($kk,$minArray) || in_array($kk,$maxArray)`
            <div class="clr"></div>
			</div>
			</div>
	   ~/if`
	   ~if !in_array($kk,$minArray)`<div class="clr"></div>
	    </div>
	   	</div>
  ~/if`
  ~/foreach`
  <!--end:age--> 
  <!--start:tab-->
  <div class="pad3">
    <div id="search_photo" data="~$havephoto`" class="brdr12 fullwid"> 
      <div data="" id="searchform_photo1" class='wid49p txtc fl dispbl ~if $havephoto neq "Y"`bg7 ~/if` pad21 f13'>
      <div class='txtc ~if $havephoto neq "Y"` white ~else` color2 ~/if` wid140'>
        <div class="lh30">All Profiles</div>
      </div>
      </div>
      <div data="Y" id="searchform_photo2" class='wid49p txtc fr dispbl ~if $havephoto eq "Y"`bg7 ~/if` pad21 f13'>
      <div class='txtc ~if $havephoto eq "Y"` white ~else` color2 ~/if` wid140'>
        <div class="lh30">Profile with Photos</div>
      </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <!--end:tab--> 
<!--start:age-->
~foreach from=$moredropdownArr item=moredropdown key=key1`
  ~append var='minArray' value='LAGE' index=0`
  ~append var='minArray' value='LHEIGHT' index=1`
  ~append var='minArray' value='LINCOME' index=2`
  ~append var='maxArray' value='HAGE' index=0`
  ~append var='maxArray' value='HHEIGHT' index=1`
  ~append var='maxArray' value='HINCOME' index=2`
        <div class="showmorelink pad18 txtc bg6 ~$moredropdown['showhide']['showMore']`"  id="moreoptions~$key1`" rel="~$key1`" >
                <span class="moreoptions color8">~$moredropdown['showhide']['showMore_label']` </span><i class="mainsp arow7 fr"></i>
        </div>
        <div class="showlesslink pad18 txtc ~$moredropdown['showhide']['showLess']`"  id="lessoptions~$key1`" rel="~$key1`" >
                <span class="lessoptions">~$moredropdown['showhide']['showLess_label']` </span><i class="arow8 fr"></i>
        </div>
  <div id="moreoptblock~$key1`" class="~$moredropdown['showhide']['showLess']`">
  ~foreach from=$moredropdown['ddData'] item=value key=kk`
  ~if !in_array($kk,$maxArray)`
	~if !in_array($kk,$minArray)`
		<div class="brdr1" id="search_~$kk`" ~if $value["dd"]["dropdownmenu"] eq 1` dropdownmenu=~$value["dd"]["dropdownmenu"]` dmove=~$value["dd"]["dmove"]` dshow=~$value["dd"]["dshow"]` dhide=~$value["dd"]["dhide"]` dselect=~$value["dd"]["dselect"]` ~if $value["dd"]["dependant"]` dependant=~$value["dd"]["dependant"]` dcallback="UpdateSectionWithDependant" ~else` dcallback="UpdateSection"~/if` haveSearch=~$value["dd"]["haveSearch"]` ~/if`>
	~else`
		<div class="brdr1">
	~/if`
  <div class="pad18">
   ~/if`
	  ~if in_array($kk,$minArray) || in_array($kk,$maxArray)`
		<div class="wid40p ~if in_array($kk,$maxArray)` fr mrr5 ~else` fl ~/if`">
        <div class="fullwid" id="search_~$kk`" ~if $value["dd"]["dropdownmenu"] eq 1` dropdownmenu=~$value["dd"]["dropdownmenu"]` dmove=~$value["dd"]["dmove"]` dshow=~$value["dd"]["dshow"]` dhide=~$value["dd"]["dhide"]` dselect=~$value["dd"]["dselect"]` ~if $value["dd"]["dependant"]` dependant=~$value["dd"]["dependant"]` dcallback="UpdateSectionWithDependant" ~else` dcallback="UpdateSection"~/if` haveSearch=~$value["dd"]["haveSearch"]` ~/if`>

	  ~/if`
          <div class="fl wid94p srfrm_wrap">
            <div class="color8 f12">~$value["dd"]["mylabel"]`</div>
            <div class="color8 f17 pt10" data=~$value["value"]`><span class="label wid70p">~$value["label"]`</span>
             ~if $kk eq "LAGE" || $kk eq "HAGE"`
				Years
             ~/if`
            ~if $value["valueDependant"]`
				<span class="dependant f13 color7" data=~$value["valueDependant"]["data"]`>~$value["valueDependant"]["value"]`</span>
			~/if`
			</div>	
          </div>
          <div class="fr wid4p pt8"> <i class="mainsp arow1"></i> </div>
          
       ~if in_array($kk,$minArray) || in_array($kk,$maxArray)`
            <div class="clr"></div>
			</div>
			</div>
	   ~/if`
	   ~if !in_array($kk,$minArray)`<div class="clr"></div>
	    </div>
	   	</div>
  ~/if`
  ~/foreach`
  </div>
  ~/foreach`
  <!--end:age--> 
  <!--start:Next-->
  <div class=""><div style="position: relative; overflow: hidden;">
                   <div id="search_submit" class="bg7 white lh30 fullwid dispbl txtc lh50 pinkRipple">Search</div>
        </div> </div>
  <div id="search_form"></div>
  ~if $savedSearches && $savedSearches|@count gt 0`
  <div id="savedSearches">
	<!--start:save search-->
     <div class="pt22">           
	  <!--start:div-->
	  <div class="brdr1">
	    <div class="pad18">
	      <div class="fullwid clearfix">
		<div class="fl wid10p"><i class="savsrc-sp savsrc-icon2"></i></div>
		
		<div class="fl savsrc-mrt2 wid90p savsrc-ft1">
			<div>
				<div class="fl dispibl color2">Saved Searches (~$savedSearches|@count`)</div>
			</div>
			<a id="manageSavedSearch" href="javascript:void(0);" class="OpenManagelayer dispibl fr color8 padl20" style="text-align: right;">Manage</a>
		</div>
	       </div>
	    </div>
	  </div>
	<!--end:div--> 
        ~include_partial("search/mobSearch/savedSearch",[savedSearches=>$savedSearches,maxSaveSearches=>$maxSaveSearches])`
	
     </div>
  </div>
  ~/if`

  <!--end:Next--> 
  
</div>
</div>
<div class="hamburger dn" id="dropdown" >
~include_partial("search/mobSearch/dropdown")`
</div>
<div id="hamburger" class="hamburgerCommon dn fullwid">	
	~include_component('static', 'newMobileSiteHamburger')`	
</div>
<div class="hamoverlay" id="dropdownoverlay"></div>
</div>
<script>	
var fieldArray = "~$searchFields`";
$(document).ready(function(){
~if $noResultFound`
showSlider('','No results found.<br>Kindly broaden your search criteria and try again','','',4000);
~else`
	if(!ISBrowser("UC") && !ISBrowser("safari"))
	{
		var myLoc = $(location).attr('href');
	        if (myLoc.indexOf("random") >= 0)
	        {
	                document.cookie="jssf=John";
	        }
	        else
	                document.cookie = "jssf=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
	
	}

~/if`
});
</script>
