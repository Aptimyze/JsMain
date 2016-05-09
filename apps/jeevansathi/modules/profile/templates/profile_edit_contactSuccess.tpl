<style>
.rsp{padding-top:6px;}
#box {width:285px; height:auto; background-color:#fdea66;  border:1px solid #f4d95a;
padding:15px 17px; font-family:Arial, Helvetica, sans-serif; color:#343434}
#box div {float:left !important;}
.fs12 { font-size:12px;} .fs11 { font-size:11px;list-style-type:decimal;}
.b { font-weight:bold;} .i { font-style:italic}
.sp5_help{ clear:both; display:block; overflow:hidden; height:5px;}
.fl_help{float:left !important;}
.black {color:#000;}.red_new {color:#e93a3e !important}
.list_imp {margin-left:17px; _margin-left:22px; clear: left; display: block;list-style: outside;}
*+html .list_imp {margin-left:22px;}
.text_new_help {color:#ee0002; position:relative !important; top:-4px !important;margin-bottom:-16px; margin-left:5px !important;left:-7px !important; width:10px !important;}
span.arrw_help {display: block !important;position: relative !important;right: 14px !important;top: 94px !important;z-index: 1000 !important; width:13px !important;}

.ie_mess_id_err {
left: -283px;
float: left;
position: relative;
display: inline;
}

.ie_mess_chnl_err {
left: -118px;
float: left;
position: relative;
display: inline;
}

.no_ie_mess_id_err {
margin-left: 203px;
float: left;
padding-left: 0;
display: inline;
position: relative;
}

.no_ie_mess_chnl_err {
margin-left: 203px;
float: left;
padding-left: 165px;
display: inline;
position: relative;
}

div.row3 label.grey, div.row4 label.grey {vertical-align:top;color:#797979!important; font-size:13px;padding:0px 8px 0px 0px!important; }
</style>
<style type="text/css">
input.blur {
color: #808080;
}
</style>
<script>
~if $CALL_NOW`$("#dont_show,#dont_show1,#ALT_Showmobile").hover(function(){show_help_box(1);},function(){show_help_box(2);});~/if`
var docF=document.form1;
$(document).ready(function() {
        populate_city(docF.city_residence_selected.value);
        //global vars
        var MId = $("#Messenger_ID");
        var aMId = $("#Alt_Messenger_ID");
        var MCh = $("#mess_channel");
        var aMCh = $("#alt_mess_channel");
        var EMId = $("#js_messenger_id_error");
        var aEMId = $("#js_alt_messenger_id_error");
        var alt_err_msg = $("#alt_error_message");
        var ht = "e.g. raj1983, vicky1980 ";

        EMId.html("");
        aEMId.html("");
        MId.tbHinter({
            text: 'e.g. raj1983, vicky1980 ',
            styleClass: 'blur'
        });

        aMId.tbHinter({
            text: 'e.g. raj1983, vicky1980 ',
            styleClass: 'blur'
        }); 

$(function () {
        $.checkId=function(mess_id, err_fld) {
            alt_err_msg.css('display', 'none');
            var result = check_messenger_id(mess_id.val());
            if(result.value != "0") {

                if ($.browser.msie) {
                    err_fld.removeClass("ie_mess_chnl_err").addClass("ie_mess_id_err");
                } else {
                    err_fld.removeClass("no_ie_mess_chnl_err").addClass("no_ie_mess_id_err");
                }
                showError(mess_id, result.reason);
            } else {
                showError(mess_id, "");
            }
        }
        });

$(function () {
        var msie = $.browser.msie;
        alt_err_msg.css('display', 'none');
        $.checkCh=function(mess_id, chnl, err_fld) {
            var result = is_messenger_channel_selected(mess_id.val(), chnl.val());
            if (result.value != "0") {
                if (msie) {
                    switch(result.value) {
                    case "4": 
                        err_fld.removeClass("ie_mess_id_err").addClass("ie_mess_chnl_err");
                    break;
                    case "1":
                    case "2":
                    case "3":
                    case "5":
                        err_fld.removeClass("ie_mess_chnl_err").addClass("ie_mess_id_err");
                    break;
                }
            } else {
                switch(result.value) {
                    case "4":
                        err_fld.removeClass("no_ie_mess_id_err").addClass("no_ie_mess_chnl_err");
                    break;
                    case "1":
                    case "2":
                    case "3":
                    case "5":
                        err_fld.removeClass("no_ie_mess_chnl_err").addClass("no_ie_mess_id_err");
                    break;
                }
            }
            showError(chnl, result.reason);
        } else {
            if (msie) {
                err_fld.removeClass("ie_mess_id_err");
                err_fld.removeClass("ie_mess_chnl_err");
            } else {
                err_fld.removeClass("no_ie_mess_id_err");
                err_fld.removeClass("no_ie_mess_chnl_err");
            }
            err_fld.html("");
        }
        }
});

MId.blur(function () {
        $.checkId(MId, EMId);
        });
aMId.blur(function () {
        $.checkId(aMId, aEMId);
        });

MCh.blur(function () {
        $.checkCh(MId, MCh, EMId);
        });

aMCh.blur(function () {
        $.checkCh(aMId, aMCh, aEMId);
        });

function showError(context, reason) {
    var msie = $.browser.msie;
    switch(context) {
        case MId:
        case MCh:
            EMId.html(reason);
            break;
        case aMId:
        case aMCh:
            aEMId.html(reason);
            break;
    }
}
});



function check_alternate()
{
    var alternate_mobile_number=dID('ALT_MOBILE').value;
    var mobile=dID('Mobile').value;
    alternate_mobile_number=alternate_mobile_number.substr(-10);

    if(alternate_mobile_number!='' && alternate_mobile_number==mobile){
        dID('alt_mobile_error').style.display='block';
        return false;
    }
    else {
        dID('alt_mobile_error').style.display='none';
        return true;	
    }
}

function show_help_box(para)
{
    if(para==1){
        dID('helpbox').style.display='block';
    }
    else
        dID('helpbox').style.display='none';
}

function show_alter(field,action)
{
    dID(field).style.display="block";
    if(field=='alter_mobile_section')
    {
        dID('add_more_mobile').style.display="none";
        dID('hide_more_mobile').style.display="block";
    }
    else if(field=='alter_messenger_section')
    {
        dID('add_more_messenger').style.display="none";
        dID('hide_more_messenger').style.display="block";
    }

    if(action=='HIDE' && field=='alter_mobile_section')
    {
        dID(field).style.display="none";
        dID('add_more_mobile').style.display="block";
        dID('hide_more_mobile').style.display="none";
        dID('ALT_MOBILE_NUMBER_OWNER').value="~if $GENDER eq  'F'`1~else`2~/if`";
        dID('ALT_MOBILE').value="";
        dID('ALT_MOBILE_OWNER_NAME').value="";
        dID('ALT_MOBILE_ISD').value=document.getElementById('country_code').value;
    }
    else if(action=='HIDE' && field=='alter_messenger_section')
    {
        dID(field).style.display="none";
        dID('hide_more_messenger').style.display="none";
        dID('add_more_messenger').style.display="block";
        dID('Alt_Messenger_ID').value="";
        dID('alt_mess_channel').value="";
    }
}
~if $ALT_MOBILE`
show_alter("alter_mobile_section");
~/if`
~if $ALT_MESSENGER_ID`
show_alter("alter_messenger_section");
    ~/if`
function validate()
{
    var err=0;
    var email_id=dID('email').value;
    var theStr = new String(email_id);
    var index = theStr.indexOf("@")+1;
    var index1 = theStr.indexOf(".");
    if(theStr.substring(index,index1)=='jeevansathi' && dID('source').value!='ofl_prof')
    {
        dID('img_avail').style.display="none";
        dID('my_inemail').style.display="none";
        dID('my_email').style.display="none"; 
        dID('contain_js').style.display="block";
        dID('email').focus();
        return false;
    }
    else if(!checkemail(email_id))
    {
        dID('inv').value=1;
        dID('my_inemail').style.display="none";
        dID('my_email').style.display="none";
        dID('contain_js').style.display="none";
        dID('email').focus();
        return false;
    }

    if(dID('junk').value=='JM'){
        dID('mobile_in_name_span').style.display="none";
        dID('mobile_span').style.display="block";
        dID('international_mobile_span').style.display="none";
        dID('Mobile').focus();
        err++;
    }

    if(dID('junk').value=='JL'){
        dID('phone_in_name_span').style.display="none";
        dID('phone_span').style.display="block";
        dID('Phone').focus();
        err++;
    }
    if(!check_alternate())
        err++;
    var country_code = dID('country_code').value;
    if(country_code=='+91'){
        if(!check_pincode(1))
            err++;
        if(!check_pincode(2))
            err++;
    }
	if(isd_verify_on_submit())
	 err++;
    var phone_mob = validate_phone_mobile(dID('Phone').value,document.getElementById('Mobile').value,document.getElementById('ALT_MOBILE').value);
    if(phone_mob != "OK")
    {	
        if(phone_mob == "PM")
        {
            dID('phone_in_name_span').style.display="none";
            dID('mobile_in_name_span').style.display="none";
            dID('phone_span').style.display="block";
            dID('mobile_span').style.display="block";
        }
        else if(phone_mob == "P")
        {
            dID('phone_in_name_span').style.display="none";
            dID('phone_span').style.display="block";
            dID('Phone').focus();
        }
        else if(phone_mob == "S")
        {
            dID('state_code_span').style.display="block";
            dID('state_code').focus();
        }
        else if(phone_mob == "M")
        {
            dID('mobile_in_name_span').style.display="none";
            dID('mobile_span').style.display="block";
            dID('international_mobile_span').style.display="none";
            dID('Mobile').focus();
        }
        else if(phone_mob == "AM")
        {
            dID('alt_mobile_in_name_span').style.display="none";
            dID('alt_mobile_span').style.display="block";
            dID('alt_international_mobile_span').style.display="none";
            dID('ALT_MOBILE').focus();
        }
        else if(phone_mob == "IM")
        {
            dID('mobile_in_name_span').style.display="none";
            dID('mobile_span').style.display="none";
            dID('international_mobile_span').style.display="block";
            dID('Mobile').focus();
        }
        else if(phone_mob == "IAM")
        {
            dID('alt_mobile_in_name_span').style.display="none";
            dID('alt_mobile_span').style.display="none";
            dID('alt_international_mobile_span').style.display="block";
            dID('ALT_MOBILE').focus();
        }
        err++;
    }
    else
    {
        if((dID('PHONE_OWNER_NAME').value == '')&&(document.getElementById('Phone').value != ''))
        {
            dID('phone_span').style.display="none";
            dID('phone_in_name_span').style.display="none";
            dID('phone_name_span').style.display="block";
            dID('PHONE_OWNER_NAME').focus();
            err++;
        }
        else if((dID('MOBILE_OWNER_NAME').value == '')&&(document.getElementById('Mobile').value != ''))
        {	
            dID('phone_span').style.display="none";
            dID('phone_name_span').style.display="none";
            dID('phone_in_name_span').style.display="none";
            dID('mobile_in_name_span').style.display="none";
            dID('mobile_span').style.display="none";
            dID('mobile_name_span').style.display="block";
            dID('MOBILE_OWNER_NAME').focus();
            err++;
        }
        else if((dID('ALT_MOBILE_OWNER_NAME').value == '')&&(document.getElementById('ALT_MOBILE').value != ''))
        {	
            dID('phone_span').style.display="none";
            dID('phone_name_span').style.display="none";
            dID('phone_in_name_span').style.display="none";
            dID('mobile_in_name_span').style.display="none";
            dID('mobile_span').style.display="none";
            dID('mobile_name_span').style.display="none";

            dID('alt_mobile_in_name_span').style.display="none";
            dID('alt_mobile_span').style.display="none";
            dID('alt_mobile_name_span').style.display="block";

            dID('ALT_MOBILE_OWNER_NAME').focus();
            err++;
        }
        else 
        {
            var x = dID('PHONE_OWNER_NAME').value;
            var y = dID('MOBILE_OWNER_NAME').value;
            var z = dID('ALT_MOBILE_OWNER_NAME').value;
            var lwr = "abcdefghijklmnopqrstuvwxyz ";
            var upr = "ABCDEFGHIJKLMNOPQRSTUVWXYZ ";
            if(!isValid(x,lwr+upr))
            {
                dID('phone_span').style.display="none";
                dID('phone_name_span').style.display="none";	
                dID('phone_in_name_span').style.display="block";
                dID('PHONE_OWNER_NAME').focus();
                err++;
            }
            else if(!isValid(y,lwr+upr))
            {       
                dID('mobile_span').style.display="none";
                dID('mobile_name_span').style.display="none";
                dID('phone_in_name_span').style.display="none";
                dID('mobile_in_name_span').style.display="block";
                dID('MOBILE_OWNER_NAME').focus();
                err++;
            }
            else if(!isValid(z,lwr+upr))
            {       
                dID('mobile_span').style.display="none";
                dID('mobile_name_span').style.display="none";
                dID('phone_in_name_span').style.display="none";
                dID('mobile_in_name_span').style.display="none";
                dID('alt_mobile_in_name_span').style.display="block";
                dID('ALT_MOBILE_OWNER_NAME').focus();
                err++;
            }
        }
    }
    var err1 = 0, err2 = 0;
    var result1 = is_messenger_channel_selected(dID('Messenger_ID').value, dID('mess_channel').value);
    var msie = $.browser.msie;
    var EMId = $("#js_messenger_id_error");
    if (result1.value != "0") {
        if (msie) {
            switch(result1.value) {
                case "4":
                    EMId.removeClass("ie_mess_id_err").addClass("ie_mess_chnl_err");
                    break;
                case "1":
                case "2":
                case "3":
                case "5":
                    EMId.removeClass("ie_mess_chnl_err").addClass("ie_mess_id_err");
                    break;
            }
        } else {
            switch(result1.value) {
                case "4":
                    EMId.removeClass("no_ie_mess_id_err").addClass("no_ie_mess_chnl_err");
                    break;
                case "1":
                case "2":
                case "3":
                case "5":
                    EMId.removeClass("no_ie_mess_chnl_err").addClass("no_ie_mess_id_err");
                    break;
            }
        }

        EMId.html(result1.reason);
        err1 = 1;
    } else {
        if (dID('Messenger_ID').value.indexOf("@") != -1) {
            var temp = dID('Messenger_ID').value.split("@");
            dID('Messenger_ID').value = temp[0];
        }
        if (dID('Messenger_ID').value == "e.g. raj1983, vicky1980 ") {
            dID('Messenger_ID').value = "";
            dID('mess_channel').value = "";
        }
        err1 = 0;
    }
    var aEMId = $("#js_alt_messenger_id_error");
    var result2 = is_messenger_channel_selected(dID('Alt_Messenger_ID').value, dID('alt_mess_channel').value);
    if (result2.value != "0") {
        if (msie) {
            switch(result2.value) {
                case "4":
                    aEMId.removeClass("ie_mess_id_err").addClass("ie_mess_chnl_err");
                    break;
                case "1":
                case "2":
                case "3":
                case "5":
                    aEMId.removeClass("ie_mess_chnl_err").addClass("ie_mess_id_err");
                    break;
            }
        } else {
            switch(result2.value) {
                case "4":
                    aEMId.removeClass("no_ie_mess_id_err").addClass("no_ie_mess_chnl_err");
                    break;
                case "1":
                case "2":
                case "3":
                case "5":
                    aEMId.removeClass("no_ie_mess_chnl_err").addClass("no_ie_mess_id_err");
                    break;
            }
        }
        aEMId.html(result2.reason);
        err2 = 1;
    } else {
        if (dID('Alt_Messenger_ID').value.indexOf("@") != -1) {
            var temp = dID('Alt_Messenger_ID').value.split("@");
            dID('Alt_Messenger_ID').value = temp[0];
        }
        if (dID('Alt_Messenger_ID').value == "e.g. raj1983, vicky1980 ") {
            dID('Alt_Messenger_ID').value = "";
            dID('alt_mess_channel').value = "";
        }
        err2 = 0;
    }

    if ((dID('Messenger_ID').value != "") && (dID('Alt_Messenger_ID').value != ""))
        if ((dID('Messenger_ID').value == dID('Alt_Messenger_ID').value) && (dID('mess_channel').value == dID('alt_mess_channel').value)) {
            dID('alt_error_message').style.display = 'inline';
            return false;
        } else {
            dID('alt_error_message').style.display = 'none';
        }
    if(err || err1 || err2) {
        return false;
    }
    else
        return true;
}
function isValid(parm,val) {
    if (parm == "") return true;
    for (i=0; i<parm.length; i++) {
        if (val.indexOf(parm.charAt(i),0) == -1) return false;
    }
    return true;
}
function checkemail(emailadd)
{
    var result = false;
    var theStr = new String(emailadd);
    var index = theStr.indexOf("@");
    if (index > 0)
    {
        var pindex = theStr.indexOf(".",index);
        if ((pindex > index+1) && (theStr.length > pindex+2))
            result = true;
    }
    return result;
}
function validate_phone_mobile(phone,mobile,alter_mobile)
{
    var country_val = dID('country_code').value;
    std_code=dID('state_code').value;
    if((phone=="") && (mobile==""))
    {	
        return "PM";
    }
    else
    {	
        if(phone!="")
        {
            if(phone.length < 6)
            {
                return "P";
            }
            var x = phone;
            var filter  = /^[0-9]+$/;
            if (!filter.test(x))
            {
                return "P";
            }
            if(!filter.test(std_code))
                return "S";
            //if(dID('junk').value=='JL')
            //	return "P";
        }
        if(mobile!="")
        {
            if(mobile.length != 10 && country_val == "+91")
                return "M";
            else if(mobile.length < 5 && country_val != "+91")
                return "IM";
            var x = mobile;
            var filter  = /^[0-9]+$/;
            if (!filter.test(x))
            {
                return "M";
            }
        }

        if(alter_mobile!="")
        {
            if(alter_mobile.length != 10 && country_val == "+91")
                return "AM";
            else if(alter_mobile.length < 5 && country_val != "+91")
                return "IAM";
            var x = alter_mobile;
            var filter  = /^[0-9]+$/;
            if (!filter.test(x))
            {
                return "AM";
            }
        }
    }
    return "OK";
}
function isd_verify_on_submit(){
  var err_elem=$("#isd_change_src").val();
  var isd_code=$("#country_code").val();
  var err=isd_check(isd_code);
  if(err_elem=="")
    err_elem='phone';
  var span_id="#"+err_elem;
  var err_exist=false;
  switch(err){
    case "IP":
	 span_id=span_id+"_isd_span";
	 $(span_id).css("display","block");
	 err_exist=true;
	break;
	case "IV":
	 span_id=span_id+"_isd_valid_span";
	 $(span_id).css("display","block");
	 err_exist=true;
	break;
	default:
	  $("#phone_isd_span").css("display","none");
	  $("#phone_isd_valid_span").css("display","none");
	  $("#mobile_isd_span").css("display","none");
	  $("#moble_isd_valid_span").css("display","none");
	  $("#alt_mobile_isd_span").css("display","none");
	  $("#alt_mobile_isd_valid_span").css("display","none");
  }
  return err_exist;
}
function isd_verify(pos,elem){
    var isd_code=elem.value;
	var res=isd_check(isd_code);
	switch(pos){
	case "P":
	  $("#mobile_isd_span").css("display","none");
	  $("#mobile_isd_valid_span").css("display","none");
	  $("#alt_mobile_isd_span").css("display","none");
	  $("#alt_mobile_isd_valid_span").css("display","none");
	  switch(res){
	  case "IP":
	  $("#phone_isd_span").css("display","block");
	  $("#phone_isd_valid_span").css("display","none");
	  break;
	  case "IV":
	  $("#phone_isd_span").css("display","none");
	  $("#phone_isd_valid_span").css("display","block");
	  break;
	  default:
	  $("#phone_isd_span").css("display","none");
	  $("#phone_isd_valid_span").css("display","none");
	  break;
	  }
	break;
	case "M":
	  $("#phone_isd_span").css("display","none");
	  $("#phone_isd_valid_span").css("display","none");
	  $("#alt_mobile_isd_span").css("display","none");
	  $("#alt_mobile_isd_valid_span").css("display","none");
	  switch(res){
	  case "IP":
	  $("#mobile_isd_span").css("display","block");
	  $("#mobile_isd_valid_span").css("display","none");
	  break;
	  case "IV":
	  $("#mobile_isd_valid_span").css("display","block");
	  $("#mobile_isd_span").css("display","none");
	  break;
	  default:
	  $("#mobile_isd_span").css("display","none");
	  $("#mobile_isd_valid_span").css("display","none");
	  break;
	  }
	break;
	case "AM":
	  $("#phone_isd_span").css("display","none");
	  $("#phone_isd_valid_span").css("display","none");
	  $("#mobile_isd_span").css("display","none");
	  $("#mobile_isd_valid_span").css("display","none");
	  switch(res){
	  case "IP":
	  $("#alt_mobile_isd_span").css("display","block");
	  $("#alt_mobile_isd_valid_span").css("display","none");
	  break;
	  case "IV":
	  $("#alt_mobile_isd_valid_span").css("display","block");
	  $("#alt_mobile_isd_span").css("display","none");
	  break;
	  default:
	  $("#alt_mobile_isd_span").css("display","none");
	  $("#alt_mobile_isd_valid_span").css("display","none");
	  break;
	  }
	break;
	}
}
function isd_check(isd_code){
				if(isd_code=="")
				 return "IP";
				var isd_filter=/^[+0-9][0-9]*$/;
				if(!isd_filter.test(isd_code))
					return "IV";
				isd_code=isd_code.replace("+","");
				var isd_zero_filter=/^0+/;
				if(isd_zero_filter.test(isd_code)){
					var isd_zero_match=isd_code.match(isd_zero_filter);
					var leading_zeros=isd_zero_match[0];
					isd_code=isd_code.replace(leading_zeros,"");
				}
				if(isd_code.length>3 || isd_code.length==0)
					return "IV";
				return "OK";
}
function phoneJCheck(type)
{
    var phone_mob = validate_phone_mobile(dID('Phone').value,document.getElementById('Mobile').value,document.getElementById('ALT_MOBILE').value);
    if(phone_mob != "OK")
    {       
        if(phone_mob == "PM")
        {
            dID('phone_in_name_span').style.display="none";
            dID('mobile_in_name_span').style.display="none";
            dID('phone_span').style.display="block";
            dID('mobile_span').style.display="block";
            dID('state_code_span').style.display="none";
        }
        else if(phone_mob == "P")
        {
            dID('phone_in_name_span').style.display="none";
            dID('phone_span').style.display="block";
            dID('Phone').focus();
        }
        else if(phone_mob == "S")
        {
            dID('state_code_span').style.display="block";
            dID('mobile_span').style.display="none";
            dID('state_code').focus();
        }
        else if(phone_mob == "M")
        {
            dID('mobile_in_name_span').style.display="none";
            dID('state_code_span').style.display="none";
            dID('mobile_span').style.display="block";
            dID('international_mobile_span').style.display="none";
            dID('Mobile').focus();
        }
        else if(phone_mob == "AM")
        {
            dID('alt_mobile_in_name_span').style.display="none";
            dID('alt_mobile_span').style.display="block";
            dID('alt_international_mobile_span').style.display="none";
            dID('ALT_MOBILE').focus();
        }
        else if(phone_mob == "IM")
        {
            dID('mobile_in_name_span').style.display="none";
            dID('mobile_span').style.display="none";
            dID('international_mobile_span').style.display="block";
            dID('Mobile').focus();
        }
        return false;
    }
    to_send_ajax_req=true;
    if(type=='L')
        phone =dID('Phone').value;
    else if(type=='M')
        phone =dID('Mobile').value;
    else if(type=='AM')
        phone =dID('ALT_MOBILE').value;
    if(phone == '')
        to_send_ajax_req=false;
    if(to_send_ajax_req){	
        var str ="&phone="+phone+"&type="+type;

        dID('img_sav').style.display="none";        
        dID('img_test1').style.display="block";

        request_url = "~$SITE_URL`/profile/edit_profile.php?Junkcheck=1"+str;
        $.ajax({
url: request_url,
success: function(data){
show_junk_number(data);
}
});
}

dID('alt_mobile_in_name_span').style.display="none";
dID('alt_mobile_span').style.display="none";
dID('alt_international_mobile_span').style.display="none";
dID('alt_mobile_name_span').style.display="none";
dID('mobile_in_name_span').style.display="none";
dID('mobile_span').style.display="none";
dID('international_mobile_span').style.display="none";
dID('phone_in_name_span').style.display="none";
dID('phone_span').style.display="none";
dID('mobile_name_span').style.display="none";
dID('phone_name_span').style.display="none";
dID('state_code_span').style.display="none";
}
function show_junk_number(response)
{
    dID('junk').value='';
    dID('mobile_span').style.display="none";
    dID('alt_mobile_span').style.display="none";
    dID('phone_span').style.display="none";
    if(response=='JM'){
        dID('mobile_span').style.display="block";
    }
    else if (response=='JAM'){
        dID('alt_mobile_span').style.display="block";
    }
    else if(response=='JL')	
        dID('phone_span').style.display="block";
    if(response !='NJ')
        dID('junk').value=response;

    dID('img_test1').style.display="none";
    dID('img_sav').style.display="block";
    return false;
}
function change_city()
{
    var country_code = dID('country_residence').value;
    request_url = "~$SITE_URL`/profile/edit_profile.php?Only_city=1&Country_code="+country_code;
    sendRequest('GET',request_url);
}
function show_code()
{
    var city_code = dID('City_arr').value;
    request_url = "~$SITE_URL`/profile/edit_profile.php?Only_city2=1&City_code="+city_code;
    sendRequest('GET',request_url);
}
function invalidDomain(value)
{
	var invalidDomainArr = new Array("jeevansathi", "dontreg","mailinator","mailinator2","sogetthis","mailin8r","spamherelots","thisisnotmyrealemail","jndhnd","jsxyz");
	var start = value.indexOf('@');
	var end = value.lastIndexOf('.');
	var diff = end-start-1;
	var user = value.substr(0,start);
	var len = user.length;
	
	var domain = value.substr(start+1,diff).toLowerCase();
	
	if(jQuery.inArray(domain.toLowerCase(),invalidDomainArr) !=  -1)
		return false;
	else if(domain == 'gmail')
	{
		if(!(len >= 6 && len <=30))
			return false;
	}
	else if(domain == 'yahoo' || domain == 'ymail' || domain == 'rocketmail' )
	{
		if(!(len >= 4 && len <=32))
			return false;
	}
	else if(domain == 'rediff')
	{
		if(!(len >= 4 && len <=30))
			return false;
	}
	else if(domain == 'sify')
	{
		if(!(len >= 3 && len <=16))
			return false;
	}
	
	return true;
}
function show_lo()
{
	dID('img_avail').style.display="none";
	dID('my_inemail').style.display="none";
	dID('my_exemail').style.display="none";
	dID('my_email').style.display="none"; 
	dID('contain_js').style.display="none";
    var email_id=dID('email').value;
    email_id = $.trim(email_id);
    var theStr = new String(email_id);
    theStr = $.trim(theStr).toLowerCase();
    var index = theStr.indexOf("@")+1;
    //var index1 = theStr.indexOf(".");
    if(email_id == '')
    {
        dID('inv').value=1;
        dID('img_avail').style.display="none";
        dID('my_inemail').style.display="none";
        dID('my_exemail').style.display="none";
        dID('my_email').style.display="block"; 
        dID('contain_js').style.display="none";
        dID('email').focus();
        return false;
    }
    else if(!invalidDomain(theStr) && dID('source').value!='ofl_prof')
    {
        dID('inv').value=1;
        dID('img_avail').style.display="none";
        dID('my_exemail').style.display="none";
        dID('my_inemail').style.display="none";
        dID('my_email').style.display="none"; 
        dID('contain_js').style.display="block";
        dID('email').focus();
        return false;
    }
    else    
    {
        dID('img_avail').style.display="none";
        dID('img_sav').style.display="none";        
        dID('img_test1').style.display="block";
        request_url = "~$SITE_URL`/profile/edit_profile.php";
        var emailPost="verify_email=1&email_id="+email_id;
        sendRequest('POST',request_url,'',emailPost);
        var filter  = /^([A-Z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
        if(!filter.test(email_id))
        {
            dID('inv').value=1;
            dID('img_avail').style.display="none";
            dID('my_email').style.display="none";
            dID('my_inemail').style.display="block";
            dID('my_exemail').style.display="none";
            dID('contain_js').style.display="none";
            dID('email').focus();
            return false;
        }
        else
        {
            dID('inv').value=0;
            dID('img_avail').style.display="block";
            dID('my_email').style.display="none";
            dID('my_inemail').style.display="none";
            dID('contain_js').style.display="none";
        }
    }
}
function closeLayer_changeCity()
{
    $.colorbox.close();
    window.location="/profile/viewprofile.php?ownview=1&EditWhatNew=JST2";
}
</script>
~if $sf_request->getParameter('EmailDup') eq 1`
<script>
dID('email').value=document.getElementById('DupEmail').value;
~if $sf_request->getParameter('invalid_email') eq 1`
dID('my_inemail').style.display="block"; 
~else`
dID('my_exemail').style.display="block";
~/if`
</script>
~/if`
<div class="edit_scrollbox2_1">
~$sf_data->getRaw('hiddenInput')`

<div class="row4 no-margin-padding width100">
<label class="grey"><i class="btn-archive"></i> Email ID :</label>

<span style="width:70%;"><input type="text" name="Email" value="~$EMAIL`" id="email" onblur="show_lo();"><input type="hidden" id="DupEmail" value="~$sf_request->getParameter('DupEmail')`"><input type="hidden" id="source" value="~$source`">
<div id="img_avail" style="display:none;">
<span style="color: rgb(11, 136, 5); padding-left: 4px;vertical-align: bottom;">
<img src="~$IMG_URL`/profile/images/registration_new/grtick.gif"/>
Available
</span>
</div>
<input type="hidden" id="inv" value="">
<input type="hidden" id="junk" value="">
<div class="red_new" id="my_email" style="display:none;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>
&nbsp;Please provide an email.
</div>
<div class="red_new" id="my_inemail" style="display:none;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>
&nbsp;Provide your email in proper format, e.g. raj1984@gmail.com
</div>
<div class="red_new" id="my_exemail" style="display:none;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>
&nbsp;There is an existing Profile with the email address you have entered.<br/>Please input another alternative email address.
</div>
<div class="red_new" id="contain_js" style="display:none;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>
&nbsp;Provide your email in proper format, e.g. raj1984@gmail.com
</div>
</span>
</div>

<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding width100">
<label class="grey">Country living in :</label>
<div id="county_arr">
<span><select  name="country_residence" id="country_residence" onchange="display_city_dd();">
~$sf_data->getRaw('COUNTRY_RES')`
</select></span></div>
</div>
<div class="sp15">&nbsp;</div>
<div id="city_padding"></div>
<input type="hidden" name="city_residence_selected" value="~$CITY_SELECTED`" />
<div class="row4  no-margin-padding width100" id="city_res_show_hide">
<label class="grey">City living in :</label>
<span id="city_india_visible" style="display:block">
<select style="width:185px;" name="city_residence" id="city_residence" onchange="fetch_code('CITY',this.value);">
</select></span>
<div id="city_residence_submit_err" style="display:~if $cityResidence_err`inline~else`none~/if`">
<label class="l1">&nbsp;</label><div class="err_msg">Please select a city.</div></div>
<div class="sp15">&nbsp;</div>
</div>
<div class="row4  no-margin-padding width100">
<label class="grey">Residency status :</label>
<span><select name="Rstatus">
<option value="1" ~if $RES_STATUS eq "1"`selected~/if`>Citizen</option>
<option value="2" ~if $RES_STATUS eq "2"`selected~/if`>Permanent Resident</option>
<option value="3" ~if $RES_STATUS eq "3"`selected~/if`>Work Permit</option>
<option value="4" ~if $RES_STATUS eq "4"`selected~/if`>Student Visa</option>
<option value="5" ~if $RES_STATUS eq "5"`selected~/if`>Temporary Visa</option>
</select></span>
</div>
<div class="sp15">&nbsp;</div>
<div class="row4  no-margin-padding width100">
<label class="grey"><i class="btn-archive"></i> Your contact address :</label>
<span class="containerSp">
<textarea class="textarea-big" name="Address" id="Address">~$CONTACT`</textarea>
<div class="sp5"></div>
<div id="pincodeid" style="display:~if $loginProfile->getCOUNTRY_RES() eq '51'`block~else`none~/if`;"> Pin code <input type="text" maxlength="6" id="pincode" value="~$PINCODE`" name="pincode" class="combo-small-more2 mar_left_10" onblur="check_pincode(1);">
<div class="red_new" id="pincode_span" style="display:none;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Pincode should have 6 digits.
</div>
</div>
</span>

<i class="widthauto mar_left_10"><select name="showAddress">
<option value="Y" ~if $SHOWADDRESS eq "Y"`selected~/if`>Show to my accepted contacts/paid members</option>
<option value="N" ~if $SHOWADDRESS eq "N" or $SHOWADDRESS eq ""`selected~/if`>Don't show to anybody</option>
</select> &nbsp;<i class="btn-key">&nbsp;</i></i>
</div>
<div class="sp15">&nbsp;</div>

<div class="row4  no-margin-padding width100">
<label class="grey"><i class="btn-archive"></i> Parents' address :</label>
<span class="containerSp">
<textarea class="textarea-big" name="Parents_Contact">~$PARENTS_CONTACT`</textarea>
<div class="sp5"></div>
<div id="parent_pincodeid" style="display:~if $loginProfile->getCOUNTRY_RES() eq '51'`block~else`none~/if`;">	 Pin code <input type="text" maxlength="6" id="parent_pincode" value="~$PARENT_PINCODE`" name="parent_pincode" class="combo-small-more2 mar_left_10" onblur="check_pincode(2);">
<div class="red_new" id="parent_pincode_span" style="display:none;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Pincode should have 6 digits.
</div>
</div>
</span>

<i class="widthauto mar_left_10"><select name="Show_Parents_Contact">
<option value="Y" ~if $SHOW_PARENTS_CONTACT eq "Y"`selected~/if`>Show to my accepted contacts/paid members</option>
<option value="N" ~if $SHOW_PARENTS_CONTACT eq "N" or $SHOW_PARENTS_CONTACT eq ""`selected~/if`>Don't show to anybody</option>
</select> &nbsp;<i class="btn-key">&nbsp;</i></i>
</div>
<div class="sp15">&nbsp;</div>
<input type="hidden" value="" name="isd_change_src" id="isd_change_src"/>
<div class="row4  no-margin-padding width100">
<label class="grey" ~if $post_login eq 1`style="color:red_new;"~/if`>
<i class="btn-archive"></i> Landline number :</label>
<input type="hidden" name="ISD" value="~$country_code`" id="country_code">
<span class="widthauto"><span class="widthauto"><div>Country</div><div><input class="combo-small-more2" type="text" size="3" name="phone_isd" id="phone_isd" value="~$country_code_mob`" onKeyUp="change_isd(this.value,'phone');" onblur="isd_verify('P',this);"></div>
</span>

<span class="widthauto mar_left_10"><div>Area</div>
<div><input type="text"  name="State_Code" id="state_code" value="~$state_code`" class="combo-small-more2"></div></span>
<span class="widthauto mar_left_10"><div>Number</div><div><input type="text" name="Phone" value="~$PHONE_RES`" id="Phone" onblur="phoneJCheck('L');" maxlength="12" style="height:20px;width:150px;"></div></span>
</span>
<br>&nbsp;
<i class="widthauto" >
<select name="Showphone" id="dont_show">
<option style="font-size:10px;" value="Y" ~if $SHOWPHONE_RES eq "Y" or $SHOWPHONE_RES eq ""`selected~/if`>Show to All Paid Members</option> 
 <option style="font-size:10px;" value="C" ~if $SHOWPHONE_RES eq "C"`selected~/if`>Show to only Members I Accept / Express Interest In</option> 
<option style="font-size:10px;" value="N" ~if $SHOWPHONE_RES eq "N"`selected~/if`>Don't show to anybody</option>
~if $CALL_NOW`<option style="font-size:10px;" value="CN" ~if $SHOWPHONE_RES eq "CN"`selected~/if` id="call_anonym">Receive calls anonymously</option>~/if`
</select> &nbsp;<i class="btn-key">&nbsp;</i>
</i>


<div class="sp5">&nbsp;</div>
<div class="next-line ">of &nbsp;
<select name="PHONE_NUMBER_OWNER" class="combo-small-more">
~if $GENDER eq 'F'`
<option value="1" ~if $PHONE_NUMBER_OWNER eq "1"`selected~/if`>Bride</option>
~else`
<option value="2" ~if $PHONE_NUMBER_OWNER eq "2"`selected~/if`>Groom</option>
~/if`
<option value="3" ~if $PHONE_NUMBER_OWNER eq "3"`selected~/if`>Parent</option>
<option value="6" ~if $PHONE_NUMBER_OWNER eq "6"`selected~/if`>Sibling</option>
<option value="7" ~if $PHONE_NUMBER_OWNER eq "7"`selected~/if`>Other</option>
</select>&nbsp;whose name is <input  name="PHONE_OWNER_NAME" value="~$PHONE_OWNER_NAME`" id="PHONE_OWNER_NAME" type="text"></div>
<br/>
<div class="red_new clear" id="phone_span" style="display:none; margin:0 0 0 203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please type in a valid phone number.
<div class="sp5"></div>
</div>
<div class="red_new clear" id="phone_isd_span" style="display:none; margin:0 0 0 203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please provide an ISD code.
<div class="sp5"></div>
</div>
<div class="red_new clear" id="phone_isd_valid_span" style="display:none; margin:0 0 0 203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please provide a valid ISD code.
<div class="sp5"></div>
</div>

<div class="red_new clear" id="phone_name_span" style="display:none; margin:0 0 0 203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please enter the phone number owner's name.
<div class="sp5"></div>
</div>
<div class="red_new clear" id="phone_in_name_span" style="display:none; margin:0 0 0 203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Phone number owner's name cannot contain special characters.
<div class="sp5"></div>
</div>
<div class="red_new clear" id="state_code_span" style="display:none; margin:0 0 0 203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please provide std code in correct format.
<div class="sp5"></div>
</div>
</div>

~if $CALL_NOW`
<div id="helpbox" style="z-index:1000;position:absolute;float:left;display:none;left:180px;top:30px;">
<div id="box">
<div class="b fs12">Recieve Call Anonymously 
</div> <span class="text_new_help"><b>New</b></span>
<span class="sp5_help">&nbsp;</span>
<div class="fs12 width100">Your number will not be shown to other people, they
can only call you through Jeevansathi calling system</div>
<span class="sp5">&nbsp;</span><span class="sp5">&nbsp;</span>
<span class="i fs11 red_new" style="positon:relative;left:-233px">Important</span>
<ol start="1" class="list_imp" >
<li class="fs11">Select this feature only if you are very particular about showing your number to others, this may  delay your responses from other</li><li class="fs11">If you choose "Receive Calls Anonymously" for any phone, the same will be applicable for your other phone numbers</li>
</ol>
</div>
</div>
~/if`

<div class="sp15"></div>

<div class="row4  no-margin-padding width100">
<label class="grey" ~if $post_login eq 1`style="color:red_new;"~/if`><i class="btn-archive"></i> Mobile number :</label>
<span class="containerSp lf"><span class="widthauto">Country<br><input class="combo-small-more2" type="text" size="3" name="mobile_isd" id="country_code_mob" value="~$country_code`" onKeyUp="change_isd(this.value,'mobile');" onblur="isd_verify('M',this);"></span>
<span class="widthauto mar_left_10"><div>Number</div><div><input type="text" class="textbox-small" name="Mobile" value="~$PHONE_MOB`" id="Mobile" onblur="phoneJCheck('M');" maxlength="15"></div></span></span>
<input type="hidden" name="ISDMOB" value="~$country_code`" id="country_code_mob1">
<br><i class="widthauto mar_left_10 setmargin"><select name="Showmobile" id="dont_show1">
<option style="font-size:10px;" value="Y" ~if $SHOWPHONE_MOB eq "Y" or $SHOWPHONE_MOB eq ""`selected~/if`>Show to All Paid Members</option> 
 <option style="font-size:10px;" value="C" ~if $SHOWPHONE_MOB eq "C"`selected~/if`>Show to only Members I Accept / Express Interest In</option>
<option style="font-size:10px;" value="N" ~if $SHOWPHONE_MOB eq "N"`selected~/if` >Don't show to anybody</option>
~if $CALL_NOW`<option style="font-size:10px;" value="CN" ~if $SHOWPHONE_MOB eq "CN"`selected~/if`>Receive calls anonymously</option> ~/if`
</select> &nbsp;<i class="btn-key">&nbsp;</i>
</i>
</div>
<div class="sp5">&nbsp;</div>
<div class="next-line">of &nbsp;<select name="MOBILE_NUMBER_OWNER" class="combo-small-more">
~if $GENDER eq 'F'`
<option value="1" ~if $MOBILE_NUMBER_OWNER eq "1"`selected~/if`>Bride</option>
~else`
<option value="2" ~if $MOBILE_NUMBER_OWNER eq "2"`selected~/if`>Groom</option>
~/if`
<option value="3" ~if $MOBILE_NUMBER_OWNER eq "3"`selected~/if`>Parent</option>
<option value="6" ~if $MOBILE_NUMBER_OWNER eq "6"`selected~/if`>Sibling</option>
<option value="7" ~if $MOBILE_NUMBER_OWNER eq "7"`selected~/if`>Other</option>
</select>&nbsp;whose name is <input name="MOBILE_OWNER_NAME" value="~$MOBILE_OWNER_NAME`" id="MOBILE_OWNER_NAME" type="text"></div>
<div class="clear"></div>
<div class="red_new" id="mobile_name_span" style="display:none; margin:0 0 0 203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please enter the mobile number owner's name.
</div>
<div class="red_new" id="mobile_in_name_span" style="display:none; margin:0 0 0 203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Mobile number owner's name cannot contain special characters.
</div>

<div class="red_new" id="international_mobile_span" style="display:none; margin:0 0 0 203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;International Mobile number should contain atleast 5 digits.
</div>
<div class="red_new" id="mobile_span" style="display:none; margin:0 0 0 203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please type in a valid mobile number.
</div>
<div class="red_new" id="mobile_isd_valid_span" style="display:none; margin:0 0 0 203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please provide a valid ISD code.
</div>
<div class="red_new" id="mobile_isd_span" style="display:none; margin:0 0 0 203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please provide an ISD code.
</div>
<div class="row4  no-margin-padding width100" id="add_more_mobile">
<label class="grey">&nbsp;</label>
<span class="widthauto">
<div onclick="show_alter('alter_mobile_section')">
<div class="sp5"></div><div class="sp3"></div>
<div><a href="javascript:void(0);"><strong><i class="btn-add fl"></i>Add alternate mobile number</strong></a> </div>
</div>
</span>
</div>
<div class="sp15">&nbsp;</div>

<div class="row4  no-margin-padding width100" id="alter_mobile_section" style="display:none;">
<label class="grey" ~if $post_login eq 1`style="color:red_new;"~/if`><i class="btn-archive"></i> Alternate Mobile number :</label>
<span class="containerSp lf">
<span class="widthauto">Country<br><input class="combo-small-more2" type="text" size="3" name="ALT_MOBILE_ISD" id="ALT_MOBILE_ISD" value="~$country_code`" onKeyUp="change_isd(this.value,'alt_mobile');" onblur="isd_verify('AM',this);">
</span>
<span class="widthauto mar_left_10"><div>Number</div><div><input type="text" class="textbox-small" name="ALT_MOBILE" value="~$ALT_MOBILE`" id="ALT_MOBILE" onblur="check_alternate();phoneJCheck('AM');" maxlength="15"></div>
</span>
</span>
<br>
<i class="widthauto mar_left_10 setmargin">
<select id="ALT_Showmobile" name="ALT_Showmobile">
<option style="font-size:10px;" value="Y" ~if $ALT_SHOWPHONE_MOB eq "Y" or $ALT_SHOWPHONE_MOB eq ""`selected~/if`>Show to All Paid Members</option> 
 <option style="font-size:10px;" value="C" ~if $ALT_SHOWPHONE_MOB eq "C"`selected~/if`>Show to only Members I Accept / Express Interest In</option> 
<option style="font-size:10px;" value="N" ~if $ALT_SHOWPHONE_MOB eq "N"`selected~/if`>Don't show to anybody</option>
~if $CALL_NOW`<option style="font-size:10px;" value="CN" ~if $ALT_SHOWPHONE_MOB eq "CN"`selected~/if`>Receive calls anonymously</option> ~/if`
</select> &nbsp;<i class="btn-key">&nbsp;</i><br>
</i>

<div class="clear"></div>
<div class="sp5">&nbsp;</div>
<div class="next-line">of &nbsp;<select name="ALT_MOBILE_NUMBER_OWNER" id="ALT_MOBILE_NUMBER_OWNER" class="combo-small-more">
~if $GENDER eq 'F'`
<option value="1" ~if $ALT_MOBILE_NUMBER_OWNER eq "1"`selected~/if`>Bride</option>
~else`
<option value="2" ~if $ALT_MOBILE_NUMBER_OWNER eq "2"`selected~/if`>Groom</option>
~/if`
<option value="3" ~if $ALT_MOBILE_NUMBER_OWNER eq "3"`selected~/if`>Parent</option>
<option value="6" ~if $ALT_MOBILE_NUMBER_OWNER eq "6"`selected~/if`>Sibling</option>
<option value="7" ~if $ALT_MOBILE_NUMBER_OWNER eq "7"`selected~/if`>Other</option>
</select>&nbsp;whose name is <input name="ALT_MOBILE_OWNER_NAME" value="~$ALT_MOBILE_OWNER_NAME`" id="ALT_MOBILE_OWNER_NAME" type="text"></div><div class="clear"></div>

<div class="red_new" id="alt_mobile_name_span" style="display:none;margin:0 0 0 203px">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please enter the mobile number owner's name.
</div>
<div class="red_new" id="alt_mobile_in_name_span" style="display:none;margin:0 0 0 203px">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Mobile number owner's name cannot contain special characters.
</div>


<div class="clear"></div>
<div class="red_new" id="alt_mobile_error" style="display:none;margin:0 0 0 203px">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Alternate Number cannot be same as mobile number
</div>
<div class="red_new" id="alt_international_mobile_span" style="display:none; margin:0 0 0 203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;International Mobile number should contain atleast 8 digits.
</div>
<div class="red_new" id="alt_mobile_span" style="display:none; margin:0 0 0 203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please type in a valid mobile number.
</div>
<div class="red_new" id="alt_mobile_isd_span" style="display:none; margin:0 0 0 203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please provide an ISD code.
</div>
<div class="red_new" id="alt_mobile_isd_valid_span" style="display:none; margin:0 0 0 203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>&nbsp;Please provide a valid ISD code.
</div>
</div>

<div class="row4  no-margin-padding width100" id="hide_more_mobile" style="display:none">
<label class="grey">&nbsp;</label>
<span class="widthauto">
<div onclick="show_alter('alter_mobile_section','HIDE')">
<div class="sp5"></div><div class="sp3"></div>
<div><a href="javascript:void(0);"><strong><i class="btn-rem fl"></i>Remove alternate mobile number</strong></a> </div>
</div>
</span>
<div class="sp15">&nbsp;</div>
</div>

<div class="row4  no-margin-padding width100">
<label class="grey">Suitable time to call :</label>
<span><select style="width:40px;height:20px;" name="time_to_call_start">
<option value=12 ~if $time_to_call_start eq 12`selected~/if`>12</option>
<option value=1 ~if $time_to_call_start eq 1`selected~/if`>1</option>
<option value=2 ~if $time_to_call_start eq 2`selected~/if`>2</option>
<option value=3 ~if $time_to_call_start eq 3`selected~/if`>3</option>
<option value=4 ~if $time_to_call_start eq 4`selected~/if`>4</option>
<option value=5 ~if $time_to_call_start eq 5`selected~/if`>5</option>
<option value=6 ~if $time_to_call_start eq 6`selected~/if`>6</option>
<option value=7 ~if $time_to_call_start eq 7`selected~/if`>7</option>
<option value=8 ~if $time_to_call_start eq 8`selected~/if`>8</option>
<option value=9 ~if $time_to_call_start eq 9 or $time_to_call_start eq ''`selected~/if`>9</option>
<option value=10 ~if $time_to_call_start eq 10`selected~/if`>10</option>
<option value=11 ~if $time_to_call_start eq 11`selected~/if`>11</option>
</select> <select style="width:45px;height:20px;"  name="start_am_pm"><option value="AM" ~if $start_am_pm eq "AM"`selected~/if`>AM</option><option value="PM" ~if $start_am_pm eq "PM"`selected~/if`>PM</option></select> &nbsp;to <select style="width:40px;height:20px;" name="time_to_call_end">
<option value=12 ~if $time_to_call_end eq 12`selected~/if`>12</option>
<option value=1 ~if $time_to_call_end eq 1`selected~/if`>1</option>
<option value=2 ~if $time_to_call_end eq 2`selected~/if`>2</option>
<option value=3 ~if $time_to_call_end eq 3`selected~/if`>3</option>
<option value=4 ~if $time_to_call_end eq 4`selected~/if`>4</option>
<option value=5 ~if $time_to_call_end eq 5`selected~/if`>5</option>
<option value=6 ~if $time_to_call_end eq 6`selected~/if`>6</option>
<option value=7 ~if $time_to_call_end eq 7`selected~/if`>7</option>
<option value=8 ~if $time_to_call_end eq 8`selected~/if`>8</option>
<option value=9 ~if $time_to_call_end eq 9 or $time_to_call_start eq ''`selected~/if`>9</option>
<option value=10 ~if $time_to_call_end eq 10`selected~/if`>10</option>
<option value=11 ~if $time_to_call_end eq 11`selected~/if`>11</option>
</select> <select style="width:45px;height:20px;" name="end_am_pm"><option value="AM" ~if $end_am_pm eq "AM"`selected~/if`>AM</option><option value="PM" ~if $end_am_pm eq "PM" or $end_am_pm eq ""`selected~/if`>PM</option></select></span>
</div>
<div class="sp15"></div>

<div class="row4  no-margin-padding width100">
<label class="grey"><i class="btn-archive"></i> Messenger ID :</label>
<span class="widthauto">
<input class="textbox-small" type="text" name="Messenger_ID" id="Messenger_ID" value="~$MESSENGER_ID`">&nbsp;<select class="combo-dimens" name="Messenger" id="mess_channel"><option value="" ~if !$MESSENGER_CHANNEL && !$MESSENGER_ID`selected~/if`>Select option</option>
<option value="1" ~if $MESSENGER_CHANNEL eq '1' && $MESSENGER_ID`  selected ~/if`>Yahoo</option>
<option value="2" ~if $MESSENGER_CHANNEL eq '2' && $MESSENGER_ID` selected ~/if`>MSN</option>
<option value="3" ~if $MESSENGER_CHANNEL eq '3' && $MESSENGER_ID` selected ~/if`>Skype</option>
<option value="5" ~if $MESSENGER_CHANNEL eq '5' && $MESSENGER_ID` selected ~/if`>ICQ</option>
<option value="6" ~if $MESSENGER_CHANNEL eq '6' && $MESSENGER_ID` selected ~/if`>Google Talk</option>
<option value="7" ~if $MESSENGER_CHANNEL eq '7' && $MESSENGER_ID` selected ~/if`>Rediff Bol</option>

</select>
</span>
<i class="widthauto mar_left_10">
<select name="showMessenger">
<option value="Y" ~if $SHOWMESSENGER eq "Y" or $SHOWMESSENGER eq ""`selected~/if`>Show to my accepted contacts/paid members</option>
<option value="N" ~if $SHOWMESSENGER eq "N"`selected~/if`>Don't show to anybody</option>
</select> &nbsp;<i class="btn-key">&nbsp;</i>
</i>

<div class="red_new" id="js_messenger_id_error">
<div class="sp15"></div>
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif" />&nbsp; </div>
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding width100" id="add_more_messenger">
<label class="grey">&nbsp;</label>
<span class="widthauto">
<div onclick="show_alter('alter_messenger_section')">
<div><a href="javascript:void(0);"><strong><i class="btn-add fl"></i>Add alternate messengers ID</strong></a> </div>
</div>
</span>
</div>
<div class="sp15"></div>

<div class="row4  no-margin-padding width100" style="display:none" id="alter_messenger_section">
<label class="grey"> Alternate Messenger ID :</label>
<span class="widthauto">
<input class="textbox-small" type="text" name="Alt_Messenger_ID" id="Alt_Messenger_ID" value="~$ALT_MESSENGER_ID`">&nbsp;<select class="combo-dimens" name="Alt_Messenger" id="alt_mess_channel">
<option value="" ~if !$ALT_MESSENGER_CHANNEL && !$ALT_MESSENGER_ID`selected~/if`>Select option</option>
<option value="1" ~if $ALT_MESSENGER_CHANNEL eq '1' && $ALT_MESSENGER_ID`  selected ~/if`>Yahoo</option>
<option value="2" ~if $ALT_MESSENGER_CHANNEL eq '2' && $ALT_MESSENGER_ID` selected ~/if`>MSN</option>
<option value="3" ~if $ALT_MESSENGER_CHANNEL eq '3' && $ALT_MESSENGER_ID` selected ~/if`>Skype</option>
<option value="5" ~if $ALT_MESSENGER_CHANNEL eq '5' && $ALT_MESSENGER_ID` selected ~/if`>ICQ</option>
<option value="6" ~if $ALT_MESSENGER_CHANNEL eq '6' && $ALT_MESSENGER_ID` selected ~/if`>Google Talk</option>
<option value="7" ~if $ALT_MESSENGER_CHANNEL eq '7' && $ALT_MESSENGER_ID` selected ~/if`>Rediff Bol</option>
</select>
</span>
<i class="widthauto mar_left_10 setmargin">
<select name="Alt_showMessenger" id="Alt_showMessenger" >
<option value="Y" ~if $ALT_SHOWMESSENGER eq "Y" or $ALT_SHOWMESSENGER eq ""`selected~/if`>Show to my accepted contacts/paid members</option>
<option value="N" ~if $ALT_SHOWMESSENGER eq "N"`selected~/if`>Don't show to anybody</option>
</select>  &nbsp;<i class="btn-key">&nbsp;</i>
</i>

<div class="red_new" id="js_alt_messenger_id_error">
<div class="sp15"></div>
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif" />&nbsp;</div>
<div class="clr"></div>
<div class="red_new" id="alt_error_message" style="display:none;margin-left:203px;">
<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>
&nbsp;Alternate Messenger ID can't be same as Messenger ID.
</div>
<div class="sp12">&nbsp;</div>
</div>

<div class="row4 no-margin-padding width100" id="hide_more_messenger" style="display:none">
<label class="grey">&nbsp;</label>
<span class="widthauto">
<div onclick="show_alter('alter_messenger_section','HIDE')">
<div><a href="javascript:void(0);"><strong><i class="btn-rem fl"></i>Remove alternate messengers ID</strong></a> </div>
</div>
</span>
<div class="sp15">&nbsp;</div>
</div>



<div class="row4  no-margin-padding width100">
<label class="grey"> Blackberry Pin :</label>
<span class="containerSp">
<input type="text" name="blackberry_pin" value="~$blackberry_pin`" id="blackberry_pin">
</span>

<i class="mar_left_10" >
<select  name="show_blackberry">
<option value="Y" ~if $SHOWBLACKBERRY eq "Y" or $SHOWBLACKBERRY eq ""`selected~/if`>Show to my accepted contacts/paid members</option>
<option value="N" ~if $SHOWBLACKBERRY eq "N"`selected~/if`>Don't show to anybody</option>
</select> &nbsp;<i class="btn-key">&nbsp;</i>
</i>
</div>
<div class="sp15">&nbsp;</div>
<div class="row4  no-margin-padding width100">
<label class="grey">Linkedin ID :</label>
<span class="containerSp">
<input type="text" name="linkedin_id" value="~$linkedin_url`" id="linkedin_id">
</span>
<i class="mar_left_10" >
<select name="show_linkedin">
<option value="Y" ~if $SHOWLINKEDIN eq "Y" or $SHOWLINKEDIN eq ""`selected~/if`>Show to my accepted contacts/paid members</option>
<option value="N" ~if $SHOWLINKEDIN eq "N"`selected~/if`>Don't show to anybody</option>
</select> &nbsp;<i class="btn-key">&nbsp;</i>
</i>
</div>
<div class="sp15">&nbsp;</div>
<div class="row4  no-margin-padding width100">
<label class="grey"> Facebook ID :</label>
<span class="containerSp">
<input type="text" name="facebook_id" value="~$FB_URL`" id="facebook_id">
</span>
<i class="mar_left_10" >
<select name="show_facebook">
<option value="Y" ~if $SHOWFACEBOOK eq "Y" or $SHOWFACEBOOK eq ""`selected~/if`>Show to my accepted contacts/paid members</option>
<option value="N" ~if $SHOWFACEBOOK eq "N"`selected~/if`>Don't show to anybody</option>
</select> &nbsp;<i class="btn-key">&nbsp;</i>
</i>
</div>
<div class="sp15">&nbsp;</div>
</div>

~if $callTime eq '1'`
<script type="text/javascript" language="JavaScript">
dID('Messenger_ID').focus();
</script>
~/if`
