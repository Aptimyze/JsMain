<div class="bg4">

<!--start:div-->
<div class="fullwid bg2">
  <div class="pad5">
    <div class="fl wid20p color5 fontlig f12 pt2 opa70">Album</div>
    <div class="fl wid60p txtc color5 fontlig f14"> ~if $GENDER eq 'M'` Groom ~else` Bride's~/if` Details<span class="arow4"></span> </div>
    <!--<div class="fl pt10 wid10p"><i class="mainsp arow3"></i></div>-->
    <div class="fl wid20p color5 txtr fontlig f12 pt2 opa70">Family</div>
    <div class="clr"></div>
  </div>
</div>
<!--end:div--> 
<!--start:div-->
<div class="fullwid txtc color1 brdr1 pad4">
  <div class="fontlig f12"> Complete your profile by filling fields marked in Red </div>
</div>
<!--endt:div--> 
<!--start:div-->
<div class="fullwid  brdr1">
  <div class="pad1">
    <div class="pad2">
      <div class="fl wid94p">
        
        ~if $MyProfileYourInfo.yourinfo1 || $MyProfileYourInfo.yourinfo`
        <div class="color3 f14 fontlig">About Me</div>
			<div  id="yourinfo" class="color4 f12 pt10 fontlig">~if $MyProfileYourInfo.yourinfo1` ~$MyProfileYourInfo.yourinfo1|decodevar`<a onclick="view_more(0);" class="color2">...more</a> ~else`~$MyProfileYourInfo.yourinfo|decodevar`~/if`
			</div>
		~else`
		<div class="color2 f14 fontlig">About Me</div>
        <div class="color2 f12 pt10 fontlig">Introduce yourself</div>
        ~/if`
        
      </div>
      <div class="fr wid4p pt8"> <i class="mainsp arow1"></i> </div>
      <div class="clr"></div>
    </div>
  </div>
</div>
<!--end:div--> 

<!--start:div-->
<div class="fullwid  brdr1">
  <div class="pad1">
    <div class="pad2">
      <div class="fl wid94p">
		  ~if $basicStr eq 1`
			<div class="color3 f14 fontlig">
		  ~else`
			<div class="color2 f14 fontlig">
		  ~/if`
			Basic Details</div>
        <div class="color4 f12 pt10 fontlig" slideOverLayer=1>
			<span class="color2">Bride's Name</span>, Gender, Date of Birth, Height, Marital Status, Country - City Living In</div>
      </div>
      <div class="fr wid4p pt8"> <i class="mainsp arow1"></i> </div>
      <div class="clr"></div>
    </div>
  </div>
</div>
<!--end:div-->
<!--start:div-->
<div class="fullwid  brdr1">
  <div class="pad1">
    <div class="pad2">
      <div class="fl wid94p">
        <div class="color3 f14 fontlig">Ethnicity</div>
        <div class="color4 f12 pt10 fontlig">Religion, Caste, Mother Tongue,<span class="color2"> Sect</span>, <span class="color2">Gothra</span>, <span class="color2">Native Place</span></div>
      </div>
      <div class="fr wid4p pt8"> <i class="mainsp arow1"></i> </div>
      <div class="clr"></div>
    </div>
  </div>
</div>
<!--end:div-->
<!--start:div-->
<div class="fullwid  brdr1">
  <div class="pad1">
    <div class="pad2">
      <div class="fl wid94p">
        <div class="color3 f14 fontlig">Belief System</div>
        <div class="color4 f12 pt10 fontlig">Amritdhari, Cuts Hair, Trims Beard, Wears Turban,Clean Shaven</div>
      </div>
      <div class="fr wid4p pt8"> <i class="mainsp arow1"></i> </div>
      <div class="clr"></div>
    </div>
  </div>
</div>
<!--end:div-->
<!--start:div-->
<div class="fullwid  brdr1">
  <div class="pad1">
    <div class="pad2">
      <div class="fl wid94p">
        <div class="color3 f14 fontlig">Appearance</div>
        <div class="color4 f12 pt10 fontlig"><span class="color2">Complexion</span>, <span class="color2">Body Type</span>, Weight</div>
      </div>
      <div class="fr wid4p pt8"> <i class="mainsp arow1"></i> </div>
      <div class="clr"></div>
    </div>
  </div>
</div>
<!--end:div-->
<!--start:div-->
<div class="fullwid  brdr1">
  <div class="pad1">
    <div class="pad2">
      <div class="fl wid94p">
        <div class="color3 f14 fontlig">Special Cases</div>
        <div class="color4 f12 pt10 fontlig">Challenged, Thalessemia, HIV +</div>
      </div>
      <div class="fr wid4p pt8"> <i class="mainsp arow1"></i> </div>
      <div class="clr"></div>
    </div>
  </div>
</div>
<!--end:div-->
</div>


