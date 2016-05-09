<!--listing start -->
 <div class=~if $left`"pro_tup pro_tup2"~else`"pro_tup pro_tup1 pro_tup1b"~/if`>
	
	~foreach $profileArr as $finalval`
	
  	<div>
		<div class="img_cont~$left` fl">
			<div>
			~if $finalval["MAIN_PIC"][1]`
			<a class='thickbox' href="/social/album?profilechecksum=~$finalval['profilechecksum']`&seq=1" ><div style=" cursor:pointer;float:left; margin:0 3px 3px 0;background-image:url(~$finalval["MAIN_PIC"][0]`)" align='left'><img src="http://ser4.jeevansathi.com/profile/images/transparent_img.gif" alt="~$levelObj->getAltTag()`" width="100" height="133" border="0" ></div></a>
			~else`
			<a href="/profile/viewprofile.php?profilechecksum=~$finalval['profilechecksum']`&stype=Z"><div style=" display:inline; margin:0 3px 3px 0; "><img border="0" vspace="0" src="~$finalval["MAIN_PIC"][0]`"></div></a>
			~/if`
			</div>	
			~if $finalval["MAIN_PIC"][2]`
				<div><a href= "~sfConfig::get('app_site_url')`/social/album?checksum=&profilechecksum=~$finalval['profilechecksum']`&seq=1" class="thickbox">More Photos</a>
				</div>
			~else`
					<div>
						<a href= "~sfConfig::get('app_site_url')`/social/album?checksum=&profilechecksum=~$finalval['profilechecksum']`&seq=1" class="thickbox">&nbsp;</a>
					</div>

			~/if`
		</div>
		<div class="p_tp1">
			<div class="fl p_tp2">
				<a href="~sfConfig::get('app_site_url')`/~$finalval["PROFILE_URL"]`" class="mcol">~$title` id - ~$finalval["USERNAME"]`</a>
			</div>
			<div class="fr p_tp3" >
				<img src="~sfConfig::get('app_img_url')`/profile/images/comm_pages/v_sim_profile.gif" />&nbsp;
				<a href="~sfConfig::get('app_site_url')`/profile/simprofile_search.php?checksum=&contact=~$finalval["PROFILEID"]`&SIM_USERNAME=~$finalval["USERNAME"]`" class="bcol">View similar profiles</a>
			</div>
		</div>
		<div class="prof_info~$left` fl ml_5">
			~$finalval["AGE"]`, ~$finalval["HEIGHT"]`, <br>

			~if $finalval["REL_LINK"]`
			<a class="bcol" title="~$finalval["RELIGION"]` Matrimony" href="~$finalval["REL_LINK"]`">~$finalval["RELIGION"]`, </a>
			~else` ~$finalval["RELIGION"]`, 
			~/if`
			~if $finalval["MTNG_LINK"]`
			<a class="bcol" title="~preg_replace('/\/|-/',' ',$finalval["MTONGUE"])` Matrimony" href="~$finalval["MTNG_LINK"]`">~$finalval["MTONGUE"]`,</a>
			~else`~$finalval["MTONGUE"]` ,
			~/if`
			<br /> 
			~if $finalval["CASTE_LINK"]`
			<a class="bcol" title="~preg_replace('/\/|-/',' ',$finalval["CASTE"])` Matrimony" href="~$finalval["CASTE_LINK"]`">~$finalval["CASTE"]`,<br /></a>
			~else` 
			~$finalval["CASTE"]`,<br />
			~/if`~$finalval["GOTHRA"]`
			~if $finalval["EDU_LEVEL_NEW"]`
			~$finalval["EDU_LEVEL_NEW"]`, 
			~/if`
			<br />~$finalval["INCOME"]`,
			~if $finalval["OCC_LINK"]`
			<a class="bcol" title="~preg_replace('/\/|-/',' ',$finalval["OCCUPATION"])` Matrimony" href="~$finalval["OCC_LINK"]`"> ~$finalval["OCCUPATION"]`</a>
			~else` ~$finalval["OCCUPATION"]`
			~/if` 
			in 
			~if $finalval["CITY_LINK"]`
			<a class="bcol" title="~preg_replace('/\/|-/',' ',$finalval["CITY_RES"])` Matrimony" href="~$finalval["CITY_LINK"]`">~$finalval["CITY_RES"]`</a>
			~else`
			~$finalval["CITY_RES"]`
			~/if`
		</div>
		<p class="fl" style="width:~$textWidth`px;">
   			~$finalval["YOURINFO"]|decodevar` 
			<a href="~sfConfig::get('app_site_url')`/~$finalval["PROFILE_URL"]`" class="fr mar_top_15 b bcol">View Profile</a> 
		</p>
		<div class="clr"></div>
	</div>
	<div style="border-bottom:1px  #000000;margin: 10px 0px 10px 0px;"></div>
	~/foreach`
   </div>

 <!--listing  left end-->
  
