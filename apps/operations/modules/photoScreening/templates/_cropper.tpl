    <div id="clickHolderCE" onclick="javascript:updateClickHolderCE(false,event)" style="height:0px;width:0px">&nbsp;</div>
    <div id="clickHolder" onclick="javascript:updateClickHolder(false,event)" style="height:0px;width:0px" >&nbsp;</div><div id="commonOverlay" class="jspcOverlay js-overlay overlayZ " style="display:block;"></div>
<!--start:profile photo select from facebook-->
<div class="pos_fix fullwid layersZ fontlig js-cropper js-aboveLayer" style="top:1%;display:block;">
	<div class="bg-white mauto puwid5">
	    	<!--start:title-->
        ~if $search neq 1`
                <table width="600" border="1" cellspacing="0" cellpadding='3' ALIGN="CENTER" >
                    <tr class=label align=center>
                        <td width=10%>&nbsp;Gender</td>
                        <td width=10%>&nbsp;Age</td>
                        <td width=10%>&nbsp;Country</td>
                        <td width=10%>&nbsp;City</td>
                        <td width=10%>&nbsp;Marital Status</td>
                        <td width=10%>&nbsp;Ethinicty (State of Origin)</td>
                        <td width=10%>&nbsp;Religion</td>
                        <td width=10%>&nbsp;Caste</td>
                        <td width=10%>&nbsp;Country of Birth</td>
                        <td width=10%>&nbsp;City of Birth</td>
                    </tr>

                    <tr class=fieldsnew align=center ~if $profileData['USERPAID'] eq '1'` style="background:#00FF00;"~/if`>
                        <td>&nbsp;~$profileData['GENDER']`</td>
                        <td>&nbsp;~$profileData['AGE']`</td>
                        <td>&nbsp;~$profileData['COUNTRY_RES']`</td>
                        <td>&nbsp;~if $profileData['CITY_RES'] neq ''` ~$profileData['CITY_RES']` ~else` Outside India ~/if`</td>
                        <td>&nbsp;~$profileData['MSTATUS']`</td>
                        <td>&nbsp;~$profileData['MTONGUE']`</td>
                        <td>&nbsp;~$profileData['RELIGION']`</td>
                        <td>&nbsp;~$profileData['CASTE']`</td>
                        <td>&nbsp;~$profileData['COUNTRY_BIRTH']`</td>
                        <td>&nbsp;~if $profileData['CITY_BIRTH'] neq ''` ~$profileData['CITY_BIRTH']` ~else` Not Specified ~/if`</td>
                    </tr>
                </table>
        ~/if`

        	<!--end:title-->

	        <!--start:middle-->
        	<div class="fullwid  pos-rel">
        		<div class="pup10 clearfix">
				<!--start:div-->
				<div class="fl puwid6 txtc" style="margin-left:70px;width:358px;">
					<!--lavesh-->
					~*
					<!--img src="../SRP/images/srch_image1.jpg" style="width:268px; height:400px"/-->
					*`
					<div class="img-container">
						<script>
						var startTime = new Date().getTime();
						function doneLoading() {
						   /* var loadtime = new Date().getTime() - startTime;
						    var src = $("#cropperPic").attr("src");
						    var url = '/operations.php/photoScreening/trackProcessPicUpload?loadtime='+loadtime+'&url='+src;
						    $.ajax(
										{	
											url: url,
									    type: 'GET',
											timeout: 60000,
											success: function(response) 
											{ 	
												
											},
											error: function(xhr) 
											{
												
											}
										})
							*/
								$("#submit1").attr("style","display:block");    
							};
						    
						</script>
						<img src="~$uploadUrl`~$photoArr['profilePic']['MainPicUrl']`" alt="Picture" id="cropperPic" onload="doneLoading();">
					</div>
					<!--lavesh-->
				</div>                
				<!--end:div-->


				<!--start:div-->
				<div class="fl">
					<div class="pubdr8 mt20 mb20 puhgt3"></div>                
				</div>                
				<!--end:div-->
			
				<!--start:div-->
				<div class="fr puwid7">
					<div class="pup11">
						<p class="f15 fontlig color11">Username: ~$profileData['USERNAME']`   <font style="font-size:16px;" class="red"> Gender: ~$profileData['GENDER']`</font></p>
						<!--start:crop pictures-->
						<div class="clearfix pt10">
							<div class="fl">
								<!--lavesh-->
								<!--img  src="../SRP/images/srch_image1.jpg" class="pudim6 vtop"-->
								<div style="width:220px;height:220px;overflow:hidden;" id="imgPreviewLG">
									<div class="img-preview preview-lg" class="pudim6 vtop">
									</div>
								</div>
								<!--lavesh-->

							</div>
							<div class="fl ml20">
								<!--lavesh-->
								~*
								<!--img src="../SRP/images/srch_image1.jpg" class="pudim7 vtop"--> 
								*`
								<div class="img-preview preview-md" class="pudim6 vtop" id="imgPreviewMD">
								</div>
								<!--lavesh-->
							</div>
						</div>
			
						<div class="clearfix pt20">
							<div class="fl">
								<!--lavesh-->
								~*
								<!--img src="../SRP/images/srch_image1.jpg" class="pudim8 vtop"-->
								*`
								<div style="width:87px;height:87px;overflow:hidden;" id="imgPreviewSM">
									<div class="img-preview preview-sm" class="pudim6 vtop">
									</div>
								</div>
								<!--lavesh-->
							</div>

							<div class="fl ml20">
								<!--lavesh-->
								~*
								<!--img src="../SRP/images/srch_image1.jpg" class="pudim9 purad2 vtop"-->
								*`
								<div style="width:117px;height:117px;overflow:hidden;" class="purad2" id="imgPreviewXS">
									<div class="img-preview preview-xs" class="pudim6 purad2 vtop ">
									</div>
								</div>
								<!--lavesh-->
							</div>

							<div class="fl ml20">
								~*
								<!--img src="../SRP/images/srch_image1.jpg" class="pudim10 vtop"--> 
								*`
								<!--lavesh-->
								<div style="width:150px;height:150px;overflow:hidden;" id="imgPreviewSS">
									<div class="img-preview preview-ss" class="pudim6 vtop">
									</div>
								</div>
								<!--lavesh-->
							</div>

						</div>
						<!--end:crop pictures-->
					</div>                
				</div>                
			<!--end:div-->
			</div>
		</div>
		<!--end:middle-->

		<!--start:bottom-->
		<div class="pubdr3 pup8 clearfix fontlig">
			<span class="fl wid190 txtc lh40 cursp pos_rel scrollhid" id="ops-cropperSave">
<input type="submit" id="submit1" name="Save" value="Save" tabIndex="~$tabIndex++`" style = "display:none">
			</span>
			<span class="fl pl30">
				<a href="#" id="js-cropperOpsClose" class="f20">Cancel</a>
			</span>	
		</div>        
	<!--end:bottom-->
	</div>
</div>
