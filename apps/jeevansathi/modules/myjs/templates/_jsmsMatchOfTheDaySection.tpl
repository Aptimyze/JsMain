<!--start:div-->
~if $matchOfDay.tuples neq ''`
<div class="pad1">
  <div class="fullwid pb10 ~if $matchOfDay.tuples eq ''` pt15 ~/if`">
    <div class="fl color7"> <span class="f17 fontlig">Profile of the day</span>&nbsp;<span id='matchOfDay_count' class="opa50 f14">~$matchOfDay.tuples|@count`</span> </div>
    <div class="clr"></div>
  </div>
    <div class="swrapper" id="swrapper">
        <div class="wrap-box" id="wrapbox">
 <div id="matchOfDay_tuples"  style="white-space: nowrap; margin-left:10px; font-size:0px; overflow-x:hidden; width:200%; ">
   
        ~foreach from=$matchOfDay.tuples item=tupleInfo key=id`
                        ~include_partial("myjs/jsmsProfileTuple",[profileTuple=>$tupleInfo,section=>"matchOfDay",index=>$id,gender=>$gender,total=>$matchOfDay.view_all_count,contactId=>$matchOfDay.contact_id])`
                
        ~/foreach`

         ~for $i=1 to 10`
     <div style="margin-right:10px; display: inline-block;margin-left:0px; position:relative;"></div>
         ~/for`
         
        <div class="clr"></div>
 </div>
    </div>
    </div>    
<div class="hgt10"></div>

</div>
~/if`
