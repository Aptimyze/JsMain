<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html"/>
<xsl:template match="/topSearchBand">

<xsl:variable name="selectedGender" select="normalize-space(selectedGender)" />
<xsl:variable name="selectedLage" select="normalize-space(selectedLage)" />
<xsl:variable name="selectedHage" select="normalize-space(selectedHage)" />
<xsl:variable name="selectedMstatus" select="normalize-space(selectedMstatus)" />
<xsl:variable name="selectedCaste" select="normalize-space(selectedCaste)" />
<xsl:variable name="selectedReligion" select="normalize-space(selectedReligion)" />
<xsl:variable name="selectedMtongue" select="normalize-space(selectedMtongue)" />
<xsl:variable name="selectedCity_Country" select="normalize-space(selectedCity_Country)" />
<xsl:variable name="selectedHavePhoto" select="normalize-space(selectedHavePhoto)" />
<xsl:variable name="bigBand" select="normalize-space(bigBand)" />

<xsl:variable name="genderWidth">
	<xsl:choose><xsl:when test="$bigBand = 'Y'"><xsl:value-of select="'w_153'" /></xsl:when><xsl:otherwise><xsl:value-of select="'w_133'" /></xsl:otherwise></xsl:choose>
</xsl:variable>

<xsl:variable name="ageWidth">
	<xsl:choose><xsl:when test="$bigBand = 'Y'"><xsl:value-of select="'w_69'" /></xsl:when><xsl:otherwise><xsl:value-of select="'w_59'" /></xsl:otherwise></xsl:choose>
</xsl:variable>

<xsl:variable name="mtongueWidth">
	<xsl:choose><xsl:when test="$bigBand = 'Y'"><xsl:value-of select="'w_167'" /></xsl:when><xsl:otherwise><xsl:value-of select="'w_150'" /></xsl:otherwise></xsl:choose>
</xsl:variable>

<xsl:variable name="lastDivWidth">
	<xsl:choose><xsl:when test="$bigBand = 'Y'"><xsl:value-of select="'width:200px;'" /></xsl:when><xsl:otherwise><xsl:value-of select="'width:180px;'" /></xsl:otherwise></xsl:choose>
</xsl:variable>

<xsl:variable name="sprteClass">
	<xsl:choose><xsl:when test="$bigBand = 'Y'"><xsl:value-of select="'sprte'" /></xsl:when><xsl:otherwise><xsl:value-of select="''" /></xsl:otherwise></xsl:choose>
</xsl:variable>

<xsl:for-each select="religion_caste/data">
	<xsl:variable name="religionValue" select="normalize-space(religionValue)" />
	<xsl:variable name="casteString" select="normalize-space(casteString)" />
	<input type = "hidden" id = "{concat('religion',$religionValue)}" value = "{$casteString}" />
</xsl:for-each>

		<form name="search_partner" style="margin:0px;padding:0px" action="/search/quick" method="post" onsubmit="return checkData();">	

			<div class="{concat('q_search ',$sprteClass,' m_t_b_10')}">
				<div class="{concat('l_search ',$sprteClass,' fl')}"></div>
				<div style="float:left; padding:4px 0 4px 15px;">

					<div class="fl mt_10">
                                                <select name="gender" class="{concat($genderWidth,' mt_4')}">
							<xsl:for-each select="gender/data">
								<xsl:variable name="value" select="normalize-space(value)" />
								<xsl:choose>
                                                                <xsl:when test="$selectedGender = $value">
                							<option value = "{$value}" selected = "yes"><xsl:value-of select="label" /></option>
								</xsl:when>
                                                                <xsl:otherwise>
                							<option value = "{$value}"><xsl:value-of select="label" /></option>
								</xsl:otherwise>
                                                                </xsl:choose>
        						</xsl:for-each>
                                                </select>
						<br />	
	                			<select class="{concat($ageWidth,' mt_4')}" id="lage" name = "lage">
							<xsl:for-each select="age/value">
								<xsl:variable name="value" select="normalize-space(.)" />
								<xsl:choose>
                                				<xsl:when test="$selectedLage = $value">
                							<option value = "{$value}" selected = "yes"><xsl:value-of select="." /> Yrs</option>
								</xsl:when>
                                				<xsl:otherwise>
                							<option value = "{$value}"><xsl:value-of select="." /> Yrs</option>
								</xsl:otherwise>
                                				</xsl:choose>
        						</xsl:for-each>
	                			</select> to
	                                        <select class="{concat($ageWidth,' mt_4')}" id="hage" name = "hage">
							<xsl:for-each select="age/value">
								<xsl:variable name="value" select="normalize-space(.)" />
								<xsl:choose>
                                				<xsl:when test="$selectedHage = $value">
                							<option value = "{$value}" selected = "yes"><xsl:value-of select="." /> Yrs</option>
								</xsl:when>
                                				<xsl:otherwise>
                							<option value = "{$value}"><xsl:value-of select="." /> Yrs</option>
								</xsl:otherwise>
                                				</xsl:choose>
        						</xsl:for-each>
	                                        </select>
					</div>		
					<div class="fl mt_10 ml_17">
	                                        <select class="w_167 mt_4" name = "religion" id="religion" onchange="populateCasteFromReligion();">
							<xsl:for-each select="religion/data">
                                                                <xsl:variable name="value" select="normalize-space(value)" />
                                                                <xsl:choose>
                                                                <xsl:when test="$selectedReligion = $value">
                                                                        <option value = "{$value}" selected = "yes"><xsl:value-of select="label" /></option>
                                                                </xsl:when>
                                                                <xsl:otherwise>
                                                                        <option value = "{$value}"><xsl:value-of select="label" /></option>
                                                                </xsl:otherwise>
                                                                </xsl:choose>
                                                        </xsl:for-each>
	                                        </select>
						<br />	
                				<select name="mstatus" class="w_167 mt_4">
							<xsl:for-each select="mstatus/data">
								<xsl:variable name="value" select="normalize-space(value)" />
                                                                <xsl:choose>
                                                                <xsl:when test="$selectedMstatus = $value">
                                                                        <option value = "{$value}" selected = "yes"><xsl:value-of select="label" /></option>
                                                                </xsl:when>
                                                                <xsl:otherwise>
                                                                        <option value = "{$value}"><xsl:value-of select="label" /></option>
                                                                </xsl:otherwise>
                                                                </xsl:choose>
							</xsl:for-each>
                				</select>
					</div>				
					<div class="fl mt_10 ml_17">
	                                        <select class="{concat($mtongueWidth,' mt_4')}" name="mtongue">
							<xsl:for-each select="mtongue/data">
                                                                <xsl:variable name="value" select="normalize-space(value)" />
                                                                <xsl:variable name="isRegion" select="normalize-space(isRegion)" />
                                                                <xsl:choose>
                                                                <xsl:when test="$selectedMtongue = $value">
									<xsl:choose>
                                                                        <xsl:when test="$isRegion = 'Y'">
                                                                                <option value = "" disabled = "yes"></option>
                                                                                <option value = "{$value}" selected = "yes" style="font-weight: bold;color:#E06400"><xsl:value-of select="label" /></option>
                                                                        </xsl:when>
									<xsl:otherwise>
                                                                        	<option value = "{$value}" selected = "yes"><xsl:value-of select="label" /></option>
									</xsl:otherwise>
                                                                	</xsl:choose>
                                                                </xsl:when>
                                                                <xsl:otherwise>
									<xsl:choose>
                                                                        <xsl:when test="$isRegion = 'Y'">
                                                                                <option value = "" disabled = "yes"></option>
                                                                                <option value = "{$value}" style="font-weight: bold;color:#E06400"><xsl:value-of select="label" /></option>
                                                                        </xsl:when>
                                                                        <xsl:otherwise>
                                                                                <option value = "{$value}"><xsl:value-of select="label" /></option>
                                                                        </xsl:otherwise>
                                                                        </xsl:choose>
                                                                </xsl:otherwise>
                                                                </xsl:choose>
                                                        </xsl:for-each>
	                                        </select>
						<br />
						<select class="{concat($mtongueWidth,' mt_4')}" name="location">
							<xsl:for-each select="location/data">
                                                                <xsl:variable name="value" select="normalize-space(value)" />
                                                                <xsl:choose>
                                                                <xsl:when test="$selectedCity_Country = $value">
									<xsl:choose>
                                                                        <xsl:when test="$value = 22">
                                                                        	<option value = "{$value}" selected = "yes"><xsl:value-of select="label" /></option>
                                                                                <option disabled = "yes" style = "padding-left:25px; background-color:#CCCCCC; color:#000;">Major Indian Cities</option>
									</xsl:when>
									 <xsl:when test="$value = 'AN' ">
                                                                                <option disabled = "yes" style = "padding-left:25px; background-color:#CCCCCC; color:#000;">Indian States</option>
                                                                                <option value = "{$value}" selected = "yes"><xsl:value-of select="label" /></option>                                                           
                                                                        </xsl:when>
                                                                        <xsl:when test="$value = 'WB'">
                                                                                <option value = "{$value}" selected = "yes"><xsl:value-of select="label" /></option>
                                                                                <option disabled = "yes" style = "padding-left:25px; background-color:#CCCCCC; color:#000;">All Indian Cities</option>
                                                                        </xsl:when>
                                                                        <xsl:otherwise>
                                                                        	<option value = "{$value}" selected = "yes"><xsl:value-of select="label" /></option>
									</xsl:otherwise>
                                                                        </xsl:choose>
                                                                </xsl:when>
                                                                <xsl:otherwise>
									<xsl:choose>
                                                                        <xsl:when test="$value = 22">
                                                                        	<option value = "{$value}"><xsl:value-of select="label" /></option>
                                                                                <option disabled = "yes" style = "padding-left:25px; background-color:#CCCCCC; color:#000;">Major Indian Cities</option>
									</xsl:when>
									<xsl:when test="$value = 'AN'">
                                                                                <option disabled = "yes" style = "padding-left:25px; background-color:#CCCCCC; color:#000;">Indian States</option>
                                                                                <option value = "{$value}"><xsl:value-of select="label" /></option>
                                                                        </xsl:when>
									<xsl:when test="$value = 'WB'">
                                                                                <option value = "{$value}"><xsl:value-of select="label" /></option>
                                                                                <option disabled = "yes" style = "padding-left:25px; background-color:#CCCCCC; color:#000;">All Indian Cities</option>
                                                                        </xsl:when>
                                                                        <xsl:otherwise>
                                                                        	<option value = "{$value}"><xsl:value-of select="label" /></option>
									</xsl:otherwise>
                                                                        </xsl:choose>
                                                                </xsl:otherwise>
                                                                </xsl:choose>
                                                        </xsl:for-each>
						</select>
                                        </div>
					<div class="fl mt_10 ml_17" style="{$lastDivWidth}">
						<select class="w_167 mt_4" id="caste" name = "caste">
							<xsl:for-each select="caste/data">
                                                                <xsl:variable name="value" select="normalize-space(value)" />
                                                                <xsl:variable name="isAll" select="normalize-space(isAll)" />
                                                                <xsl:variable name="isGroup" select="normalize-space(isGroup)" />
                                                                <xsl:variable name="isChild" select="normalize-space(isChild)" />
                                                                <xsl:choose>
                                                                <xsl:when test="$selectedCaste = $value">
                                                                	<xsl:choose>
									<xsl:when test="$isAll = 'Y'">
                                                                        	<option value = "" disabled = "yes"></option>
                                                                        	<option value = "{$value}" selected = "yes" style = "background-color:#FFD84F"><xsl:value-of select="label" /></option>
									</xsl:when>
                                                                	<xsl:otherwise>
										<xsl:choose>
                                                                        	<xsl:when test="$isGroup = 'Y'">
                                                                        		<option value = "{$value}" selected = "yes" style = "color:#E06400"><xsl:value-of select="label" /> - All</option>
                                                                        	</xsl:when>
                                                                        	<xsl:otherwise>
											<xsl:choose>
                                                                                	<xsl:when test="$isChild = 'Y'">
                                                                                        	<option value = "{$value}" selected = "yes" style = "padding-left:25px;">- <xsl:value-of select="label" /></option>
                                                                                	</xsl:when>
											<xsl:otherwise>
                                                                                		<option value = "{$value}" selected = "yes"><xsl:value-of select="label" /></option>
											</xsl:otherwise>
                                                                                	</xsl:choose>
                                                                        	</xsl:otherwise>
                                                                        	</xsl:choose>
									</xsl:otherwise>
                                                                	</xsl:choose>
                                                                </xsl:when>
                                                                <xsl:otherwise>
									<xsl:choose>
                                                                        <xsl:when test="$isAll = 'Y'">
                                                                        	<option value = "" disabled = "yes"></option>
                                                                                <option value = "{$value}" style = "background-color:#FFD84F"><xsl:value-of select="label" /></option>
                                                                        </xsl:when>
                                                                        <xsl:otherwise>
										<xsl:choose>
                                                                        	<xsl:when test="$isGroup = 'Y'">
                                                                        		<option value = "{$value}" style = "color:#E06400"><xsl:value-of select="label" /> - All</option>
                                                                        	</xsl:when>
                                                                        	<xsl:otherwise>
											<xsl:choose>
                                                                                	<xsl:when test="$isChild = 'Y'">
                                                                                        	<option value = "{$value}" style = "padding-left:25px;">- <xsl:value-of select="label" /></option>
                                                                                	</xsl:when>
                                                                                	<xsl:otherwise>
                                                                                		<option value = "{$value}"><xsl:value-of select="label" /></option>
											</xsl:otherwise>
                                                                                	</xsl:choose>
                                                                        	</xsl:otherwise>
                                                                        	</xsl:choose>
                                                                        </xsl:otherwise>
                                                                        </xsl:choose>
                                                                </xsl:otherwise>
                                                                </xsl:choose>
                                                        </xsl:for-each>
						</select>

						<p class="clr" style="margin-bottom:5px;"></p>
                				<div class="fl" style="width:106px; margin-top:4px;">
							<xsl:choose>
                                                      	<xsl:when test="$selectedHavePhoto = 'Y'">
								<input type="checkbox" style="border:0; width:15px; height:15px; vertical-align:top; margin:0;" name="Photos" value="Y" checked="yes" />With photos only
							</xsl:when>
                                                    	<xsl:otherwise>
								<input type="checkbox" style="border:0; width:15px; height:15px; vertical-align:top; margin:0;" name="Photos" value="Y" />With photos only
							</xsl:otherwise>
                                                      	</xsl:choose>
                				</div>
							<xsl:choose>
                                        		<xsl:when test="$bigBand = 'N'">
								<input type="submit" id="searchButton" name="Search" value="" class="{concat('s_btn ',$sprteClass,' fl')}" style="cursor:pointer;border:0pt none;" />
							</xsl:when>
							</xsl:choose>
					</div>
					<xsl:choose>
					<xsl:when test="$bigBand = 'Y'">
						<div class="fl mt_4"> <br />
							<input type="submit" id="searchButton" name="Search" value="" class="{concat('s_btn ',$sprteClass,' fl')}" style="cursor:pointer;border:0pt none;" />
						</div>
					</xsl:when>
					</xsl:choose>
					</div>
					<div class="{concat('r_search ',$sprteClass,' fr')}"></div>
			</div>

			<input type="hidden" name="CLICKTIME"  value="1" />
			<input type="hidden" name="TOP_BAND_SEARCH" value="Y" />
		</form>
</xsl:template>
</xsl:stylesheet>
