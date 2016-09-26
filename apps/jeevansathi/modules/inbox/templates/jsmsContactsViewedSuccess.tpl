<script>
var vhgt = $(window).innerHeight();
var ol_vwid = $(window).width();
var isEvalue='~$isEvalue`';

if(isEvalue=='Y'){
var resp=~$responseJS|decodevar`;
$(document).ready(function () {
~if $firstResponse.profiles neq ''`
{
$("#mainContent").css({"height":vhgt+'px', "overflow":"auto"});
$("#noMsgDiv").css('height',$(window).height());
}
~else`
{
$("#mainContent").css({"height":vhgt+'px', "overflow":"hidden"});
$("#noMsgDiv").css('height',$(window).height());
}
~/if`
});
}
</script>

  <!--start:div-->
<div class="perspective" id="perspective">
<div class="" id="pcontainer">
	~include_partial("global/jsms3DotLayer")`
 <!--start:div-->
 
  <div id="phonebook_header" class="fullwid bg1">
    <div class="pad5">
      <div class="fl wid20p pt4"><i id ="hamburgerIcon" class="mainsp baricon " hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i></div>
      <div class="fl wid60p txtc color5  fontthin f19">Phonebook</div>
       <div class="clr"></div>
    </div>
  </div>
  <!--end:div--> 
  ~if $isEvalue eq 'Y'`
    <!--start:listing div--> 

    <div style="overflow-y: auto;" id="tupleContainer">
  ~if $firstResponse.profiles eq ''` 

	<div id="noMsgDiv" class="posrel fullwid" style="width: 1286px; height: 657px;">
		~if $gender eq 'M'`
		<img class="classimg1 blurred_bg_contacts_bride fullwid">
		~else`
		<img class="classimg1 blurred_bg_contacts_groom fullwid">
		~/if`
		<div class="posabs fullwid" style="z-index:105; top:40%;">
			<div class="pad1 txtc f16 fontlig">
			<p class="pt15">Contacts viewed by you would be shown here </p>
			</div>
		</div>
	</div>

	

~else`
<!--start:tuple-->


~foreach from=$firstResponse.profiles item=tupleInfo key=id`
~assign var=offset value=(($firstResponse.page_index-1)*$_SEARCH_RESULTS_PER_PAGE) +$id`
~assign var=idd value=$offset+1`
<div class="tupleOuterDiv searchNavigation bg4 padsp1 bbtsp1 inview">
    <div class="fullwid ">
		<div class="fl widrsp1 txtc">
			<a href="~$SITE_URL`/profile/viewprofile.php?profilechecksum=~$tupleInfo.profilechecksum`&~$NAVIGATOR`&stype=~SearchTypesEnums::PHONEBOOK_JSMS`&total_rec=~$firstResponse.no_of_results`&tupleId=~$idd`&searchid=~$firstResponse.searchid`&responseTracking=~JSTrackingPageType::PHONEBOOK_JSMS`&offset=~$offset`&contact_id=~$firstResponse.contact_id`&actual_offset=~$idd`" > 
			<img src="~$tupleInfo.photo.url`" class="brdr_radsrp classimg1 img_s_1 sImageClass" style="width:75px;height: 75px;"/> 
			</a>
			<div class="f13 fontlig"></div>
		</div>      
		<div class="fl padlr_1 widrsp2 posrel">
			<div class="posabs telicon" style="top:21px; right:5px;" onClick="showDirectContactDetailLayer('~$tupleInfo.photo.url`','~$tupleInfo.profilechecksum`');"; id="1_3Dots">
				<i class="mainsp" style="background-position:-134px -290px; width:33px; height:30px;"></i>
			</div>
            <a class="searchNavigation" href="~$SITE_URL`/profile/viewprofile.php?profilechecksum=~$tupleInfo.profilechecksum`&~$NAVIGATOR`&stype=~SearchTypesEnums::PHONEBOOK_JSMS`&responseTracking=~JSTrackingPageType::PHONEBOOK_JSMS`&total_rec=~$firstResponse.no_of_results`&tupleId=~$idd`&searchid=~$firstResponse.searchid`&offset=~$offset`&contact_id=~$firstResponse.contact_id`&actual_offset=~$idd`" onclick="">
				<div class="fontreg f14 color7 txtdec">
					<span class="fontreg f14 color7 txtdec textTru wid60p dispibl vbtm">
                                        ~if $tupleInfo.name_of_user neq '' && $tupleInfo.name_of_user neq null` 
                                                ~$tupleInfo.name_of_user`
                                        ~else` 
                                                ~$tupleInfo.username`
                                        ~/if`
                                        </span><span class="f11 colrsp1 fontreg padl5 fb"></span><span class="f11 color2 fontreg padl5 fb">~$tupleInfo.subscription_icon`</span>
				</div>
				<div class="f13 color3 fontlig txtdec">
					~assign var="MTONGUE" value="/"|explode:$tupleInfo.mtongue`
					~assign var="CASTE" value=":"|explode:$tupleInfo.caste`	
					~assign var="RELIGION" value=":"|explode:$tupleInfo.religion`
					<p>~$tupleInfo.age` Yrs, ~$tupleInfo.height`</p>
					<p> ~$RELIGION[0]`, ~$CASTE[1]` </p>
					<p>~$MTONGUE[0]`, ~$tupleInfo.location`</p>
					<p>~$tupleInfo.occupation`
					<p>~$tupleInfo.edu_level_new`</div>
				</div>
			</a>
			<div class="clr"></div>
		</div>
	</div>
~/foreach`

  <!--end:tuple--> 

  
 ~/if`
 
  <!--end:listing div--> 
   <!--start:pink bar-->
  
    <!--end:pink bar--> 
 </div>
 
~else`
<div id="noMsgDiv" class="posrel fullwid" style="width: 1286px; height: 657px;">
       		~if $gender eq 'M'`
			<img class="classimg1 blurred_bg_contacts_bride fullwid">
			~else`
			<img class="classimg1 blurred_bg_contacts_groom fullwid">
			~/if`
            <div class="posabs fullwid" style="z-index:105; top:40%;">
            	<div class="pad1 txtc f16 fontlig">
                	<p class="sclick_colr1">With an upgraded membership: </p>
                    <p class="bold pt15">See all phone numbers in one list </p>
					<p class="bold pt5">Call people instantly</p>
					<p class="bold pt5">No need to note down contact details </p>
                
                </div>
            
            </div>
            <div class="posfix comH_pos2 fullwid">
			<div class="bg7 wid94p clearfix ">
				<div class="txtc" id="brideClick">
					<div class="white fontreg f16 lh40"><a class="white" href="/profile/mem_comparison.php">Upgrade Now</a></div>
				</div>
			</div>
		</div>
       
       </div>
  ~/if`
</div>
<div id="hamburger" class="hamburgerCommon dn fullwid">	
	~include_component('static', 'newMobileSiteHamburger')`	
</div>
</div>
<script>
function showDirectContactDetailLayer(url,profileChecksum)
{
	$("#ce_photo").attr("src", url);
	resetLayerButtons(); 
	showCommonOverlay();
	performAction('CONTACT_DETAIL','profilechecksum='+profileChecksum+'&actionName=ContactDetails&fromPhonebook=1','1');
	
}
var heightHeader=$("#phonebook_header").height();
if($('#noMsgDiv').length){
	$('#noMsgDiv').css({"width":ol_vwid,"height":vhgt-heightHeader});
	$("#mainContent").css({"height":vhgt+'px', "overflow":"hidden"});
}

</script>
