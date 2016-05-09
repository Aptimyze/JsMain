<style>
body {
margin:0;
padding:0;
font-size:12px;
color:#000000;
font-family:Arial, sans-serif
}
#main {
width:100%;
margin:0px;
float:left;
}
#container {
width: 930px;
margin: 0px auto;
}

.pd2 {
padding:2px 2px 2px 5px;
}
div.row {
clear:both;
padding:3px 0 0px 0;
color:#000;
width:98%;
margin:auto;
}
.child_arr {
margin:5px 5px 0 10px;
}
.mar_top_35 {
margin-top:35px;
}
.search_box {
margin:0;
padding:0;
}
.clearfix{*zoom:1}
.clearfix:before,.clearfix:after{display:table;line-height:0;content:""}
.clearfix:after{clear:both}
.extracss{border-top:1px solid #aaa;}
/*status curves ends  here*/
/*advertisement form starts here*/
.advertise_form{width:98%; padding:0 0 0 15px;}
.advertise_form ul.form{margin:0; padding:0;}
.advertise_form ul.form li{list-style:none; margin-bottom:5px;}
.advertise_form ul.form li.err{margin-bottom:10px;}
.advertise_form ul.form li label.l1{width:200px; text-align:right; float:left; margin-right:10px;}
.advertise_form ul.form li input.txt1{width:190px; height:16px; border:1px solid #ccc;}
.advertise_form ul.form li textarea.txt1{width:278px; height:81px; border:1px solid #ccc;}
.advertise_form .all_addresses{background:#f4f4f4; border:1px solid #eee; padding:10px;}
.clearfix:after {content: ".";display: block;clear: both;visibility: hidden;line-height: 0;height: 0;}
.clearfix {display: inline-block;}
html[xmlns] .clearfix {display: block;}
* html .clearfix {height: 1%;}
/*advertisement form ends here*/

</style>
<script type="text/javascript">
var error =0;
function checknull(value)
{
  if((value != '') && (value != null) && (value != "")) return 1; 
  else return 0; 
}
function checkname(name)
{
    if(!checknull(name))
    {
       $('#b1').css('display','block');
       $('#b1 span#nameerr').html('Please specify Your Name');
       return 0; 
    }
    else 
    {
        $('#b1').css('display','none');
        var nameReg = /^[A-Za-z ]*$/;        
        if (!nameReg.test(name)) 
        {
          $('#b1').css('display','block'); 
          $('#b1 span#nameerr').html('No number or special character(s) are allowed in Name');
          return 0; 
        } 
        else 
        {
           $('#b1').css('display','none'); 
          return 1;
        }
       
      }
}
function checkphn(phn)
{
  if(!checknull(phn))
  {
   $('#d1').css('display','block');
   $('#d1 span#phnerror').html('Please specify Contact Phone');
   return 0;
  } 
  else
  {
    $('#d1').css('display','none');
    var ph_val = phn;
    ph_val=ph_val.replace(/-/g,"");
    ph_val=ph_val.replace(/\+/g,"");
    ph_val=ph_val.replace(/\,/g,"");
    ph_val=ph_val.replace(/\//g,"");
    if($.isNumeric(ph_val)) 
    {

      $('#d1').css('display','none'); 
      return 1;
    }
    else
    {
      $('#d1').css('display','block'); 
      $('#d1 span#phnerror').html('Please specify a valid Phone number');
      return 0; 
    }
  }
}
function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 
function checkemail(email)
{
  if(!checknull(email))
  {
   $('#e1').css('display','block');
   $('#e1 span#emailerror').html('Please specify E-mail');
   return 0;
  }  
  else if (!validateEmail(email)) 
  {
    $('#e1').css('display','block');
    $('#e1 span#emailerror').html('Please specify correct E-mail');
    return 0;

  }
  else
  {
    $('#e1').css('display','none');
    return 1;
  }
}
function checkvalueStr(val_in,val_id)
{
  if(!checknull(val_in))
  {
   $('#'+val_id+'1').css('display','block');
   return 0;
  }  
  else 
  {
    $('#'+val_id+'1').css('display','none');
    return 1;
  }
    
}
// check for onblur
function checkone(val_in, val_id)
{
   if(val_id=="a") checkvalueStr(val_in,val_id);
   if(val_id=="b") checkname(val_in);
   if(val_id=="c") checkvalueStr(val_in,val_id);
   if(val_id=="d") checkphn(val_in);
   if(val_id=="e") checkemail(val_in);
   if(val_id=="f") checkvalueStr(val_in,val_id);
}
function validate(oname,name,addr,phone,email,query)
{
  
  if(!checkvalueStr(oname,'a'));
  if(!checkname(name));
  if(!checkvalueStr(addr,'c'));
  if(!checkphn(phone));
  if(!checkemail(email)); 
  if(!checkvalueStr(query,'f'));
  if((!checkvalueStr(oname,'a'))||(!checkname(name))||(!checkvalueStr(addr,'c'))||(!checkphn(phone))||(!checkemail(email))||(!checkvalueStr(query,'f')))
  return false;
  else return true;
}
$(function(){
  $('#Adsubmit').click(function(){
      var oname = $('#a').val();
      var name=$('#b').val();
      var addr=$('#c').val();
      var phone=$('#d').val();
      var email=$('#e').val();
      var query =$('#f').val();
      var flag = validate(oname,name,addr,phone,email,query);
      console.log(flag);
      if(!(flag)){return false;}
      else return true;
      

  });
 
  $( 'a[href="#"]' ).click( function(e) {
      $('#confirm').css('display','none');
      e.preventDefault();
   } );

});

</script>
<!--Header starts here-->
 <?php include_partial('global/header') ?>
<!--Header ends here-->

<!--pink strip starts here-->
<!--Main container starts here-->

<div id="main_cont">

<div id="container">

<!--pink strip ends here-->
  <p class="clr_4"></p>
<div id="topSearchBand"></div>
<?php include_partial('global/sub_header') ?>
  <p class="clr_4"></p>
<br>
<div class="clear"></div>
~$SUB_HEAD`
<div class="sp16"></div>
<BR>
~if $THNX eq "1"`
<!--start:thanks message -->
 <div id="confirm" class="lf" style="padding:5px; margin:5px 0px; background:#ffffbb">
    <div class="lf">
      <img src="~$IMG_URL`/img_revamp/green_tick_mark.gif" hspace="10" vspace="0" align="absmiddle">
    </div>
    <div class="lf t14 b pad7top pad10left" style="width:690px;">
      Your request has been sent.
    </div>
    <div class="rf b">
      <a href='#' id='hide'>[x]</a>
    </div>
 </div>
 <div class="clr" style="height:10px;"></div> 
 <!--end:thanks message --> 
 ~/if`
 <h1>Advertise with us</h1>
~if $THNX eq "1"`
<!--start:thanks message -->
 <div class="clr"></div> 
 <div>
    <div class="lf t14 b" style="margin-top:20px;">
         <div class="lf t14 b" style="margin-top:20px;">Dear Customer,<br>
          <div style="margin:20px 0 0;">Thank You for selecting us for your Business/Brand promotion.</div>
          We will soon get back to you.
          <div style="margin:20px 0 0;">Thanks<br><br>Jeevansathi team</div>
        </div>
    </div>
 </div>
 <div class="clr"></div> 
 <!--end:thanks message -->
 ~/if`

~if $THNX neq "1"`
<div class="b pad5top pad5bottom pad5left">Advertise your Business / Brand on our site</div>
<div class="pad10top pad10bottom pad5left">Fields marked * are compulsory</div>
<div class="lf" style="width: 750px;">

	<div class="advertise_form lf">
		<ul class="form">
			<form name="form1" method="post" action="~$SITE_URL`/static/advertise">
     
				<!-- start:organization name -->
                <li>~$form['organisation']->renderLabel(null,['class'=>'l1 b'])`
                ~$form['organisation']->render(['id'=>'a','class'=>'txt1','onblur'=>'checkone(this.value,this.id)'])`</li>
                <li class="err" id='a1' style="display:none">
                    <label class="l1">&nbsp;</label>
                    <img src="~$IMG_URL`/img_revamp/alert.gif" alt="error" title="error" align="absmiddle">&nbsp;&nbsp;
                    <span class="red t11">Please specify Organisation / Company Name</span>
                 </li>
                <!-- end:organization name -->

                <!-- start:name -->
                <li>~$form['name']->renderLabel(null,['class'=>'l1 b'])`
                ~$form['name']->render(['id'=>'b','class'=>'txt1','onblur'=>'checkone(this.value,this.id)'])`</li>
                <li class="err"  id='b1' style="display:none">
                        <label class="l1">&nbsp;</label>
                        <img src="~$IMG_URL`/img_revamp/alert.gif" alt="error" title="error" align="absmiddle">&nbsp;&nbsp;
                        <span class="red t11" id="nameerr"></span>
                </li>                
                <!-- end:name -->

                <!-- start:business -->
                <li>~$form['business']->renderLabel(null,['class'=>'l1 b'])`
                ~$form['business']->render(['id'=>'bname','class'=>'txt1'])`</li>
                <!-- end:business-->

                 <!-- start:address -->
                 <li>~$form['address']->renderLabel(null,['class'=>'l1 b'])`
                ~$form['address']->render(['id'=>'c','class'=>'txt1','rows'=>'2','cols'=>'2','onblur'=>'checkone(this.value,this.id)'])`</li>
                <li class="err"  id='c1' style="display:none">
                    <label class="l1">&nbsp;</label>
                    <img src="~$IMG_URL`/img_revamp/alert.gif" alt="error" title="error" align="absmiddle">&nbsp;&nbsp;
                    <span class="red t11">Please specify Contact Address</span>
                </li>
                <!-- end:address -->

                 <!-- start:phone -->
                 <li>~$form['phone']->renderLabel(null,['class'=>'l1 b'])`
                ~$form['phone']->render(['id'=>'d','class'=>'txt1','onblur'=>'checkone(this.value,this.id)'])`</li>
                <li>
                  <label class="l1">&nbsp;</label>
                  <span class="t11">(Do not add +91 in mobiles numbers, for landlines use "STD Code-Number" format) </span>
                </li>
                <li class="err"  id='d1' style="display:none">
                    <label class="l1">&nbsp;</label>
                    <img src="~$IMG_URL`/img_revamp/alert.gif" alt="error" title="error" align="absmiddle">&nbsp;&nbsp;
                    <span class="red t11" id="phnerror"></span>
                </li>               
                <!-- end:phone -->

                 <!-- start:email -->
                <li>~$form['email']->renderLabel(null,['class'=>'l1 b'])`
                ~$form['email']->render(['id'=>'e','class'=>'txt1','onblur'=>'checkone(this.value,this.id)'])`</li>   
                <li class="err"  id='e1' style="display:none">
                    <label class="l1">&nbsp;</label>
                    <img src="~$IMG_URL`/img_revamp/alert.gif" alt="error" title="error" align="absmiddle">&nbsp;&nbsp;
                    <span class="red t11" id="emailerror"></span>
                </li>                  
                <!-- end:email -->

                 <!-- start:details -->
                <li>~$form['details']->renderLabel(null,['class'=>'l1 b'])`
                ~$form['details']->render(['id'=>'f','class'=>'txt1','rows'=>'2','cols'=>'2','onblur'=>'checkone(this.value,this.id)'])`</li>
                <li class="err"  id='f1' style="display:none">
                    <label class="l1">&nbsp;</label>
                    <img src="~$IMG_URL`/img_revamp/alert.gif" alt="error" title="error" align="absmiddle">&nbsp;&nbsp;
                    <span class="red t11" id="detailerror">Please specify Details / Queries about advertisement</span>
                </li>  
                <!-- end:details -->
                ~$form['_csrf_token']->render()`
                ~$form->renderGlobalErrors()`
                <li>
                    <label class="l1">&nbsp;</label>
                    <span class="t11">Enter the correct information above and then, enter the letters as they are shown in image. </span>
                </li>
                 <li class="clearfix">
                    <label class="l1">&nbsp;</label>
                    <span class="t11">~$RECAP|decodevar`</span>
                </li>

                ~if $CAPFLAG eq "1"`
                 <li class="err clearfix">
                    <label class="l1">&nbsp;</label>
                    <img src="~$IMG_URL`/img_revamp/alert.gif" alt="error" title="error" align="absmiddle">&nbsp;&nbsp;
                    <span class="red t11">Please enter the correct captha</span>
                </li>
                ~/if`

                <li>
                    <label class="l1">&nbsp;</label>
                    <input type="submit" name="submit" id="Adsubmit" class="green_btn_new b" value="Submit" style="width:80px;">
                </li>

			</form>
		</ul>
	</div>

 
</div>
~/if`
~include_partial("successStory/rightPanel",[rightPanelStory=>"$rightPanelStory",loginData=>"$loginData",bms_1=>"$bms_1",bms_2=>"$bms_2"])`


</div>
</div>


~include_partial('global/footer',[NAVIGATOR=>~$NAVIGATOR`,bms_topright=>$bms_topright,bms_bottom=>$bms_bottom,G=>$G,viewed_gender=>$GENDER,data=>''])`
