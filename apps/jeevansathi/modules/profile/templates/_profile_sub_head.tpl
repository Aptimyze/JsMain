<div class="txt_plus_ul11 fl">
    <ul class="tab">
    ~if $FROM_PROFILEPAGE`
       <li class="active">
       <a  onclick="return false" name="det_prof"><i></i><u class="b"><h1 style="color:#000">Detailed Profile ~if $TopUsername`of ~$TopUsername`~/if`&nbsp;</h1></u><i class="rightgap"></i></a></li>
      <li><a ~if !$stopAlbumView`href="~sfConfig::get("app_site_url")`/profile/albumpage?~if $curLink`&curLink=~$curLink`~else`~if $total_rec`total_rec=~$total_rec`~/if`~if $actual_offset_real`&actual_offset=~$actual_offset_real`~/if`~if $show_profile`&show_profile=~$show_profile`~/if`~if $j`&j=~$j`~/if`~if $searchid`&searchid=~$searchid`~/if`~$other_params`&~$NAVIGATOR`&profilechecksum=~$PROFILECHECKSUM`&~if $sf_request->getParameter('stype')`&stype=~$sf_request->getParameter('stype')`~/if`~if $sf_request->getParameter('clicksource')`&clicksource=~$sf_request->getParameter('clicksource')`~/if`~if $sf_request->getParameter('countlogic')`&countlogic=~$sf_request->getParameter('countlogic')`~/if`~if $sf_request->getParameter('matchalert_mis_variable')`&matchalert_mis_variable=~$sf_request->getParameter('matchalert_mis_variable')`~/if`~if $sf_request->getParameter('suggest_profile')`&suggest_profile=~$sf_request->getParameter('suggest_profile')`~/if`~if $sf_request->getParameter('CAME_FROM_CONTACT_MAIL')`&CAME_FROM_CONTACT_MAIL=~$sf_request->getParameter('CAME_FROM_CONTACT_MAIL')`~/if`~/if`~if $responseTracking`&responseTracking=~$responseTracking`~/if`"~/if`  rel="nofollow" ><i></i>
      <u class="b" ~if !$stopAlbumView`style="cursor:pointer"~/if`>Photos & more... </u><i class="rightgap"></i></a></li>
      ~else`
      <li>
       <a ~if $PROFILECHECKSUM` href="~sfConfig::get("app_site_url")`/profile/viewprofile.php?~if $curLink`&curLink=~$curLink`~else`~if $total_rec`total_rec=~$total_rec`~/if`~if $actual_offset_real`&actual_offset=~$actual_offset_real`~/if`~if $j`&j=~$j`~/if`~if $show_profile`&show_profile=~$show_profile`~/if`~if $searchid`&searchid=~$searchid`~/if`~$other_params`&~$NAVIGATOR`&profilechecksum=~$PROFILECHECKSUM`~if $sf_request->getParameter('stype')`&stype=~$sf_request->getParameter('stype')`~/if`~if $sf_request->getParameter('clicksource')`&clicksource=~$sf_request->getParameter('clicksource')`~/if`~if $sf_request->getParameter('countlogic')`&countlogic=~$sf_request->getParameter('countlogic')`~/if`~if $sf_request->getParameter('matchalert_mis_variable')`&matchalert_mis_variable=~$sf_request->getParameter('matchalert_mis_variable')`~/if`~if $sf_request->getParameter('suggest_profile')`&suggest_profile=~$sf_request->getParameter('suggest_profile')`~/if`~if $sf_request->getParameter('CAME_FROM_CONTACT_MAIL')`&CAME_FROM_CONTACT_MAIL=~$sf_request->getParameter('CAME_FROM_CONTACT_MAIL')`~/if`~/if`~if $responseTracking`&responseTracking=~$responseTracking`~/if`"  ~/if`><i></i><u class="b" style="cursor:pointer"><h1 class="b" style="color:#000000; font-size:15px;">Detailed Profile ~if $TopUsername`of ~$TopUsername`~/if`&nbsp;</h1></u><i class="rightgap"></i></a></li>
      <li class="active"><a rel="nofollow"><i></i>
      <u class="b" style="color:#000;">Photos & more... </u><i class="rightgap"></i></a></li>
      ~/if`
      
     ~if $SHOW_NEXT_PREV`
		~if $SHOW_PREV || $SHOW_NEXT`
		<li style="float:right;width:300px;padding-top:5px">
			<div class="lstnxt fr b">
				&nbsp;&nbsp;
				~if $SHOW_PREV`
				~if $fromPage eq contacts`
					<a href="/profile/viewprofile.php?~$prevLink`&responseTracking=~$responseTracking`#det_prof" class="lstnxt" style="color:#117DAA;">&lt; Previous Profile</a>
				~else`
				<a href="/profile/viewprofile.php?show_profile=prev&total_rec=~$total_rec`&responseTracking=~$responseTracking`&actual_offset=~$actual_offset`&j=~$j`&searchid=~$searchid`~$other_params`&~$NAVIGATOR`#det_prof" class="lstnxt" style="color:#117DAA;">&lt; Previous Profile</a>
				~/if`
				~else`
					 <span style="color:#000000;">Previous Profile</span>
				~/if`&nbsp;&nbsp;
				~if $SHOW_NEXT`
					~if $fromPage eq contacts`
					<a href="viewprofile.php?~$nextLink`&responseTracking=~$responseTracking`#det_prof" class="lstnxt" style="color:#117DAA;"> Next Profile &gt;</a>
					~else`
					<a href="/profile/viewprofile.php?show_profile=next&total_rec=~$total_rec`&actual_offset=~$actual_offset`&j=~$j`&responseTracking=~$responseTracking`&searchid=~$searchid`~$other_params`&~$NAVIGATOR`#det_prof" class="lstnxt" style="color:#117DAA;" >Next Profile &gt;</a>
					~/if`
				~else`
					 <span style="color:#000000;">Next Profile </span>
				~/if`	
			</div>
			</li>
		~/if`	
      ~/if`
<li style="margin:0px;padding:0px 10px 0px 0px">
<div class="lstlgn lsonl" style="margin:0px 0px 0px 0px">
</div></li>
   </ul>
<p class="clr"></p>
</div>
