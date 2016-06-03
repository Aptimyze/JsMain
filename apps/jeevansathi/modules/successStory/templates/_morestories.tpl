<!-- Main Container -->
	<div style="width:720px;">
~foreach from=$storyArr key=k item=nostory`
	~if $nostory.USERNAME neq ''` 

		<!-- Left Side Starts from Here -->
	    ~if $k % 2 eq 0`
	
		<div class="lf" style="margin-top:10px;" >
		    <div class="lf">
				<img src="~sfConfig::get("app_img_url")`/success/images/sr_top_left.gif">
		    </div>
		    <div class="lf sr_top_bg red b" style="width:311px;" id="~$a[$k]`">
				<div class="lf" style="_padding:3px 0;">
					<input type="checkbox" align="absbottom" style="border:none;display:none;">~$nostory.USERNAME` 
				</div>
		    </div>
	
	
		    <div class="lf">
				<img src="~sfConfig::get("app_img_url")`/success/images/sr_top_right.gif">
		    </div>
		    <div class="clear">
		    </div>
	
	
	   		 <div class="lf orange_border_small" id="~$boxheight[$k]`" style="_margin-top:-3px;">
	   		 	<div class="lf" style="margin-right:10px;"  oncontextmenu="return false;">
					~if $nostory.PHOTO_P`
						<a onMouseover="showtrail2('~$nostory.PHOTO_CHECKSUM`','~$nostory.USERNAME`',event,'~PictureFunctions::getCloudOrApplicationCompleteUrl($nostory.PHOTO_P)`')" onMouseout="hidetrail2()"><div style=" float:left;margin:0 3px 3px 0;background-image:url(~PictureFunctions::getCloudOrApplicationCompleteUrl($nostory.PHOTO_T)`)" align='left'><img src="~sfConfig::get("app_img_url")`/profile/images/transparent_img.gif" width="60" height="60" border="0" ></div></a>
					~else`
						<div style="float:left;margin:0 3px 3px 0;background-image:url(~sfConfig::get("app_img_url")`/profile/images/nophotoimage.gif)" align='left'><img src="~sfConfig::get("app_img_url")`/profile/images/transparent_img.gif" width="60" height="60" border="0" ></div>
					~/if`
				</div>
		    	<div class="lf b gray" id="~$b[$k]`" style="width:210px">
					<a class="gray">~$nostory.INFO` 
					</a>
		    	</div>
	   		 </div>
			
			<div class="clear"></div>
			<div class="lf"><img src="~sfConfig::get("app_img_url")`/success/images/sr_bottom_left.gif"></div>
			<div class="lf sr_bottom_bg" style="width:311px;"></div>
			<div class="lf"><img src="~sfConfig::get("app_img_url")`/success/images/sr_bottom_right.gif"></div>
	</div>
		~else`
	
			<div class="rf" style="margin-top:10px; width:350px;">
		    	<div class="lf">
					<img src="~sfConfig::get("app_img_url")`/success/images/sr_top_left.gif">
		    	</div>
	
	    	    <div class="lf sr_top_bg red b" style="width:311px;" id="~$a[$k]`">
		    		<div class="lf" style="_padding:3px 0;">
						<input type="checkbox" align="absbottom" style="border:none;display:none;">~$nostory.USERNAME` 
					</div>
	    	    </div>
		
	    	    <div class="lf"><img src="~sfConfig::get("app_img_url")`/success/images/sr_top_right.gif"></div>
	            	<div class="clear"></div>
	           		 <div class="lf orange_border_small" id="~$boxheight[$k]`" style="_margin-top:-3px;">
	
		    			<div class="lf" style="margin-right:10px;" oncontextmenu="return false;" >
		    				~if $nostory.PHOTO_P`
								<a onMouseover="showtrail2('~$nostory.PHOTO_CHECKSUM`','~$nostory.USERNAME`',event,'~PictureFunctions::getCloudOrApplicationCompleteUrl($nostory.PHOTO_P)`')" onMouseout="hidetrail2()"><div style=" float:left;margin:0 3px 3px 0;background-image:url(~PictureFunctions::getCloudOrApplicationCompleteUrl($nostory.PHOTO_T)`)" align='left'><img src="~sfConfig::get("app_img_url")`/profile/images/transparent_img.gif" width="60" height="60" border="0" ></div></a>
							~else`
								<div style="float:left;margin:0 3px 3px 0;background-image:url(~sfConfig::get("app_img_url")`/profile/images/nophotoimage.gif)" align='left'><img src="~sfConfig::get("app_img_url")`/profile/images/transparent_img.gif" width="60" height="60" border="0" ></div>
							~/if`
		 	  			 </div>
	
		    			<div class="lf b gray" id="~$b[$k]`" style="width:210px">
							<a class="gray">~$nostory.INFO`
							</a>
		    			</div>
	    		</div>
	
		    	<div class="clear"></div>
		    	<div class="lf"><img src="~sfConfig::get("app_img_url")`/success/images/sr_bottom_left.gif"></div>
		    	<div class="lf sr_bottom_bg" style="width:311px;"></div>
		    	<div class="lf"><img src="~sfConfig::get("app_img_url")`/success/images/sr_bottom_right.gif"></div>
	
		</div>

		<!-- End -->
		~/if`
	

    <!-- End of Right Side -->

~/if`
~/foreach`
</div>
<div id="show_big_image" style="position:absolute;" style="display:none;z-index:10000">
&nbsp;
</div>
<!--ends-->
<script>
		// JavaScript for the on Mouseover Opening of Photo

		var show_big_image=document.getElementById("show_big_image");
		var common_HTML = '<div id=start style="padding: 5px; background-color: #FFF; border: 1px solid #888; font: normal 11px verdana,arial"> <div align="center" style="padding: 2px 2px 2px 2px;">';

		var newHTML="";
function showtrail2(photochecksum,username,e,url)		//Symfony Photo Modification
{
			if(photochecksum)
			{
				var mousex=0;
		        	var mousey=0;
				if(!e)
					var e = window.event;
		        	mousex=e.clientX //to get client window X axis
					virtual_top=e.clientY
			        mousey=e.clientY+document.documentElement.scrollTop//to get client window Y axis
			
				if (virtual_top>220)
				{
					top_pos=mousey-220;
					left_position=mousex+20
				}
				else
				{
					top_pos=mousey+30
					left_position=mousex+20
				}
				top_position=top_pos+"px";
				left_position=left_position+"px";

						if(username!='')
                					var title="&nbsp; Photo of "+username;
				imagename=url;				//Symfony Photo Modification
				if(newHTML=="")
				{
				
					newHTML = common_HTML + '<img src="' + imagename + '" border="0"></div>';
	
					if(username!='')
        	        			newHTML = newHTML + title ;
	
					newHTML = newHTML + '</div>';
				}
				
				show_big_image.innerHTML=newHTML;
				show_big_image.style.display='inline';
				show_big_image.style.top=top_position;
				show_big_image.style.left=left_position;
			}
		}
		
		function hidetrail2()
		{
			newHTML="";
			show_big_image.innerHTML='';
			show_big_image.style.display='none';
		}
		successOnLoad();
</script>