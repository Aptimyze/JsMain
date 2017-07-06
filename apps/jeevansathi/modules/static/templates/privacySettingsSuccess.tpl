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
    ~if $phoneMob && $isd`
    <div class="fullwid brdr1 bg4">
    	<div class="pad1">
			<div class="pad2 color3 f14 fontlig">
            	<div class="pb12">Mobile No.</div>
                <div class="pb12">+~$isd` ~$phoneMob`</div>
                <div class="js-showPr" data-title="Mobile_No" value="~$showPhoneMob`">
                ~if $showPhoneMob eq "" || $showPhoneMob eq "Y"`
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
    ~if $phoneRes && $std`
    <div class="fullwid brdr1 bg4">
    	<div class="pad1">
			<div class="pad2 color3 f14 fontlig">
            	<div class="pb12">Landline Number</div>
                <div class="pb12">~$std` ~$phoneRes`</div>
                 <div class="js-showPr" data-title="Landline_Number" value="~$showPhoneRes`">
                ~if $showPhoneRes eq "" || $showPhoneRes eq "Y"`
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
                <div class="js-showPr" data-title="Photo_Privacy" value="~$photoDisplay`">
                ~if $photoDisplay eq "" || $photoDisplay eq "Y"`
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
                <div class="js-showPv" data-title="Profile_Visibility" value="~$privacy`">
                 ~if $privacy eq "" || $privacy eq "A"`
                <div><span>Visible to All (Recommended) </span><i class="mainsp dropar"></i></div> 
                ~elseif $privacy eq "F"`
                <div><span>Visible to members who pass my filters </span><i class="mainsp dropar"></i></div>
                ~elseif $privacy eq "C"`
                <div><span>Not visible to anyone (Not Recommended) </span><i class="mainsp dropar"></i></div>
                ~/if`            
            </div>
        </div>    
    </div>
    <!--end-->
   
   
  </div>

  
</body>
</html>