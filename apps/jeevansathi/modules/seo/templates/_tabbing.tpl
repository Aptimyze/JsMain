<div align="center">
<div class="txt_plus_ul">
	<ul class="tab">
		<h2><b style="padding: 0px 23px 0px 20px;" class="f_14 gry fl">Browse Matrimonial Profiles by</b></h2>
		<li style="margin:0 5px 0 0;" id="community_li" onclick="seo_change_tab('tab1');" class="active">
			<i class="deco"></i><u class="deco">Mother tongue</u>
		</li>
		<li style="margin:0 5px 0 0;" id="caste_li" onclick="seo_change_tab('tab2');">
			<i class="deco"></i><u class="deco">Caste</u>
		</li>
		<li style="margin:0 5px 0 0;" id="religion_li" onclick="seo_change_tab('tab3');">
			<i class="deco"></i><u class="deco">Religion</u>
		</li>
		
		<li style="margin:0 5px 0 0;" id="city_li" onclick="seo_change_tab('tab4');">
			<i class="deco"></i><u class="deco">City</u>
		</li>
		<li style="margin:0 5px 0 0;" id="occupation_li" onclick="seo_change_tab('tab5');">
			<i class="deco"></i><u class="deco">Occupation</u>
		</li>
		<li style="margin:0 5px 0 0;" id="state_li" onclick="seo_change_tab('tab6');">
			<i class="deco"></i><u class="deco">State</u>
		</li>
		<li style="margin:0 5px 0 0;" id="nri_li" onclick="seo_change_tab('tab7');">
			<i class="deco"></i><u class="deco">NRI</u>
		</li>
		<li style="margin:0 5px 0 0;" id="splcases_li" onclick="seo_change_tab('tab8');">
			<i class="deco"></i><u class="deco">Special Cases</u>
		</li>
	</ul>

	<div class="tabcontents">
		<div class="content" id="tab1" tabindex="0">
			<p class="clr"></p>
			<p class="brwse">
				~foreach from=$SEO_FOOTER.MTONGUE key=k item=v name=seoFoot`
                                               <span class="sBG">
						<a title="~$v.N[2]` Matrimony" href="~$SITE_URL`~$v.N[0]`">~$v.N[1]`</a> 
						~if $v.B[0] || $v.G[0]`
						
						<span class="sub-options" id="s_mt_~$smarty.foreach.seoFoot.iteration`">
						
						<a title="~$v.B[2]` brides Matrimony" href="~$SITE_URL`~$v.B[0]`">~$v.B[1]` Brides</a> | <a title="~$v.G[2]` grooms Matrimony" href="~$SITE_URL`~$v.G[0]`">~$v.G[1]` Grooms</a> 
						</span>
                                                ~/if`
                                                ~if $smarty.foreach.seoFoot.last` ~else`|~/if`
                                                </span>
                                        ~/foreach`
			</p>
		</div>
		<div class="content" id="tab2" tabindex="1">
			<p class="brwse">
				~foreach from=$SEO_FOOTER.CASTE key=k item=v name=seoFoot`
                                        <span class="sBG">
						<a title="~$v.N[2]` Matrimony" href="~$SITE_URL`~$v.N[0]`">~$v.N[1]`</a> 
						~if $v.B[0] || $v.G[0]`
						
						<span class="sub-options" id="s_c_~$smarty.foreach.seoFoot.iteration`">
						
						<a title="~$v.B[2]` brides Matrimony" href="~$SITE_URL`~$v.B[0]`">~$v.B[1]` Brides</a> | <a title="~$v.G[2]` grooms Matrimony" href="~$SITE_URL`~$v.G[0]`">~$v.G[1]` Grooms</a> 
						</span>
                                                ~/if`
                                                ~if $smarty.foreach.seoFoot.last` ~else`|~/if`
                                                </span>
                                        ~/foreach`
			</p>
		</div>

		<div class="content" id="tab3" tabindex="2">
			<p class="brwse">
				~foreach from=$SEO_FOOTER.RELIGION key=k item=v name=seoFoot`
                                        <span class="sBG">
						<a title="~$v.N[2]` Matrimony" href="~$SITE_URL`~$v.N[0]`">~$v.N[1]`</a> 
						~if $v.B[0] || $v.G[0]`
						
						<span class="sub-options" id="s_r_~$smarty.foreach.seoFoot.iteration`">
						
						<a title="~$v.B[2]` brides Matrimony" href="~$SITE_URL`~$v.B[0]`">~$v.B[1]` Brides</a> | <a title="~$v.G[2]` grooms Matrimony" href="~$SITE_URL`~$v.G[0]`">~$v.G[1]` Grooms</a> 
						</span>
                                                ~/if`
                                                ~if $smarty.foreach.seoFoot.last` ~else`|~/if`
                                                </span>
                                        ~/foreach`
			</p>
		</div>
		
		<div class="content" id="tab4" tabindex="3">
			<p class="brwse">
				~foreach from=$SEO_FOOTER.CITY key=k item=v name=seoFoot`
                                        <span class="sBG">
						<a title="~$v.N[2]` Matrimony" href="~$SITE_URL`~$v.N[0]`">~$v.N[1]`</a> 
						~if $v.B[0] || $v.G[0]`
						
						<span class="sub-options" id="s_ct_~$smarty.foreach.seoFoot.iteration`">
						
						<a title="~$v.B[2]` brides Matrimony" href="~$SITE_URL`~$v.B[0]`">~$v.B[1]` Brides</a> | <a title="~$v.G[2]` grooms Matrimony" href="~$SITE_URL`~$v.G[0]`">~$v.G[1]` Grooms</a> 
						</span>
                                                ~/if`
                                                ~if $smarty.foreach.seoFoot.last` ~else`|~/if`
                                                </span>
                                        ~/foreach`
			</p>
		</div>
		<div class="content" id="tab5" tabindex="4">
			<p class="brwse">
				~foreach from=$SEO_FOOTER.OCCUPATION key=k item=v name=seoFoot`
                                       <span class="sBG">
						<a title="~$v.N[2]` Matrimony" href="~$SITE_URL`~$v.N[0]`">~$v.N[1]`</a> 
						~if $v.B[0] || $v.G[0]`
						
						<span class="sub-options" id="s_o_~$smarty.foreach.seoFoot.iteration`">
						
						<a title="~$v.B[2]` brides Matrimony" href="~$SITE_URL`~$v.B[0]`">~$v.B[1]` Brides</a> | <a title="~$v.G[2]` grooms Matrimony" href="~$SITE_URL`~$v.G[0]`">~$v.G[1]` Grooms</a> 
						</span>
                                                ~/if`
                                                ~if $smarty.foreach.seoFoot.last` ~else`|~/if`
                                                </span>
                                        ~/foreach`
			</p>
		</div>
		<div class="content" id="tab6" tabindex="5">
			<p  class="brwse">
				~foreach from=$SEO_FOOTER.STATE key=k item=v name=seoFoot`
                                                <span class="sBG">
						<a title="~$v.N[2]` Matrimony" href="~$SITE_URL`~$v.N[0]`">~$v.N[1]`</a> 
						~if $v.B[0] || $v.G[0]`
						
						<span class="sub-options" id="s_s_~$smarty.foreach.seoFoot.iteration`">
						
						<a title="~$v.B[2]` brides Matrimony" href="~$SITE_URL`~$v.B[0]`">~$v.B[1]` Brides</a> | <a title="~$v.G[2]` grooms Matrimony" href="~$SITE_URL`~$v.G[0]`">~$v.G[1]` Grooms</a> 
						</span>
                                                ~/if`
                                                ~if $smarty.foreach.seoFoot.last` ~else`|~/if`
                                                </span>
                                        ~/foreach`
			</p>
		</div>
		<div class="content" id="tab7" tabindex="6">
			<p class="brwse">
			~foreach from=$SEO_FOOTER.COUNTRY key=k item=v name=seoFoot`
                                        <span class="sBG">
						<a title="~$v.N[2]` Matrimony" href="~$SITE_URL`~$v.N[0]`">~$v.N[1]`</a> 
						~if $v.B[0] || $v.G[0]`
						
						<span class="sub-options" id="s_co_~$smarty.foreach.seoFoot.iteration`">
						
						<a title="~$v.B[2]` brides Matrimony" href="~$SITE_URL`~$v.B[0]`">~$v.B[1]` Brides</a> | <a title="~$v.G[2]` grooms Matrimony" href="~$SITE_URL`~$v.G[0]`">~$v.G[1]` Grooms</a> 
						</span>
                                                ~/if`
                                                ~if $smarty.foreach.seoFoot.last` ~else`|~/if`
                                                </span>
                                        ~/foreach`
			</p>
		</div>
		<div class="content" id="tab8" tabindex="7">
			<p class="brwse">
			~foreach from=$SEO_FOOTER.SPECIAL_CASES key=k item=v name=seoFoot`
                                       <span class="sBG">
						<a title="~$v.N[2]` Matrimony" href="~$SITE_URL`~$v.N[0]`">~$v.N[1]`</a> 
						~if $v.B[0] || $v.G[0]`
						
						<span class="sub-options" id="s_sc_~$smarty.foreach.seoFoot.iteration`">
						
						<a title="~$v.B[2]` brides Matrimony" href="~$SITE_URL`~$v.B[0]`">~$v.B[1]` Brides</a> | <a title="~$v.G[2]` grooms Matrimony" href="~$SITE_URL`~$v.G[0]`">~$v.G[1]` Grooms</a> 
						</span>
																								~/if`
                                                ~if $smarty.foreach.seoFoot.last` ~else`|~/if`
                                                </span>
                                        ~/foreach`
			</p>
		</div>
	</div>	
</div>
</div>
<script>
seo_change_tab('tab1');
</script>

