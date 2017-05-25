<div style="position:absolute;z-index:-1" id="handle_click"></div>
<style>
.abs{left:-147px;}
</style>
   <!--Header starts here-->
 ~include_partial('global/header',[showGutterBanner=>1])`
 <!--Header ends here-->
<!--Main container starts here-->
<!--pink strip starts here-->
<div id="main_cont">	
    
<!--pink strip ends here-->
  <p class="clr_4"></p>
<div id="topSearchBand"></div>
<?php include_partial('global/sub_header') ?>
  <p class="clr_4"></p>
 <!--slide-bluetop starts here-->

 <!--slide-bluetop ends here-->
<!--orange strip starts here here-->

<!--orange strip ends here here-->

<!--top tab  start -->

<div class="lstnxt b">~$BREADCRUMB|decodevar`</div>
~if $OFFLINE_CALL_PROGRESS`
<div class="b fr">Communication with client in progress</div>
~else`
<div class="~if !$OFFLINE_ASSISTANT_REM`addmem~else` b ~/if` fr">~if $OFFLINE_ASSISTANT_ADD`<img src="~sfConfig::get(app_img_url)`/images/plus-icon.gif" align="absmiddle">&nbsp;<a href="~$SITE_URL`/profile/invoke_contact_engine.php?width=400&height=360&checksum=&profilechecksum=~$PROFILECHECKSUM`&index=0&to_do=add_intro&ajax_error=1" class="thickbox">Add members to "intro call" list</a>~/if`~if $OFFLINE_ASSISTANT_REM`Added to `members to be called list`~/if` </div>
~/if`

~include_partial("profile_sub_head",[FROM_PROFILEPAGE=>$FROM_PROFILEPAGE,TopUsername=>$TopUsername,total_rec=>$total_rec,actual_offset=>$actual_offset,j=>$j,searchid=>$searchid,other_params=>$other_params,NAVIGATOR=>$NAVIGATOR,PROFILECHECKSUM=>$PROFILECHECKSUM,SHOW_NEXT_PREV=>$SHOW_NEXT_PREV,SHOW_PREV=>$SHOW_PREV,SHOW_NEXT=>$SHOW_NEXT,SHOW_PREV=>$SHOW_PREV,fromPage=>$fromPage,prevLink=>$prevLink,nextLink=>$nextLink,OnlineMes=>$OnlineMes,show_profile=>$show_profile,actual_offset_real=>$actual_offset_real,curLink=>$curLink,responseTracking=>$responseTracking])`
<p class="clr"></p>
<div>
 	<!--left part start -->
    <p class="clr"></p>
	<div style="width:550px;padding:5px; margin-right:0px;" class="lf t12 b">

	<!--slider content start-->
	<p class="clr_4"></p>
<div><br><br>
</div>
<div id="container" style="position:relative;width:500px">
~include_partial('social/social_mainpic',[currentPicIndex=>$currentPicIndex,countOfPics=>$countOfPics,frontPicUrl=>$frontPicUrl,countOfPics=>$countOfPics,FromPage=>"ViewAlbum",widthOfMainPic=>$widthOfMainPic,heightOfMainPic=>$heightOfMainPic])`
</div>
<!--slider content end-->
		
	<div class="phtagfb ">
	<div><p class="clr_18"></p></div>
	<div style="float:left;display:none" id="album_keywords">
	<strong>Keywords &nbsp;</strong>
	</div>
	<div class="phblw no_b" id="picture_keywords" style="clear:both;padding-top:6px"></div>
	<div style="width: 430px; float: left;"></div>

</div>
<p class="clr_4"></p>

<div><p class="clr_18"></p></div>

</div>


<!--left part end -->

<!--right part start -->

<div style="width:350px;float:left;position:relative">
~include_partial('album_contact_engine',[PROFILENAME=>~$PROFILENAME`,tempContact=>~$tempContact`,type_of_contact=>~$contact_status`,checksum=>$checksum,profilechecksum=>$PROFILECHECKSUM,STYPE=>$STYPE,suggest_profile=>$suggest_profile,matchalert_mis_variable=>$matchalert_mis_variable,CURRENTUSERNAME=>$CURRENTUSERNAME,CALL_ACCESS=>$CALL_ACCESS,IMG_URL=>sfConfig::get(app_img_url)])`

<div class="clr_18"></div>
<!--slider2 start -->
~include_partial('social/social_slider',[sliderNo=>$sliderNo,tempCount=>$tempCount,allThumbnailPhotos=>$allThumbnailPhotos,picIdArr=>$picIdArr,countOfPics=>$countOfPics,whichPage=>'view',fromPage=>'album'])`
</div>
<!--slider2 end -->
<div style="float: left; padding-top: 20px;">
~foreach from=$hobArray item=message key=id`
<div class="favleft ">
<p class="jshup8 sprte fl"></p>~$id`
<div class="favleft1">~$message`</div>
</div>
~/foreach`


</div>
 <!--right part end -->

<!--top tab  end -->
<p class="clr_4"></p>
<p class="clr_4"></p>
<!--bottom content start -->


</div>

 <p class=" clr_2"></p>

<p class="clr_18"></p>
<!--mid bottom content end -->
 <p class=" clr_18"></p>
<!--footer tabbing  start -->
<!--Main container ends here-->	
</div>
~include_partial('global/footer',[NAVIGATOR=>$NAVIGATOR,bms_topright=>$bms_topright,bms_bottom=>$bms_bottom,G=>$G,viewed_gender=>$GENDER,data=>$loginProfile->getPROFILEID()])`
<script>
var ALB_IMG_URL='~sfConfig::get("app_img_url")`';
var PH_LAYER_STATUS_PL="~$PH_UNVERIFIED_STATUS`";
~if $contactLimitMessage eq "Not Valid" && $PH_UNVERIFIED_STATUS`
noExpressInterest=1;
~/if`
var thumbnails=[];
var mainpic=[];
var titles=[];
var keywords=[];
var imgarr=[];
var labKeywords=[];
var totalcnt=0;
~if $countOfPics > 0`
	totalCnt=~$countOfPics`;
~/if`

~assign var="tab" value=0`
~section name=foo loop=$countOfPics`
imgarr[~$tab`]='~$picIdArr[$tab]`';
thumbnails[~$picIdArr[$tab]`]="~$allThumbnailPhotos[$tab]`";
mainpic[~$picIdArr[$tab]`]="~$mainPicArr[$tab]`";
titles[~$picIdArr[$tab]`]="~$titleArr[$tab]`";
keywords[~$picIdArr[$tab]`]="~$keywordArrStr[$tab]`";
~assign var="tab" value=$tab+1`
~/section`
~if $albumPage`
albumPage=1;
~/if`

~if $autocontactlayer`

~/if`
var postDataVar={'profilechecksum':'','stype':"~$STYPE`",'suggest_profile':'~$suggest_profile`','matchalert_mis_variable':'~$matchalert_mis_variable`','CURRENTUSERNAME':'~$CURRENTUSERNAME`','page_source':'album','divname':'div','responseTracking':'~$responseTracking`'};
</script>
