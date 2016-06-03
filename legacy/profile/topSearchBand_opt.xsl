<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html"/>
<xsl:template match="/registrationPage1">
		<form name="search_partner" style="margin:0px;padding:0px" action="/search/perform" method="post" onsubmit="return checkData();">	
		<xsl:variable name="doesnt_mater_arr_0" select="checkParams/doesnt_mater_arr_0" />
		<xsl:variable name="doesnt_mater_arr_1" select="checkParams/doesnt_mater_arr_1" />
		<xsl:variable name="doesnt_mater_arr_3" select="checkParams/doesnt_mater_arr_3" />
		<xsl:variable name="doesnt_mater_arr_2" select="checkParams/doesnt_mater_arr_2" />
		<xsl:variable name="list_all_caste_labels" select="checkParams/list_all_caste_labels" />
		<xsl:variable name="selectedRel" select="checkParams/selectedRel" />

		<xsl:variable name="SITE_URL" select="checkParams/SITE_URL" />
		<xsl:variable name="IMG_URL" select="checkParams/IMG_URL" />
		<xsl:variable name="Gender" select="checkParams/Gender" />
		<xsl:variable name="hp_mstatus" select="checkParams/hp_mstatus" />
		<xsl:variable name="selectedlage" select="checkParams/selectedlage" />
		<xsl:variable name="selectedhage" select="checkParams/selectedhage" />
		<xsl:variable name="CHECKSUM" select="checkParams/CHECKSUM" />
		<xsl:variable name="STYPE" select="checkParams/STYPE" />
		<xsl:variable name="E_CLASS" select="checkParams/E_CLASS" />
		<xsl:variable name="mtongueVal" select="checkParams/mtongueVal" />
		<xsl:variable name="SEARCHID" select="checkParams/SEARCHID" />
		<!--  SOURCE check param for sulekha -->
		<xsl:variable name="SOURCE" select="checkParams/SOURCE" />
		<!--  Ends  -->
		<xsl:variable name="Photos" select="checkParams/Photos" />
		<xsl:variable name="casteVal" select="checkParams/casteVal" />

			<div class="q_search">
				<div class="l_search fl"></div>
				<div style="float:left; padding:4px 0 4px 10px;">

					<div class="fl mt_10 mt_10">	
                                                <select name="Gender" class="w_133" onchange="set_default_age_range(lage,hage);">
                                                        <option value="F" >
                                                                <xsl:value-of select="Gender/selbride" />
                                                        </option>
                                                        <xsl:choose>
                                                                <xsl:when test="$Gender ='M' ">
                                                                        <option value="M" selected="yes">
                                                                                <xsl:value-of select="Gender/selgroom" />
                                                                        </option>
                                                                </xsl:when>
                                                                <xsl:otherwise>
                                                                        <option value="M" >
                                                                                <xsl:value-of select="Gender/selgroom" />
                                                                        </option>
                                                                </xsl:otherwise>
                                                        </xsl:choose>
                                                </select>
						<br />	
	                			<select class="w_59 mt_4" name="lage" id="lageId">
							<xsl:for-each select="populate/ageshow">
								<xsl:variable name="lage_var" select="." />	
								<xsl:choose>
									<xsl:when test="$lage_var = $selectedlage">	
										<option value="{$lage_var}" selected="yes">
											<xsl:value-of select="." /> Yrs
										</option>
									</xsl:when>
									<xsl:otherwise>
                                                                                <option value="{$lage_var}" >
                                                                                        <xsl:value-of select="." /> Yrs
                                                                                </option>
									</xsl:otherwise>
								</xsl:choose>
							</xsl:for-each>
	                			</select> to 
	                                        <select class="w_59 mt_4" name="hage">
	                                                <xsl:for-each select="populate/ageshow">
								<xsl:variable name="hage_var" select="." />	
                                                                <xsl:choose>
                                                                        <xsl:when test="$hage_var = $selectedhage">
                                                                                <option value="{$hage_var}" selected="yes">
                                                                                        <xsl:value-of select="." /> Yrs
                                                                                </option>
                                                                        </xsl:when>
                                                                        <xsl:otherwise>
                                                                                <option value="{$hage_var}">
                                                                                        <xsl:value-of select="." /> Yrs
                                                                                </option>
                                                                        </xsl:otherwise>
                                                                </xsl:choose>
	                                                </xsl:for-each>
	                                        </select>
					</div>		
					<div class="fl mt_10 ml_17">
                                                <xsl:variable name="religion_str" select="religion_label/religion_string" />
                                                <input type="hidden" id="religion" value="{$religion_str}" />
                                                <span id="religionID"></span>

						<br />	
                				<select name="hp_mstatus" onChange="disableMstatus();" class="w_167 mt_4">
                        				<option id="mstatusDisable" value="" selected="yes">
								<xsl:value-of select="martialStatus/selectMartial" />
							</option>
							<xsl:choose>
								<xsl:when test="$doesnt_mater_arr_2 !='' ">
                        						<option value="DONT_MATTER" selected="yes">
										<xsl:value-of select="martialStatus/selectAll" />
									</option>    
								</xsl:when>
								<xsl:otherwise>
                                                                        <option value="DONT_MATTER" >
                                                                                <xsl:value-of select="martialStatus/selectAll" />
                                                                        </option>
								</xsl:otherwise>	
							</xsl:choose>			
							<xsl:choose>
								<xsl:when test="$hp_mstatus='N'">
	                						<option value="N" selected="yes">
										<xsl:value-of select="martialStatus/selectMarried" />
									</option>
								</xsl:when>
								<xsl:otherwise>
                                                                        <option value="N" >
                                                                                <xsl:value-of select="martialStatus/selectMarried" />
                                                                        </option>
								</xsl:otherwise>
							</xsl:choose>
							<xsl:choose>
								<xsl:when test="($hp_mstatus='D,W,S') or ($hp_mstatus='E')">
                                                                        <option value="E" selected="yes">
                                                                                <xsl:value-of select="martialStatus/selectMarrEarlier" />                                                            </option>
								</xsl:when>	
								<xsl:otherwise>
	                        					<option value="E" >
										<xsl:value-of select="martialStatus/selectMarrEarlier" />	       						     </option>
								</xsl:otherwise>
							</xsl:choose>
                				</select>
					</div>				
					<div class="fl mt_10 ml_17">
						<xsl:variable name="mtongue_str" select="mtongue_label/mtongue_string" />
						<input type="hidden" id="mtongue_caste" value="{$mtongue_str}" />
						 <span id="mtongue_casteID"></span>		
						<br />

						<xsl:variable name="country_str" select="country_label/country_string" />
						<input type="hidden" id="country" value="{$country_str}" />	
						<span id="countryID"></span>	
                                                
                                        </div>
					<div class="fl mt_10 ml_17" style="width:180px;">
                				<xsl:variable name="caste_str" select="caste_label/caste_string" />
                                                <input type="hidden" id="allcaste" value="{$caste_str}" />
                                                <span id='logiccasteId'>
                                                </span>

						<p class="clr" style="margin-bottom:5px;"></p>
                				<div class="fl" style="width:106px; margin-top:4px;">
							<xsl:choose>
								<xsl:when test="$Photos ='Y'">
									<input type="checkbox" style="border:0; width:15px; height:15px; vertical-align:top; margin:0;" name="Photos" value="Y" checked="yes" />With photos only
								</xsl:when>
								<xsl:otherwise>
									<input type="checkbox" style="border:0; width:15px; height:15px; vertical-align:top; margin:0;" name="Photos" value="Y" />With photos only
								</xsl:otherwise>
							</xsl:choose>
                				</div>
						<input type="submit" id="searchButton" name="Search" value="" class="s_btn fl" style="border:0;" />
                				
						<input type="hidden" name="Search" value="" />
					</div>
					</div>
					<div class="r_search fr"></div>
			</div>
		<input type="hidden" name="casteVal" value="{$casteVal}" />
		<input type="hidden" name="religionVal" value="{$selectedRel}" />
		<input type="hidden" name="checksum" value="{$CHECKSUM}" />
		<!--<input type="hidden" name="SEARCHID" value="{$SEARCHID}" />-->
		<input type="hidden" name="CLICKTIME"  value="1" />
		<input type="hidden" name="STYPE"  value="{$STYPE}" />
		<input type="hidden" name="E_CLASS"  value="{$E_CLASS}" />
		<input type="hidden" name="TOP_BAND_SEARCH" value="Y" />
		<input type="hidden" name="mtongueVal" value='{$mtongueVal}' />
	
		<input type="hidden" name="doesnt_mater_arr_0"  value="{$doesnt_mater_arr_0}" />
		<input type="hidden" name="doesnt_mater_arr_1"  value="{$doesnt_mater_arr_1}" />
		<input type="hidden" name="doesnt_mater_arr_3"  value="{$doesnt_mater_arr_3}" />
		<input type="hidden" name="selectedRel"  value="{$selectedRel}" />
		<input type="hidden" name="list_all_caste_labels"  value="{$list_all_caste_labels}" />

		</form>	

</xsl:template>
</xsl:stylesheet>
