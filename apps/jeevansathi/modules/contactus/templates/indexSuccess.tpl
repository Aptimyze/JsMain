
<script type="text/javascript">

function apply_thickbox_class()
{
        $('.thickbox').colorbox();
        imgLoader = new Image();// preload image
        imgLoader.src = tb_pathToImage;
}

var cityArray = new Array(~foreach from=$city_label_Arr.$state_sel key=cityKey item=cityItem name=city`  ~if $smarty.foreach.city.last` ~assign var='quot' value="'"` ~assign var='cont' value=$cityItem`  ~$quot|cat:$cont|cat:"'"`  ~else` ~assign var='quot' value="'"` ~assign var='cont' value=$cityItem` ~$quot|cat:$cont|cat:"'"|cat:','` ~/if` ~/foreach`);
        totlen = cityArray.length;
        var cityValue = new Array();
        for(c=0;c<totlen;c++)
        {
                cityValue[c] = cityArray[c]; 
        }

var locationArray = new Array(~foreach from=$locationArr key=locKey item=locItem name=loc`  ~if $smarty.foreach.loc.last` ~assign var='quot' value="'"` ~assign var='cont' value=$locItem`  ~$quot|cat:$cont|cat:"'"`  ~else` ~assign var='quot' value="'"` ~assign var='cont' value=$locItem` ~$quot|cat:$cont|cat:"'"|cat:','` ~/if` ~/foreach`);
        loclen = locationArray.length;
        var locationValue = new Array();
        for(i=0;i<loclen;i++)
        {
                locationValue[i] = locationArray[i]; 
        }
function getDisplay(selState,selCity,locString)
{

        var newArray = new Array();
        if(selState =='Delhi'){
                var cnt = locationValue.length;
                newArray = locationValue;
        }
        else{
                var cnt = cityValue.length;
                newArray = cityValue;
                var brokenStr = locString.split(",");
                if(selCity =='All')
                        explodeString(brokenStr,'block');
                else
                        explodeString(brokenStr,'none');
        }

        for(ci=0;ci<cnt;ci++)
        {
                if(selCity=='All')
                {
                
                        document.getElementById(newArray[ci]).style.display ="block";

                        // All gets Selected
                        id=selState+"-"+selCity;        
                        document.getElementById(id).className="blinkB";
                        
                        // Other gets un-selected       
                        for(aci=0;aci<cnt;aci++)
                        {
                                id3=selState+"-"+newArray[aci];
                                document.getElementById(id3).className="blink";
                        }
                }
                else
                {
                        if(newArray[ci] ==selCity)
                        {
                                // 'All' gets de-Selected
                                id=selState+"-All";
                                document.getElementById(id).className="blink";                          
                                document.getElementById(selCity).style.display ="block";
                
                                // Selected city gets Selected  
                                id1=selState+"-"+selCity;       
                                document.getElementById(id1).className="blinkB";
                
                                document.getElementById(selCity).style.display ="block";                
                        }
                        else
                        {
                                // Other gets de-Selected     
                                id2=selState+"-"+newArray[ci];
                                document.getElementById(id2).className="blink";

                                document.getElementById(newArray[ci]).style.display ="none";
                        }
                }
        }        
}

function explodeString(itemArr,disp_status) {
        var totItem = itemArr.length;
        for(items=1;items<totItem;items++)
        {
                key = "Name-"+itemArr[items];
                document.getElementById(key).style.display =disp_status;
        }
}
</script>



<!--Header starts here-->
 <?php include_partial('global/header') ?>
<!--Header ends here-->

<!--pink strip starts here-->
<!--Main container starts here-->

<div id="main">

<div id="container">

<!--pink strip ends here-->
  <p class="clr_4"></p>
<div id="topSearchBand"></div>
<?php include_partial('global/sub_header') ?>
  <p class="clr_4"></p>
<br>
<div class="clear"></div>
~$SUB_HEAD`
<div class="sp16"></div>
<div class="mid_cont">
	<h1 style="margin-left:12px;">Contact us</h1>

<div class="bdr_top mt_10">
      <div class="bdr_bot">
        <div class="bdr_left">
          <div class="bdr_right">
            <div class="ft_tp_l_cur">
              <div class="ft_tp_r_cur">
                <div class="ft_btm_l_cur">
                  <div class="ft_btm_r_cur">
                    <div style="padding:10px 0;">
                    <div  class="contact_left lf" style="margin-left:15px;display:inline; "><span class="orange t14 b">Head office</span>
                    <div class="sp12">
                    </div>
                    <div>
                    <label class="l1 b">Contact Person :</label> ~$infoSel.HO.CONTACT`
                    </div>
                    <div class="mar_top_5">

                    <label class="l1 b">Address :</label> ~$infoSel.HO.ADDRESS` ~if $infoSel.HO.LATITUDE && $infoSel.HO.LONGITUDE`<span class="blink b">[</span><a onclick="point_it(event,'l');ReverseContentDisplay('u9','~$infoSel.HO.LATITUDE`','~$infoSel.HO.LONGITUDE`');" href="#" class="blink"> View Map </a> <span class="blink b">]~/if`</span>
                    </div>
                    <div class="mar_top_5">
                    <label class="l1 b">Phone :</label><span class="lf"> ~$infoSel.HO.PHONE` <br>
                        ~$infoSel.HO.MOBILE` ~if $infoSel.HO.MOBILE` (mobile) ~/if` </span>
                    </div>
                    <div class="sp12"></div>
                    <div class="clear">
                    </div>
                    </div>
                     <div  class="contact_left rf back_none"><span class="orange t14 b">Matchpoint office</span>
                    <div class="sp12">
                    </div>
                    <div>
                    <label class="l1 b">Contact Person :</label>~$infoSel.MPO.CONTACT`
                    </div>
                    <div class="mar_top_5">

                    <label class="l1 b">Address :</label> ~$infoSel.MPO.ADDRESS`  ~if $infoSel.MPO.LATITUDE && $infoSel.MPO.LONGITUDE` <span class="blink b">[</span><a onclick="point_it(event,'r'); ReverseContentDisplay('u9','~$infoSel.MPO.LATITUDE`','~$infoSel.MPO.LONGITUDE`');" href="#" class="blink"> View Map </a><span class="blink b">] ~/if` </span>
                    </div>
                    <div class="mar_top_5">
                    <label class="l1 b">Phone :</label><span class="lf">~$infoSel.MPO.PHONE`<br>
                        ~$infoSel.MPO.MOBILE` ~if $infoSel.MPO.MOBILE` (mobile) ~/if`</span>
                    </div>
                    <div class="sp12"></div>
                    <div class="clear">
                    </div>
                    </div>

    <div class="clear"></div>
    <div style="margin:1px;">
    </div>
    </div></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>




<div class="sp16"></div>
<div><strong style="color:#ff0000;">Note:</strong>In case of any summons, you are advised to fill <a href="~sfConfig::get('app_site_url')`/profile/summon_grievance.php?summon=1">Complaint / Status</a> form with the summon/notice attached in the prescribed format under the applicable procedural law. Approach the Grievance Officer for any complaint, please use the <a href="~sfConfig::get('app_site_url')`/profile/summon_grievance.php?grievance=1">complaints form</a>.</div>

<div class="sp16"></div>
<div style="padding:0 10px;">
<span class="t14 orange b">View other branch offices-</span><div class="sp8">
</div>
</div>

<div class="below_cont_left lf">
<span class="b">&nbsp;&nbsp;&nbsp;&nbsp;Select state</span><div class="sp16">
</div>

<div class="lf" style="width: 168px;">
<div><img src="~sfConfig::get('app_img_url')`/img_revamp/top_tab_setting.gif"></div>
<div style="clear:both;"></div>
<div style="background-image:url(~sfConfig::get('app_img_url')`/img_revamp/hr_tab_setting.gif);float:left;width:164px;background-repeat:repeat-y;">

~foreach from=$state key=st item=items`
        ~if $state_sel eq $items`
                <div class="st_close_tab" style="width:164px;">
        ~else`
                <div class="st_open_tab">
        ~/if`
       
        <div class="sub_st_tab"><a href="?st_sel=~$items`" class="blink" >~$items`</a></div></div>
~/foreach`

</div><div style="clear:both;"></div>
<div><img src="~sfConfig::get('app_img_url')`/img_revamp/bottom_tab_setting.gif"></div>

</div>
</div>

<!-- String holds all locations -->
~foreach from=$infoCity key=dataK1 item=informat1 name=data1`
        ~foreach from=$informat1 key=dataK item=information name=data`
                ~if $dataK1 neq $dataK`
                      ~if $dataK1 neq 'Delhi'`
                                ~assign var='locStr' value=$dataK`
                                ~assign var='locString' value=$locString|cat:','|cat:$locStr`
                        ~/if`
                ~/if`
        ~/foreach`
~/foreach`
<div class="below_cont_right rf" style="position:relative;" id="gmap_d" >
<span class="b lf">
Office Locations in  ~$state_sel`
<img src="~sfConfig::get('app_img_url')`/img_revamp/grey_arrow.gif" alt="location" title="location">&nbsp;&nbsp;</span>
<span class="lf" style="cursor:pointer;">
                        ~if $show_sel_city`
                        <a  id="~$state_sel`-All" class="blink" onClick="javascript:getDisplay('~$state_sel`','All','~$locString`');return false;"> All </a>
                        ~else`
                        <a  id="~$state_sel`-All" class="blinkB" onClick="javascript:getDisplay('~$state_sel`','All','~$locString`');return false;"> All </a>
                        ~/if`

        ~if $defaultStateFlag`
                ~foreach from=$locationArr key=dataK_D item=locationVal`
                                ~if $locationVal` | ~/if` ~if $dataK_D eq 7`  <br> ~/if`
                                <a id="~$state_sel`-~$locationVal`" class="blink" onClick="javascript:getDisplay('~$state_sel`','~$locationVal`','~$locString`');return false;">~$locationVal`</a>
                ~/foreach`
        ~else`
                ~foreach from=$city key=ct item=cityVal`
                                ~if $cityVal` | ~/if` ~if $ct eq 7` <br> ~/if`
                                ~if $city_sel eq $cityVal`
                                <a id="~$state_sel`-~$cityVal`" class="blinkB" onClick="javascript:getDisplay('~$state_sel`','~$cityVal`','~$locString`');return false;">~$cityVal`</a>
                                ~else`
                                <a id="~$state_sel`-~$cityVal`" class="blink" onClick="javascript:getDisplay('~$state_sel`','~$cityVal`','~$locString`');return false;">~$cityVal`</a>
                                ~/if`
                ~/foreach`
        ~/if`
</span>
<div class="sp5" style="border-bottom:1px solid #ccc;">
</div>
<div class="address_block" >


~foreach from=$infoCity key=dataK1 item=informat1 name=data1`
        <div id="~$dataK1`" ~if $show_sel_city` ~if $city_sel eq $dataK1` style="display:block" ~else` style="display:none" ~/if` ~else` style="display:block" ~/if`>
        ~foreach from=$informat1 key=dataK item=information name=data`
                <div id="~$dataK`" class="~cycle values="r1,r1 light_grey"`" >
                        ~if $dataK1 neq $dataK`
                                ~if $dataK1 neq 'Delhi'`
                                        <span id="Name-~$dataK`" class="b lf" ~if $show_sel_city`  ~if $city_sel eq $dataK1` style="display:none" ~else` style="display:display" ~/if` ~else` ~/if`> ~$dataK1` - </span>
                                ~/if`
                        ~/if`
                        <span class="b lf">~$information.NAME` </span>
                        ~if $information.MATCH_POINT_SERVICE`
                        <span class="l2 lf" style="color: rgb(255, 102, 0);"> ( Match Point services also available ) </span>
                        ~/if`
                        <div class="sp12">
                        </div>
                        <div>
                            <label class="l1" style="width:101px;"><span class="rf">Contact Person :</span></label><span class="lf">~$information.CONTACT`</span>
                        </div>
                        <div class="sp5"></div>
                        <div>
                                <label class="l1" style="width:101px;">Address :</label><label class="l2 lf ">~$information.ADDRESS` ~if $information.LATITUDE && $information.LONGITUDE`<span class="blink b">[</span><a onclick="point_it(event,''); ReverseContentDisplay('u9','~$information.LATITUDE`','~$information.LONGITUDE`');" href="#" class="blink">View Map</a><span class="blink b">]</span>~/if`</label>
                        </div>
                        <div class="clear"></div>
                        <div class="mar_top_5">
                        ~if $information.PHONE || $information.MOBILE`
                        <label class="l1" style="width:101px;">Phone :</label><span class="lf">~if $information.PHONE` ~$information.PHONE` <br> ~/if`
                        ~$information.MOBILE` ~if $information.MOBILE` (mobile) ~/if` </span>
                        ~/if`
                        </div>
                        <div class="clear">
                        </div>
                </div>
        ~/foreach`
        </div>
~/foreach`
<!--google map starts here-->
<!--google map ends here-->
</div>
</div>
<div class="sp12">
</div>
<div class="sp16"></div>
<div class="sp16">
</div>
</div>
</div>
</div>














</div> <!--id mid-container ends  -->

</div> <!-- id container ends -->

</div>  <!-- id main ends -->

<!--  Containser div starts -->
<div id="u9" style="border:1px solid #ccc; display:none; position:absolute; left:0px;top:0px;z-index:100">
<!--google map starts here-->
<div id="u8" class="g_map" style="display:block; position:absolute; left:0px;top:0px;z-index:2">
<input type="hidden" value="~$googleApiKey`" id="google_api_key">
<input type="hidden" value="" id="g_latitude">
<input type="hidden" value="" id="g_longitude">
<div class="t_line">
<div class="b_line">
<div class="r_line">
<div class="l_line">
<div class="top_right">
<div class="bot_right">
<div class="bot_left">
  <a onmouseclick="ReverseContentDisplay('u9'); return true;"
   href="javascript:ReverseContentDisplay('u9')" ><img src="~sfConfig::get('app_img_url')`/img_revamp/close_btn.gif" alt="close" border="0" style="margin:0 10px 5px; padding:0; float:right;" title="close" /></a>
<div class="clear">
</div>

<div id="left_arrow" style="display:block; position:absolute; top:100px; left:-29px">
<img src="~sfConfig::get('app_img_url')`/img_revamp/g_map_left_arrow.gif" alt="google map" title="google map" />
</div>

<div id="top_arrow" style="display:none; position:absolute; top:-30px; left:170px">
<img src="~sfConfig::get('app_img_url')`/img_revamp/g_map_top_arrow.gif" alt="google map" title="google map" />
</div>
<div class="clear">
</div>
<div class="g_img" id="map">
<!--
  <img style="margin: 0pt; padding: 0pt; float: left;" title="google map" alt="google map" src="~sfConfig::get('app_img_url')`/img_revamp/google_map.jpg"/>
-->
</div>
<div class="clear">
</div>

<div id="right_arrow" style="display:block; position:absolute; top:100px; left:369px">
<img src="~sfConfig::get('app_img_url')`/img_revamp/g_map_right_arrow.gif" alt="google map" title="google map" />
</div>

<div id="bottom_arrow" style="display:none; position:absolute; top:237px; left:170px">
<img src="~sfConfig::get('app_img_url')`/img_revamp/g_map_bot_arrow.gif" alt="google map" title="google map" />
</div>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
<!--google map ends here-->
</div>
<!--Containser div ends here -->

~include_partial('global/footer',[NAVIGATOR=>~$NAVIGATOR`,G=>$G,viewed_gender=>$GENDER,data=>''])`

