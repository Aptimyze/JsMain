<!--start:div-->
<div class="pad1">
  <div class="fullwid pb10 ~if $matchalertData.tuples eq ''` pt15 ~/if`">
    ~if $matchalertData.tuples neq ''`
    <div class="fl color7"> <span class="f17 fontlig">~$matchalertData.title`</span>&nbsp;<span id='matchAlert_count' class="opa50 f14">~$matchalertData.view_all_count`</span> </div>
    <div class="fr pt5"> <a href="~$SITE_URL`/profile/contacts_made_received.php?page=matches&filter=R" class="f14 color7 opa50 icons1 myjs_arow1">View all </a> </div>
    <div class="clr"></div>
    ~else`
      <div class="f17 fontlig color7">Daily Recommendations</div>
    ~/if`
  </div>
~if $matchalertData.tuples neq ''`
    <div class="swrapper" id="swrapper">
        <div class="wrap-box" id="wrapbox">
 <div id="match_alert_tuples"  style="white-space: nowrap; margin-left:10px; font-size:0px; overflow-x:auto; width:100%; ">
   
        ~foreach from=$matchalertData.tuples item=tupleInfo key=id`
                        ~include_partial("myjs/jsmsProfileTuple",[profileTuple=>$tupleInfo,section=>"matchAlert",index=>$id,gender=>$gender,total=>$matchalertData.view_all_count,contactId=>$matchalertData.contact_id])`
                
        ~/foreach`
        
        ~for $i=1 to 10`
    <div style="margin-right:10px; display: inline-block;margin-left:0px; position:relative;"></div>
        ~/for`
        
        
        <div class="clr"></div>
 </div>
    </div>
    </div>    
        
<div class="hgt10"></div>
~else`
<!--end:div-->
<div class="pb20" id="matchAlertNotPresent">
      <div class="bg8">
        <div class="pad14 txtc">
          <div id='matchAlertAbsentText' class="fontlig f14 color8">Members Matching Your Desired Partner Profile Will Appear Here</div>
        </div>
      </div>
</div>
~/if`
<div class="pb20 dispnone" id="matchAlertAbsent">
      <div class="bg8">
        <div class="pad14 txtc">
          <div  id='matchAlertAbsentText' class="fontlig f14 color8">No more profiles for today</div>
        </div>
      </div>
</div>  
  
  
</div>
<!--end:div-->

<!--start:div-->
