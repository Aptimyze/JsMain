~$i=0`
    ~foreach $profileArr as $finalval`
  <!--start:tuple-->
    ~$sub=$finalval["SUBSCRIPTION"]`
    ~$subval=""`
    ~if CommonFunction::isEvalueMember($sub)`
        ~$subval="eValue"`
    ~/if`
    ~if CommonFunction::isErishtaMember($sub)`
        ~$subval="eRishta"`
    ~/if`
    <div class="~if $i%2==0` bg5 ~else` bg4 ~/if` pad18">
    <div class="fullwid">
    ~if $finalval["MAIN_PIC"][0]`
        <div class="fl">~if $finalval["MAIN_PIC"][1]`<a id="albumLink" href= "~sfConfig::get('app_site_url')`/social/album?checksum=&profilechecksum=~$finalval['profilechecksum']`&seq=1&bg=~$GENDER`">~/if` <img src="~$finalval["MAIN_PIC"][0]`" class="brdr_radsrp" style="width:75px;height: 75px;"> </a></div>
    ~/if`
      <div class="fl padlr_1">
		~assign var="City_res" value="/"|explode:$finalval["CITY_RES"]`
		~assign var="MTONGUE" value="/"|explode:$finalval["MTONGUE"]`
          <div><a class="fontreg f14 color7 txtdec" href="~sfConfig::get('app_site_url')`/~$finalval["PROFILE_URL"]`" bind-slide="1">~$finalval["USERNAME"]`</a><span class="f11 color2 fontreg padl5 fb">~$subval`</span></div>
        <div class="f13 color3 fontlig txtdec" href='~sfConfig::get('app_site_url')`/~$finalval["PROFILE_URL"]`' bind-slide="1">
          <p>~$finalval["AGE"]` Yrs, ~$finalval["RELIGION"]`~if $finalval["CASTE"]!=$finalval["RELIGION"]`: ~$finalval["CASTE"]|truncate:15:"..":true`~/if`, </p>
          <p>~$MTONGUE[0]`, ~$City_res[0]|truncate:19:"..":true`, </p>
          <p>~$finalval["OCCUPATION"]` </p>
          <p>~$finalval["EDU_LEVEL_NEW"]`</p>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <!--end:tuple--> 
  ~$i=$i+1`
  ~/foreach`
