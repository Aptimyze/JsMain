<script type="text/javascript">
    var SITE_URL="~sfConfig::get('app_site_url')`";
    var prof_checksum="~$sf_request->getAttribute('checksum')`";
    var user_login="~$sf_request->getAttribute('login')`";
    var google_plus=0;
    var searchId = "~$sf_request->getParameter("searchid")`";
    var seoField = "~$field`";
    var seoValue = "~$value`";
    var seoFlag = "yes";
</script>

~if $GR_LOGGEDIN eq 0 and !$GR_ISEARCH`

<!-- Google Remarketing Starts -->
<script>
    var google_conversion_id = 1056682264;
    var google_conversion_label = "j5CPCPy1_gIQmOLu9wM";
    //  Below custom params may be modified. When no value, use empty string ''
    var google_custom_params = {
    CurrentDate : '~$GR_DATE`',
                PageType : 'CommunityPages',
                Gender : '~$GR_GENDER`',
                Religion : '~$GR_RELIGION`',
                Residence : '~$GR_RESIDENCE`',
                Edu_Occ : '~$GR_EDU_OCC`',
                MotherTongue : '~$GR_MTONGUE`',
                Caste : '~$GR_CASTE`',
        MaritalStatus: '~$GR_MSTATUS`'
    };
    var google_remarketing_only = true;
</script>

<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js"></script>

<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="https://googleads.g.doubleclick.net/pagead/viewthroughconversion/1056682264/?value=0&amp;label=j5CPCPy1_gIQmOLu9wM&amp;guid=ON&amp;script=0"/>
    </div>
</noscript>
<!-- Google Remarketing Ends -->

<script type="text/javascript">
(function() {
    try {
        var viz = document.createElement('script');
        viz.type = 'text/javascript';
        viz.async = true;
        viz.src = ('https:' == document.location.protocol ?'https://ssl.vizury.com' : 'http://www.vizury.com')+ '/analyze/pixel.php?account_id=VIZVRM782';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(viz, s);
        viz.onload = function() {
            pixel.parse();
        };
        viz.onreadystatechange = function() {
            if (viz.readyState == "complete" || viz.readyState == "loaded") {
                pixel.parse();
            }
        };
    } catch (i) {
    }
})();
</script>
~/if`

<!--start:header-->
<header style="margin-top:-20px;">
    <div style="background: rgba(0, 0, 0, 0) url('~sfConfig::get('app_img_url')`/seo_pg_img/~$SLIDER_IMAGE`') no-repeat scroll center center / cover">
        <div class="mainwid container">
            <!--start:top navigation-->
            <div class="pt35">
                ~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1,"registerSource"=>$registerSource])`
            </div>
            <!--end:top navigation-->
            <p class="txtc fontreg f30 colrw comp3 pb15 txtshadow">Happily ever after is not a fairy tale. It's a choice.</p>
        </div>
        <!--start:pink bg-->
    </div>
    <div class="compinkbg txtc">
        <div class="mainwid container fontlig f15 colrw pt30 pb30">
            <p class="f26 fontreg pb40">Become a member</p>
            <p>100% screening of profiles before they start appearing in your search results</p>
            <p class="pb24">'Verified Seal' added to members who we have met in person and collected their documents on ID, education, income etc. </p>
            <div class="mauto wid280">
                <div class="fullwid txtc fontlig f24 bg5 lh63"> <a href="/profile/registration_new.php?source=~if $registerSource`~$registerSource`~else`L2_MT_242~/if`&mini_reg=1" class="colrw disp_b">Register Free</a> </div>
            </div>
        </div>
        <!--start:pink bg-->
    </div>
</header>
<!--end:header-->
<!--start:middle-->
<div class="fontlig communitymid">
    <!--start:h1-->
    <div class="combg1 lh61 txtc fontlig"><h1>~$levelObj->getH1Tag()` Matrimonial</h1></div>
    <!--end:h1-->
    <div class="combg2">
        <div class="mainwid container">
            <!--start:breadcrumb-->
            <div class="pt20 pb30">
                <ul class="bcrumb fontlig hor_list clearfix">
                    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span title="Homepage" itemprop="title"><a href="~sfConfig::get('app_site_url')`" itemprop="url">Home</a></span></li>
                    ~if $urlLevel2 neq ''`
                        <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="vicons comic1"><span title="~$level1` Matrimonial" itemprop="title"><a href="~sfConfig::get('app_site_url')`~$urlLevel1`" itemprop="url">~$level1` Matrimonial</a></span></li>
                        <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="vicons comic1"><span title="~$level2` Matrimonial" itemprop="title"><a href="~sfConfig::get('app_site_url')`~$urlLevel2`" itemprop="url">~$level2` Matrimonial</a></span></li>
                        <li itemprop="title" class="vicons comic1"><span title="~$levelObj->getH1Tag()` ~if $levelObj->getPageSource() eq 'N'`Matrimonial~/if`">~$levelObj->getH1Tag()` ~if $levelObj->getPageSource() eq 'N'`Matrimonial~/if`</span></li>
                    ~else if  $urlLevel2 eq '' && $level2`
                        <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="vicons comic1"><span title="~$level1` Matrimonial" itemprop="title"><a href="~sfConfig::get('app_site_url')`~$urlLevel1`" itemprop="url">~$level1` Matrimonial</a></span></li>
                        <li itemprop="title" class="vicons comic1"><span title="~$level1` Matrimonial">~$level2`</span></li>
                        <li itemprop="title" class="vicons comic1"><span title="~$levelObj->getH1Tag()` ~if $levelObj->getPageSource() eq 'N'`Matrimonial~/if`">~$levelObj->getH1Tag()` ~if $levelObj->getPageSource() eq 'N'`Matrimonial~/if`</span></li>
                    ~else` 
                        ~if $levelObj->getPageSource() neq 'N'`
                            <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="vicons comic1"><span title="~$level1` Matrimonial" itemprop="title"><a href="~sfConfig::get('app_site_url')`~$urlLevel1`" itemprop="url">~$level1` Matrimonial</a></span></li>
                        ~/if`
                        <li itemprop="title" class="vicons comic1"><span title="~$levelObj->getH1Tag()` ~if $levelObj->getPageSource() eq 'N'`Matrimonial~/if`" itemprop="title">~$levelObj->getH1Tag()` ~if $levelObj->getPageSource() eq 'N'`Matrimonial~/if`</span></li>
                    ~/if`
                </ul>
            </div>
            <!--end:breadcrumb-->
            <!--start:div-->
            <div class="clearfix">
                <!--start:left-->
                <div class="fl comwid1">
                    <!--start:tabbing-->
                    <div class="combdr1">
                        <ul class="hor_list clearfix comopts fontlig pos-rel">
                            ~if $rightCnt neq '0' && $leftCnt neq '0' && $page_source eq 'N'`
                                <li class="active"><span id="seca">~$levelObj->getH1Tag()` Brides</span></li>
                                <li ><span id="secb">~$levelObj->getH1Tag()` Grooms</span></li>
                                <li class="pos-abs comhgt1 bg_pink comline" style="width:220px; bottom:-1px"></li>
                            ~else if $rightCnt eq '0' && $leftCnt neq '0' && $page_source eq 'N'`
                                <li class="active"><span id="seca">~$levelObj->getH1Tag()` Brides</span></li>
                                <li class="pos-abs comhgt1 bg_pink comline" style="width:220px; bottom:-1px"></li>
                            ~else if $rightCnt neq '0' && $leftCnt eq '0' && $page_source eq 'N'`
                                <li ><span id="secb">~$levelObj->getH1Tag()` Grooms</span></li>
                                <li class="pos-abs comhgt1 bg_pink comline" style="width:220px; bottom:-1px"></li>
                            ~else`
                                ~if $leftCnt neq '0' && $page_source eq 'B'`
                                <li class="active"><span id="seca"  pagesource='~$page_source`'>~$levelObj->getH1Tag()` ~if $levelObj->getBirdeURL()`Brides~/if`</span></li>
                                ~/if`
                                ~if $rightCnt neq '0' && $page_source eq 'G'`
                                <li class="active"><span id="seca" pagesource='~$page_source`'>~$levelObj->getH1Tag()` ~if $levelObj->getGroomURL()`Grooms~/if`</span></li>
                                ~/if`
                            ~/if`
                        </ul>
                    </div>
                    <!--end:tabbing-->
                    <!--start:content-->
                    <div>
                        <div class="comwid1 scrollhid pb40">
                            <div class="pos-rel clearfix js-comshift" style="width:1464px">
                                ~if $rightCnt neq '0' && $leftCnt neq '0' && $page_source eq 'N'`
                                    <!--start:bride div-->
                                    ~if $leftCnt neq '0' && $page_source eq 'N' || $page_source eq 'B'`
                                    <div class="comwid1 fl">
                                        <!--start:listing-->
                                        <ul class="comlist clearfix hor_list fontreg">
                                            ~foreach $leftArr as $finalval`
                                            <li>
                                                <div class="bg-white">
                                                    <a href="/~$finalval["PROFILE_URL"]`">
                                                        <div class="comdim1" style="overflow: hidden;">
                                                            <img src="~$finalval["MAIN_PIC"][0]`" class="vtop" style="width:235px;"/>
                                                        </div>
                                                    </a>
                                                    <div class="comp1 profileContent color11" style="height:175px;">
                                                        <p class="f16 color11"><a href="/~$finalval["PROFILE_URL"]`">~$finalval["USERNAME"]`</a></p>
                                                        <p class="f13 pt10 lh20" style="height:100px;">~$finalval["AGE"]` Years, ~$finalval["HEIGHT"]`~if $finalval["REL_LINK"]`<a title="~$finalval["RELIGION"]` Matrimonial" href="~$finalval["REL_LINK"]`">, ~$finalval["RELIGION"]`</a>~else`, ~$finalval["RELIGION"]`~/if` / ~if $finalval["MTNG_LINK"]`<a title="~preg_replace('/\/|-/',' ',$finalval["MTONGUE"])` Matrimonial" href="~$finalval["MTNG_LINK"]`">~$finalval["MTONGUE"]`</a>~else`~$finalval["MTONGUE"]`~/if`~if $finalval["CASTE_LINK"]`<a title="~preg_replace('/\/|-/',' ',$finalval["CASTE"])` Matrimonial" href="~$finalval["CASTE_LINK"]`">, ~$finalval["CASTE"]`</a>~else`, ~$finalval["CASTE"]`~/if`~if $finalval["GOTHRA"] neq ''`, ~$finalval["GOTHRA"]`~/if`~if $finalval["EDU_LEVEL_NEW"]`, ~$finalval["EDU_LEVEL_NEW"]`~/if`~if $finalval["INCOME"]`, ~$finalval["INCOME"]`~/if`~if $finalval["OCC_LINK"]`<a title="~preg_replace('/\/|-/',' ',$finalval["OCCUPATION"])` Matrimonial" href="~$finalval["OCC_LINK"]`">, ~$finalval["OCCUPATION"]`</a>~else`, ~$finalval["OCCUPATION"]`~/if`~if $finalval["CITY_LINK"]`<a title="~preg_replace('/\/|-/',' ',$finalval["CITY_RES"])` Matrimonial" href="~$finalval["CITY_LINK"]`">, ~$finalval["CITY_RES"]`</a>~else`, ~$finalval["CITY_RES"]`~/if`</p>
                                                        <p class="f13 pt10 lh20 color12">~substr($finalval["YOURINFO"],0,50)|decodevar`~if strlen($finalval["YOURINFO"]) > 50`...~/if`</p>
                                                    </div>
                                                </div>
                                            </li>
                                            ~/foreach`
                                        </ul>
                                        <!--end:listing-->
                                        ~if $page_source eq 'N' && $levelObj->getBrideURL()`
                                        <p class="f15 fontlig txtr pt15"><a href="~$levelObj->getBrideURL()`" class="color11">View More ~$levelObj->getH1Tag()` Brides</a></p>
                                        ~/if`
                                    </div>
                                    ~/if`
                                    <!--end:bride div-->
                                    <!--start:groom div-->
                                    ~if $rightCnt neq '0' && $page_source eq 'N' || $page_source eq 'G'`
                                    <div class="comwid1 fl">
                                        <!--start:listing-->
                                        <ul class="comlist clearfix hor_list fontreg">
                                            ~foreach $rightArr as $finalval`
                                            <li>
                                                <div class="bg-white">
                                                    <a href="/~$finalval["PROFILE_URL"]`">
                                                        <div class="comdim1" style="overflow: hidden;">
                                                            <img src="~$finalval["MAIN_PIC"][0]`" class="vtop" style="width:235px;"/>
                                                        </div>
                                                    </a>
                                                    <div class="comp1 profileContent color11" style="height:175px;">
                                                        <p class="f16 color11"><a href="/~$finalval["PROFILE_URL"]`">~$finalval["USERNAME"]`</a></p>
                                                        <p class="f13 pt10 lh20" style="height:100px;">~$finalval["AGE"]` Years, ~$finalval["HEIGHT"]`~if $finalval["REL_LINK"]`<a title="~$finalval["RELIGION"]` Matrimonial" href="~$finalval["REL_LINK"]`">, ~$finalval["RELIGION"]`</a>~else`, ~$finalval["RELIGION"]`~/if` / ~if $finalval["MTNG_LINK"]`<a title="~preg_replace('/\/|-/',' ',$finalval["MTONGUE"])` Matrimonial" href="~$finalval["MTNG_LINK"]`">~$finalval["MTONGUE"]`</a>~else`~$finalval["MTONGUE"]`~/if`~if $finalval["CASTE_LINK"]`<a title="~preg_replace('/\/|-/',' ',$finalval["CASTE"])` Matrimonial" href="~$finalval["CASTE_LINK"]`">, ~$finalval["CASTE"]`</a>~else`, ~$finalval["CASTE"]`~/if`~if $finalval["GOTHRA"] neq ''`, ~$finalval["GOTHRA"]`~/if`~if $finalval["EDU_LEVEL_NEW"]`, ~$finalval["EDU_LEVEL_NEW"]`~/if`~if $finalval["INCOME"]`, ~$finalval["INCOME"]`~/if`~if $finalval["OCC_LINK"]`<a title="~preg_replace('/\/|-/',' ',$finalval["OCCUPATION"])` Matrimonial" href="~$finalval["OCC_LINK"]`">, ~$finalval["OCCUPATION"]`</a>~else`, ~$finalval["OCCUPATION"]`~/if`~if $finalval["CITY_LINK"]`<a title="~preg_replace('/\/|-/',' ',$finalval["CITY_RES"])` Matrimonial" href="~$finalval["CITY_LINK"]`">, ~$finalval["CITY_RES"]`</a>~else`, ~$finalval["CITY_RES"]`~/if`</p>
                                                        <p class="f13 pt10 lh20 color12">~substr($finalval["YOURINFO"],0,50)|decodevar`~if strlen($finalval["YOURINFO"]) > 50`...~/if`</p>
                                                    </div>
                                                </div>
                                            </li>
                                            ~/foreach`
                                        </ul>
                                        <!--end:listing-->
                                        ~if $page_source eq 'N' && $levelObj->getGroomURL()`
                                        <p class="f15 fontlig txtr pt15"><a href="~$levelObj->getGroomURL()`" class="color11">View More ~$levelObj->getH1Tag()` Grooms</a></p>
                                        ~/if`
                                    </div>
                                    ~/if`
                                    <!--end:groom div-->
                                ~else`
                                    <div class="comwid1 fl">
                                        <!--start:listing-->
                                        <ul class="comlist clearfix hor_list fontreg">
                                            ~foreach $leftArr as $finalval`
                                            <li>
                                                <div class="bg-white">
                                                    <a href="/~$finalval["PROFILE_URL"]`">
                                                        <div class="comdim1" style="overflow: hidden;">
                                                            <img src="~$finalval["MAIN_PIC"][0]`" class="vtop" style="width:235px;"/>
                                                        </div>
                                                    </a>
                                                    <div class="comp1 profileContent color11" style="height:175px;">
                                                        <p class="f16 color11"><a href="/~$finalval["PROFILE_URL"]`">~$finalval["USERNAME"]`</a></p>
                                                        <p class="f13 pt10 lh20" style="height:100px;">~$finalval["AGE"]` Years, ~$finalval["HEIGHT"]`~if $finalval["REL_LINK"]`<a title="~$finalval["RELIGION"]` Matrimonial" href="~$finalval["REL_LINK"]`">, ~$finalval["RELIGION"]`</a>~else`, ~$finalval["RELIGION"]`~/if` / ~if $finalval["MTNG_LINK"]`<a title="~preg_replace('/\/|-/',' ',$finalval["MTONGUE"])` Matrimonial" href="~$finalval["MTNG_LINK"]`">~$finalval["MTONGUE"]`</a>~else`~$finalval["MTONGUE"]`~/if`~if $finalval["CASTE_LINK"]`<a title="~preg_replace('/\/|-/',' ',$finalval["CASTE"])` Matrimonial" href="~$finalval["CASTE_LINK"]`">, ~$finalval["CASTE"]`</a>~else`, ~$finalval["CASTE"]`~/if`~if $finalval["GOTHRA"] neq ''`, ~$finalval["GOTHRA"]`~/if`~if $finalval["EDU_LEVEL_NEW"]`, ~$finalval["EDU_LEVEL_NEW"]`~/if`~if $finalval["INCOME"]`, ~$finalval["INCOME"]`~/if`~if $finalval["OCC_LINK"]`<a title="~preg_replace('/\/|-/',' ',$finalval["OCCUPATION"])` Matrimonial" href="~$finalval["OCC_LINK"]`">, ~$finalval["OCCUPATION"]`</a>~else`, ~$finalval["OCCUPATION"]`~/if`~if $finalval["CITY_LINK"]`<a title="~preg_replace('/\/|-/',' ',$finalval["CITY_RES"])` Matrimonial" href="~$finalval["CITY_LINK"]`">, ~$finalval["CITY_RES"]`</a>~else`, ~$finalval["CITY_RES"]`~/if`</p>
                                                        <p class="f13 pt10 lh20 color12">~substr($finalval["YOURINFO"],0,50)|decodevar`~if strlen($finalval["YOURINFO"]) > 50`...~/if`</p>
                                                    </div>
                                                </div>
                                            </li>
                                            ~/foreach`
                                            ~foreach $rightArr as $finalval`
                                            <li>
                                                <div class="bg-white">
                                                    <a href="/~$finalval["PROFILE_URL"]`">
                                                        <div class="comdim1" style="overflow: hidden;">
                                                            <img src="~$finalval["MAIN_PIC"][0]`" class="vtop" style="width:235px;"/>
                                                        </div>
                                                    </a>
                                                    <div class="comp1 profileContent color11" style="height:175px;">
                                                        <p class="f16 color11"><a href="/~$finalval["PROFILE_URL"]`">~$finalval["USERNAME"]`</a></p>
                                                        <p class="f13 pt10 lh20" style="height:100px;">~$finalval["AGE"]` Years, ~$finalval["HEIGHT"]`~if $finalval["REL_LINK"]`<a title="~$finalval["RELIGION"]` Matrimonial" href="~$finalval["REL_LINK"]`">, ~$finalval["RELIGION"]`</a>~else`, ~$finalval["RELIGION"]`~/if` / ~if $finalval["MTNG_LINK"]`<a title="~preg_replace('/\/|-/',' ',$finalval["MTONGUE"])` Matrimonial" href="~$finalval["MTNG_LINK"]`">~$finalval["MTONGUE"]`</a>~else`~$finalval["MTONGUE"]`~/if`~if $finalval["CASTE_LINK"]`<a title="~preg_replace('/\/|-/',' ',$finalval["CASTE"])` Matrimonial" href="~$finalval["CASTE_LINK"]`">, ~$finalval["CASTE"]`</a>~else`, ~$finalval["CASTE"]`~/if`~if $finalval["GOTHRA"] neq ''`, ~$finalval["GOTHRA"]`~/if`~if $finalval["EDU_LEVEL_NEW"]`, ~$finalval["EDU_LEVEL_NEW"]`~/if`~if $finalval["INCOME"]`, ~$finalval["INCOME"]`~/if`~if $finalval["OCC_LINK"]`<a title="~preg_replace('/\/|-/',' ',$finalval["OCCUPATION"])` Matrimonial" href="~$finalval["OCC_LINK"]`">, ~$finalval["OCCUPATION"]`</a>~else`, ~$finalval["OCCUPATION"]`~/if`~if $finalval["CITY_LINK"]`<a title="~preg_replace('/\/|-/',' ',$finalval["CITY_RES"])` Matrimonial" href="~$finalval["CITY_LINK"]`">, ~$finalval["CITY_RES"]`</a>~else`, ~$finalval["CITY_RES"]`~/if`</p>
                                                        <p class="f13 pt10 lh20 color12">~substr($finalval["YOURINFO"],0,50)|decodevar`~if strlen($finalval["YOURINFO"]) > 50`...~/if`</p>
                                                    </div>
                                                </div>
                                            </li>
                                            ~/foreach`
                                        </ul>
                                        <!--end:listing-->
                                    </div>
                                ~/if`
                            </div>
                        </div>
                    </div>
                    <!--end:content-->
                </div>
                <!--end:left-->
                <!--start:right-->
                ~if $breadCrumbObj->getLevelTwoBreadCrumb()`
                <div class="fr comwid2 fontlig">
                    <table>
                        <tbody><tr>
                            <th class="f20 color11 comp2 fontlig">Filter profiles by</th>
                        </tr>
                        ~assign var=LevelTwoBreadCrumb value=$breadCrumbObj->getLevelTwoBreadCrumb()`
                        ~foreach $LevelTwoBreadCrumb as $key=>$val`
                        ~if $val`
                        <tr>
                            <td class="pt40">
                                <table class="fillist">
                                    <tbody><tr>
                                        <td class="f20 color11 comp2 fontlig">~str_ireplace('Mtongue','Mother Tongue',$val[3][0])`</td>
                                    </tr>
                                    ~assign var="tab" value=0`
                                    <tr>
                                        ~section name="tr1" start=0 loop=count($val[0])`
                                        ~if stripos($val[2][$tab],$seoUrl)`
                                        <td class="cells" style="background-color:#d9475c;">
                                            <a href="~$urlLevel1`" title="~$val[6][$tab]`" style="color:#fff">~$val[1][$tab]`&nbsp;&nbsp;<span class="disp_ib vicons comnCross pr10 cursp"></span></a>
                                        </td>
                                        ~else`
                                        <td class="cells">
                                            <a href="~$val[2][$tab]`" title="~$val[6][$tab]`">~$val[1][$tab]`</a>
                                        </td>
                                        ~/if`
                                        ~assign var="tab" value=$tab+1`
                                        ~/section`
                                    </tr>
                                </tbody></table>
                            </td>
                        </tr>
                        ~/if`
                        ~/foreach`
                    </tbody></table>
                    <div class="f11 fontlig color13 pt40 pb5 combdr1 comp2">
                        Last updated on ~$curDate`
                    </div>
                </div>
                ~/if`
                <!--end:right-->
            </div>
            <!--end:div-->
        </div>
    </div>
</div>
<!--end:middle-->
<!--start:div-->
<div class="combg3 fontlig">
    <div class="container mainwid">
        <!--start:matrimonial-->
        ~if $levelObj->getContent()`
        <div class="pt40 pb48 txtc">
        <table>
            <tbody>
                <tr>
                    <th>
                        <h2>~$levelObj->getH1Tag()` ~if $levelObj->getPageSource() eq 'N'`Matrimonial~/if`</h2>
                    </th>
                </tr>
                <tr>
                    <td class="f15 fontlig color11 pt20 lh18">~$levelObj->getContent()|decodevar`</td>
                </tr>
            </tbody>
        </table>
        </div>
        ~/if`
        <!--end:matrimonial-->
        <!--start:Matched by Jeevansathi-->
        <div class="pb30 ~if not $levelObj->getContent()`pt40~/if`">
            <div class="txtc pb30"><h3>Matched by Jeevansathi</h3></div>
            <!--   =======  this widget is prsent on home, if possible include it from there ===== -->
            <ul class="hor_list clearfix mtch f14 color11 fontlig">
                ~foreach from=$successStoryData key=k item=successStory`
                <li> <img style="width:220px; height:220px;" src="~PictureFunctions::getCloudOrApplicationCompleteUrl($successStory.SQUARE_PIC_URL)`" class="brdr-0">
                    <p>~$successStory.NAME2` weds ~$successStory.NAME1`</p>
                </li>
                ~/foreach`
            </ul>
        </div>
        <!--end:Matched by Jeevansathi-->
    </div>
</div>
<!--end:div-->
<!--start:row 5-->
~include_partial("global/JSPC/_jspcMatrimonialLinks")`
<!--end:row 5-->
<!--start:footer-->
~include_partial('global/JSPC/_jspcCommonFooter')`
<!--end:footer-->
<script>
~if $crazyEgg`
    setTimeout(function(){var a=document.createElement("script");
    var b=document.getElementsByTagName("script")[0];
    a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0011/8626.js?"+Math.floor(new Date().getTime()/3600000);
    a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
~/if`
$(document).ready(function(){
    $('ul.comopts li.active span').trigger('click');
});
</script>
