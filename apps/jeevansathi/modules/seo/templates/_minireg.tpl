<script>
function form_submit()
{
	val_ret =lead_valid();
    if(val_ret)
    	document.mini_reg_lead.submit(); 
}
</script>
<div class="lgn_pn2">
    <div class="lr">&nbsp;<div class="fr t10">This page was last updated on ~date("d/m/Y")`</div>
    </div>
    <div>
    	<div class="sprtlgn t_lrc">&nbsp;</div>
     	<div class="sprtlgn t_mc">
            <div style="width:270px; margin:0px 10px 0px 10px;"> <p class="clr_4"></p><b class="mar" style="font-size:14px;">Like a Member? Register to Contact</b><br />

      <div class="clr"></div>
    <ul class="form">
	    <form name="mini_reg_lead" action="/profile/registration_new.php?source=~$SOURCE`&mini_reg=1" method="post" enctype="multipart/form-data">
	    <input type="hidden" name="site_url" value="~sfConfig::get('app_site_url')`" />
          <li>
          		<label id="email_err_red">Email  :</label>
		    <input type="text" id="email_val" name="email" maxlength="40" style="width:167px;font-size:11px;" /><br />
          </li>
          <div class="clr"></div>
		    <div style="display:none" id="email_err">
		    <s style="color: rgb(255, 0, 0); font-size: 11px; float: left; text-decoration:none;" id="email_err_txt">Please enter email address in proper format</s>
          </div>
          <li >
            	<label id="mobile_err_red">Mobile No  :</label>
	            <input style="width: 25px;" id="country_Code" name="country_Code" value="+91" type="text" maxlength="4" />
		    <input class="ml_6" type="text" style="width:135px;" id="mobile" name="mobile" value="" maxlength="12" onBlur="javascript:getlead();"/>
          </li>
          <div class="clr"></div>
          <p id="mobile_error" style="display:none">
		    <s style="color: rgb(255, 0, 0); font-size: 11px; float: left; text-decoration:none;" id="mobile_err_txt"></s>
          </p>
          <li>
          		<label id="match_err_red">Looking For :</label>
              	<select name="relationship" id="relationship_val" style="width:171px;font-size:11px;">
	                  <option value="" selected="selected">Please Select</option>
		          <option value="1">Bride for Self</option>
		          <option value="2">Bride for Son</option>
		          <option value="6">Bride for Brother</option>
		          <option value="4">Bride for Friend/Relative/Niece/Others</option>
		          <option value="1D">Groom for Self</option>
		          <option value="2D">Groom for Daughter</option>
		          <option value="6D">Groom for Sister</option>
		          <option value="4D">Groom for Friend/Relative/Niece/Others</option>
		    </select>
          </li>
          <div class="clr"></div>
          <li>
              <label id="dob_err_txt">Date of Birth :</label>
              <select style="width:55px;font-size:11px;" name="day" id="day">
		     <option selected value="">Day</option>
          	 	 ~section name=foo loop=count($minireg->dayArray)`				    
		     	    <option value=~$minireg->dayArray[foo]` >~$minireg->dayArray[foo]` </option>
          		 ~/section`		  	
	      </select>
	     <select style="width:55px;font-size:11px;" name="month" id="month">
		     <option selected value="">Month</option>
		     <option value="1">Jan</option>
		     <option value="2">Feb</option>
		     <option value="3">Mar</option>
		     <option value="4">Apr</option>
		     <option value="5">May</option>
		     <option value="6">Jun</option>
		     <option value="7">Jul</option>
		     <option value="8">Aug</option>
		     <option value="9">Sep</option>
		     <option value="10">Oct</option>
		     <option value="11">Nov</option>
		     <option value="12">Dec</option>
       	 </select>
	     <select style="width:55px;font-size:11px;" name="year" id="year">
		    <option selected value="">Year</option>
	            ~section name=foo loop=count($minireg->yearArray)`
		           <option value=~$minireg->yearArray[foo]` >~$minireg->yearArray[foo]` </option>     
	            ~/section`					 
	     </select>
          <div class="clr"></div>
          </li>
          <li>
              <label id="mtongue_err_red">Community :</label>
				    <select name="mtongue" id="mtongue" style="width:172px;font-size:11px;">
							<option value="" selected="selected">Please Select</option>
						~foreach from=$MtongueDropdown item=value key=kk`
						<optgroup label="&nbsp;"></optgroup>
						<optgroup label="~$value['LABEL']`">
							~foreach from=$value['VALUES'] item=value1 key=kk1`
								<option ~if $minireg->getMtongue() eq $kk1` selected ~/if` value="~$kk1`">~$value1`</option>
							~/foreach`
						</optgroup>
						~/foreach`
       				 </select>
          </li>
          <div class="clr"></div>
          <div>
          	   <b> 
	     	<span style="font:bold 11px arial,verdana; display:none; color:#f00;" id="common_error_sul">*Please fill all details to proceed.</span> 
	       </b>	       
          </div>
          <div>
	     <!--b style="float:left; width:155px;">&nbsp;</b-->
	     <a href="#" class="fl sprte reg_btn" ></a>
             <!--i class="fr f_13"><br />*Mandatory field</i><br /><br /-->
          </div>
       </form>
    </ul>
<div align="center" style="padding-left:59px;">
		<input type="submit" class="s_btn1 sprte" onClick="javascript:form_submit();" value="" style="border:0;" /><br />
		<div style="padding-left:28px;font-size:11px;">Existing member <a href="~$SITE_URL`/profile/login.php?SHOW_LOGIN_WINDOW=1" class="thickbox b l_col">login here</a></div>
	</div>
	</div>
	<div  style="font-size:9px; padding-left:8px;padding-top:5px;">By Clicking Register, you accept Jeevansathi 
		<a href="~$SITE_URL`/P/disclaimer.php" target="_blank" class="l_col">terms and conditions</a>
	</div>
</div>
<div class="sprtlgn b_lrc">&nbsp;</div></div></div> 
