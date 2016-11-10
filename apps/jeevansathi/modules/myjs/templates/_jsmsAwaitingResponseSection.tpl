<!--start:div-->

<div class="pad1">
   <div class="fullwid pb10">
    <div class="fl color7"> <span class="f17 fontlig">~$eoiData.title`</span>&nbsp;
	<span class="opa50 f14" id="eoi_count">~$eoiData.view_all_count`</span> 
    </div>
     
  <div class="fr pt5"> <a href="~$SITE_URL`/profile/contacts_made_received.php?page=eoi&filter=R" class="f14 color7 opa50 icons1 myjs_arow1">View all</a> </div>
    <div class="clr"></div>
  </div>

<!--endt:slider div-->
<!--start:div-->

     <div class="swrapper" id="swrapper">
        <div class="wrap-box" id="wrapbox">
    <div id="awaiting_tuples"  style=" white-space: nowrap; margin-left:10px; font-size:0px; width:200%">
        ~foreach from=$eoiData.tuples item=tupleInfo key=id`
               
                        ~include_partial("myjs/jsmsProfileTuple",[profileTuple=>$tupleInfo,section=>"eoi",index=>$id,gender=>$gender,total=>$eoiData.view_all_count,contactId=>$eoiData.contact_id])`
                
        ~/foreach`
      
       
       
        <div style="margin-right:10px; display: inline-block;margin-left:0px; display:none;position:relative;" id="loadingMorePic">
        <div class="bg4">
             	<div class="row minhgt199">
                	<div class="cell vmid txtc pad17">
                    	<i class="mainsp heart"></i>
                        <div class="color3 f14 pt5">Loading More Interests</div>
                    
                    </div>
                </div>
             </div> </div>
        ~for $i=1 to 10`
            <div style="margin-right:10px; display: inline-block;margin-left:0px; position:relative;"></div>
        ~/for`
        
    
    </div>

         
    </div>
    </div>
    
    
<div class="hgt10"></div>

    <div class="pb20" id="eoiAbsent" style="display:none;">
      <div class="bg8">
        <div class="pad14 txtc">
          <div class="fontlig f14 color8">Members Who Showed Interest In Your Profile Will Appear Here</div>
        </div>
      </div>
    </div>

<!--end:div-->
</div>
      
     