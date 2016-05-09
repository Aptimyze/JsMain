<div class="bg4 mndivhgt">
   <div class="hgt10 bg7"></div>  
   <div class="pad5 color13">
   		<div class="f40 opa50 fontreg">~$data.title`</div>
        <div class="f20 fontlig pad20 mem_brdr3">~$data.message`</div>
        <div class="f16 mem_brdr3">
        	<div class="hgt10"></div>
            <!--start:amount-->        
            <div class="fontlig">Amount</div>
            <div class="fontreg">~$data.order_content.amount`</div>
            <!--end:amount-->
            <div class="hgt10"></div>
            <!--start:plan-->        
            <div class="fontlig">Membership Plan</div>
            <div class="fontreg">~$data.order_content.membership_plan`</div>
            <!--end:plan-->
            <div class="hgt10"></div>
            <!--start:duration-->        
            <div class="fontlig">Duration</div>
            <div class="fontreg">~$data.order_content.duration`</div>
            <!--end:duration-->
            <div class="hgt10"></div>
            <!--start:VAS--> 
            ~if $data.order_content.vas_services`
                <div class="fontlig">Value Added Services</div>
                ~foreach from=$data.order_content.vas_services key=k item=v name=vasLoop`       
                    <div class="fontreg">~$v`</div>
                ~/foreach`
            ~/if`
            <!--end:VAS-->
            <div class="hgt10"></div>
            <!--start:orderid-->        
            <div class="fontlig">Order ID</div>
            <div class="fontreg">~$data.order_content.orderid`</div>
            <!--end:orderid-->
            <div class="hgt10"></div>
             <!--start:tdate-->        
            <div class="fontlig">Transaction Date</div>
            <div class="fontreg">~$data.order_content.transaction_date`</div>
            <!--end:tdate-->
            <div class="hgt10"></div>
        </div>
   		<div class="color2 fontreg f20 fullwid pad12">
        	<div class="fl" onclick="window.location='~sfConfig::get('app_site_url')`/search/partnermatches'" style="cursor:pointer;">~$data.proceed_text`</a></div>
            <div class="fl pt3 padl5"><i class="mem-spite mem_arwdownpnk"></i></div>
            <div class="clr"></div>
        </div>
   </div>
</div>