<script>
    var resp=~$responseJS|decodevar`;
    function setLocalStorageUrl()
    {
      try
      {
        localStorage.setItem("prevUrlListing",window.location.href);
      }
      catch(e)
      {
        console.log(e);
      }
    }

    $(document).ready(function () {
        var vhgt = $(window).innerHeight();
    $("#mainContent").css({"height":vhgt+'px', "overflow":"hidden"});
        ~if $firstResponse.profiles neq ''`
        $("#tupleContainer").height($("#uploadPhotoBar").offset().top-$("#tupleContainer").offset().top) ;
        var imageWidth=$(".tupleImage").eq(0).css('width');
     $(".tupleImage").css('height',imageWidth).css('width',imageWidth);
 ~else`
$("#noMsgDiv").css('height',$(window).height());
~/if`
    });
</script>



  <!--start:div-->
<div class="perspective" id="perspective">
<div class="" id="pcontainer">
	~include_partial("global/jsms3DotLayer")`
 <!--start:div-->
 
  <div class="fullwid bg1">
    <div class="pad5">
      <div class="fl wid20p pt4"><i id ="hamburgerIcon" class="mainsp baricon " hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i></div>
      <div class="fl wid60p txtc color5  fontthin f19">Photo Requests</div>
       <div class="clr"></div>
    </div>
  </div>
  <!--end:div--> 
    <!--start:listing div--> 
    ~if $firstResponse.profiles neq ''` 

  <a href="~$SITE_URL`/social/MobilePhotoUpload"> <div class="fullwid bg7" style="position:fixed; bottom:0px; left:0px;" id="uploadPhotoBar">
    
      <div class="txtc pad5">
        <div class="posrel"> 
          <div class="fontlig f18 white cursp lh30">Upload Photo</div>
        </div>
      </div>

    </div>
    </a>
  ~/if`
    <div style="overflow-y: auto;" id="tupleContainer">
  ~if $firstResponse.profiles eq ''` 
<div class="disptbl fullwid bg4 pad5" id='noMsgDiv' ><div class="dispcell txtc vertmid" ><div><img src="~$IMG_URL`/images/jsms/commonImg/face.png"></div><div class="pt10"></div><div class="f14 fontlig">~$firstResponse.noresultmessage`</div></div></div> 
~else`
<div class="ot_bg1 pad15 f14 opa80 white txtc">    
  ~$firstResponse.total`~if ~$firstResponse.total` eq '1'` person has ~else` people have ~/if`asked you to upload your photo
</div>

<!--start:tuple-->


~foreach from=$firstResponse.profiles item=tupleInfo key=id`
~assign var=offset value=(($firstResponse.page_index-1)*$_SEARCH_RESULTS_PER_PAGE) +$id`
~assign var=idd value=$offset+1`
    <a href="~$SITE_URL`/profile/viewprofile.php?profilechecksum=~$tupleInfo.profilechecksum`&~$NAVIGATOR`&stype=~SearchTypesEnums::PHOTO_REQUEST_RECEIVED_JSMS`&total_rec=~$firstResponse.total`&tupleId='~$idd`&searchid=~$firstResponse.searchid`&offset=~$offset`&contact_id=~$firstResponse.contact_id`&actual_offset=~$idd`"> 
<div class="~if $id is even`bg5~else`bg4~/if` pad18" onClick="setLocalStorageUrl()">
    <div class="fullwid">
      <img src="~$tupleInfo.photo.url`" class="brdr_radsrp wid24p tupleImage fl"/> 
      <div class="fl padlr_1" style="width:75%;">
        <div><span class="fontreg f14 color7 textTru wid40p dispibl vbtm">
                ~if $tupleInfo.name_of_user neq '' && $tupleInfo.name_of_user neq null` 
                                        ~$tupleInfo.name_of_user`
                                ~else` 
                                        ~$tupleInfo.username`
                                ~/if`
                </span><span class="f11 color4 fontlig padl5">~$tupleInfo.timetext`</span><span class="f11 color2 fontreg padl5 fr">~$tupleInfo.subscription_icon`</span></div>
        <div class="f13 color3 fontlig fullwid" style="text-overflow:ellipsis; overflow-x: hidden; white-space:nowrap; ">
            ~assign var="MTONGUE" value="/"|explode:$tupleInfo.mtongue`
	~assign var="CASTE" value=":"|explode:$tupleInfo.caste`	
          ~$tupleInfo.age` Yrs, ~$tupleInfo.caste` 
          <br />~$MTONGUE[0]`, ~$tupleInfo.location`
          <br />~$tupleInfo.occupation` 
          <br />~$tupleInfo.edu_level_new`</div>
      </div>
      
      <div class="clr"></div>
    </div>
  </div>
    </a>
~/foreach`

  <!--end:tuple--> 

  
 ~/if`
  <!--end:listing div--> 
   <!--start:pink bar-->
  
    <!--end:pink bar--> 
 </div>
</div>
	~include_component('static', 'newMobileSiteHamburger')`	
</div>
