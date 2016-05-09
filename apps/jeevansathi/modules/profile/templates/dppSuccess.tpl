<script>
var page='edit1';
</script>
<style>
.subhd2 {background-color:#f5f5f5; width:300px; padding:4px; margin-bottom:6px;}
</style>
 <?php include_partial('global/header') ?>
 <!--Header ends here-->
<!--Main container starts here-->
<!--pink strip starts here-->
<div id="main_cont">	
  <p class="clr_4"></p>
<div id="topSearchBand"></div>
<?php include_partial('global/sub_header') ?>
<!--slide-bluetop ends here-->
   <p class="clr_4"></p>
   <p class="clr_4"></p>
<!--orange strip starts here here>
<div class="stripyl">
	<div class="stripylc">
   <p class="lf">
   <b style="color:#e58c00;text-decoration:underline;" class="jshup sprte">Jeevansathi Suggests</b>&nbsp;Upload more photos and get 10% more response from members</p>			   <p class="rf"><a href="#"><img src="~sfConfig::get(app_img_url)`/images/js_sug_ic1.gif" border="0" /></a>&nbsp;<a href="#"><img src="~sfConfig::get(app_img_url)`/images/js_sug_ic2.gif" border="0"/></a></p>
   </div>
</div-->
<!--orange strip ends here here-->
<!--top tab  start here-->
    <p class="clr"></p>
    <p class=" clr"></p>
    <p class=" clr_4"></p>
<div class="lf t12 b" style="width:753px;padding:5px; margin-right:6px;">
~include_partial("profile/dppPart",['loginProfile'=>$loginProfile,'dpartner'=>$dpartner,'casteLabel'=>$casteLabel,'religionSelf'=>$religionSelf,'isEdit'=>1,'apEditMsg'=>$apEditMsg,'editDpp'=>1,'show_nhandicap'=>$show_nhandicap,'clicksource'=>$clicksource,'logic_used'=>$logic_used,'subHeading'=>$subHeading,'subHeadingLinkText'=>$subHeadingLinkText,'subHeadingLogic'=>$subHeadingLogic])`
</div>
  
 <p class=" clr_2"></p>

<p class="clr_18"></p>
</div>
~include_partial('global/footer',[NAVIGATOR=>~$NAVIGATOR`,G=>$G,data=>$loginProfile->getPROFILEID()])`
~if $flag eq "INTM"`
<SCRIPT>
$.colorbox({href:"~$SITE_URL`/profile/edit_dpp.php?width=600&flag=INTM&oldFlag=~$oldFlag`"});
</SCRIPT>
~/if`
~if $EditWhatNew`
<SCRIPT>
function pop_layer()
{
	var url;
	~if $EditWhatNew eq 'Dpp_Info'`
		url="~$SITE_URL`/profile/edit_dpp.php?width=700&flag=PPA&FLAG=partner&relation=trim(\"~$RELATION`\")";
	~/if`

	~if $EditWhatNew eq 'Dpp_Details'`
		 url="~$SITE_URL`/profile/edit_dpp.php?width=600&flag=PPBD&FLAG=partner&profilechecksum=~$profileChecksum`&gli=~$GENDER`&APeditID=";
	~/if`

	~if $EditWhatNew eq 'Dpp_Religion'`
		url="~$SITE_URL`/profile/edit_dpp.php?width=700&flag=PPRE&FLAG=partner&profilechecksum=~$profileChecksum`&gli=~$GENDER`&APeditID=";
	~/if`

	~if $EditWhatNew eq 'Dpp_Life'`
		url="~$SITE_URL`/profile/edit_dpp.php?width=600&flag=PPLA&FLAG=partner&profilechecksum=~$profileChecksum`&gli=~$GENDER`&APeditID=";
	~/if`
	
	~if $EditWhatNew eq 'Dpp_Edu'`
		url="~$SITE_URL`/profile/edit_dpp.php?width=600&flag=PPEO&FLAG=partner&profilechecksum=~$profileChecksum`&gli=~$GENDER`&APeditID=";
	~/if`

	if(url)
	{
		$.colorbox({href:url});
	}	
	return;
}

$(document).ready(function() {
	pop_layer();
});
</SCRIPT>
~/if`
