<div class="cover1">
    <div class="container mainwid pt35 pb40"> 
      <!--start:top nav case logged in-->
      ~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1])`
      </div>
 </div>
      <!--end:top nav case logged in-->
      <div class="bg-4">
  <div class="container mainwid"> 
    <!--start:tabbing-->
    <div class="setbg1 fullwid pos-rel">
      <ul class="settingTab clearfix fontlig f15 color11">
      	~foreach from=$legalPageTabs key=linkName item=data` 
      	<li>
      	<a href="/static/page/~$linkName`" class="color11 cursp"><div>~$data`</div></a>
      	</li>
      	~/foreach`
        <li id="slideBar"class="pos_abs hgt2 bg5" style="width:20%; left:0%"></li>
      </ul>
    </div>
    <!--end:tabbing--> 
    <!--start:content-->
    <div class="pt30 pb30"> 
      <div class="bg-white fullwid">
      <div class="padalln">
      ~include_partial("static/jspcStatic/_$viewThisPage")`
      </div>
      </div> 
      
    </div>
    <!--start:content-->
    
  </div>
</div>
<!--start:footer-->
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

<script>
	var pageName="~$pageName`";
</script>



