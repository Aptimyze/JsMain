<div class="tapoverlay posfix" style="display: none"></div>
  <!--start:overlay box 1-->
  <div class="zl-102 posfix setndiv wid90p bg4" style="display: none">
    <div class="nbg1 txtc f16 lh50" id="layerT">        
    </div>
    <ul class="nlistp fontlig f15">
        <li class="clearfix js-saveOption" value="Y">
             <div class="fl wid90p visible textV">
                Visible to All(Recommended)
            </div>
        </li>
        <li class="clearfix js-saveOption" value="C">
            <div class="fl wid90p semiVisible textV">
                Visible to Members I Accept/Express Interest in 
            </div>
        </li>            
    </ul>
    
    
    
  </div>
  <!--end:overlay box 1-->
<div> 
    <!--start:top-->
    <div class="bg1 txtc pad15">
      <div class="posrel">
        <div class="fontthin f20 white">Privacy Settings</div>
        <a href="/profile/mainmenu.php"><i class="mainsp posabs set_arow1 set_pos1"></i></a> </div>
    </div>
    <!--end:top--> 
    <!--start-->
    ~if $phoneMob && $isd`
    <div class="fullwid brdr1 bg4">
    	<div class="pad1">
			<div class="pad2 color3 f14 fontlig">
            	<div class="pb12">Mobile No.</div>
                <div class="pb12">+~$isd` ~$phoneMob`</div>
                <div class="js-showPr" data-title="Mobile" value="~$showPhoneMob`">
                ~if $showPhoneMob eq "" || $showPhoneMob eq "Y"`
                <div><span>Visible to All(Recommended)</span> <i class="mainsp dropar"></i></div> 
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
                 ~if $showAltMob eq "" || $showAltMob eq "Y"`
                <div>Visible to All (Recommended) <i class="mainsp dropar"></i></div> 
                ~else`
                <div >Visible to Members I Accept/Express Interest in <i class="mainsp dropar"></i></div>
                ~/if` 
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
                ~if $showPhoneRes eq "" || $showPhoneRes eq "Y"`
                <div>Visible to All (Recommended) <i class="mainsp dropar"></i></div> 
                ~else`
                <div>Visible to Members I Accept/Express Interest in <i class="mainsp dropar"></i></div>
                ~/if`
        </div>    
    </div>
    ~/if`
    <!--end-->
    <!--start-->
    <div class="fullwid brdr1 bg4">
    	<div class="pad1">
			<div class="pad2 color3 f14 fontlig">
            	<div class="pb12">Photo Privacy</div>
                ~if $photoDisplay eq "" || $photoDisplay eq "Y"`
                <div>Visible to All (Recommended) <i class="mainsp dropar"></i></div> 
                ~else`
                <div>Visible to Members I Accept/Express Interest in <i class="mainsp dropar"></i></div>
                ~/if`            
            </div>
        </div>    
    </div>
    <!--end-->
    <!--start-->
    <div class="fullwid brdr1 bg4">
    	<div class="pad1">
			<div class="pad2 color3 f14 fontlig">
            	<div class="pb12">Profile Visibility</div>
                 ~if $privacy eq "" || $privacy eq "A"`
                <div>Visible to All (Recommended) <i class="mainsp dropar"></i></div> 
                ~elseif $privacy eq "F"`
                <div>Visible to members who pass my filters <i class="mainsp dropar"></i></div>
                ~elseif $privacy eq "C"`
                <div>Not visible to anyone (Not Recommended) <i class="mainsp dropar"></i></div>
                ~/if`            
            </div>
        </div>    
    </div>
    <!--end-->
   
   
  </div>

  
</body>
</html>