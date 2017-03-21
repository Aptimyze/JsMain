~if isset($arrData.city_country) || isset($arrData.date_time) || isset($arrData.more_astro)`
    <!--start:Kundali And Astro-->
    <div class="pad5 bg4 fontlig color3 clearfix f14">
      <div class="fl"><i class="vpro_sprite vpro_kund"></i></div>
      <div class="fl color2 f14 vpro_padlTop" id="vpro_astroSection">Kundali & Astro</div>
      <div class="clr hgt10"></div>
        ~if isset($arrData.city_country)` 
            <div class="f12 color1">City, Country of Birth</div>
            <div class="fontlig pb15" id="vpro_city_country" >~$arrData.city_country`</div>
        ~/if`
        ~if isset($arrData.date_time)` 
            <div class="f12 color1">Date &amp; Time of Birth</div>
            <div class="fontlig pb15" id="vpro_date_time" >~$arrData.date_time`</div>
        ~/if`
        ~if isset($arrData.more_astro)`
            <div class="f12 color1">More</div>
            <div class="fontlig pb15">
                ~if isset($arrData.more_astro.rashi)`              
                <div class="clearfix">
                    <div class="fontlig fl vpro_wordwrap" id="vpro_more_astro_rashi" >~$arrData.more_astro.rashi`</div>
                </div>
                ~/if`	            
                ~if isset($arrData.more_astro.nakshatra)`  
                <div class="clearfix">
                    <div class="fontlig fl vpro_wordwrap2" id="vpro_more_astro_nakshatra" >~$arrData.more_astro.nakshatra`</div>
                </div>
                ~/if`
                ~if $arrData.sameGender neq 1`
                <!--start:button-->
                <div class="clearfix pb20 pt20">
                ~if ($arrData.othersHoroscope eq 'Y' && ($arrData.toShowHoroscope eq 'Y' || $arrData.toShowHoroscope eq '') )`
                <a href = "~$SITE_URL`/api/v1/profile/downloadHoroscope?SAMEGENDER=&FILTER=&ERROR_MES=&view_username=~$arrData.username`&SIM_USERNAME=~$arrData.username`&type=Horoscope&checksum=&otherprofilechecksum=~$otherProfileChecksum`&randValue=890&GENDER=~$arrData.gender`"><button class="fontlig lh40 astroBtn1" style="width:49%">Download Horoscope</button></a>
                ~/if`
                ~if (!$arrData.NO_ASTRO && $arrData.sameGender neq 1)`
                 <button class="fontlig lh40 astroBtn1 fr ~if $arrData.COMPATIBILITY_SUBSCRIPTION eq 'N' AND $arrData.paidMem eq 'Y'` js-astroCompMem ~elseif $arrData.COMPATIBILITY_SUBSCRIPTION eq 'N'` js-freeAstroComp ~elseif $arrData.COMPATIBILITY_SUBSCRIPTION neq 'N'` js-astroMem ~/if`" style="width:48%">Get Astro Report</button>
                 ~/if`
                 ~/if`
             </div>
             <!--end:button-->
                ~if isset($arrData.more_astro.horo_match)`
                <div class="clearfix pt10">
                    <div class="fl"><i class="vpro_sprite vpro_pin"></i></div>
                    <div class="fontlig padl5 fl vpro_wordwrap" id="vpro_more_astro_horo_match">~$arrData.more_astro.horo_match`</div>
                </div>
                ~/if`
                <div class="clearfix vpro_dn" id='gunaScore'>
                </div>
                
            </div>
        ~/if`
       
    </div>
    <!--end:Kundali And Astro--> 
~/if`
<!-- Religious Beliefs Section-->
~if isset($arrData.muslim_m) || isset($arrData.sikh_m) || isset($arrData.christian_m)`
<div class="pad5 bg4 fontlig color3 clearfix f14">
    <div class="fl"><i class="vpro_sprite vpro_kund"></i></div>
     <div class="fl color2 f14 vpro_padlTop">Religious Beliefs</div>
     <div class="clr hgt10"></div>
    ~if isset($arrData.muslim_m)`
        ~foreach from=$arrData.muslim_m key=k item=v`
            ~if isset($v) && $k neq working_marriage`
                <div class="f12 color1">~$v['label']`</div>
                <div class="fontlig pb15" id="vpro_~$k`">~$v['value']`</div>
            ~else if isset($v)`
                 <div class="fl"><i class="vpro_sprite vpro_pin"></i></div>
                <div class="fontlig padl5 fl vpro_wordwrap pb15" id="vpro_~$k`" >~$v['value']`</div>
            ~/if`
        ~/foreach`
    ~/if`
    ~if isset($arrData.sikh_m)`
        <div class="fontlig pb15" id="vpro_more_sikh">~$arrData.sikh_m`</div>
    ~/if`
    ~if isset($arrData.christian_m)`
        <div class="fontlig pb15" id="vpro_more_christian">~$arrData.christian_m`</div>
    ~/if`
</div>    
~/if`

<!--start:later for astro download-->
      
    
    <div class="setcenter fontlig f18 dispnone js-astroTextButton">
        <div class="astrob1 js-textAstro">            
        </div>
        <button class="f18 fontlig astrob2 js-buttonAstro"></button>
    </div>
    <!--end:later for astro download-->
<script>

var username = "~$arrData.username`";
var otherProfilechecksum = "~$otherProfilechecksum`";
var gender = "~$arrData.gender`";
</script>