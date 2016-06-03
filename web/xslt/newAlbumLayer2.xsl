<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">


<xsl:variable name="imageUrl" select="normalize-space(userData/imageUrl)" />
<xsl:variable name="noAlbumToDisplay" select="normalize-space(userData/noAlbumToDisplay)" />
<xsl:variable name="profileDoesntExist" select="normalize-space(userData/profileDoesntExist)" />
<xsl:variable name="noOfPics" select="normalize-space(userData/noOfPics)" />

<div id="allUrls" style="display:none;">
	<xsl:for-each select="userData/albumInfo">
      		<xsl:value-of select="url"/>###
	</xsl:for-each>
</div>
<div id="allTitles" style="display:none;">
	<xsl:for-each select="userData/albumInfo">
      		<xsl:value-of select="title"/>###
	</xsl:for-each>
</div>
<div id="allKeywords" style="display:none;">
	<xsl:for-each select="userData/albumInfo">
      		<xsl:value-of select="keywords"/>###
	</xsl:for-each>
</div>

<input type="hidden" id="totalPhotos" value="{$noOfPics}" />

<div class="overlay_wrapper_775px" style="background-color:white;" >

<!-- TITLE OF POPUP -->
        
	<div class="top">     	
		<div class="text white b widthauto fl">
			Photo album - <xsl:value-of select="userData/username"/>
		</div>
		<div class="fr div_close_button_green" onclick="$.colorbox.close();" style="cursor:pointer;">
			&#xa0;
		</div>
	</div>

<!-- End -->

<!-- CONTENT -->
	<div class="sp15">
	</div>

<xsl:choose>
        <xsl:when test="$noAlbumToDisplay = 1">
		<div style="width:775px; margin: 0 7%;height:372px;font-size:25px;text-align:center;">
		Album visible if contact accepted
		</div>
        </xsl:when>
        <xsl:when test="$profileDoesntExist = 1">
             <div style="width:775px; margin: 0 7%;height:372px;font-size:25px;text-align:center;">
                     Sorry, the profile you requested was not found
             </div>
        </xsl:when>
        <xsl:otherwise>
	<div class="mid" id="albumDiv" >
		<div style="width:775px; margin:auto;position:relative;" >
			<div>
				<xsl:choose>
        			<xsl:when test="$noOfPics > 1">
				<div style="position:relative;left:10px;font-size:16px;" >
					<span class="fl" style="margin: 10px 0 0 10px;" >
						Photo&#xa0;&#xa0;
					</span>
					<span class="fl " style="margin: 10px 0 0 0px;text-align: center;" id="currentPhotoNo" >
						1
					</span>
					<span style=" margin: 10px 0 0 0;text-align: center;" class = "fl mar_left_10" >
						of
					</span>
					<span style="margin: 10px 0 0 0px;text-align: center;" class="fl mar_left_10">
						<xsl:value-of select="userData/noOfPics"/>&#xa0;&#xa0;
					</span>
				</div>
				</xsl:when>
				<xsl:otherwise>
				<span class=" fl " style="clear: both;	margin: 28px 0 0 0;" >
				</span>
				</xsl:otherwise>
      				</xsl:choose>
         
				<div id="fadeshow2toggler" >
					<a class="prev">
						<span class="fl" style=" margin-top:193px">
						<xsl:choose>
                                		<xsl:when test="$noOfPics > 1">
							<div id="albumPrevious1" style="cursor:default;background-color:#e1e1e1;border-radius:7px 0px 0px 7px;padding:0px 7px;margin-left:-50px;" class = "leftArrow1" onclick="previousClick();" onmouseover="previousClickMouseOver();" onmouseout="previousClickMouseOut();"></div>
							<div id="albumPrevious2" style="cursor:pointer;background-color:#e1e1e1;border-radius:7px 0px 0px 7px;padding:0px 7px;margin-left:-50px; display:none;" class = "leftArrow2" onclick="previousClick();" onmouseover="previousClickMouseOver();" onmouseout="previousClickMouseOut();"></div>
							<div id="albumPrevious3" style="cursor:pointer;background-color:#e1e1e1;border-radius:7px 0px 0px 7px;padding:0px 7px;margin-left:-50px; display:none;" class = "leftArrow3" onclick="previousClick();" onmouseover="previousClickMouseOver();" onmouseout="previousClickMouseOut();"></div>
						</xsl:when>
                                		<xsl:otherwise>
							<div id="albumPrevious1" style="cursor:default;visibility:hidden;background-color:#e1e1e1;border-radius:7px 0px 0px 7px;padding:0px 7px;margin-left:-50px;width:173px;" class = "leftArrow1"></div>
						</xsl:otherwise>
                                		</xsl:choose>
						</span>
					</a> 
					<div id="fadeshow2" style="position: relative; height: 500px; padding: 0px; float: left; width: 514px; background: none repeat scroll 0% 0%;margin:0px 0px;">

						<div style="position: relative;  height: 100%; background: #f7f7f7 repeat scroll 0% 0% ; z-index: 1000; opacity: 1;height: 500px; width: 514px; overflow-y: auto;overflow-x:hidden;text-align:center;vertical-align:middle;border:1px solid #000;">
							<table width="514" height="450" >
								<tbody>
									<tr>
										<td valign="center">
											<center>
												<img id="imgLoader" style="display:inline;" src="{concat($imageUrl,'/images/loader_big.gif')}" />
												<img id="centerAlbumPic" style="display:none;" src="" onload="hideLoader();" />
											</center>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div id="overlapLoader" class="gallerylayer" style="position: absolute; left: 0px; top: 5px;  height: 100%;z-index: 2000;height: 450px; width: 502px;">
						</div>
					</div>

					<div id="titleAndTagsLayer" class="fadeslidedescdiv" style="position: absolute; left: 0px; top: 358px; font: 12px Arial; z-index: 1001; height: 37px;visibility:hidden;">
						<div id="layer1" class="descpanelbg" style="position: relative; top: 0px; padding: 10px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 1px solid rgb(230, 219, 220); height: 100%; opacity: 0.3;margin-left:144px;width: 489px; margin-top: 82px; left: -2px;filter: alpha(opacity = 30);">
						</div>
						<div id="layer2" style="position: absolute; left: 15px; top: 0px; margin-top:89px;margin-left:137px;">
							<span id="titleSpan" style="display:none;" >
								<br />
								<span >
									<strong>
										Title&#xa0;: &#xa0;-&#xa0; 
									</strong>
								</span> 
								<span id="centerAlbumTitle">
								</span>
							</span>
							<span id="tagsSpan" style="display:none;" >
								<br />
								<span>
									<strong>
										Tags&#xa0;: &#xa0;-&#xa0; 
									</strong>
								</span>
								<span id="centerAlbumTag">
								</span>
							</span>
						</div>
					</div>
					<xsl:if test="$noOfPics > 1">
					<a class="next">
						<span class="fl" style=" margin-top:193px">
							<div id="albumNext2" style="cursor:pointer;background-color:#e1e1e1;border-radius:0px 7px 7px 0px;padding:0px 7px;" class="rightArrow2" onclick="nextClick();" onmouseover="nextClickMouseOver();" onmouseout="nextClickMouseOut();"></div>
							<div id="albumNext1" style="cursor:default;background-color:#e1e1e1;border-radius:0px 7px 7px 0px;padding:0px 7px; display:none;" class="rightArrow1" onclick="nextClick();" onmouseover="nextClickMouseOver();" onmouseout="nextClickMouseOut();"></div>
							<div id="albumNext3" style="cursor:pointer;background-color:#e1e1e1;border-radius:0px 7px 7px 0px;padding:0px 7px; display:none;" class="rightArrow3" onclick="nextClick();" onmouseover="nextClickMouseOver();" onmouseout="nextClickMouseOut();"></div>
						</span>
					</a>
					</xsl:if>

				</div>
			</div>

			 <div class="sp5">
			</div>

			 <div class="sp5">
			</div>

			 <div class="sp5">
			</div>

			<div>
			</div>
		</div>
        </div>
	</xsl:otherwise>
      </xsl:choose>
</div>
</xsl:template>
</xsl:stylesheet>
