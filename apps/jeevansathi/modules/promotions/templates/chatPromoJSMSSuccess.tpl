<body>
	<div id="chatPormoMS">
    	<div class="fullwid fullheight cpbg1 posrel">
        	<div class="posabs setshare txtc color7 wid94p"> 
            	<p class="fontreg f14">JeevanSathi Chat now on Android!</p>  
                <ul class="txtc fontlig f12 pt15 lh25" style="list-style-position:inside">
                	<li>Connect faster with your matches through the Chat feature. </li>
                    <li>Get instantly notified about messages.</li>
                    <li>Chat with real time online matches and get instant response. </li>                    
                </ul> 
                <p class="fontreg f14 pt10 pb20">All this and much more !!</p>    	
            	<a class="closeCP" href=""></a>
                <div>
              	<img src="/images/chatPromoImg1.png" class="txtc"/>
                </div>
                
                <button class="bg7 white fontreg f16" onclick="goToPlayStore()">Download APP</button>
            
            </div>
        
        </div>
    </div>
</body>
<script type="text/javascript">
    $(function(){

        var Phgt = $(window).height();
        $('#chatPormoMS').css('height',Phgt);
    })

    function goToPlayStore()
    {
       var firstPart = '~sfConfig::get('app_site_url')`';
       var partLink = '/static/appredirect?type=androidMobFooter';
       window.location.href = firstPart+partLink;
    }

</script>

