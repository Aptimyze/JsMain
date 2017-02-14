<script>
  ~if $noIndexNoFollow`
  var m = document.createElement('meta'); 
  m.name = 'ROBOTS'; 
  m.content = 'NOINDEX, NOFOLLOW'; 
  document.head.appendChild(m);
  ~/if`
</script>
<div class="pos-rel fullwid">   
  <!--start:top part-->
  <div class="prf-coverNoPro" style="height:387px">
    <div class="container mainwid pt35"> 
      <!--start:top nav case logged in--> 
        <!--start:logo-->
        ~include_partial('global/JSPC/_jspcCommonTopNavBar')`
        <!--end:logo--> 
    </div>
      <!--end:top nav case logged in--> 
    <div class = "pt100 txtc color colrw fontlig">
        ~if $LOGIN_REQUIRED`
        <div>
            <p class="f26">Login Required</p>
            <p class="f15 pt30 wid280 mauto">To see this profile <span class="loginLayerOnShareClick cursp colorC3">Login</span> or <a class="colorC3" href="/register/page1?source=pd_login">Register</a></p>
        </div>
        ~/if`
        ~if $MESSAGE`
        <div>
            <p class="f26">~$PRIMARYMESSAGE`</p>
            <p class="f15 pt30 wid280 mauto">~$MESSAGE|decodevar`</p>
        </div>
        ~/if`
     </div>
    </div>
</div>
<div class="bg4 blankHgt">
    <div class="pos-rel container mainwid settop1">
        ~if $SHOW_NEXT_PREV eq null or $SHOW_NEXT_PREV eq 0 or $searchIdExpired eq 1`
        <div class="srppad4" id="srpOpaque"> 
            ~include_partial("search/JSPC/searchBand",["searchSummaryFormatted"=>"",'populateDefaultValues'=>$populateDefaultValues,'staticSearchData'=>$staticSearchDataArray,'loggedIn'=>$loggedIn,'pageSource'=>"noProfile"])`
        </div>
        ~/if`
    </div>        
</div>
~if $SHOW_NEXT_PREV && $searchIdExpired neq 1`
  <!--start:next/previous button-->
  ~if $SHOW_PREV`
  	<!--start:prv-->
    <a id="show_prevListingProfile" ~if isset($prevLink)`href ="/profile/viewprofile.php?~$prevLink|decodevar`&stype=~$STYPE`&responseTracking=~$responseTracking`~$other_params|decodevar`&~$NAVIGATOR`"~else`href ="/profile/viewprofile.php?show_profile=prev&total_rec=~$total_rec`&actual_offset=~$actual_offset`&j=~$j`&responseTracking=~$responseTracking`&searchid=~$searchid`~$other_params|decodevar`&~$NAVIGATOR`&tupleId=~$preTupleId`"~/if`>
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

    <a id="show_nextListingProfile" ~if isset($nextLink)`href ="/profile/viewprofile.php?~$nextLink|decodevar`&stype=~$STYPE`&responseTracking=~$responseTracking`~$other_params|decodevar`&~$NAVIGATOR`"~else`href ="/profile/viewprofile.php?show_profile=next&total_rec=~$total_rec`&actual_offset=~$actual_offset`&j=~$j`&responseTracking=~$responseTracking`&searchid=~$searchid`~$other_params|decodevar`&~$NAVIGATOR`&tupleId=~$nextTupleId`"~/if`>
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
<!--start:footer-->
<footer> 
  <!--start:footer band 1-->      
     ~include_partial('global/JSPC/_jspcCommonFooter')`
    </div>
  </div>
  <!--endt:footer band 1--> 
  
</footer>
<!--end:footer--> 
