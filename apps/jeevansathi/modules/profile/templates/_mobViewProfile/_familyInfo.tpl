<!--start:content-->
~if isset($arrData.myfamily) || isset($arrData.family_bg) || isset($arrData.family_income) || isset($arrData.father_occ) || isset($arrData.mother_occ) || isset($arrData.sibling_info) || isset($arrData.sub_caste) || isset($arrData.gothra) || isset($arrData.native_place) || isset($arrData.caste) || isset($arrData.mathab) || isset($arrData.diocese) ||isset($arrData.sect) || isset($arrData.living)`
    <div class="pad5 bg4 fontlig color3 clearfix f14">
        ~if isset($arrData.myfamily)`
            <div class="fontlig pad20 wordBreak vpro_lineHeight" id="vpro_myfamily">~$arrData.myfamily`</div>
        ~else`
            <div class="hgt10"></div>
        ~/if`
        
        ~if isset($arrData.family_bg)`
            <div class="f12 color1">Family Background</div>
            <div class="fontlig pb15" id="vpro_family_bg" >~$arrData.family_bg`</div>
        ~/if`
        ~if isset($arrData.family_income)`
            <div class="f12 color1">Family Income</div>
            <div class="fontlig pb15" id="vpro_family_income" >~$arrData.family_income`</div>
        ~/if`
        ~if isset($arrData.native_place)`   
            <div class="f12 color1">Family based out of</div>
            <div class="fontlig pb15" id="vpro_native_place">~$arrData.native_place`</div>
        ~/if`
        ~if isset($arrData.father_occ)`
            <div class="f12 color1">Father's Occupation</div>
            <div class="fontlig pb15" id="vpro_father_occ">~$arrData.father_occ`</div>
        ~/if`
        ~if isset($arrData.mother_occ)`
            <div class="f12 color1">Mother's Occupation</div>
            <div class="fontlig pb15" id="vpro_mother_occ">~$arrData.mother_occ`</div>
        ~/if`
        ~if isset($arrData.sibling_info)`
            <div class="f12 color1">Brother / Sister</div>
            <div class="fontlig pb15" id="vpro_sibling_info">~$arrData.sibling_info|nl2br`</div>
        ~/if`
        ~if isset($arrData.sub_caste)`
            <div class="f12 color1">Sub Caste</div>
            <div class="fontlig pb15" id="vpro_sub_caste">~$arrData.sub_caste`</div>
        ~/if`
        ~if isset($arrData.gothra)`
            <div class="f12 color1">Gothra</div>
            <div class="fontlig pb15" id="vpro_gothra">~$arrData.gothra`</div>
        ~/if`        
        ~if isset($arrData.caste)`
            <div class="f12 color1">Caste</div>
            <div class="fontlig pb15" id="vpro_caste">~$arrData.caste`</div>
        ~/if`
        ~if isset($arrData.mathab)`	
            <div class="f12 color1">Ma'thab</div>
            <div class="fontlig pb15" id="vpro_mathab">~$arrData.mathab`</div>
        ~/if`
        ~if isset($arrData.diocese)`	
            <div class="f12 color1">Diocese</div>
            <div class="fontlig pb15" id="vpro_diocese">~$arrData.diocese`</div>
        ~/if`
        ~if isset($arrData.sect)`	
            <div class="f12 color1">Sect</div>
            <div class="fontlig pb15" id="vpro_sect">~$arrData.sect`</div>
        ~/if`
        ~if isset($arrData.living)`	
            <div class="fontlig color1 pb15" id="vpro_living">~$arrData.living`</div>
        ~/if`
    </div>
<!--end:content--> 
~else`
    <div class="hgt10"></div>
    <div class="fontlig color1 f14 pb10 txtc" id="vpro_no_family_detail">~$userName` has not provided family details yet</div>
~/if`
