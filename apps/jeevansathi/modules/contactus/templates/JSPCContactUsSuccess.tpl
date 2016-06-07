~assign var=currency value= $sf_request->getAttribute('currency')`
<!--start:header-->
<div class="cover1">
	<div id="top0" class="container mainwid pt35 pb30">
    	<!--start:top horizontal bar-->
            ~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1])`
        <!--end:top horizontal bar--> 
    </div>
</div>
<!--end:header-->

<!--start:middle part-->
<div class="bg4">
	<div class="container mainwid">
        <!--start:nav 1-->
        <div class="cubg1 crbdr1">
            <ul class="hor_list clearfix fontlig f15 cunav1 color11 fullwid">
                <li class="wid50p active"><span>Contact Us</span></li>
                <li class="wid49p cursp"><span><a class="disp_b color11" href="/faq/feedback">Feedback</a></span></li>
            </ul>
        </div>
        <!--end:nav 1-->
        
        <div class="pt30 pb30">
        	<!--start:contact us html-->
            	<div class="cubg2">
            	<p class="txtc fontreg f17 color11 lh61 crbdr1">~$data.details[0].header`</p>
                <ul class="hor_list clearfix cunav2 pt15 pb15 color11">
                	<li>
                    	<i class="sprite2 cuhome"></i>
                        <p class="f17 color11 fontlig pt15">~$data.details[0].office[0].address`</p>
                        <p class="f13 fontreg  mt15"><!--<span class="disp_ib vicons pl20 cumap">View map</span>--></p>
                    </li>
                    <li>
                    	<i class="sprite2 cuphn"></i>                       
                        <div class="clearfix f17 fontlig pt15">
                        	<div class="fl fullwid txtc">
                                <p>~if $currency eq 'RS'`1-800-419-6299~else`+91-120-4393500~/if`~if $currency eq 'RS'`<span class="f13 pt10 fontreg"> (Toll Free)</span>~/if`</p>
                            </div>                        
                        </div>
                    </li>
                    <li>
                    	<i class="sprite2 cumail"></i>
                        <p class="f17 color11 fontlig pt15"><a class="notc" href="mailto:help@jeevansathi.com">help@jeevansathi.com</a></p>
                    </li>
                </ul>            
            </div>            
            	<!--start:tabbing section-->
                <div class="mt30 clearfix">
                	<!--start:left-->
                    <div id="leftStateDiv" class="cuwid1 cubg3 fl">
                    	<!--start:tile-->
                        <div class="pl40 lh63 fontreg f15 color12 crbdr1">State</div>                        
                        <!--end:tile-->
                        <!--start:vertical listing-->
                        <ul id="verticalStateUl" class="cunav3 listnone pt30 pb30 f15 fontlig color11">
                        	~foreach from=$data.details key=offices item=value name=verticalStateLoop`
                                ~if $value.header neq "Head Office"`
                                <li id="li_~$value.office[0].state_val`" class="stateTab cursp" stateIdVertical="~$value.office[0].state_val`">
                                    <span id="sp_~$value.office[0].state_val`">~$value.header` </span>
                                     <p id="p_~$value.office[0].state_val`" class="culine disp-none"></p>
                                </li>
                                ~/if`
                            ~/foreach`
                        </ul>                        
                        <!--end:vertical listing-->
                    
                    </div>                    
                    <!--end:left-->
                    <!--start:right-->
                    <div id="rightCityDiv" class="fl cubg2 cuwid2">
                    	<!--start:div-->
                        <div class="clearfix lh61 crbdr1 cup1">
                        	<div class="fl fontreg f15 color12">City Location</div>
                        	<!--start:horizontal menu-->
                            <div class="fr" style="width:80%; margin-left:10px">
                            	<div class="fullwid clearfix">
                                	<!--start:left arrow-->
                                    <div id="prevButton" class="fl controlBtn">
                                    	<i class="sprite2 crlft cursp"></i>
                                    </div>                                    
                                    <!--end:left arrow-->
                                    <!--start:listing-->
                                    <div class="fl scrollhid cup2 cuwid3">
                                        <ul class="hor_list clearfix fontlig f15 ccnav4 color11 pos-rel" style="width:1000px;">
                                            <!--<li id="allCities" class="cup3 cursp">All</li>-->
                                            <!--
                                            <div class="scrollbar">
                                                <div class="culine1"></div>
                                            </div>
                                            -->
                                            ~foreach from=$data.details key=k item=value name=centersLoop`
                                                ~if $value.header neq "Head Office"`
                                                <div id="wrap~$value.office[0].state_val`" class="disp-none">
                                                    
                                                    <div id="parentDiv~$value.office[0].state_val`" class="disp-none">
                                                        <ul id="ulAllCities~$value.office[0].state_val`" class="lstn ulAllCities">
                                                            <li id="allCities~$value.office[0].state_val`" class="cup3 cursp disp-none allCities">All</li>
                                                            ~foreach from=$value.office key=centers item=val name=centersCities`
                                                                ~if $val.city neq "Noida - Head office"`
                                                                <li class="horizontalCitiesTab cursp cup3 disp-none" stateIdTopRow="~$val.state_val`" cityIdTopRow="~$val.city_id`~$centers`">~$val.city`</li>
                                                                <!--<li id="hl~$val.city_id`~$centers`" class="culine1 disp-none" style="width:46px"></li>-->
                                                                ~/if`
                                                            ~/foreach`
                                                        </ul>
                                                    </div>
                                                </div>
                                                ~/if`
                                            ~/foreach`
                                        </ul>
                                     </div>
                                    <!--end:listing-->                                    
                                    <!--start:right arrow-->
                                    <div  id="nextButton" class="fr controlBtn" >
                                    	<i class="sprite2 crrgt cursp"></i>
                                    </div>                                    
                                    <!--end:right arrow-->                                
                                </div>
                            </div>                            
                            <!--end:horizontal menu-->
                        </div>
                        
                        <!--end:div-->
                        <!--start:content-->
                        <div id="allCenters" class="cupad f15 fontlig color11">
                        	<!--start:address-->
                            ~foreach from=$data.details key=k item=value name=centersLoop`
                                ~if $value.header neq "Head Office"`
                                    ~foreach from=$value.office key=centers item=val name=centersCities`
					~if $val.city neq "Noida - Head office"`
                                        <div class="pt35 disp-none" stateIdCenters="~$val.state_val`" cityIdCenters="~$val.city_id`~$centers`">
                                            <p class="fontreg">~$val.city`</p>
                                            <ul class="addrlist">
                                                <li class="clearfix">
                                                    <p>Contact Person</p>                                       
                                                    <p>~$val.contact_person`</p>
                                                </li>
                                                <li class="clearfix">
                                                    <p>Address</p>                                       
                                                    <p>~$val.address`<!--<span class="disp_ib vicons pl20 cumap fontreg f13">View map</span>--></p>
                                                </li>
                                                 <li class="clearfix">
                                                    <p>Phone</p>
                                                    <p>
                                                    ~foreach from=$val.phone key=kk item=vv name=phoneLoop`
                                                        ~if $kk eq "0"`
                                                            ~$vv`
                                                        ~else`
                                                            ,&nbsp ~$vv`
                                                        ~/if`
                                                    ~/foreach`
                                                    </p>
                                                </li>

                                            </ul>
                                        </div>
				    ~/if`	
                                    ~/foreach`
                                ~/if`
                            ~/foreach`
                            <!--end:address-->
                        </div>
                        <!--end:content-->
                    
                    </div>                    
                    <!--end:right-->
                
                
                </div>                
                <!--end:tabbing section-->
            
            <!--end:contact us html-->      
        </div>
        
    </div>
    
    
  
</div>
<!--end:middle part-->
<!--start:footer-->
~include_partial('global/JSPC/_jspcCommonFooter',["fromSideLink"=>$fromSideLink])`
<!--end:footer--> 

<script type="text/javascript">
    var prevStateTab;
    var currentState;
    var previousCityTab;
    var defaultCityTab = "DE";
    var defaultVerticalStateListingHeight;
    if("~$userCountry`" == "51" && ("~$userCity`" && "~$userCity`" !="0")){
        var city = "~$userCity`".substring(0,2);
        $("#verticalStateUl li").each(function(index){
            if($(this).attr("id").substring(3,5) == city){
                defaultCityTab = city;
            }
        });
    }
    $(".stateTab").bind('click', function(){
        if ($(':animated').length) {
            return false;
        }
        setHorizontalTabs(this);
    });
    $(".horizontalCitiesTab").bind('click', function(){
        if ($(':animated').length) {
            return false;
        }
        setVerticalCenters(this);
    });
    $(document).ready(function(){
       defaultVerticalStateListingHeight = $("#leftStateDiv").height(); 
       setHorizontalTabs($("#li_"+defaultCityTab));
    });
    $(".allCities").click(function(){
        $(this).addClass("active disabledTab").removeClass("cursp");
        $("[cityIdTopRow='"+previousCityTab+"']").removeClass("disabledTab active");
        $("[stateIdCenters='"+currentState+"']").each(function(index){
            $(this).removeClass("disp-none");
        });
        
        setHeightLeftRightPanel();
    });
    $(".controlBtn").click(function(){
        if ($(':animated').length) {
            return false;
        }
        moveSlider(this);
    });
    
   
</script>
