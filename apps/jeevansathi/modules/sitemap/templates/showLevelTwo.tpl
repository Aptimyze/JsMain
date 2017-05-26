<div class="cover1">
	<div class="container mainwid pt35 pb40"> 
		<!--start:top nav case logged in-->
		~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1])`
	</div>
</div>
      <!--end:top nav case logged in-->
<div class="bg4">
      	<div class="mainwid container">
      		<div class="pt25 pb30"> 
      			<div class="bg-white fullwid fontlig color11">
      				<div class="padalln">
      					<h1 class="txtc f30 fontlig">Sitemap</h1>

      					<div class="f15 color12 pt25 pb5">By ~$showParentType` + ~$showMappedType`:</div>
      					<div class="sitemap_cont">
      						~foreach $siteMapLinks as $key=>$val`
      						<a href="~sfConfig::get('app_site_url')`~$val['URL']`" title="~preg_replace('/\/|-/',' ',$val['LABEL'])`~if $pageSource eq 'N'` Matrimony~else if $pageSource eq 'B'` Brides~else if $pageSource eq 'G'` Grooms~/if`">~$val['LABEL']`~if $pageSource eq 'N'` Matrimony ~elseif $pageSource eq 'B'` Brides ~elseif $pageSource eq 'G'` Grooms ~/if`</a>
      						~/foreach`
      					</div>
      				</div>
      			</div>
      		</div>
      		<!--start:Banner-->
      		
      	</div>
      	<!--end:Banner-->
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