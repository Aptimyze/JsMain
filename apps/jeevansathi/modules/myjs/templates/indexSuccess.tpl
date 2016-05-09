~include_partial('global/header')`
<script type="text/javascript">
$(document).ajaxStart(function(){
                $('#loginHistoryValues').html('<img src="IMG_URL/img_revamp/loader_big.gif">');
            });

</script>


	 <div class="clr_v1"></div>   
     <section>
     	 <div class="container_v1 pt25_v1">
         	<div class="fullwidth_v1">
            	<div class="fl_v1 wid266_v1">
                	<!--start:profile update-->
                    <div class="wid242_v1">
                     	<div class="brdr4_v1 lpbg1_v1">
                        	<div class="padall_10_v1">
                            	<!--start:photo and link section-->
                                <div>
                                	<div class="fl_v1">
                                    	<img src="~$myPic`" alt="user photo"/>
                                    </div>  
                                    <div class="fl_v1" id="profillink">
                                    	<ul>
					    ~if $pictureUploadLink eq 'U'`
                                            	<li><a href="~$SITE_URL`/social/addPhotos" class="ftbld">Upload your Photo</a></li>
					    ~elseif $pictureUploadLink eq 'M'`
                                            	<li><a href="~$SITE_URL`/social/addPhotos" class="ftbld">Upload more Photos</a></li>
					    ~/if`
                                            <li><a href="~$SITE_URL`/P/viewprofile.php?profilechecksum=~$profilechecksum`&EditWhatNew=incompletProfile" class="ftbld">Edit your profile</a></li>
                                        </ul>
                                    </div>
                                    <div class="clr_v1"></div>                              
                                </div>                                
                                <!--end:photo and link section-->
                                <div class="pt20_v1 classrela_v1">
                                	<div id="viewhistory" class="fl_v1 txtcolblu1_v1 curpoint_v1 padallfour_v1">View your login history</div>
                                    <!--start:popup-->
					<div class="loginhistory_v1" style="display:none;">
                                    	<div class="brdr6_v1 classrela_v1">
                                        	<div class="classabso_v1 pos1_v1">
                                            	<div class="loginhistry_v1"></div>
                                            </div>
                                            <div class="brdr8_v1 bgclr9_v1 wid400_v1">
                                            	<div class="padall_10_v1">
                                                    <!--start:table headings-->
                                                    <div>
                                                       <div class="fl_v1 ftbld_v1 wid130_v1">IP Address</div>
                                                       <div class="fl_v1 ftbld_v1 wid230_v1">Date & Time in Indian Standard Time</div>
                                                       <div class="fl_v1"><div class="closepopup1_v1 curpoint_v1"></div></div>
                                                       <div class="clr_v1"></div>                                                        	
                                                    </div>
                                                    <!--end:table headings-->
                                                    <!--start:data and pre next buttons -->
                                                    <div id="viewhistoryvalues">
							<div id="loginHistoryValues" class="datascroll1_v1">
                                                    	</div>
							<div id="loginprenextlink">
							</div>
						    </div>
                                                    <!--end:data-->  
                                                </div>                                          
                                            </div>                                        
                                        </div>                                    
                                    </div>
                                    <!--end:popup-->
                                    <div class="fl_v1" id="help_v1">
                                        <a>
                                        	<div class="helpimg_v1"></div>
                                            	<div class="helppopup_v1">
                                                	<div class="popup2_v1">
                                                    	<div class="paddala_b_v1">Lorem Ipsim Lorem Ipsim Lorem Ipsim Lorem Ipsim Lorem Ipsim Lorem Ipsim Ipsim Lorem Ipsim Ipsim Lorem  
                                                        Ipsim Ipsim Lorem Ipsim Ipsim Lorem Ipsim </div>
                                                    </div>                                                    
                                                </div>
                                         </a>
                                     </div>
                                     <div class="clr_v1"></div>
                                     <div class="padallfour_v1">Your IP address, ~$ipAddress`</div>                                
                                </div>
                            </div>
                        </div>                     
                    </div>
                    <!--end:profile update-->
                    <!--start:left menu-->
		    ~include_partial("leftPanel", ['countObj' => $count])`
                    <!--end:left menu-->
                </div>
                
              <!--start:right part-->
              <div class="fl_v1 wid663_v1">
              	<div class="fullwidth_v1">
                	
                    <!--start:banner-->
                     <article>
                     	<div class="brdr3_v1 bgorg_v1 hgt148_v1">
                        	<div class="padall_10_v1">
                            	<div class="txtalign_c_v1 fntsize17_v1 txt2color_v1" >You are a free member, Upgrade your membership to directly contact your matches</div>
                            </div>
                            <div class="padall_10_v1">
                            	<div class="padleft22_v1">
                                	<div class="fl_v1">
                                    	<div>
                                        	<div class="seephn_v1"></div> 
                                        </div>
                                        <div>See phone <br/>numbers /email</div>
                                    </div>
                                    <div class="fl_v1 padleft50_v1">
                                    	<div class="padleft15_v1">
                                        	<div class="seechat_v1"></div>
                                        </div>
                                        <div>Initiate chat on <br/> website or gtalk</div>
                                    </div>
                                    <div class="fl_v1 padleft50_v1">
                                    	<div class="padleft22_v1">
                                        	<div class="seemsg_v1"></div>
                                        </div>
					<div>Send Personalized <br/>messages</div>
                                    </div>
                                    <div class="fl_v1 padleft50_v1">
                                    	<div>
                                        	<a href="#"><div class="upgraade_v1"></div></a>
                                        </div>
                                    </div>
                                    <div class="clr_v1"></div>
                                </div>
                            </div> 
                        </div>
                     </article>                    
                    <!--end:banner-->   
                    <div class="clr_v1 hgt10_v1"></div>     
                    <!--start:profile update %-->
                    <div class="brdr4_v1 lpbg1_v1">
                    	<div class="padall_10_v1">
                        	<div class="fl_v1">
                            	<div class="padall_5_v1 ftbld_v1">Your profile is ~$profilePercent` complete</div>
                                <div>
                                	<div class="brdr2_v1">
                                    	<div class="paddala_2_v1">
                                        	<div class="prgrbg_v1">
                                            	<div class="prgressbar_v1" style="width:~$profilePercent`%">
                                                	<div class="txtcolwhite_v1 ftbld_v1 padleft4_v1">~$profilePercent`</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="fl_v1 padleft50_v1">
                            	<div>
                                    <ul class="linkblue">
					~section name=profileVar loop=3`
                                    		<li class="txtcolblu1"><a href='~$SITE_URL`~$profilePercentVars[profileVar]["Link"]`'>~$profilePercentVars[profileVar]["Message"]`</a></li>
                                   	~/section` 
				   </ul>
                                </div>                                    
                            </div>
                            <div class="clr_v1"></div>
                        </div>
                    </div>
                    <!--end:profile update %-->    


                    <!--start:new intrest recived-->
		    	~foreach from=$displayObj key=key item=item`
                    		<div class="clr_v1 hgt10_v1"></div>    
				~include_partial('BigIconTuple',[widgetData=>$item])`
			~/foreach`				
                    <!--end:new intrest recived-->



                    <div class="clr_v1 hgt10_v1"></div>
                    <!--start:new acceptance recived-->
                    <div class="pt10_v1">
                    	<div class="brdr4_v1">  
                        	<!--start:header div-->
                            <div class="fullwidth_v1 bgcol1_v1">
                            	<div class="paddala_d_v1">
                                	<!--start:left-->
                                    <div class="fl_v1">
                                      <div class="fl_v1 wid16_v1 txtalign_c_v1 pt4_v1">
                                      	<div class="numberbox_v1 fntsize12_v1">1</div>
                                      </div>
                                      <div class="fl_v1">
                                      	<div class="fntsize14_v1 padleft10_v1 pt4_v1">New Acceptance Received </div>
                                      </div>
                                      <div class="fl_v1">
                                      	<div class="txtcolblu1_v1 padleft4_v1 pt4_v1">[ View all 10 ]  </div>
                                      </div>
                                    </div>
                                    <!--end:left-->
                                    <div class="clr_v1"></div>
                                </div>
                            </div>
                            <!--end:header div-->
                            <!--Start:listing-->
                            <div>
                            	 <!--start:profile div-->
                                <div class="boxprofile_v1">  
                                	<div class="probrdrbtm_v1">
                                    	<div class="paddala_d_v1">
                                        	<!--start:pic right part-->
                                            <div class="fl_v1">
                                            	<div class="imgbrder_v1 imone_v1">
                                                	<img src="images/my_js/userpic2.png" alt="pic"/>                                                
                                                </div>   
                                                <div class="txtalign_c_v1 pt4_v1">
                                                	<a class="txtcolblu1_v1">View Album</a>
                                                </div>                                         
                                            </div>                                            
                                            <!--end:pic right part-->
                                            <!--start:left part-->
                                            <div class="fl_v1 padleft15_v1">
                                            	<!--start:left top part-->
                                                <div>
                                                	<!--start:profile info-->
                                                    <div class="fl_v1 wid320_v1">
                                                    	<div>
                                                        	<!--start:name-->
                                                            <div class="fl_v1">
                                                                <a class="fntsize16_v1 txtcolblu1_v1">
                                                                    VTY9580
                                                                </a>
                                                            </div>
                                                            <!--end:name-->  
                                                        </div> 
                                                        <!--start:information-->
                                                        <div class="pt20_v1 txt3color_v1 ftbld_v1">
                                                            <div class="lht20_v1">25 yrs, 5' 2', Hindu, Bania, Hindi-Delhi, </div>
                                                            <div class="lht20_v1">MB/PGDM, Rs. 4 - 5 lac p.a.,</div>
                                                            <div class="lht20_v1"> Advertising, Delhi</div>
                                                        </div>
                                                        <!--end:information-->                                                      
                                                    </div>
                                                    <!--end:profile info-->
                                                    <!--start:comment box-->
                                                    <div class="fl_v1 padleft22_v1">
                                                    	<div class="brdr5_v1 bgclr2 hgt70_v1 wid153_v1 classrela_v1 brdr1radius_v1">
                                                        	<div class="paddala_e_v1 txt4color_v1">She expressed interest 2 days ago</div>
                                                        	<div class="arrowpos_v1">
                                                            	<div class="commentarrow_v1"></div>
                                                            </div>
                                                        </div>                                                    
                                                    </div>
                                                    <!--end:comment box-->
                                                    <div class="clr_v1"></div>
                                                </div>
                                                <!--end:left top part--> 
                                                <!--start:button-->
                                                <div class="pt10_v1">
                                              		<div class="fl_v1">
                                                        <!--start:green btn-->
                                                        <div>
                                                            <input type="submit" value="Write message" class="btnwid_v1 fntinpt_v1 greenbtn_v1" name="">
                                                        </div>                                                    
                                                        <!--end:green btn-->
                                                    </div>
                                                    <div class="fl_v1 padleft10_v1">
                                                        <!--start:green btn-->
                                                        <div>
                                                             <input type="submit" value="See Phone/ Email" class="btnwid_v1 fntinpt_v1 greybtn_v1" name="">
                                                        </div>                                                    
                                                        <!--end:green btn-->
                                                    </div>
                                                    <div class="clr_v1"></div>
                                                </div>                                                     
                                                <!--end:button-->                                       
                                            </div>
                                            <!--end:left part-->
                                           
                                            <div class="clr_v1"></div>
                                        </div>
                                    </div>                                
                                </div>                                
                                <!--end:profile div-->  
                            </div>
                            <!--end:listing-->
                        </div>                    
                    </div>
                    <!--end:new acceptance recived-->
                    <div class="clr_v1 hgt10_v1"></div>
                	<!--start:new message recived-->
                    <div class="pt20_v1">
                    	<div class="brdr4_v1"> 
                        	<!--start:header div-->
                            <div class="fullwidth_v1 bgcol1_v1">
                            	<div class="paddala_d_v1">
                                	<!--start:left-->
                                    <div class="fl_v1">
                                    	<div class="fl_v1 wid16_v1 txtalign_c_v1 pt4_v1">
                                        	<div class="numberbox_v1 fntsize12_v1">2</div>
                                        </div>
                                        <div class="fl_v1">
                                        	<div class="fntsize14_v1 padleft10_v1 pt4_v1">New Message Received </div>
                                        </div>
                                        <div class="fl_v1">
                                        	<div class="txtcolblu1_v1 padleft4_v1 pt4_v1">[ View all 10 ]  </div>
                                        </div>
                                    </div>
                                    <!--end:left-->                                        
                                    <div class="clr_v1"></div>
                                </div>                                
                            </div>
                            <!--end:header div-->
                            <!--Strat:listing-->
                            <div>
                            	 <!--start:profile div-->
                                <div class="boxprofile_v1">  
                                	<div class="probrdrbtm_v1">
                                    	<div class="paddala_d_v1">
                                        	<!--start:pic right part-->
                                            <div class="fl_v1">
                                            	<div class="imgbrder_v1 imone_v1">
                                                	<img src="images/my_js/userpic2.png" alt="pic"/>                                                
                                                </div>   
                                                <div class="txtalign_c_v1 pt4_v1">
                                                	<a class="txtcolblu1_v1">View Album</a>
                                                </div>                                         
                                            </div>                                            
                                            <!--end:pic right part-->
                                            <!--start:left part-->
                                            <div class="fl_v1 padleft15_v1">
                                            	<!--start:left top part-->
                                                <div>
                                                	<!--start:profile info-->
                                                    <div class="fl_v1 wid320_v1">
                                                    	<div>
                                                        	<!--start:name-->
                                                            <div class="fl_v1">
                                                                <a class="fntsize16_v1 txtcolblu1_v1">
                                                                    VTY9580
                                                                </a>
                                                            </div>
                                                            <!--end:name-->  
                                                        </div> 
                                                        <!--start:information-->
                                                        <div class="pt20_v1 txt3color_v1 ftbld_v1">
                                                            <div class="lht20_v1">25 yrs, 5' 2', Hindu, Bania, Hindi-Delhi, </div>
                                                            <div class="lht20_v1">MB/PGDM, Rs. 4 - 5 lac p.a.,</div>
                                                            <div class="lht20_v1"> Advertising, Delhi</div>
                                                        </div>
                                                        <!--end:information-->                                                      
                                                    </div>
                                                    <!--end:profile info-->
                                                    <!--start:comment box-->
                                                    <div class="fl_v1 padleft22_v1">
                                                    	<div class="brdr5_v1 bgclr2 hgt70_v1 wid153_v1 classrela_v1 brdr1radius_v1">
                                                        	<div class="paddala_e_v1 txt4color_v1">She has sent you message2 days ago</div>
                                                        	<div class="arrowpos_v1">
                                                            	<div class="commentarrow_v1"></div>
                                                            </div>
                                                        </div>                                                    
                                                    </div>
                                                    <!--end:comment box-->
                                                    <div class="clr_v1"></div>
                                                </div>
                                                <!--end:left top part--> 
                                                <!--start:msg box-->
                                                <div class="pt10_v1">
                                                	<div class="bgclr6_v1 padall_10_v1 wid480_v1">
                                                    	<span class="ftbld">Her message</span> : Lorem Ipsum Lorem IpsumLorem Ipsum Lorem Ipsum Lorem Ipsum Lorem IpsumLorem 
                                                        IpsumLorem IpsumLorem IpsumLorem IpsumLorem more
                                                    </div>
                                                    <div class="clr_v1"></div>                                                
                                                </div>                                                
                                                <!--end:msg box-->     
                                                <!--start:button-->
                                                <div class="pt10_v1">
                                              		<div class="fl_v1">
                                                        <!--start:green btn-->
                                                        <div>
                                                            <input type="submit" value="Reply" class="btnwid_v1 fntinpt_v1 greenbtn_v1" name="">
                                                        </div>                                                    
                                                        <!--end:green btn-->
                                                    </div>
                                                    <div class="fl_v1 padleft10_v1">
                                                        <!--start:green btn-->
                                                        <div>
                                                             <input type="submit" value="See Phone/ Email" class="btnwid_v1 fntinpt_v1 greybtn_v1" name="">
                                                        </div>                                                    
                                                        <!--end:green btn-->
                                                    </div>
                                                    <div class="clr_v1"></div>
                                                </div>                                                     
                                                <!--end:button-->                                       
                                            </div>
                                            <!--end:left part-->
                                           
                                            <div class="clr_v1"></div>
                                        </div>
                                    </div>                                
                                </div>                                
                                <!--end:profile div-->  
                                 <!--start:profile div-->
                                <div class="boxprofile_v1">  
                                	<div class="probrdrbtm_v1">
                                    	<div class="paddala_d_v1">
                                        	<!--start:pic right part-->
                                            <div class="fl_v1">
                                            	<div class="imgbrder_v1 imone_v1">
                                                	<img src="images/my_js/userpic2.png" alt="pic"/>                                                
                                                </div>   
                                                <div class="txtalign_c_v1 pt4_v1">
                                                	<a class="txtcolblu1_v1">View Album</a>
                                                </div>                                         
                                            </div>                                            
                                            <!--end:pic right part-->
                                            <!--start:left part-->
                                            <div class="fl_v1 padleft15_v1">
                                            	<!--start:left top part-->
                                                <div>
                                                	<!--start:profile info-->
                                                    <div class="fl_v1 wid320_v1">
                                                    	<div>
                                                        	<!--start:name-->
                                                            <div class="fl_v1">
                                                                <a class="fntsize16_v1 txtcolblu1_v1">
                                                                    VTY9580
                                                                </a>
                                                            </div>
                                                            <!--end:name-->  
                                                        </div> 
                                                        <!--start:information-->
                                                        <div class="pt20_v1 txt3color_v1 ftbld_v1">
                                                            <div class="lht20_v1">25 yrs, 5' 2', Hindu, Bania, Hindi-Delhi, </div>
                                                            <div class="lht20_v1">MB/PGDM, Rs. 4 - 5 lac p.a.,</div>
                                                            <div class="lht20_v1"> Advertising, Delhi</div>
                                                        </div>
                                                        <!--end:information-->                                                      
                                                    </div>
                                                    <!--end:profile info-->
                                                    <!--start:comment box-->
                                                    <div class="fl_v1 padleft22_v1">
                                                    	<div class="brdr5_v1 bgclr2 hgt70_v1 wid153_v1 classrela_v1 brdr1radius_v1">
                                                        	<div class="paddala_e_v1 txt4color_v1">She has sent you message2 days ago</div>
                                                        	<div class="arrowpos_v1">
                                                            	<div class="commentarrow_v1"></div>
                                                            </div>
                                                        </div>                                                    
                                                    </div>
                                                    <!--end:comment box-->
                                                    <div class="clr_v1"></div>
                                                </div>
                                                <!--end:left top part--> 
                                                <!--start:msg box-->
                                                <div class="pt10_v1">
                                                	<div class="bgclr6_v1 padall_10_v1 wid480_v1">
                                                    	<span class="ftbld">Her message</span> : Lorem Ipsum Lorem IpsumLorem Ipsum Lorem Ipsum Lorem Ipsum Lorem IpsumLorem 
                                                        IpsumLorem IpsumLorem IpsumLorem IpsumLorem more
                                                    </div>
                                                    <div class="clr_v1"></div>                                                
                                                </div>                                                
                                                <!--end:msg box-->     
                                                <!--start:button-->
                                                <div class="pt10_v1">
                                              		<div class="fl_v1">
                                                        <!--start:green btn-->
                                                        <div>
                                                            <input type="submit" value="Reply" class="btnwid_v1 fntinpt_v1 greenbtn_v1" name="">
                                                        </div>                                                    
                                                        <!--end:green btn-->
                                                    </div>
                                                    <div class="fl_v1 padleft10_v1">
                                                        <!--start:green btn-->
                                                        <div>
                                                             <input type="submit" value="See Phone/ Email" class="btnwid_v1 fntinpt_v1 greybtn_v1" name="">
                                                        </div>                                                    
                                                        <!--end:green btn-->
                                                    </div>
                                                    <div class="clr_v1"></div>
                                                </div>                                                     
                                                <!--end:button-->                                       
                                            </div>
                                            <!--end:left part-->
                                           
                                            <div class="clr_v1"></div>
                                        </div>
                                    </div>                                
                                </div>                                
                                <!--end:profile div-->  
                            
                            </div>                            
                            <!--end:listing-->
                        </div>
                    </div>
                    <!--end:new message recived-->
                	<div class="clr_v1 hgt10_v1"></div>
                    <!--start:new photo request recived-->
                    <div class="pt20_v1">
                    	<div class="brdr4_v1">   
                            <!--start:header div-->
                            <div class="fullwidth_v1 bgcol1_v1">
                            	<div class="paddala_d_v1">
                                	<!--start:left-->
                                    <div class="fl_v1">
                                    	<div class="fl_v1 wid16_v1 txtalign_c_v1 pt4_v1">
                                        	<div class="numberbox_v1 fnt12_v1">4</div>
                                        </div>
                                        <div class="fl_v1">
                                        	<div class="fntsize14_v1 padleft10_v1 pt4_v1">New Photo Requests Received</div>
                                        </div>
                                        <div class="fl_v1">
                                        	<div class="txtcolblu1_v1 padleft4_v1 pt4_v1">[ View all 10 ]  </div>
                                        </div>
                                    </div>
                                    <!--end:left-->
                                    <!--start:right-->
                                    <div class="fr_v1">
                                    	<div class="fl_v1">
                                        	<div class="pt4_v1 padright4_v1">[ Showing 1-3 of 5 ]</div>
                                        </div>
                                        <div class="fl_v1">
                                        	<div class="fl_v1"><a id="prev1"><div class="prevbtn_v1"></div></a></div>
                                            <div class="fl_v1"><a id="next1"><div class="nextbtn_v1"></div></a></div>
                                            <div class="clr_v1"></div>
                                        </div>
                                        <div class="clr_v1"></div>
                                    </div>
                                    <!--end:right-->
                                     <div class="clr_v1"></div>
                                </div>
                            </div>
                            <!--end:header div-->  
                            <!--start:listing-->
                            <div>
                             <!--start:profile div-->
                                <div class="boxprofile_v1">  
                                	<div class="probrdrbtm_v1">
                                    	<div class="paddala_d_v1">
                                        	<!--start:pic right part-->
                                            <div class="fl_v1">
                                            	<div class="imgbrder_v1 imone_v1">
                                                	<img src="images/my_js/userpic2.png" alt="pic"/>                                                
                                                </div>   
                                                <div class="txtalign_c_v1 pt4_v1">
                                                	<a class="txtcolblu1_v1">View Album</a>
                                                </div>                                         
                                            </div>                                            
                                            <!--end:pic right part-->
                                            <!--start:left part-->
                                            <div class="fl_v1 padleft15_v1">
                                            	<!--start:left top part-->
                                                <div>
                                                	<!--start:profile info-->
                                                    <div class="fl_v1 wid320_v1">
                                                    	<div>
                                                        	<!--start:name-->
                                                            <div class="fl_v1">
                                                                <a class="fntsize16_v1 txtcolblu1_v1">
                                                                    VTY9580
                                                                </a>
                                                            </div>
                                                            <!--end:name-->  
                                                        </div> 
                                                        <!--start:information-->
                                                        <div class="pt20_v1 txt3color_v1 ftbld_v1">
                                                            <div class="lht20_v1">25 yrs, 5' 2', Hindu, Bania, Hindi-Delhi, </div>
                                                            <div class="lht20_v1">MB/PGDM, Rs. 4 - 5 lac p.a.,</div>
                                                            <div class="lht20_v1"> Advertising, Delhi</div>
                                                        </div>
                                                        <!--end:information-->                                                      
                                                    </div>
                                                    <!--end:profile info-->
                                                    <!--start:comment box-->
                                                    <div class="fl_v1 padleft22_v1">
                                                    	<div class="brdr5_v1 bgclr2 hgt70_v1 wid153_v1 classrela_v1 brdr1radius_v1">
                                                        	<div class="paddala_e_v1 txt4color_v1">She Requested for your photo 2 days ago</div>
                                                        	<div class="arrowpos_v1">
                                                            	<div class="commentarrow_v1"></div>
                                                            </div>
                                                        </div>                                                    
                                                    </div>
                                                    <!--end:comment box-->
                                                    <div class="clr_v1"></div>
                                                </div>
                                                <!--end:left top part--> 
                                                <!--start:button-->
                                                <div class="pt10_v1">
                                              		<div class="fl_v1">
                                                        <!--start:green btn-->
                                                        <div>
                                                            <input type="submit" value="Upload Photo" class="btnwid_v1 fntinpt_v1 greenbtn_v1" name="">
                                                        </div>                                                    
                                                        <!--end:green btn-->
                                                    </div>
                                                    <div class="clr_v1"></div>
                                                </div>                                                     
                                                <!--end:button-->                                       
                                            </div>
                                            <!--end:left part-->
                                           
                                            <div class="clr_v1"></div>
                                        </div>
                                    </div>                                
                                </div>                                
                                <!--end:profile div-->                              
                            
                            </div>                            
                            <!--end:listing-->
                        </div>
                         <!--start:pagination botton-->
                        <div class="txtalign_c_v1 pt10_v1" >
                        	<ul class="navlist">
                            	<li><a href="#">1</a></li>
								<li><a href="#">2</a></li>
                            </ul>
                        </div>                        
                        <!--end:pagination botton-->
                    </div>                        
                    <!--end:new photo request recived-->
                    <div class="clr_v1 hgt10_v1"></div>
                	<!--start:member visited my profile-->
                	<div class="pt20_v1">
                    	<div class="brdr4_v1">  
                        	<!--start:header div-->
                            <div class="fullwidth_v1 bgcol1_v1">
                            	<div class="paddala_d_v1">
                                	<!--start:left-->
                                    <div class="fl_v1">
                                    	<div class="fl_v1 wid16_v1 txtalign_c_v1 pt4_v1">
                                        	<div class="numberboxgrey_v1 fntsize12_v1">4</div>
                                        </div>
                                        <div class="fl_v1">
                                        	<div class="fntsize14_v1 padleft10_v1 pt4_v1">New members visited my profile</div>
                                        </div>
                                        <div class="fl_v1">
                                        	<div class="txtcolblu1_v1 padleft4_v1 pt4_v1">[ View all 10 ]  </div>
                                        </div>
                                    </div>
                                    <!--end:left-->
                                    <div class="clr_v1"></div>
                                </div>
                            </div>
                            <!--end:header div-->  
                            <!--start:listing-->
                            <div>
                            	 <!--start:profiles div-->
                                <div class="boxprofile_v1">   
                                	<div class="probrdrbtm_v1">
                                    	<div class="paddala_d_v1">
                                        	<!--start:pic right part-->
                                            <div class="fl_v1">
                                            	<div class="imgbrder imtwo_v1">
                                                	<img src="images/my_js/userpic4.png" alt="pic"/>                                                
                                                </div>   
                                                <div class="txtalign_c_v1 pt4_v1">
                                                	<a class="txtcolblu1_v1">View Album</a>
                                                </div>                                         
                                            </div>
                                            <!--end:pic right part-->
                                            <!--start:left part-->
                                            <div class="fl_v1 padleft15_v1">
                                            	<!--start:left top part-->
                                                <div>
                                                	<!--start:profile info-->
                                                	<div class="fl_v1 wid361_v1">
                                                    	<div>
                                                        	<!--start:name-->
                                                            <div class="fl_v1">
                                                                <a class="fntsize16_v1 txtcolblu1_v1">
                                                                    VTY9580
                                                                </a>
                                                            </div>
                                                            <!--end:name-->
                                                            <!--start:just joined-->
                                                            <div class="fl_v1">
                                                            	<div class="justjoin_v1"></div>
                                                            </div>
                                                            <!--end:just joined-->
                                                            <div class="clr_v1"></div>
                                                        </div>
                                                         <!--start:information-->
                                                        <div class="pt10_v1 txt3color_v1">
                                                            <div class="lht20_v1">28 yrs, 5' 4", Hindu, Rajput, Hindi-UP, B.E/B.Tech, </div>
                                                            <div class="lht20_v1">Software Professional, Rs. 10 - 15lac, Pune/ Chinchwad</div>
                                                        </div>
                                                        <!--end:information-->   
                                                    </div>
                                                    <!--end:profile info-->
                                                    <!--start:comment box-->
                                                    <div class="fl_v1 padleft22_v1">
                                                    	<div class="brdr5_v1 bgclr2 hgt70_v1 wid153_v1 classrela_v1 brdr1radius_v1">
                                                        	<div class="paddala_e_v1 txt4color_v1">She visited your profile 2 days ago</div>
                                                        	<div class="arrowpos_v1">
                                                            	<div class="commentarrow_v1"></div>
                                                            </div>
                                                        </div>                                                    
                                                    </div>
                                                    <!--end:comment box-->
                                                    <div class="clr_v1"></div>
                                                </div>
                                                <!--end:left top part-->
                                                <!--start:button-->
                                                <div class="pt10_v1">
                                              		<div class="fl_v1">
                                                        <!--start:green btn-->
                                                        <div>
                                                            <input type="submit" value="Express Intrest" class="btnwid_v1 fntinpt_v1 greenbtn_v1" name="">
                                                        </div>                                                    
                                                        <!--end:green btn-->
                                                    </div>
                                                    <div class="fl_v1 padleft10_v1">
                                                        <!--start:green btn-->
                                                        <div>
                                                             <input type="submit" value="Not Intrested" class="btnwid_v1 fntinpt_v1 greybtn_v1" name="">
                                                        </div>                                                    
                                                        <!--end:green btn-->
                                                    </div>
                                                    <div class="clr_v1"></div>
                                                </div>                                                     
                                                <!--end:button-->
                                            </div>
                                            <!--end:left part-->
                                       	<div class="clr_v1"></div>
                                        </div>
                                    </div>
                                </div>
                                <!--end:profiles div-->
                                 <!--start:profiles div-->
                                <div class="boxprofile_v1">   
                                	<div class="probrdrbtm_v1">
                                    	<div class="paddala_d_v1">
                                        	<!--start:pic right part-->
                                            <div class="fl_v1">
                                            	<div class="imgbrder imtwo_v1">
                                                	<img src="images/my_js/userpic4.png" alt="pic"/>                                                
                                                </div>   
                                                <div class="txtalign_c_v1 pt4_v1">
                                                	<a class="txtcolblu1_v1">View Album</a>
                                                </div>                                         
                                            </div>
                                            <!--end:pic right part-->
                                            <!--start:left part-->
                                            <div class="fl_v1 padleft15_v1">
                                            	<!--start:left top part-->
                                                <div>
                                                	<!--start:profile info-->
                                                	<div class="fl_v1 wid361_v1">
                                                    	<div>
                                                        	<!--start:name-->
                                                            <div class="fl_v1">
                                                                <a class="fntsize16_v1 txtcolblu1_v1">
                                                                    VTY9580
                                                                </a>
                                                            </div>
                                                            <!--end:name-->
                                                            <!--start:just joined-->
                                                            <div class="fl_v1">
                                                            	<div class="justjoin_v1"></div>
                                                            </div>
                                                            <!--end:just joined-->
                                                            <div class="clr_v1"></div>
                                                        </div>
                                                         <!--start:information-->
                                                        <div class="pt10_v1 txt3color_v1">
                                                            <div class="lht20_v1">28 yrs, 5' 4", Hindu, Rajput, Hindi-UP, B.E/B.Tech, </div>
                                                            <div class="lht20_v1">Software Professional, Rs. 10 - 15lac, Pune/ Chinchwad</div>
                                                        </div>
                                                        <!--end:information-->   
                                                    </div>
                                                    <!--end:profile info-->
                                                    <!--start:comment box-->
                                                    <div class="fl_v1 padleft22_v1">
                                                    	<div class="brdr5_v1 bgclr2 hgt70_v1 wid153_v1 classrela_v1 brdr1radius_v1">
                                                        	<div class="paddala_e_v1 txt4color_v1">She visited your profile 2 days ago</div>
                                                        	<div class="arrowpos_v1">
                                                            	<div class="commentarrow_v1"></div>
                                                            </div>
                                                        </div>                                                    
                                                    </div>
                                                    <!--end:comment box-->
                                                    <div class="clr_v1"></div>
                                                </div>
                                                <!--end:left top part-->
                                                <!--start:button-->
                                                <div class="pt10_v1">
                                              		<div class="fl_v1">
                                                        <!--start:green btn-->
                                                        <div>
                                                            <input type="submit" value="Express Intrest" class="btnwid_v1 fntinpt_v1 greenbtn_v1" name="">
                                                        </div>                                                    
                                                        <!--end:green btn-->
                                                    </div>
                                                    <div class="fl_v1 padleft10_v1">
                                                        <!--start:green btn-->
                                                        <div>
                                                             <input type="submit" value="Not Intrested" class="btnwid_v1 fntinpt_v1 greybtn_v1" name="">
                                                        </div>                                                    
                                                        <!--end:green btn-->
                                                    </div>
                                                    <div class="clr_v1"></div>
                                                </div>                                                     
                                                <!--end:button-->
                                            </div>
                                            <!--end:left part-->
                                       	<div class="clr_v1"></div>
                                        </div>
                                    </div>
                                </div>
                                <!--end:profiles div-->                            
                            </div>
                            <!--end:listing-->
                        </div>
                    </div>
                    <!--end:member visited my profile-->
                    <div class="clr_v1 hgt10_v1"></div>
                    <!-- start:matches-->
                    	
                        <!--start:match heading-->
                         <div>
                         	<!--start:no of matches-->
                            <div class="fl_v1">
                            	<div class="fntsize20_v1 txt4color_v1">6354 Matches</div>
                            </div>                          
                          	<!--end:no of matches-->
                            <!--start:prefrence links-->
                            <div class="fl_v1 padleft15_v1 pt5_v1">
                            	<div class="fl_v1">
                                	<a href="#" class="txtcolblu1_v1 txtdeco_v1">Your Partner Prefrences <div class="fr_v1 paddall_f_v1 smallarrow_v1 mrtop15n_v1"></div></a>
                                </div>
                                <div class="fl_v1">
                                	<div class="seprator3_v1"></div>
                                </div>
                                <div class="fl_v1">
                                	<a href="#" class="txtcolblu1_v1 txtdeco_v1">Edit Partner Prefrences <div class="fr_v1 paddall_f_v1 smallarrow_v1 mrtop15n_v1"></div></a>
                                </div>
                                <div class="clr_v1"></div>
                            </div>
                            <!--end:prefrence links-->
                            <div class="clr_v1"></div>
                         </div>
                        <!--end:match heading-->
                        
                        <!--start:profile box for matches-->
                        <div class="pt17_v1">
                        	<div class="brdr4_v1 bgcol2_v1 classrela_v1">    
                            	<div>
                                	<!--start:close button-->
                                    <div class="closeabs_v1">
                                    	<div class="clsbtn_v1"></div>
                                    </div>                                                
                                    <!--end:close button-->
                                    <div class="padall_10_v1">
                                    	<!--start:pic right part-->
                                        <div class="fl_v1">
                                        	<div class="imgbrder imone">
                                            	<img src="images/my_js/userpic.png" alt="pic"/>                                                
                                            </div>   
                                            <div class="txtalign_c_v1 pt4_v1">
                                            	<a class="txtcolblu1_v1">View Album</a>
                                            </div>                                         
                                        </div>
                                        <!--end:pic right part-->
                                        <!--start:left part-->
                                        <div class="fl_v1 padleft15_v1">
                                        	<!--start:left top part-->
                                            <div>
                                            	<!--start:profile info-->
                                                <div class="fl_v1 wid320_v1">
                                                	<div>
                                                    	<!--start:name-->
                                                        <div class="fl_v1">
                                                        	<a class="fntsize16_v1 txtcolblu1_v1">
                                                            VTY9580
                                                            </a>
                                                        </div>
                                                        <!--end:name-->
                                                        <!--start:just joined-->
                                                        <div class="fl_v1">
                                                        	<div class="justjoin_v1"></div>
                                                        </div>
                                                        <!--end:just joined-->
                                                        <!--start:seprator-->
                                                        <div class="fl_v1 padleft6_v1">
                                                        	<div class="seprator2_v1"></div>
                                                        </div>
                                                        <!--end:seprator-->
                                                        <!--start:mobile icon-->
                                                        <div class="fl_v1 padleft10_v1">
                                                        	<div class="mobileicon_v1"></div>
                                                        </div>                                                            
                                                        <!--end:mobile icon-->
                                                        <div class="clr_v1"></div>
                                                    </div>
                                                    <!--start:information-->
                                                    <div class="pt20_v1 txt3color_v1 ftbld_v1">
                                                    	<div class="lht20_v1">25 yrs, 4' 11" </div>
                                                        <div class="lht20_v1">Hindu, Bania</div>
                                                        <div class="lht20_v1">Hindi-Delhi, B.E/B.Tech</div>
                                                        <div class="lht20_v1">Software Professional</div>
                                                        <div class="lht20_v1">Rs. 4 - 5Lac, New Delhi</div>
                                                    </div>
                                                    <!--end:information-->   
                                                </div>
                                                <!--end:profile info-->                                                                                                     
                                                <div class="clr_v1"></div>
                                            </div>
                                            <!--end:left top part-->
                                        </div>
                                        <!--end:left part-->
                                        <!--start:option list-->
                                        <div class="fl_v1" style="border-left:1px solid #999;">
                                        	<div class="padleft10_v1">
                                            	<!--start-->
                                                        <div>
                                                        	<!--start:gtalk-->
                                                            <div>
                                                                    <div class="fl_v1 pt9_v1 txtcolblu1_v1">Online now...</div>
                                                                    <div class="fl_v1">
                                                                        <div class="gtalkicon_v1"></div>
                                                                    </div>
                                                                    <div class="clr_v1"></div>
                                                            </div>                                                         
                                                            <!--end:gtalk-->
                                                            <!--start:view profile-->
                                                            <div class="pt10_v1">
                                                            	<input type="submit" value="View Profile" class="btnwid_v1 fntinpt_v1 greenbtn_v1" name="">
                                                            </div>
                                                            <!--end:view profile-->                                                            
                                                            <!--start:express start-->
                                                            <div class="pt10_v1">
                                                            	<div class="fl_v1">
                                                                	<div class="expitn_v1"></div>
                                                                </div>
                                                                <div class="fl_v1">
                                                                	<div class="padleft10_v1 pt6_v1">
                                                                    	<a class="txtcolblu1_v1 txtdeco_v1">Express Intrest</a>	
                                                                    </div>																			                                                               	</div>
                                                            </div>                                                 
                                                            <!--end:express start-->
                                                            <div class="clr_v1"></div>
                                                            <!--start:see phone/ email-->
                                                            <div class="pt10_v1">
                                                            	<div class="fl_v1">
                                                                	<div class="phnicon_v1"></div>
                                                                </div>
                                                                <div class="fl_v1">
                                                                	<div class="padleft10_v1 pt6_v1">
                                                                        <a class="txtcolblu1_v1 txtdeco_v1">See Phone/ Email</a>	
                                                                    </div>																			                                                                 </div>
                                                            </div>                                                  
                                                            <!--end:phone/ email-->
                                                            <div class="clr_v1"></div>
                                                            <!--start:see phone/ email-->
                                                            <div class="pt10_v1">
                                                            	<div class="fl_v1">
                                                                	<div class="shrtlistcon_v1"></div>
                                                                </div>
                                                                <div class="fl_v1">
                                                                	<div class="padleft10_v1 pt6_v1">
                                                                    	<a class="txtcolblu1_v1 txtdeco_v1">Shortlist</a>	
                                                                    </div>																			                                                                </div>
                                                            </div>                                               
                                                            <!--end:phone/ email-->
                                                            </div>   
                                                        <!--end-->                                                 
                                                    </div>  
                                                     <div class="clr_v1"></div>                                             
                                               </div>
                                                <!--end:option list-->
                                               <div class="clr_v1"></div>
                                           </div>
                                       </div>
                                       <div class="clr_v1"></div>
                                   </div>                                    
                                </div>                                    
                                <!--end:profile box for matches-->  
                                
                                 <!--start:profile box for matches-->
                        <div class="pt17_v1">
                        	<div class="brdr4_v1 bgcol2_v1 classrela_v1">    
                            	<div>
                                	<!--start:close button-->
                                    <div class="closeabs_v1">
                                    	<div class="clsbtn_v1"></div>
                                    </div>                                                
                                    <!--end:close button-->
                                    <div class="padall_10_v1">
                                    	<!--start:pic right part-->
                                        <div class="fl_v1">
                                        	<div class="imgbrder imone">
                                            	<img src="images/my_js/userpic.png" alt="pic"/>                                                
                                            </div>   
                                            <div class="txtalign_c_v1 pt4_v1">
                                            	<a class="txtcolblu1_v1">View Album</a>
                                            </div>                                         
                                        </div>
                                        <!--end:pic right part-->
                                        <!--start:left part-->
                                        <div class="fl_v1 padleft15_v1">
                                        	<!--start:left top part-->
                                            <div>
                                            	<!--start:profile info-->
                                                <div class="fl_v1 wid320_v1">
                                                	<div>
                                                    	<!--start:name-->
                                                        <div class="fl_v1">
                                                        	<a class="fntsize16_v1 txtcolblu1_v1">
                                                            VTY9580
                                                            </a>
                                                        </div>
                                                        <!--end:name-->
                                                        <!--start:evalue-->
                                                            <div class="fl_v1 padleft15_v1">
                                                                <div class="evallogo_v1"></div>
                                                            </div>
                                                            <!--end:evalue-->
                                                            <!--start:help-->
                                                            <div  class="fl_v1 classrela_v1 help1">
                                                                <a>
                                                                    <div class="helpimg_v1"></div>
                                                                    <div class="helppopup3">
                                                                        <div class="popup3">
                                                                            <div class="paddala_b_v1">eValue members can SEE phone/email of other members. They can also SHOW their
                                                                             phone/email to all members <span class="txtcolblu1 txtdeco">Upgrade to eValue</span></div>
                                                                        </div>                                                    
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <!--end:help-->
                                                        <!--start:seprator-->
                                                        <div class="fl_v1 padleft6_v1">
                                                        	<div class="seprator2_v1"></div>
                                                        </div>
                                                        <!--end:seprator-->
                                                        <!--start:mobile icon-->
                                                        <div class="fl_v1 padleft10_v1">
                                                        	<div class="mobileicon_v1"></div>
                                                        </div>                                                            
                                                        <!--end:mobile icon-->
                                                        <div class="clr_v1"></div>
                                                    </div>
                                                    <!--start:information-->
                                                    <div class="pt20_v1 txt3color_v1 ftbld_v1">
                                                    	<div class="lht20_v1">25 yrs, 4' 11" </div>
                                                        <div class="lht20_v1">Hindu, Bania</div>
                                                        <div class="lht20_v1">Hindi-Delhi, B.E/B.Tech</div>
                                                        <div class="lht20_v1">Software Professional</div>
                                                        <div class="lht20_v1">Rs. 4 - 5Lac, New Delhi</div>
                                                    </div>
                                                    <!--end:information-->   
                                                </div>
                                                <!--end:profile info-->                                                                                                     
                                                <div class="clr_v1"></div>
                                            </div>
                                            <!--end:left top part-->
                                        </div>
                                        <!--end:left part-->
                                        <!--start:option list-->
                                        <div class="fl_v1" style="border-left:1px solid #999;">
                                        	<div class="padleft10_v1">
                                            	<!--start-->
                                                        <div>
                                                        	<!--start:gtalk-->
                                                            <div>
                                                                    <div class="fl_v1 pt9_v1 txtcolblu1_v1">Online now...</div>
                                                                    <div class="fl_v1">
                                                                        <div class="gtalkicon_v1"></div>
                                                                    </div>
                                                                    <div class="clr_v1"></div>
                                                            </div>                                                         
                                                            <!--end:gtalk-->
                                                            <!--start:view profile-->
                                                            <div class="pt10_v1">
                                                            	<input type="submit" value="View Profile" class="btnwid_v1 fntinpt_v1 greenbtn_v1" name="">
                                                            </div>
                                                            <!--end:view profile-->                                                            
                                                            <!--start:express start-->
                                                            <div class="pt10_v1">
                                                            	<div class="fl_v1">
                                                                	<div class="expitn_v1"></div>
                                                                </div>
                                                                <div class="fl_v1">
                                                                	<div class="padleft10_v1 pt6_v1">
                                                                    	<a class="txtcolblu1_v1 txtdeco_v1">Express Intrest</a>	
                                                                    </div>																			                                                               	</div>
                                                            </div>                                                 
                                                            <!--end:express start-->
                                                            <div class="clr_v1"></div>
                                                            <!--start:see phone/ email-->
                                                            <div class="pt10_v1">
                                                            	<div class="fl_v1">
                                                                	<div class="phnicon_v1"></div>
                                                                </div>
                                                                <div class="fl_v1">
                                                                	<div class="padleft10_v1 pt6_v1">
                                                                        <a class="txtcolblu1_v1 txtdeco_v1">See Phone/ Email</a>	
                                                                    </div>																			                                                                 </div>
                                                            </div>                                                  
                                                            <!--end:phone/ email-->
                                                            <div class="clr_v1"></div>
                                                            <!--start:see phone/ email-->
                                                            <div class="pt10_v1">
                                                            	<div class="fl_v1">
                                                                	<div class="shrtlistcon_v1"></div>
                                                                </div>
                                                                <div class="fl_v1">
                                                                	<div class="padleft10_v1 pt6_v1">
                                                                    	<a class="txtcolblu1_v1 txtdeco_v1">Shortlist</a>	
                                                                    </div>																			                                                                </div>
                                                            </div>                                               
                                                            <!--end:phone/ email-->
                                                            </div>   
                                                        <!--end-->                                                 
                                                    </div>  
                                                     <div class="clr_v1"></div>                                             
                                               </div>
                                                <!--end:option list-->
                                               <div class="clr_v1"></div>
                                           </div>
                                       </div>
                                       <div class="clr_v1"></div>
                                   </div>                                    
                                </div>                                    
                                <!--end:profile box for matches-->  
                    
                    <!-- end:matches-->
                    <div class="clr_v1 hgt10_v1"></div>
                
                </div>              
              </div>
              <!--end:right part-->
                
                
                
                
                
            </div>         
         </div>     
     </section>

	<!-- album-->
	<div style = "display:none">
		<div id = "albumCode">
		</div>
	</div>
	<!-- album-->

	<div class="clr_v1"></div>   

<script>
var showOnlyGunaMatch='Y';
var Guna="Y";    
var next,id,page;
astro_icons();

/*- lavesh */
$('.prevbtn_v1').bind( "click", function() {
        next = $(this).closest('div').attr('id');
        id = next.split('_')[0];
        page = next.split('_')[1];
        //alert(next+"::"+id+"::"+page);
        nextTuple(id,page);
});


$('.nextbtn_v1').bind( "click", function() {
	next = $(this).closest('div').attr('id');
	id = next.split('_')[0];
	page = next.split('_')[1];
	//alert(next+"::"+id+"::"+page);
	nextTuple(id,page);
});

function nextTuple(id,page)
{
	var tempLoop,counter=0;
	$.ajax(
	{
		url: "/myjs/"+id+"/"+page,
		dataType : "json",
		success: function(response) 
		{
			if(response)
			{
				$("#SHOWING_START"+response.ID).html(response.SHOWING_START);
				$("#SHOWING_COUNT"+response.ID).html(response.SHOWING_COUNT);
				tempLoop = response.CONFIG_COUNT;

				if(response.SHOW_NEXT)
					$("#SHOW_NEXT"+response.ID).show();
				else
					$("#SHOW_NEXT"+response.ID).hide();
				if(response.CURRENT_NAV!=1)
					$("#CURRENT_NAV"+response.ID).show();
				else
					$("#CURRENT_NAV"+response.ID).hide();


				$.each(response.TUPLES, function(i,item) {
					counter = counter+1;
					$("#SearchPicUrl_"+response.ID+"_"+counter).attr("src",item.SearchPicUrl);
					$("#USERNAME_"+response.ID+"_"+counter).html(item.USERNAME);
					$("#displayString_"+response.ID+"_"+counter).html(item.displayString);
					$.each(item.CALLOUT_MESSAGES, function(i2,item2)
					{
						$("#CALLOUT_MESSAGES_"+i2+"_"+response.ID+"_"+counter).html(item2);
					});
					divcount =0;
					$.each(item.ICONS, function(i2,item2)
                                        {
						ifDiv = $("#ICON_"+divcount+"_"+response.ID+"_"+counter);
						if( ifDiv.length === 0)
						{
							if(divcount !=0)
                                                        {
                                                                $("#ICONS_"+response.ID+"_"+counter).append('<div class="fl_v1 padleft5_v1"><div class="seprator2_v1"></div></div>');
                                                        }
							$("#ICONS_"+response.ID+"_"+counter).append('<div class="fl_v1 padleft15_v1"><div id="ICON_'+divcount+"_"+response.ID+"_"+counter+'"></div></div>');
							$("#ICONS_"+response.ID+"_"+counter).append('<div id="ICON_PARTIAL_'+divcount+"_"+response.ID+"_"+counter+'"></div>');
						}
					       	$("#ICON_"+divcount+"_"+response.ID+"_"+counter).attr("class",item2.iconClass);
						$("#ICON_PARTIAL_"+divcount+"_"+response.ID+"_"+counter).load("/var/www/html/branches/milestoneConfig/apps/jeevansathi/templates/eRishtaHelp.html");
						
						divcount=divcount+1;
                                        });
				});
				astro_icons();
				for(var i=tempLoop;i>0;i--)
				{
					if(i>counter)
						$("#tupleBox_"+response.ID+"_"+i).hide();
					else
						$("#tupleBox_"+response.ID+"_"+i).show();
				}
			}
		},
		error: function(xhr) 
		{
			alert("Error! Please TRY AGAIN");
		}       
	});
}

/** Album **/
$('.myjsAlbum').bind( "click", function() {
		var id = this.id.replace("showAlbum_","");
		id = id.split('_')[1];
		var params = "profilechecksum="+id+"&searchPage=1";
		var xslUrl = SITE_URL+"/xslt/newAlbumLayer2.xsl";
		var xmlUrl = SITE_URL+"/social/album";
		sendXSLTrequest(xslUrl,xmlUrl,"albumCode",params,"searchPage");
});
/** Album **/

/*- lavesh */





 
	function callloginajax(page)
	{
                $('.loginhistory_v1').css('display','block');
		$.ajax(
                {
                        url: "/common/LoginHistory",
                        data: "page="+page,
			dataType : "json",
                        success: function(response) 
                        {
                                if(response)
				{
					var valhtml='';
		                        $.each(response.VALUES, function(i,item) {
					valhtml+='<div><div class="fl_v1 wid130_v1">'+item.IPADDR+'</div>';
					valhtml+='<div class="fl_v1 wid230_v1">'+item.TIME +' </div><div class="clr_v1"></div> </div>';
                        		});
					$('#loginHistoryValues').html(valhtml);
					var linkshtml='';
					if(response.PREV)
						linkshtml+='<div><a href="#" onClick="callloginajax('+response.PREV+'); return false;">PREV</a></div>';
					if(response.NEXT)
						linkshtml+='<div><a href="#" onClick="callloginajax('+response.NEXT+'); return false;">NEXT</a></div>';
					$('#loginprenextlink').html(linkshtml);
                                        
                                }
                        },
                        error: function(xhr) 
                        {
                                alert("Error! Please TRY AGAIN");
                        }       
                });

	}
	
	$(".closepopup1_v1").click(function(){
		$('.loginhistory_v1').css('display','none');
	});
        $("#viewhistory").click(function(){
                callloginajax(1);
        });
</script>
	
	
	
      <!--start:footer-->
    <footer>
   	~include_partial('global/footer')` 
    </footer>
    <!--end:footer-->
