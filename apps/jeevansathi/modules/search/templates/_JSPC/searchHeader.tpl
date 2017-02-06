<link rel="stylesheet" async=true type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700">
<header>
  <div class="cover1">
    <div class="container mainwid pt35"> 
					~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1])`
					<div>
					<!--start:modify-->
			      <div class="srppad4">
							~if !$searchListings`
			        ~include_partial("search/JSPC/searchBand",["searchSummaryFormatted"=>$searchSummaryFormatted,'populateDefaultValues'=>$populateDefaultValues,'staticSearchData'=>$staticSearchData,'loggedIn'=>$loggedIn,'stype'=>$stype])`  
							~/if`
			      </div>
			      <!--end:modify--> 
			      <!--start:search link-->
			      ~if !$searchListings`
				      <div class="f14 srppad6 ulinline clearfix">
				        <div class="fr fontreg f14">
				          <ul>
				            <li class="srpbdr1 pr10 mt5"><a class="js-srchbyid colrw cursp">Search by Profile ID</a></li>
				            <li class="~if $loggedIn eq 1` srpbdr1 ~/if` pl10 pr10 mt5"><a href="/search/AdvancedSearch" class="colrw cursp">Advanced Search</a></li>
				           ~if $loggedIn eq 1` <li class="pl10 colrw mt5 cursp" onclick="displaySavedSearches();">My Saved Searches<span class="disp_ib pl5" id="saveSearchCount">~if $saveSearchArray`~$saveSearchArray|count`~/if`</span></li> ~/if`
				          </ul>
				        </div>
				      </div>
				      <!--end:search link--> 
				      
				      
				      <!--start:saved searches-->
				      <div id="savedSearch" class="pb23 disp-none">
				      	<div id="equalheight"  class="clearfix">
				        	<!--start:saved search info box-->
				                <div id="savedSearchesListTop">
				                
				                </div>
				            <!--end:saved search info box-->
				            
				            <!--start:saved search info box-->
				            <div id="saveSearchExtraBox" class="fr wid190 equal disp-none color_blockthree mr10" style="min-height:100px;">
				            	<div class="fontlig disp-tbl equal txtc">	
				            		<div class="disp-cell vmid f12 opa50 padallm" style="height: 100px; min-height: 100px;"></div>
				                   
				                </div>
				            </div>              
				            
				            <!--end:saved search info box-->          
				        	
				        
				        </div>      
				      </div>
				      <!--end:saved searches-->
				     ~/if`
			     </div>
			    
	  </div>
  </div>
</header>
