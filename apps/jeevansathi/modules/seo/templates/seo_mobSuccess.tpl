<!--start:header-->
<div class="fullwid bg10 pad18">
  <div class="posrel txtc">
    <h1>
    <div class="color5 fontthin f19">~$levelObj->getH1Tag()`<span id="brideGroom"> Matrimonial</span></div>
    <a href="/browse-matrimony-profiles-by-community-jeevansathi" bind-slide="2"><i class="mainsp comH_close posabs comH_pos1"></i></a></div>
    </h1>
</div>
<!--end:header--> 
<!--start:list div-->
<div id="listView"> 
        <div id = "leftProfiles">
			~include_partial('seo_mob_profiles_list',[profileArr=>$leftArr,GENDER=>"brides"])`
		</div>
		<div id = "rightProfiles" style="display:none;">
			~include_partial('seo_mob_profiles_list',[profileArr=>$rightArr,GENDER=>"grooms"])`
		</div>
  <!--start:cloud-->
  <div class="textcl pad18">
    <ul>
      <li ><a class="fontre cursp" onclick="return false" >~$levelObj->getContent()|decodevar`</a></li>
    
    </ul>
  </div>
  <!--end:cloud--> 
</div>
<div class="clr hgt35"></div>
<!--end:list div--> 
<!--start:select-->
<div class="posfix comH_pos2 fullwid">
  <div class="bg7 wid94p clearfix comH_radius">
    <div class="wid49p txtc fl" id="brideClick"> <div class="white fontreg f15 lh40 opa70">Bride</div> </div>
    <div class="wid49p txtc fl" id="groomClick"> <div class="white fontreg f15 lh40">Groom</div> </div>
  </div>
</div>
<!--end:select-->
<script type="text/javascript">
	
	var hash=document.location.href.split('#')[1];
	
	if(typeof(hash)=="undefined" || hash=="brides" || hash=="brides,historyCall")
		bgDisplay($("#brideClick"),0,0);
	else
		bgDisplay($("#groomClick"),1,0);

    var bC=0;
    var gC=0;
    $("#brideClick").bind("click",function(){
        gC=0;
        if(!bC)
        {
            bgDisplay(this,0,1);
            bC=1;
        }
        else
			bgDisplay(this,0,0);
     });

    $("#groomClick").bind("click",function(){
        bC=0;
        if(!gC)
        {
            bgDisplay(this,1,1);
            gC=1;
        }
        else
			bgDisplay(this,1,0);
    });
function bgDisplay(ele,groom,pushback)
{
		toHistoryCall=1;
		if(groom==1 && pushback)
			if($(ele).hasClass("opa70"))
				return ;
				
		$("#leftProfiles").css("display","inline");
        $("#rightProfiles").css("display","none");
        if(groom)
		{
			$("#rightProfiles").css("display","inline");
			$("#leftProfiles").css("display","none");
			$(ele).children().addClass("opa70");
			$("#brideClick").children().removeClass("opa70")
			//$("#brideGroom").text(" Grooms");
			if(pushback)
				historyStoreObj.push(function(){bgDisplay($("#brideClick"),0,0); return true;},"#grooms");
		}
		else
		{
			$(ele).children().addClass("opa70");
			$("#groomClick").children().removeClass("opa70");
			//$("#brideGroom").text(" Brides");
			if(pushback)
				historyStoreObj.push(function(){bgDisplay($("#groomClick"),1,0);return true;},"#brides");
		}
		return true;   
}
</script>
