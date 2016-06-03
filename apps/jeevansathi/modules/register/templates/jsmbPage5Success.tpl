
<div style="display:none">
~$errMsg|decodevar`
</div>
<style type="text/css">

.fam_color1{color:#1c7bc5}
.fam_color2{color:#fff}
.fam_txtn{text-decoration:none}
.fam_txtc{text-align:center}
#fam_info{color:#000}
#fam_info .fam_dispib{display:inline-block}
#fam_info .fam_bg1{background-image:url(~sfConfig::get('app_img_url')`/images/mobilejs/revamp_mob/mainsprite.png); background-repeat:no-repeat}
#fam_info .bpos1{background-position:-3px -285px;}
#fam_info .wid40{width:40px}
#fam_info .wid83p{width:83%}
#fam_info .wid37p{width:37%}
#fam_info .wid50p{width:49%}
#fam_info .wid26p{width:26%}
#fam_info .hgt40{height:40px;}
#fam_info .pt5{padding-top:5px}
#fam_info .pt10{padding-top:10px}
#fam_info .pt20{padding-top:20px}
#fam_info .pleft2{padding-left:2px}
#fam_info .pad1{padding:2px}
#fam_info .pad2{padding:22px 0px 0px 0px;}
#fam_info .padBot10{padding:10px 0 10px 0;}
#fam_info .tabact{background-color:#67727B;}
#fam_info .tabnotact{background-color:#B1B1B1;}
#fam_info .lh35{line-height:35px;}
#fam_info .bradleft{border-top-left-radius:0.6em;border-bottom-left-radius:0.6em}
#fam_info .bradright{border-top-right-radius:0.6em;border-bottom-right-radius:0.6em}
#fam_info input[type=text]{width:100%}
#fam_info .fadeOut{color:#B1B1B1;}
#fam_info label{padding:0px 0px 0px 5px;font-size:14px;line-height:30px}
#fam_info select{width:100%}
#fam_info textarea{width:100%}

@media (min-width: 270px) and (max-width: 320px) {
	#fam_info .wid26p{width:23%}
	#fam_info .pad2{padding:22px 0px 0px 0px;}
	
}

</style>
<div class="b7nHUd" id="hamabs"></div>
	<div id="mainpart1">
		<!-- start:Sub Title -->
		<section class="s-info-bar">
			<div class="pgwrapper clearfix">
				<div class="pull-left">About your Family</div>
				 <div class="pull-right"><a href="/profile/viewprofile.php?ownview=1&groupname=~$groupname`&adnetwork1=~$adnetwork1`" class="fam_color1 fam_txtn">Skip</a></div>
			</div>
		</section>      
		<!-- end:Sub Title -->
		<!--start:part to be added-->
		<div id="fam_info">
			<!--start:text-->
			<section>
				<div class="pgwrapper pt10">
					<div class="pull-left">
						<div class="fam_dispib fam_bg1 wid40 hgt40 bpos1"></div>
					</div>
					<div class="pull-right wid83p">
						Your profile is complete, tell us more about your family to get better matches
					</div>
				</div>
			</section>
			<!--end:text-->
			<!--start:main section-->
			<section>
				<div class="pgwrapper">
					<div >
					<!--Family based out of-->
						~$form['native_state']->renderLabel()`
					</div>
					<!--start:tab-->
					<div class="clearfix pt10" id="check_outside_india">
						<div class="pull-left wid50p">
							<div class="tabact fam_color2 fam_txtc lh35 bradleft" id="india">
								India
							</div>
						</div>
						<div class="pull-left pleft2 wid50p">
							<div class="tabnotact fam_color2 fam_txtc lh35 bradright" id="out_india">
								Outside India
							</div>
						</div>                    
					</div>
					<!--endt:tab-->
					<!--start:form-->
					<div class="pt10" id="fam_info">
						~$form->renderFormTag('/register/jsmbPage5')`
							<!-- Native State-->
							<div class="pt10" id="native_state">
								~$form['native_state']->render()`
							</div>	
							<!-- Native City-->
							<div class="pt20" id="native_city">
								~$form['native_city']->render()`
							</div>	
							<!-- Native Country-->
							<div class="pt10" id="native_country">
									~$form['native_country']->render()`
							</div>
							<!-- Native Ancestral Origin-->
							<div class="pt10" id="native_place">
								~$form['ancestral_origin']->render(['placeholder'=>'Specify city/town'])`
							</div>	
							~if $RELIGION eq 1 || $RELIGION eq 4 || $RELIGION eq 7 || $RELIGION eq 9`
							<!-- Gothra-->
							<div class="pt10" id="gothra">
								~$form['gothra']->renderLabel()`
								~$form['gothra']->render()`
								
							</div>	
							<div id="gothraPat" class="fl" ></div>
							~/if`
							<!-- Father Occupation-->
							<div class="pt10" id="father_occ">
								~$form['family_back']->renderLabel()`
								~$form['family_back']->render()`
							</div>
							<!-- Mother Occupation-->
							<div class="pt10" id="mother_occ">
								~$form['mother_occ']->renderLabel()`
								~$form['mother_occ']->render()`
							</div>
							<!-- Brother Info-->
							<div class="pt10">
                            		~$form['t_brother']->renderLabel()`
                        		</div>
							<!--start:brothers-->
							<div class="clearfix">
								<div class="pull-left wid37p" id="num_brother">
									<div class="frm-container">
										~$form['t_brother']->render(['onchange'=>'married_field_brothers();'])`
									</div>  
								</div>
								<div  id="married_field"> 
								<div class="pull-left wid26p pad2">
									<div style="text-align:center;">Married</div>
								</div>
								<div class="pull-right wid37p">
									<div class="frm-container">
										~$form['m_brother']->render()`
									</div>  
								</div>
								</div>                                
							</div>
							<!--end:brothers-->
                            <!-- Sister Info-->
							<div class="pt10">
								~$form['t_sister']->renderLabel()`
							</div>
							<!--start:sister-->
							<div class="clearfix">
								<div class="pull-left wid37p">
									<div class="frm-container">
										~$form['t_sister']->render(['onchange'=>'married_field_sisters();'])`
									</div>  
								</div>
								<div  id="married_field_sis"> 
								<div class="pull-left wid26p pad2">
									<div style="text-align:center;">Married</div>
								</div>
								<div class="pull-right wid37p" >
									<div class="frm-container" >
										~$form['m_sister']->render()`
									</div>  
								</div>  
								</div>                              
							</div>
							<!--End :sister-->	
							<!-- More About Family-->
							<div class="pt10" id="familyinfo">
								~$form['familyinfo']->renderLabel()`
								~$form['familyinfo']->render(['placeholder'=>'write about your parents, brother, sister and extended family'])`
							</div>
							<div style="display:none">
							 ~foreach from=$form item=field`
								~if $field->isHidden()`
									~$field->render()`
								~/if`
							~/foreach`
							<input type="hidden" name="adnetwork1" value="~$sf_request->getParameter('adnetwork1')`">
							<input type="hidden" name="groupname" value="~$sf_request->getParameter('groupname')`">
							</div>
							<div class="pt10">
								~$form->renderGlobalErrors()`
							</div>
							<section class="padBot10">
								<input name="jsmbPage5_submit" type="submit" class="btnM proBtn" value="Continue"/>
							</section>
						</form>
					</div>
					<!--end:form-->
				</div>
			</section>
			<!--end:main section-->
		
		</div>            
		<!--end:part to be added-->
	</div>

<script type="text/javascript">
var countryDefault=~$countryDefault`;
var SITE_URL="~$SITE_URL`";
$("#reg_gothra").autocomplete(SITE_URL+"/profile/autoSug?nophver=1&type=gothra",{maxItemsToShow:10,field:'#gothraPat',from_mob_reg:1});
</script>
