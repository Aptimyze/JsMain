<div class="cover1">
    <div class="container mainwid pt35 pb40"> 
      <!--start:top nav case logged in-->
      ~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1])`
      </div>
 </div>
      <!--end:top nav case logged in-->
<div class="bg4">

	
    <div class="mainwid container pt30">
    <div class="fullwid bg-white">
      <div class="pt30 pl30 fontlig">
      <p class="fontreg">Grievance Officer</p>
        <p>Anant Gupta</p>
          <p>B-8, Sector 132</p>
          <p>Noida, UP 201301</p>
          <p>India</p>
          <p>Email:grievance-officer@jeevansathi.com</p>
        </div>
      		~if $layout eq 1` 
      		<!-- Naukri Summons/Notices Form Start --> 
      		<iframe marginwidth="0" marginheight="0" hspace="0" vspace="0"
      		frameborder="0" scrolling="no" bordercolor="#000000" height="1400" width="990"
      		src="http://w5.naukri.com/fdbck/main/grievance_iframe.php?src=jeevansathi.com"></iframe>
      		<!-- Naukri Summons/Notices Form END -->
      		~/if`
      		~if $layout neq 1`
      		<!-- Naukri Summons/Notices Form Start -->
      		<iframe marginwidth="0" marginheight="0" hspace="0" vspace="0"
      		frameborder="0" scrolling="no" bordercolor="#000000" height="1400" width="990"	 
      		src='http://w5.naukri.com/fdbck/main/complaint_iframe.php?src=jeevansathi.com'>
      	</iframe>
      	<!-- Naukri Summons/Notices Form END -->
      	~/if`
    </div>
    </div>
      	
</div>
  <footer> 
  <!--start:footer band 1-->      
     ~include_partial('global/JSPC/_jspcCommonFooter')`
    </div>
  </div>
  <!--endt:footer band 1--> 
  
</footer>
<!--end:footer--> 
</div>
</div>
