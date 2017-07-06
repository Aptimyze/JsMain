<div class="tapoverlay posfix dn"></div>
<!--start:overlay box 1-->
<div class="zl-102 posfix setndiv showT wid90p bg4 dn">
    <div class="nbg1 txtc f16 lh50" id="layerT">        
    </div>
    <ul class="nlistp fontlig f15">
        <li class="clearfix js-saveOption" value="Y">
         <div class="fl wid90p visible textV">
            Visible to All (Recommended)
        </div>
    </li>
    <li class="clearfix js-saveOption" value="C">
        <div class="fl wid90p semiVisible textV">
            Visible to Members I Accept/Express Interest in 
        </div>
    </li>            
</ul>   
</div>

<!--start:photo  privacy-->
<div class="zl-102 posfix setndiv showPp wid90p bg4 dn">
    <div class="nbg1 txtc f16 lh50" id="layerPp">        
    </div>
    <ul class="nlistp fontlig f15">
        <li class="clearfix js-saveOption" value="A">
         <div class="fl wid90p visible textV">
            Visible to All (Recommended)
        </div>
    </li>
    <li class="clearfix js-saveOption" value="C">
        <div class="fl wid90p semiVisible textV">
            Visible to Members I Accept/Express Interest in 
        </div>
    </li>            
</ul>   
</div>
<!--end:photo  privacy-->

<!--start:profile visibility-->
<div class="zl-102 posfix setndiv showpv wid90p bg4 dn">
    <div class="nbg1 txtc f16 lh50" id="layerPv">        
    </div>
    <ul class="nlistp fontlig f15">
        <li class="clearfix js-saveOption" value="A">
         <div class="fl wid90p visible textV">
            Visible to All (Recommended)
        </div>
    </li>
    <li class="clearfix js-saveOption" value="F">
        <div class="fl wid90p semiVisible textV">
            Visible to members who pass my filters
        </div>
    </li> 
    <li class="clearfix js-saveOption" value="C">
        <div class="fl wid90p notVisible textV">
            Not visible to anyone (Not Recommended) 
        </div>
    </li>            
</ul>   
</div>
<!--end:profile visibility-->
</div>
  <!--end:overlay box 1-->
<div> 
    <!--start:top-->
    <div class="bg1 txtc pad15">
      <div class="posrel">
        <div class="fontthin f20 white">Privacy Settings</div>
        <a href="/static/settings"><i class="mainsp posabs set_arow1 set_pos1"></i></a> </div>
    </div>
    <!--end:top--> 
    <!--start-->
    ~if $profileDetail["PHONE_MOB"] && $profileDetail["ISD"]`
    <div class="fullwid brdr1 bg4">
    	<div class="pad1">
			<div class="pad2 color3 f14 fontlig">
            	<div class="pb12">Mobile No.</div>
                <div class="pb12">+~$profileDetail["ISD"]` ~$profileDetail["PHONE_MOB"]`</div>
                <div class="js-showPr" data-title="Mobile_No" value="~$profileDetail['SHOWPHONE_MOB']`">
                ~if $profileDetail["SHOWPHONE_MOB"] eq "" || $profileDetail["SHOWPHONE_MOB"] eq "Y"`
                <div><span>Visible to All (Recommended)</span> <i class="mainsp dropar"></i></div> 
                ~else`
                <div><span>Visible to Members I Accept/Express Interest in</span> <i class="mainsp dropar"></i></div>
                ~/if`
                </div> 
            </div>
        </div>    
    </div>
    ~/if`
    <!--end-->
    <!--start-->
    ~if $altMobile && altMobileIsd`
    <div class="fullwid brdr1 bg4">
    	<div class="pad1">
			<div class="pad2 color3 f14 fontlig">
            	<div class="pb12">Alternate Number</div>
                <div class="pb12">+~$altMobileIsd` ~$altMobile`</div>
                <div class="js-showPr" data-title="Alternate_Number" value="~$showAltMob`">
                 ~if $showAltMob eq "" || $showAltMob eq "Y"`
                <div><span>Visible to All (Recommended) </span><i class="mainsp dropar"></i></div> 
                ~else`
                <div ><span>Visible to Members I Accept/Express Interest in </span><i class="mainsp dropar"></i></div>
                ~/if`
                </div> 
            </div>    
        </div>
    </div>
    ~/if`
    <!--end-->
    <!--start-->
    ~if $profileDetail["PHONE_RES"] && $profileDetail["STD"]`
    <div class="fullwid brdr1 bg4">
    	<div class="pad1">
			<div class="pad2 color3 f14 fontlig">
            	<div class="pb12">Landline Number</div>
                <div class="pb12">~$profileDetail["STD"]` ~$profileDetail["PHONE_RES"]`</div>
                 <div class="js-showPr" data-title="Landline_Number" value="~$profileDetail['SHOWPHONE_RES']`">
                ~if $profileDetail['SHOWPHONE_RES'] eq "" || $profileDetail['SHOWPHONE_RES'] eq "Y"`
                <div><span>Visible to All (Recommended)</span> <i class="mainsp dropar"></i></div> 
                ~else`
                <div><span>Visible to Members I Accept/Express Interest in</span> <i class="mainsp dropar"></i></div>
                ~/if`
                </div>
            </div>    
        </div>
    </div>
    ~/if`
    <!--end-->
    <!--start-->
    <div class="fullwid brdr1 bg4">
    	<div class="pad1">
			<div class="pad2 color3 f14 fontlig">
            	<div class="pb12">Photo Privacy</div>
                <div class="js-showPp" data-title="Photo_Privacy" value="~$profileDetail['PHOTO_DISPLAY']`">
                ~if $profileDetail["PHOTO_DISPLAY"] eq "" || $profileDetail["PHOTO_DISPLAY"] eq "A"`
                <div><span>Visible to All (Recommended) </span><i class="mainsp dropar"></i></div> 
                ~else`
                <div><span>Visible to Members I Accept/Express Interest in </span><i class="mainsp dropar"></i></div>
                ~/if`
                </div>            
            </div>
        </div>    
    </div>
    <!--end-->
    <!--start-->
    <div class="fullwid brdr1 bg4">
    	<div class="pad1">
			<div class="pad2 color3 f14 fontlig">
            	<div class="pb12">Profile Visibility</div>
                <div class="js-showPv" data-title="Profile_Visibility" value="~$profileDetail['PRIVACY']`">
                 ~if $profileDetail["PRIVACY"] eq "" || $profileDetail["PRIVACY"] eq "A"`
                <div><span>Visible to All (Recommended) </span><i class="mainsp dropar"></i></div> 
                ~elseif $profileDetail["PRIVACY"] eq "F"`
                <div><span>Visible to members who pass my filters </span><i class="mainsp dropar"></i></div>
                ~elseif $profileDetail["PRIVACY"] eq "C"`
                <div><span>Not visible to anyone (Not Recommended) </span><i class="mainsp dropar"></i></div>
                ~/if`            
            </div>
        </div>    
    </div>
    <!--end-->
  </div>
</body>
</html>