~if $editDpp`
	<div style="color:#575757;font-size:14px;padding:15px 0px;">Describe the kind of a person you are looking for. We will send you recommendations based on this.</div>
<style>
	div.row2{float:left;width:~if $isEdit`100~else`75%~/if`; margin:2px 0px}
        #div_topnote {width:664px; height:auto; border:1px solid #eeeeee; padding:12px ; float:left; background:#fafafa; margin-bottom:15px; }
</style>
        <div id="div_topnote">
            <div class = "fl" style="font-size:23px; color:#5b5b5b;font-weight: 100;">
                    <div class="pad4top pad10bottom fs16" style="color:#5b5b5b;">
                            ~$subHeading`~if $partnermatchesPage eq 1` <a style="text-decoration:underline;cursor:pointer;" href="/profile/dpp"> Desired Partner Profile</a>~/if`
                            ~if $subHeadingLinkText` <a style="text-decoration:underline;font-size:14px;cursor:pointer;" href="/search/MatchAlertToggle?logic=~$subHeadingLogic`">~$subHeadingLinkText`</a>~/if`
                    </div>
            </div>
        </div>
~/if`
~if $isEdit`<div class="subhd2" style="width:682px;margin-top: 86px;">About Desired Partner <a ~if $post_login eq 1`href="~sfConfig::get("app_site_url")`/profile/edit_dpp.php?width=700&flag=PPA&FLAG=partner&relation=~$RELATION`&post_login=1"~else`href="~sfConfig::get("app_site_url")`/profile/edit_dpp.php?width=700&flag=PPA&FLAG=partner&relation=trim(~$RELATION`)&clicksource=~$clicksource`&logic_used=~$logic_used`"~/if` style="font-size:14px; color:#0f71ae;" class="thickbox">  [Edit]</a></div>
~else`
<div><h3 class="protop1 b" style="color:#000;">~$PROFILENAME` Desired Partner Profile</h3></div>
~/if`
    
    <p class="clr"></p>
    <div class="lf"></div>
    <div class="sp6"></div>
~if $apEditMsg`
<span class="green lf" style="font-size:11px;">Your Desired Partner Profile is under screening. Please check back after 24 hours to see if the changes are   accepted</span>
<div class="sp3"></div>
~/if`
<div class="lf">
    <span class="no_b">  ~$loginProfile->getDecoratedSpouseInfo()|decodevar`
   </span>
</div>

    <div class="sp8"></div>
    <div class="sp8"></div>
	<div class="sp12"></div>
    
<div class="lf" style="width:48%">
<div class="lf pd5 subhd~if !$viewPage`2~/if`">Basic Details  ~if $isEdit`<a href="~$SITE_URL`/profile/edit_dpp.php?width=600&flag=PPBD&FLAG=partner&profilechecksum=~$profileChecksum`&gli=~$GENDER`&APeditID=&clicksource=~$clicksource`&logic_used=~$logic_used`" class="thickbox" style="font-size:14px; color:#0f71ae;">[Edit]</a>~/if`</div>

~if !$viewPage || $dpartner->getDecoratedLHEIGHT() neq ''`
    <div class="row2 no_b">
    <label>Height</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.HEIGHT`~/if`" style="width:175px">:  ~$dpartner->getDecoratedLHEIGHT()|decodevar` to ~$dpartner->getDecoratedHHEIGHT()|decodevar` 
    </div></div>
    ~/if`
    ~if !$viewPage || $dpartner->getDecoratedHAGE() neq ''`
    <div class="row2 no_b">
    <label>Age	</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.AGE`~/if`" style="width:175px">: ~$dpartner->getDecoratedLAGE()` to ~$dpartner->getDecoratedHAGE()`
    </div></div>
    ~/if`
    ~if !$viewPage || $dpartner->getDecoratedPARTNER_MSTATUS() neq ''`
    <div class="row2 no_b">
    <label>Marital Status</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.MSTATUS`~/if`" style="width:175px">: ~$dpartner->getDecoratedPARTNER_MSTATUS()`
    </div></div>
    ~/if`
    ~if !$viewPage || $dpartner->getDecoratedPARTNER_COUNTRYRES() neq ''`
    <div class="row2 no_b">
    <label>Country	</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.COUNTRYRES`~/if`" style="width:175px">: ~$dpartner->getDecoratedPARTNER_COUNTRYRES()|decodevar`
    </div></div>
    ~/if`
    ~if !$viewPage || $dpartner->getDecoratedPARTNER_CITYRES() neq ''`
    <div class="row2 no_b">
    <label>City</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.CITYRES`~/if`" style="width:175px">: ~$dpartner->getDecoratedPARTNER_CITYRES()|decodevar`
    </div></div>
    ~/if`
    ~if !$viewPage || $dpartner->getDecoratedCHILDREN() neq ''`
	~if  $dpartner->getDecoratedPARTNER_MSTATUS() neq 'Never Married' and $dpartner->getDecoratedPARTNER_MSTATUS() neq "-"`
    <div class="row2 no_b">
    <label>Have Children</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.CITYRES`~/if`" style="width:175px">: ~$dpartner->getDecoratedCHILDREN()`
    </div></div>
    ~/if`
    ~/if`
</div>

<div class="lf" style="width:48%;margin-left:20px;">
    <div class="lf pd5 subhd~if !$viewPage`2~/if`"> Religion & Ethnicity ~if $isEdit`<a class="thickbox" href="~$SITE_URL`/profile/edit_dpp.php?width=700&flag=PPRE&FLAG=partner&profilechecksum=~$profileChecksum`&gli=~$GENDER`&APeditID=&clicksource=~$clicksource`&logic_used=~$logic_used`" style="font-size:14px;color:#0f71ae;">[Edit]</a>~/if`</div>
    
    ~if !$viewPage || $dpartner->getDecoratedPARTNER_RELIGION() neq ''`
    <div class="row2 no_b">
    <label>Religion </label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.RELIGION`~/if`" style="width:175px">: ~$dpartner->getDecoratedPARTNER_RELIGION()|decodevar`
    </div></div>
    ~/if`
    ~if !$viewPage || $dpartner->getDecoratedPARTNER_CASTE() neq ''`
    <div class="row2 no_b">
    <label>~$casteLabel`</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.$casteLabel`~/if`" style="width:175px">: ~$dpartner->getDecoratedPARTNER_CASTE()|decodevar` 
    </div></div>
    ~/if`
    ~if !$viewPage || $dpartner->getDecoratedPARTNER_MTONGUE() neq ''`
    <div class="row2 no_b">
    <label>Mother tongue</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.MTONGUE`~/if`" style="width:175px">:  ~$dpartner->getDecoratedPARTNER_MTONGUE()|decodevar`
    </div></div>
    ~/if`
	~if !$viewPage || $dpartner->getDecoratedPARTNER_MANGLIK() neq ''`
    <div class="row2 no_b">
    <label>Manglik</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.MANGLIK`~/if`" style="width:175px">:  ~$dpartner->getDecoratedPARTNER_MANGLIK()`
    </div></div>
	~/if`
</div>
<div class="sp12"></div>
<div class="lf" style="width:48%">
<div class="lf pd5 subhd~if !$viewPage`2~/if`">Lifestyle and Attributes&nbsp;~if $isEdit`<a class="thickbox" href="~$SITE_URL`/profile/edit_dpp.php?width=600&flag=PPLA&FLAG=partner&profilechecksum=~$profileChecksum`&gli=~$GENDER`&APeditID=&clicksource=~$clicksource`&logic_used=~$logic_used`" style="font-size:14px; color:#0f71ae;">[Edit]</a>~/if`</div>
~if !$viewPage || $dpartner->getDecoratedPARTNER_DIET() neq ''`
    <div class="row2 no_b">
    <label>Diet</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.DIET`~/if`" style="width:175px">: ~$dpartner->getDecoratedPARTNER_DIET()`
    </div></div>
    ~/if`
     ~if !$viewPage || $dpartner->getDecoratedPARTNER_SMOKE() neq ''`
    <div class="row2 no_b">
    <label>Smoke</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.SMOKE`~/if`" style="width:175px">: ~$dpartner->getDecoratedPARTNER_SMOKE()`
    </div></div>
    ~/if`
    ~if !$viewPage || $dpartner->getDecoratedPARTNER_DRINK() neq ''`
    <div class="row2 no_b">
    <label>Drink</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.DRINK`~/if`" style="width:175px">: ~$dpartner->getDecoratedPARTNER_DRINK()`
    </div></div>
    ~/if`
    ~if !$viewPage || $dpartner->getDecoratedPARTNER_COMP() neq ''`
    <div class="row2 no_b">
    <label>Complexion	</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.COMP`~/if`" style="width:175px">: ~$dpartner->getDecoratedPARTNER_COMP()`
    </div></div>
    ~/if`
    ~if !$viewPage || $dpartner->getDecoratedPARTNER_BTYPE() neq ''`
    <div class="row2 no_b">
    <label>Body Type</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.BTYPE`~/if`" style="width:175px">: ~$dpartner->getDecoratedPARTNER_BTYPE()`
    </div></div>
    ~/if`
    ~if !$viewPage || $dpartner->getDecoratedHANDICAPPED() neq ''`
    <div class="row2 no_b">
    <label>Challenged</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.HANDI`~/if`" style="width:175px">: ~$dpartner->getDecoratedHANDICAPPED()` 
    </div></div>
    ~/if`
   ~if $show_nhandicap`
    ~if !$viewPage || $dpartner->getDecoratedNHANDICAPPED() neq ''`
    <div class="row2 no_b">
    <label>Nature of Handicap</label><div class="~if $isEdit`lf~else`rf~/if`" style="width:175px">: ~$dpartner->getDecoratedNHANDICAPPED()` 
    </div></div>
    ~/if`
   ~/if`

</div>

<div class="lf" style="width:48%;margin-left:20px;">
<div class="lf pd5 subhd~if !$viewPage`2~/if`">  Education and Occupation ~if $isEdit`<a class="thickbox" href="~$SITE_URL`/profile/edit_dpp.php?width=600&flag=PPEO&FLAG=partner&profilechecksum=~$profileChecksum`&gli=~$GENDER`&APeditID=&clicksource=~$clicksource`&logic_used=~$logic_used`" style="font-size:14px;color:#0f71ae;">[Edit]</a>~/if`</div>
	 ~if !$viewPage || $dpartner->getDecoratedPARTNER_ELEVEL_NEW() neq ''`
    <div class="row2 no_b">
    <label>Education Level </label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.ELEVEL_NEW`~/if`" style="width:175px">: ~$dpartner->getDecoratedPARTNER_ELEVEL_NEW()`
    </div></div>
    ~/if`
    ~if !$viewPage || $dpartner->getDecoratedPARTNER_OCC() neq ''`
    <div class="row2 no_b">
    <label>Occupation</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.OCCUPATION`~/if`" style="width:175px">: ~$dpartner->getDecoratedPARTNER_OCC()|decodevar`
    </div></div>
    ~/if`
    ~if !$viewPage || $dpartner->getDecoratedPARTNER_INCOME() neq ''`
    <div class="row2 no_b">
    <label>Income</label><div class="~if $isEdit`lf~else`rf ~$CODEDPP.INCOME`~/if`" style="width:175px">:   ~$dpartner->getDecoratedPARTNER_INCOME()|decodevar`
    </div></div>
    ~/if`
</div>

    <p class="clr_18"></p>
    <p class="clr_18"></p>
~if !$viewPage`
<div style="width:91%;" >
	<div style="float:right">
		<img src="~sfConfig::get("app_img_url")`/profile/images/arrow_top.gif" />
		<a style="color:#2673bb;font-size:13px;" href="#">Go to Top</a>
	</div>
</div>
~/if`
    <p class="clr_18"></p>
    <p class="clr_18"></p>
    <p class="clr_18"></p>
