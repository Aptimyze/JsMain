<script> 
    var resp=~$responseJS|decodevar`;
     $(window).load(function()  {
        $("#noMsgDiv").css('height',$(window).height());
    
        }) 
$(document).ready(function() {
 if ($(".tupleImage").length){
        var imageWidth=$(".tupleImage").eq(0).width();
        $(".tupleImage").css('height',imageWidth);
 }
 
        if (resp.profiles)
   for (i=0;i<resp.profiles.length;i++){
      disablePrimary[i]=false;
            bindPrimeButtonClick(i);
   
        }
    else scrollOff();
    
        

})
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
</script>

<div>

	

  <!--start:div-->
<div class="perspective" id="perspective">
<div class="" id="pcontainer">
	~include_partial("global/jsms3DotLayer")`
<input type="hidden" value="" id="updateMsgId">
  <div class="fullwid bg1">

    <div class="pad5">

      <div class="fl wid20p pt4"><i id ="hamburgerIcon" class="mainsp baricon " hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i></div>

      <div class="fl wid60p txtc color5  fontthin f19">Messages</div>

      <div class="fr"></div>

      <div class="clr"></div>

    </div>

  </div>

  <!--end:div--> 

  <!--start:listing div--> 

  <!--start:tuple-->
  ~if $firstResponse.profiles eq ''` 
<div class="disptbl fullwid bg4 pad5" id='noMsgDiv' ><div class="dispcell txtc vertmid" ><div><img src="~$IMG_URL`/images/jsms/commonImg/face.png"></div><div class="pt10"></div><div class="f14 fontlig">~$firstResponse.noresultmessage`</div></div></div> 
~else`
~foreach from=$firstResponse.profiles item=tupleInfo key=id`
              <div class="~if $id is even`bg5~else`bg4~/if` pad18" onClick="setLocalStorageUrl()">
                  
                  
    <div class="fullwid">

          <div> <a href="~$SITE_URL`/profile/viewprofile.php?profilechecksum=~$tupleInfo.profilechecksum`&~$NAVIGATOR`"> <img src="~$tupleInfo.photo.url`" class="brdr_radsrp fl  tupleImage wid24p"   /></a></div>

      <div class="fl padlr_1" style="width:75%;">

          <div><a href="~$SITE_URL`/profile/viewprofile.php?profilechecksum=~$tupleInfo.profilechecksum`&~$NAVIGATOR`"><span class="fontreg f14 color7 textTru wid40p dispibl vbtm">
                                ~if $tupleInfo.name_of_user neq '' && $tupleInfo.name_of_user neq null` 
                                        ~$tupleInfo.name_of_user`
                                ~else` 
                                        ~$tupleInfo.username`
                                ~/if`</span></a><span class="f11 color4 fontlig padl5" id="timeTextId_~$id`"> ~$tupleInfo.timetext`</span><span class="f11 color2 fontreg padl5 fr">~$tupleInfo.subscription_icon`</span></div>

        <div class="f13 color3 fontlig" id="Prime_~$id`">
            <input type="hidden" id="buttonInput~$id`" value="~$tupleInfo.profilechecksum`">
                  <input type="hidden" id="primeAction~$id`" value="WRITE_MESSAGE">
            
                <span id="lastMsgId_~$id`" style="~if $tupleInfo.seen eq 'N'` font-weight:bold; ~else` font-weight:300; ~/if` font-family:Roboto;">~$tupleInfo.last_message|nl2br`</span>
            
          

        </div>

      </div>

      
      <div class="clr"></div>

    </div>

  </div>
                        
                
        ~/foreach`
  


  ~/if`

  <!--end:listing div--> 

</div>
<div id="hamburger" class="hamburgerCommon dn fullwid">	
	~include_component('static', 'newMobileSiteHamburger')`	
</div>
</div>
