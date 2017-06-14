<div class="sp5"></div>
~if $showTollFree`
<div class="sp5"></div>
<div class="lf" style="margin-left:26px;_margin-left:6px;font-size:16px;background-color:#ececec;width:320px;height:110px">
<div class="lf" style="margin:17px">Already a member - <a href="/profile/login.php?SHOW_LOGIN_WINDOW=1" class="thickbox" style="text-decoration:none"><b> Login Here</b></a></div>
<div class="sp5"></div>
<div class="lf" style="margin:15px;margin-top:4px">New to Jeevansathi - <a href="/profile/registration_new.php?source=knwlrtyreg" ><b>Register Here</b></a></div>
<div class="sp5"></div>
<div class="sp5"></div>
</div>
<div class="sp5"></div>
<div style="height:40px;clear:both;overflow:hidden"></div>
<!--div class="lf" style="margin-left:40px;_margin-left:20px;font-size:16px;margin-bottom:31px">

Jeevansathi Mobile no. : <b>~$RANDOMNUMBER`</b>
</div-->
<div style="border-style: none none solid; border-color: rgb(182, 182, 182); border-width: 1px;float: left; padding-top: 2px; padding-bottom: 4px;width:365px;height:1px"></div>
~/if`
~if $showRegisterPage`
<script src="~sfConfig::get('app_img_url')`/min/?f=/js/~$seo_community_js`"></script>
<style>
ul.form{margin:0; list-style:none; width:100%; padding:10px;padding-bottom:0px;}
ul.form li{float:left; padding-bottom:11px;*padding-bottom:8px; width:100%;}
ul.form li label{float:left; width:82px;  padding:2px 5px 0 0; color:#4b4b4b;text-align:left}
ul.form li input.txt1{float:left; width:100px; height:17px; border:1px solid #ccc; font-size:12px;}
.sel_sml{width:58px;}
.form_left{padding-left:62px;}
.l_col{color:#3c84c1;}
</style>
<div class="lf" style=" bottom: 0px; left: 10px;margin-left:7px;font-size:16px;margin-bottom:12px">

<b>To contact this user Register Now</b>
</div>
<ul class="form">
	<form name="mini_reg_lead" action="~$SITE_URL`/profile/registration_new.php?source=profminreg&mini_reg=1" method="post" enctype="multipart/form-data">
	<input type="hidden" name="site_url" value='~sfConfig::get("app_site_url")`' />
      <li>
      		<label id="email_err_red">Email  </label>:&nbsp;&nbsp;
		<input type="text" id="email_val" name="email" maxlength="40" style="width:167px;font-size:11px;" /><br />
      </li>
      <div class="clr"></div>
		<div style="display:none" id="email_err">
		<s style="color: rgb(255, 0, 0); font-size: 11px; float: left; text-decoration:none;" id="email_err_txt">Please enter email address in proper format</s>
      </div>
      <li >
        	<label id="mobile_err_red">Mobile No  </label>:&nbsp;&nbsp;
	        <input style="width: 25px;" id="country_Code" name="country_Code" value="+91" type="text" maxlength="4" />
		<input class="ml_6" type="text" style="width:135px;" id="mobile" name="mobile" value="" maxlength="12" onBlur="javascript:getlead();"/>
      </li>
      <div class="clr"></div>
      <p id="mobile_error" style="display:none">
		<s style="color: rgb(255, 0, 0); font-size: 11px; float: left; text-decoration:none;" id="mobile_err_txt"></s>
      </p>
      <li>
      		<label id="match_err_red">Looking For </label>:&nbsp;&nbsp;
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
          <label id="dob_err_txt">Date of Birth </label>:&nbsp;&nbsp;
          <select style="width:55px;font-size:11px;" name="day" id="day">
		 <option selected value="">Day</option>
      	 	 ~foreach $dayArray as $key`				    
		 	    <option value=~$key` >~$key` </option>
      		 ~/foreach`		  	
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
		 ~foreach $yearArray as $key`
		       <option value=~$key` >~$key` </option>     
	        ~/foreach`					 
	 </select>
      <div class="clr"></div>
      </li>
      <li style="margin-bottom:4px">
          <label id="mtongue_err_red">Community </label>:&nbsp;&nbsp;
				<select name="mtongue" id="mtongue" style="width:172px;font-size:11px;">
				      <option value="" selected="selected">Please Select</option>
				~foreach from=CommonFunction::generateMtongueDropdownForTemplate() item=value key=kk`
				<optgroup label="&nbsp;"></optgroup>
                                <optgroup label="~$value['LABEL']`">
				~foreach from=$value['VALUES'] item=value1 key=kk1`
					<option ~if $mtongue eq "~$kk1`"` selected ~/if` value="~$kk1`">~$value1`</option>
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
<div style="padding-left:110px;">
<div  style="font-size:10px;padding-bottom:3px;*margin-top:-15px">By clicking on register you accept<BR> 
		<a href="~$SITE_URL`/P/disclaimer.php" target="_blank" class="l_col">terms and conditions</a>
	</div>
		<input type="submit" class="b green_btn en_btn_clr_alb" value="Register Now" onClick="javascript:form_submit();"  style="border:0;widtH:130px;padding-left:2px" />
	<div style="padding-top:2px;font-weight:bold"><BR>Existing User - <a href="/profile/login.php?SHOW_LOGIN_WINDOW=1" class="thickbox" style="text-decoration:none"><b> Login Here</b></a></div>	
	</div>
	
~/if`

