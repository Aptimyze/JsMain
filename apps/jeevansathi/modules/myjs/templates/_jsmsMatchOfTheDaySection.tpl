<!--start:div-->
<div class="pad1">
  <div class="fullwid pb10 ~if $matchOfDay.tuples eq ''` pt15 ~/if`">
  ~if $matchOfDay.tuples neq ''`

    <div class="fl color7"> <span class="f17 fontlig">Profile of the day</span>&nbsp;<span id='matchOfDay_count' class="opa50 f14">~$matchOfDay.tuples|@count`</span> </div>
    <div class="clr"></div>
  </div>
  <img id="matchLoader" src="~sfConfig::get('app_img_url')`/images/jsms/commonImg/loader.gif" style=" position: relative;margin: 0px auto;display: block;">

    <div class="swrapper" id="swrapper">
        <div class="wrap-box" id="wrapbox">
 <div id="matchOfDay_tuples"  style="white-space: nowrap; margin-left:10px; font-size:0px; overflow-x:hidden; width:200%; ">
   
        <div class="clr"></div>
 </div>
    </div>
  
~/if`
  </div>    
<div class="hgt10"></div>

</div>