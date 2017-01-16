<!--start:div-->
<div class="pad1">
  <div class="fullwid pb10 ~if $matchalertData.tuples eq ''` pt15 ~/if`">
    ~if $matchalertData.tuples neq ''`
    <div class="fl color7"> <span class="f17 fontlig">Featured Profiles</span>&nbsp;<span id='matchOfDay_count' class="opa50 f14">~$matchalertData.view_all_count`</span> </div>
    <div class="fr pt5"> <a href="~$SITE_URL`/profile/contacts_made_received.php?page=matches&filter=R" class="f14 color7 opa50 icons1 myjs_arow1">View all </a> </div>
    <div class="clr"></div>
    ~else`
      <div class="f17 fontlig color7">Featured Profiles</div>
    ~/if`
  </div>
~if $matchalertData.tuples neq ''`
    <div class="swrapper" id="swrapper">
        <div class="wrap-box" id="wrapbox">
 <div id="matchOfDay_tuples"  style="white-space: nowrap; margin-left:10px; font-size:0px; overflow-x:hidden; width:200%; ">
   
        ~foreach from=$matchalertData.tuples item=tupleInfo key=id`
                        ~include_partial("myjs/jsmsProfileTuple",[profileTuple=>$tupleInfo,section=>"matchOfDay",index=>$id,gender=>$gender,total=>$matchalertData.view_all_count,contactId=>$matchalertData.contact_id])`
                
        ~/foreach`

         ~for $i=1 to 10`
     <div style="margin-right:10px; display: inline-block;margin-left:0px; position:relative;"></div>
         ~/for`
         
        <div class="clr"></div>
 </div>
    </div>
    </div>    
        ~/if`
<div class="hgt10"></div>

</div>
<!--end:div-->

<!--start:div-->
