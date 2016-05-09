

<link rel="stylesheet" type="text/css" href="~$IMG_URL`/min/?f=/css/~$autoSelect_css`" />
<style>
.item { background-color:#FFFFFF;color:#000000;cursor:pointer;float:left;font-family:Arial,sans-serif;font-size:13px;padding:1px 0pt 0pt;width:100%;
}
.autosuggestresults {background-color:#FFFFFF;border:1px solid #000000;display:none;float:left;margin-top:0px;position:absolute;width:144px;z-index:100;
}
.autosuggestiframe {background-color:#FFFFFF;display:none;float:left;margin-top:0px;position:absolute;width:210px;z-index:50;
}
#divcontent {border-style:none solid solid;
}
.iframetrans {margin:auto;opacity:0;}
.selecteditem{background-color:#3366CC; color:#FFFFFF; cursor:pointer; float:left; font-family:Arial,sans-serif; font-size:13px; padding:1px 0pt 0pt; width:100%;}
.coverhelp{position:relative;display:block;margin-left:100px;margin-top:-15px;}
.helpbox{display:none; border:1px solid #99b1c8; left:16px; top:-7px; margin-right:25px; position:absolute; width:220px;z-index:100;background:#FFFFFF}
* html .helpbox{display:none; border:1px solid #99b1c8; left:16px; top:0px; margin-right:25px; position:absolute; width:220px;z-index:100;background:#FFFFFF}
*:first-child+html .helpbox{display:none; border:1px solid #99b1c8; left:15px; top:0px;margin-right:25px; position:absolute; width:220px;z-index:100;background:#FFFFFF}
.helptext{float:left; width:200px; font-family:Arial,Helvetica;font-size:11px;color:#000000; font-weight:normal;line-height:13px;padding:4px 6px;}
.helpimg{position: absolute; top: 5px; left:-16px;background-image:url(/profile/images/registration_new/arrow2.gif); background-repeat:no-repeat; width:16px; height:12px;}
.l1     {float:left; width:26%; font:bold 11px verdana,arial; padding:5px 10px 4px 0; text-align:right}
.l2     {float:left; width:70%; font:normal 11px verdana,arial; color:#000000; padding:3px 0 4px 0;}
.no-results span{float:none !important; padding-left:0px !important;}
</style>
<script>
var caste = $("#caste_hindu");
var subcaste = $("#subcaste");
var cityDefault = null;
var countryDefault = ~$COUNTRY_DEFAULT`;
$("#gotra").autocomplete(SITE_URL+"/profile/autoSug?type=gothra",{maxItemsToShow:10,field:'#gothraPat'});
$("#gotra_bud").autocomplete(SITE_URL+"/profile/autoSug?type=gothra",{maxItemsToShow:10,field:'#gothraPat_bud'});
$("#gotra_sik").autocomplete(SITE_URL+"/profile/autoSug?type=gothra",{maxItemsToShow:10,field:'#gothraPat_sik'});
$("#gotra_jain").autocomplete(SITE_URL+"/profile/autoSug?type=gothra",{maxItemsToShow:10,field:'#gothraPat_jain'});
$("#gotra_maternal").autocomplete(SITE_URL+"/profile/autoSug?type=gothra",{maxItemsToShow:10,field:'#gothraMat'});
$("#diocese").autocomplete(SITE_URL+"/profile/autoSug?type=dioceses",{maxItemsToShow:10,field:'#dioceses'});
var caste_id = $("#caste_hindu option:selected").attr("value");
caste.change(function () {
        caste_id = $("#caste_hindu option:selected").attr("value");
        $(".ac_results").remove(); //this was populating multiple divs.
        subcaste.autocomplete(SITE_URL+"/profile/autoSug?type=subcaste&caste=" + caste_id, {maxItemsToShow:10, field:'#subcastes'});
        });
subcaste.autocomplete(SITE_URL+"/profile/autoSug?type=subcaste&caste=" + caste_id, {maxItemsToShow:10, field:'#subcastes'});
function showContent()
{
        var rel = document.form1.Religion.value;
	var rel_temp = rel.split("|X|");
        vrel = rel_temp[0];
        if(vrel == 1)
        {
                document.getElementById("Hindu").style.display = "block";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Buddhist").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
                document.getElementById("Parsi").style.display = "none";
        }
        else if(vrel == 2)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "block";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
                document.getElementById("Parsi").style.display = "none";
                document.getElementById("Buddhist").style.display = "none";
		var rel1 = document.getElementById("cas_mus").value;
                if(rel1 == 151)
                {
                        document.getElementById("shia").style.display = "block";
                        document.getElementById("sunni").style.display = "none";
                }
                else if(rel1 == 152)
                {
                        document.getElementById("shia").style.display = "none";
                        document.getElementById("sunni").style.display = "block";
                }               
                else
                {
                        document.getElementById("shia").style.display = "none";
                        document.getElementById("sunni").style.display = "none";
                }
        }
	else if(vrel == 3)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "block";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
                document.getElementById("Parsi").style.display = "none";
                document.getElementById("Buddhist").style.display = "none";
        }
	else if(vrel == 4)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "block";
                document.getElementById("Jain").style.display = "none";
                document.getElementById("Parsi").style.display = "none";
                document.getElementById("Buddhist").style.display = "none";
        }
	else if(vrel == 9)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "block";
                document.getElementById("Parsi").style.display = "none";
                document.getElementById("Buddhist").style.display = "none";
        }
        else if(vrel == 5)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
                document.getElementById("Parsi").style.display = "block";
                document.getElementById("Buddhist").style.display = "none";
        }
        else if(vrel == 7)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
                document.getElementById("Parsi").style.display = "none";
                document.getElementById("Buddhist").style.display = "block";
        }
        else
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
                document.getElementById("Parsi").style.display = "none";
                document.getElementById("Buddhist").style.display = "none";
        }
}
function changeContent()
{
	var rel = document.form1.Religion.value;
	var rel_temp = rel.split("|X|");
        vrel = rel_temp[0];
	if(vrel == 1)
	{
		document.getElementById("Hindu").style.display = "block";
		document.getElementById("Muslim").style.display = "none";
		document.getElementById("Christian").style.display = "none";
		document.getElementById("Sikh").style.display = "none";
		document.getElementById("Jain").style.display = "none";
		document.getElementById("Parsi").style.display = "none";
                document.getElementById("Buddhist").style.display = "none";
	}
	else if(vrel == 2)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "block";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
                document.getElementById("Parsi").style.display = "none";
                document.getElementById("Buddhist").style.display = "none";
                var rel1 = document.getElementById("cas_mus").value;
        	if(rel1 == 151)
        	{
                	document.getElementById("shia").style.display = "block";
                	document.getElementById("sunni").style.display = "none";
        	}
        	else if(rel1 == 152)
        	{
                	document.getElementById("shia").style.display = "none";
                	document.getElementById("sunni").style.display = "block";
        	}		
        	else
        	{
                	document.getElementById("shia").style.display = "none";
        		document.getElementById("sunni").style.display = "none";
	       	}
        }
	else if(vrel == 3)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "block";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
                document.getElementById("Parsi").style.display = "none";
                document.getElementById("Buddhist").style.display = "none";
        }
	else if(vrel == 4)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "block";
                document.getElementById("Jain").style.display = "none";
                document.getElementById("Parsi").style.display = "none";
                document.getElementById("Buddhist").style.display = "none";
        }
	else if(vrel == 9)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "block";
                document.getElementById("Parsi").style.display = "none";
                document.getElementById("Buddhist").style.display = "none";
        }
	else if(vrel == 5)
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
                document.getElementById("Buddhist").style.display = "none";
                document.getElementById("Parsi").style.display = "block";
        }
	else if(vrel == 7)
	{
		document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
                document.getElementById("Parsi").style.display = "none";
                document.getElementById("Buddhist").style.display = "block";
	}
	else
        {
                document.getElementById("Hindu").style.display = "none";
                document.getElementById("Muslim").style.display = "none";
                document.getElementById("Christian").style.display = "none";
                document.getElementById("Sikh").style.display = "none";
                document.getElementById("Jain").style.display = "none";
                document.getElementById("Parsi").style.display = "none";
                document.getElementById("Buddhist").style.display = "none";
        }
}
function changeVal()
{
	var rel = document.form1.Religion.value;
        vrel = rel.substr(0,1);
	if(vrel == 2)
	{
        	var rel1 = document.getElementById("cas_mus").value;
	}
        if(rel1 == 151)
        {
		document.getElementById("maththab_shia").value="";
                document.getElementById("shia").style.display = "block";
                document.getElementById("sunni").style.display = "none";
        }
	else if(rel1 == 152)
        {
		document.getElementById("maththab_sunni").value="";
                document.getElementById("shia").style.display = "none";
                document.getElementById("sunni").style.display = "block";
        }
	else
	{
		document.getElementById("shia").style.display = "none";
                document.getElementById("sunni").style.display = "none";
	}
}
function auto_suggest1(obj,container_div,inside_div_name)
{
        results_container_div_id = container_div;
        results_inside_div_name = inside_div_name;
        suggest_box_id = obj.id;
	var value = escape(obj.value);
        var name = escape(obj.name);
        var req = createNewXmlHttpObject();
        var to_post = name + "=" + value + "&autosuggest=1";

        if(value != "")
        {
                req.open("POST","/profile/registration_ajax_validation.php",true);
                req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                req.send(to_post);

                req.onreadystatechange = show_result;
        }
        if(value == "")
                suggest_div("hide");
}
function ad(task)
{
	if(task == 'Y')
		document.getElementById('male_user').style.display="none";
	else
		document.getElementById('male_user').style.display="block";
}
function show_sam()
{
	if(document.getElementById('cas_ja').value==175)
		document.getElementById('samp').style.display="block";
	else
		document.getElementById('samp').style.display="none";
}
show_sam();
function showhelp()
{
	document.getElementById('gotra_help').style.display="block";
}
function showhelp_maternal()
{
	document.getElementById('gotra_maternal_help').style.display="block";
}
function showhelp1()
{
        document.getElementById('diocese_help').style.display="block";
}
function hidehelp()
{
        document.getElementById('gotra_help').style.display="none";
}
function hidehelp_maternal()
{
        document.getElementById('gotra_maternal_help').style.display="none";
}
function hidehelp1()
{
        document.getElementById('diocese_help').style.display="none";
}
function hideit()
{
	$("#gotra_results_iframe").hide();
	$("#gotra_results").hide();
	$("#gotra_maternal_results").hide();
	$("#diocese_results").hide();
	$("#gotra_maternal_results_iframe").hide();
	$("#diocese_results_iframe").hide();
}
function check_click_or_not()
{
	if($("#gotra_results_iframe").css("display")!='none')
	{
		hideit();
	}
}
</script>
<div class="edit_scrollbox2_1" onclick="javascript:check_click_or_not()">
~$sf_data->getRaw('hiddenInput')`
<div class="row4 no-margin-padding">
<label class="grey"><i class="green-hash">#</i> Religion :</label>
~$RELIGION_SELF`
</div>
<input type="hidden" name="Religion" id="Religion" value="~$RELIGION_VALUE`"/>
<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding">
<label class="grey">Mother tongue :</label>
<select name="Mtongue">~$sf_data->getRaw('MTONGUE')`</select>
<div class="sp15">&nbsp;</div>
</div>

<!-- Buddhist-->
<div name="Buddhist" id="Buddhist">
	<div class="row4 no-margin-padding">
	<label class="grey">~$sectLabel` :</label>
	<select style="width:185px;" name="sect_buddhist">
<option value="">Select</option>
~$sf_data->getRaw('SECT_BUDDHIST')`</select>
	</div><div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding" >
	<label class="grey block">Gothra :</label>
	<input class="textbox" type="textbox" name="gotra_bud" id="gotra_bud" class="fl" value="~$sf_data->getRaw('GOTHRA')`" /><br />
	<span id="gothraPat_bud" style="position:relative;clear:all"></span>
	<label class="grey">&nbsp;</label><i class="green fs12">This field will be screened before going live</i>
</div>
<div class="coverhelp" style="width:auto;float:left;position:relative;margin-bottom:-1000px;">
	<div style="display: none; border: 1px solid rgb(153, 177, 200); position: relative; width: 220px; background: none repeat scroll 0% 0% rgb(255, 255, 255); float: left; margin-left: 300px; margin-top: -20px; z-index: 2000;" id="gotra_help" class="helpbox">
		<div class="helptext" style="float:left; width:200px; font-family:Arial,Helvetica;font-size:11px;color:#000000; font-weight:normal;line-height:13px;padding:4px 6px;">
		Type a few letters and let our auto suggest feature suggest your gothra. Otherwise you can enter a new one.
			<div class="helpimg" style="position: relative; top: -48px; left:-30px;background-image:url(/profile/images/registration_new/arrow2.gif); background-repeat:no-repeat; width:16px; height:12px;">
			</div>
		</div>
	</div>
</div>
<div class="sp15">&nbsp;</div>
</div>
<!--Buddhist-->

<!--Hindu-->
<div name="Hindu" id="Hindu">
<div class="row4 no-margin-padding">
<label class="grey">Caste :</label>
<select name="Caste_hindu" id="caste_hindu">~$sf_data->getRaw('CASTE_HINDU')`</select>
</div>
<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding">
	<label class="grey">Sect :</label>
	<select name="sect_hindu">
		<option value="">Select</option>
		~$sf_data->getRaw('SECT_HINDU')`</select>
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding">
    <label class="grey block">Subcaste :</label>
    <input class="textbox" type="textbox" name="subcaste" id="subcaste" class="fl" value="~$sf_data->getRaw('SUBCASTE')`" /><br />
	<span id="subcastes" style="position:relative;clear:all"></span>
    <label class="grey">&nbsp;</label><i class="green lf fs12">This field will be screened before going live</i>
    <div id="subcaste_results" class="autosuggestresults" style="height:auto; margin-left: 0px; float: left; postion: relative; width: 200px; z-index: 100; display: none; margin-bottom: -1000px;">
        <iframe class="autosuggestiframe iframetrans" id="subcaste_results_iframe" style="display:block; cursor:pointer; padding-bottom:2px; background:#f2f2f2;"></iframe>
</div></div>
<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding" >
	<label class="grey block">Gothra :</label>
	<input class="textbox" type="textbox" name="gotra" id="gotra" class="fl" value="~$sf_data->getRaw('GOTHRA')`" /><br />
	<span id="gothraPat" style="position:relative;clear:all"></span>
	<label class="grey">&nbsp;</label><i class="green fs12">This field will be screened before going live</i>
</div>
<div class="coverhelp" style="width:auto;float:left;position:relative;margin-bottom:-1000px;">
	<div style="display: none; border: 1px solid rgb(153, 177, 200); position: relative; width: 220px; background: none repeat scroll 0% 0% rgb(255, 255, 255); float: left; margin-left: 300px; margin-top: -20px; z-index: 2000;" id="gotra_help" class="helpbox">
		<div class="helptext" style="float:left; width:200px; font-family:Arial,Helvetica;font-size:11px;color:#000000; font-weight:normal;line-height:13px;padding:4px 6px;">
		Type a few letters and let our auto suggest feature suggest your gothra. Otherwise you can enter a new one.
			<div class="helpimg" style="position: relative; top: -48px; left:-30px;background-image:url(/profile/images/registration_new/arrow2.gif); background-repeat:no-repeat; width:16px; height:12px;">
			</div>
		</div>
	</div>
</div>
<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding">
	<label class="grey block">Gothra Maternal :</label>
	<input class="textbox" type="textbox" name="gotra_maternal" id="gotra_maternal" class="fl" value="~$sf_data->getRaw('GOTHRA_MATERNAL')`" /><br/>
	<span id="gothraMat" style="position:relative;clear:all"></span>
	<label class="grey">&nbsp;</label><i class="green fs12">This field will be screened before going live</i>
	<div id="gotra_maternal_results" class="autosuggestresults" style="height:auto;margin-left: 0px;float:left; position: relative; width: 200px;z-index: 100;display:none;margin-botttom:-1000px;">
		<iframe class="autosuggestiframe iframetrans" id="gotra_maternal_results_iframe" style="display:block; cursor:pointer; padding-bottom:2px; background:#f2f2f2;"></iframe>
	</div>
</div>
<div class="coverhelp" style="width:auto;float:left;position:relative;margin-bottom:-1000px;">
	<div style="display: none; border: 1px solid rgb(153, 177, 200); position: relative; width: 220px; background: none repeat scroll 0% 0% rgb(255, 255, 255); float: left; margin-left: 300px; margin-top: -20px; z-index: 2000;" id="gotra_maternal_help" class="helpbox">
		<div class="helptext" style="float:left; width:200px; font-family:Arial,Helvetica;font-size:11px;color:#000000; font-weight:normal;line-height:13px;padding:4px 6px;">
		Type a few letters and let our auto suggest feature suggest your gothra. Otherwise you can enter a new one.
			<div class="helpimg" style="position: relative; top: -48px; left:-30px;background-image:url(/profile/images/registration_new/arrow2.gif); background-repeat:no-repeat; width:16px; height:12px;">
			</div>
		</div>
	</div>
</div>
<div class="sp15">&nbsp;</div>
</div>
<!--Hindu-->
<!--Jain-->
<div name="Jain" id="Jain">
<div class="row4 no-margin-padding">
<label class="grey">Caste :</label>
<select name="Caste_jain" id="cas_ja" onchange="show_sam();">~$sf_data->getRaw('CASTE_JAIN')`</select>
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding">
<label class="grey">Sect :</label>
<select name="sect_jain">
<option value="">Select</option>
~$sf_data->getRaw('SECT_JAIN')`</select>
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding" style="display:none;" id="samp">
<label class="grey">Sampraday :</label>
<select name="sampraday"><option value="">Select</option>~$sf_data->getRaw('SAMPRADAY_ARR')`</select>
<div class="sp15">&nbsp;</div>
</div>
<div class="row4 no-margin-padding" >
	<label class="grey block">Gothra :</label>
	<input class="textbox" type="textbox" name="gotra_jain" id="gotra_jain" class="fl" value="~$sf_data->getRaw('GOTHRA')`" /><br />
	<span id="gothraPat_jain" style="position:relative;clear:all"></span>
	<label class="grey">&nbsp;</label><i class="green fs12">This field will be screened before going live</i>
</div>
<div class="coverhelp" style="width:auto;float:left;position:relative;margin-bottom:-1000px;">
	<div style="display: none; border: 1px solid rgb(153, 177, 200); position: relative; width: 220px; background: none repeat scroll 0% 0% rgb(255, 255, 255); float: left; margin-left: 300px; margin-top: -20px; z-index: 2000;" id="gotra_help" class="helpbox">
		<div class="helptext" style="float:left; width:200px; font-family:Arial,Helvetica;font-size:11px;color:#000000; font-weight:normal;line-height:13px;padding:4px 6px;">
		Type a few letters and let our auto suggest feature suggest your gothra. Otherwise you can enter a new one.
			<div class="helpimg" style="position: relative; top: -48px; left:-30px;background-image:url(/profile/images/registration_new/arrow2.gif); background-repeat:no-repeat; width:16px; height:12px;">
			</div>
		</div>
	</div>
</div>
<div class="sp15">&nbsp;</div>
</div>
<!--Jain-->
<!--Christian-->
<div name="Christian" id="Christian">
<div class="row4 no-margin-padding">
<label class="grey">Sect :</label>
<select name="Caste_christian">~$sf_data->getRaw('CASTE_CHRISTIAN')`</select>
</div>
<div class="sp15">&nbsp;</div>


<div class="row4 no-margin-padding">
	<label class="grey block">Diocese :</label>
	<input class="textbox" type="textbox" name="diocese" id="diocese" class="fl" value="~$sf_data->getRaw('DIOCESE')`"/><br/>
	<span id="dioceses" style="position:relative;clear:all"></span>

	<label class="grey">&nbsp;</label><i class="green fs12">This field will be screened before going live</i>
	<div id="diocese_results" class="autosuggestresults" style="height:auto;margin-left: 0px;float:left; position: relative; width: 200px;z-index: 100;display:none;margin-botttom:-1000px;">
		<iframe class="autosuggestiframe iframetrans" id="diocese_results_iframe" style="display:block; cursor:pointer; padding-bottom:2px; background:#f2f2f2;"></iframe>
	</div>
</div>
<div class="coverhelp" style="width:auto;float:left;position:relative;margin-bottom:-1000px;">
	<div style="display: none; border: 1px solid rgb(153, 177, 200); position: relative; width: 220px; background: none repeat scroll 0% 0% rgb(255, 255, 255); float: left; margin-left: 300px; margin-top: -20px; z-index: 2000;" id="diocese_help" class="helpbox">
		<div class="helptext" style="float:left; width:200px; font-family:Arial,Helvetica;font-size:11px;color:#000000; font-weight:normal;line-height:13px;padding:4px 6px;">
		Type a few letters and let our auto suggest feature suggest your gothra. Otherwise you can enter a new one.
			<div class="helpimg" style="position: relative; top: -48px; left:-30px;background-image:url(/profile/images/registration_new/arrow2.gif); background-repeat:no-repeat; width:16px; height:12px;">
			</div>
		</div>
	</div>
</div>
<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding">
<label class="grey">Baptised :</label>
<input type="radio" class="chbx" style="vertical-align:middle" name="baptised" value="Y" ~if $BAPTISED eq 'Y'`checked~/if`> Yes &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="baptised" value="N" ~if $BAPTISED eq 'N'`checked~/if`> No
</div>

<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding">
<label class="grey">Do you read Bible : 
everyday?&nbsp; </label>
<input type="radio" class="chbx" style="vertical-align:middle" name="read_bible" value="Y" ~if $READ_BIBLE eq 'Y'`checked~/if`> Yes &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="read_bible" value="N" ~if $READ_BIBLE eq 'N'`checked~/if`> No
</div>

<div class="row4 no-margin-padding">
<label class="grey">Do you offer Tithe : 
regularly?&nbsp; </label>
<input type="radio" class="chbx" style="vertical-align:middle" name="offer_tithe" value="Y" ~if $OFFER_TITHE eq 'Y'`checked~/if`> Yes &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="offer_tithe" value="N" ~if $OFFER_TITHE eq 'N'`checked~/if`> No
</div>
<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding">
<label class="grey">Interested in spreading :
the Gospel? &nbsp;</label>
<input type="radio"class="chbx" style="vertical-align:middle" name="spreading_gospel" value="Y" ~if $SPREADING_GOSPEL eq 'Y'`checked~/if`> Yes &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="spreading_gospel" value="N" ~if $SPREADING_GOSPEL eq 'N'`checked~/if`> No
</div>
<div class="sp15">&nbsp;</div>
</div>
<!--Christian-->
<!--Muslim-->
<div name="Muslim" id="Muslim">
<div class="row4 no-margin-padding">
<label class="grey">Sect :</label>
        <select style="width:235px;" name="Caste_muslim" id="cas_mus" onchange="changeVal();">~$sf_data->getRaw('CASTE_MUSLIM')`</select>
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding">
<label class="grey">Caste :</label>
<select style="width:185px;" name="sect_muslim">
<option value="">Select</option>
~$sf_data->getRaw('SECT_MUSLIM')`</select>
</div>
<div class="sp15">&nbsp;</div>
<div name="sunni" id="sunni">
<div class="row4 no-margin-padding">
<label class="grey">Ma'thab  :</label>
<select style="width:131px;" name="maththab_sunni" id="maththab_sunni">
<option value="">Select</option>
~$sf_data->getRaw('MATHTHAB_SUNNI')`
</select>
</div>
</div>
<div name="shia" id="shia">
<div class="row4 no-margin-padding">
<label class="grey">Ma'thab  :</label>
<select style="width:131px;" name="maththab_shia" id="maththab_shia">
<option value="">Select</option>
~$sf_data->getRaw('MATHTHAB_SHIA')`
</select>
</div>
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding">
<label class="grey">Speak Urdu  :</label>
<input name="speak_urdu" type="checkbox" class="chbx vam" style="border:0px;" value="Y" ~if $SPEAK_URDU eq 'Y'`checked~/if`></div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding">
<label class="grey">Namaz  :</label>
<select style="width:131px;" name="namaz"><option value="">Select</option>~$sf_data->getRaw('NAMAZ_ARR')`</select>
</div>
<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding">
<label class="grey">Zakat  :</label>
<input type="radio"class="chbx" style="vertical-align:middle" name="zakat" value="Y" ~if $ZAKAT eq 'Y'`checked~/if`> Yes &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="zakat" value="N" ~if $ZAKAT eq 'N'`checked~/if`> No
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding">
<label class="grey">Fasting  :</label>
<select class="combo-small" name="fasting"><option value="">Select</option>~$sf_data->getRaw('FASTING_ARR')`</select>
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding">
<label class="grey">Umrah/Hajj  :</label>
<select name="umrah_hajj"><option value="">Select</option>~$sf_data->getRaw('UMRAH_HAJJ_ARR')`</select>
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding">
<label class="grey">Do you read the Quran :</label>
<select name="quran"><option value="">Select</option>~$sf_data->getRaw('QURAN_ARR')`</select>
</div>
<div class="sp15">&nbsp;</div>
~if $GENDER eq 'M'`
<div class="row4 no-margin-padding">
<label class="grey">Sunnah beard :</label>
<select name="sunnah_beard"><option value="">Select</option>~$sf_data->getRaw('SUNNAH_BEARD_ARR')`</select>
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding">
<label class="grey">Sunnah Cap :</label>
<select style="width:131px;" name="sunnah_cap"><option value="">Select</option>~$sf_data->getRaw('SUNNAH_CAP_ARR')`</select>
</div>
<div class="sp15">&nbsp;</div>
~/if`
~if $GENDER eq 'M'`
<div class="row4 no-margin-padding">
<label class="grey">Hijab :</label>
<input type="radio"class="chbx" style="vertical-align:middle" name="hijab" value="Y" ~if $HIJAB eq 'Y'`checked~/if`> Yes &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="hijab" value="N" ~if $HIJAB eq 'N'`checked~/if`> No
</div>
<div class="sp15">&nbsp;</div>
~else`
<div class="row4 no-margin-padding">
<label class="grey">Hijab after marriage : &nbsp;</label>
<input type="radio"class="chbx" style="vertical-align:middle" name="hijab_marriage" value="Y" ~if $HIJAB_MARRIAGE eq 'Y'`checked~/if`> Yes &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="hijab_marriage" value="N" ~if $HIJAB_MARRIAGE eq 'N'`checked~/if`> No &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="hijab_marriage" value="D" ~if $HIJAB_MARRIAGE eq 'D'`checked~/if`> Not Decided
</div>
<div class="sp15">&nbsp;</div>
~/if`
~if $GENDER eq 'M'`
<div class="row4 no-margin-padding">
<label class="grey">Can the girl work after :
marriage? &nbsp;</label>
<input type="radio"class="chbx" style="vertical-align:middle" name="working_marriage" value="Y" ~if $WORKING_MARRIAGE eq 'Y'`checked~/if`> Yes &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="working_marriage" value="N" ~if $WORKING_MARRIAGE eq 'N'`checked~/if`> Prefer a housewife
</div>
<div class="sp15">&nbsp;</div>
~/if`
</div>
<!--Muslim-->
<!--Sikh-->
<div name="Sikh" id="Sikh">
<div class="row4 no-margin-padding">
<label class="grey">Caste :</label>
<select name="Caste_sikh">~$sf_data->getRaw('CASTE_SIKH')`</select>
</div>
<div class="sp15">&nbsp;</div>

<div class="row4 no-margin-padding">
<label class="grey">Sect :</label>
<select name="sect_sikh">
<option value="">Select</option>
~$sf_data->getRaw('SECT_SIKH')`</select>
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding" >
	<label class="grey block">Gothra :</label>
	<input class="textbox" type="textbox" name="gotra_sik" id="gotra_sik" class="fl" value="~$sf_data->getRaw('GOTHRA')`" /><br />
	<span id="gothraPat_sik" style="position:relative;clear:all"></span>
	<label class="grey">&nbsp;</label><i class="green fs12">This field will be screened before going live</i>
</div>
<div class="coverhelp" style="width:auto;float:left;position:relative;margin-bottom:-1000px;">
	<div style="display: none; border: 1px solid rgb(153, 177, 200); position: relative; width: 220px; background: none repeat scroll 0% 0% rgb(255, 255, 255); float: left; margin-left: 300px; margin-top: -20px; z-index: 2000;" id="gotra_help" class="helpbox">
		<div class="helptext" style="float:left; width:200px; font-family:Arial,Helvetica;font-size:11px;color:#000000; font-weight:normal;line-height:13px;padding:4px 6px;">
		Type a few letters and let our auto suggest feature suggest your gothra. Otherwise you can enter a new one.
			<div class="helpimg" style="position: relative; top: -48px; left:-30px;background-image:url(/profile/images/registration_new/arrow2.gif); background-repeat:no-repeat; width:16px; height:12px;">
			</div>
		</div>
	</div>
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding">
<label class="grey">Are you a Amritdhari? :</label>
<input type="radio"class="chbx" style="vertical-align:middle" name="amritdhari" onclick="ad('Y');" value="Y" ~if $AMRITDHARI eq 'Y'`checked~/if`> Yes &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="amritdhari" onclick="ad('N');" value="N" ~if $AMRITDHARI eq 'N'`checked~/if`> No
</div>
<div class="sp15">&nbsp;</div>
<div id="male_user" ~if $AMRITDHARI eq 'N'`style="display:block;"~else`style="display:none;"~/if`>
<div class="row4 no-margin-padding">
<label class="grey">Do you cut your hair? :</label>
<input type="radio"class="chbx" style="vertical-align:middle" name="cut_hair" value="Y" ~if $CUT_HAIR eq 'Y'`checked~/if`> Yes &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="cut_hair" value="N" ~if $CUT_HAIR eq 'N'`checked~/if`> No
</div>
<div class="sp15">&nbsp;</div>

~if $GENDER eq 'M'`
<div class="row4 no-margin-padding">
<label class="grey">Do you trim your beard? :</label>
<input type="radio"class="chbx" style="vertical-align:middle" name="trim_beard" value="Y" ~if $TRIM_BEARD eq 'Y'`checked~/if`> Yes &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="trim_beard" value="N" ~if $TRIM_BEARD eq 'N'`checked~/if`> No
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding">
<label class="grey">Do you wear turban? :</label>
<input type="radio"class="chbx" style="vertical-align:middle" name="wear_turban" value="Y" ~if $WEAR_TURBAN eq 'Y'`checked~/if`> Yes &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="wear_turban" value="N" ~if $WEAR_TURBAN eq 'N'`checked~/if`> No &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="wear_turban" value="O" ~if $WEAR_TURBAN eq 'O'`checked~/if`> Occasionally
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding">
<label class="grey">Are you clean-shaven?  :</label>
<input type="radio"class="chbx" style="vertical-align:middle" name="clean_shaven" value="Y" ~if $CLEAN_SHAVEN eq 'Y'`checked~/if`> Yes &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="clean_shaven" value="N" ~if $CLEAN_SHAVEN eq 'N'`checked~/if`> No
</div>
<div class="sp15">&nbsp;</div>
~/if`
</div>
</div>
<!--Sikh-->
<!--Parsi-->
<div name="Parsi" id="Parsi">
<div class="row4 no-margin-padding">
<label class="grey">Are you a Zarathushtri :</label>
<input type="radio"class="chbx" style="vertical-align:middle" name="zarathushtri" value="Y" ~if $ZARATHUSHTRI eq 'Y'`checked~/if`> Yes &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="zarathushtri" value="N" ~if $ZARATHUSHTRI eq 'N'`checked~/if`> No
</div>
<div class="sp15">&nbsp;</div>
<div class="row4 no-margin-padding">
<label class="grey">Are both parents : Zarathushtri? &nbsp; </label>
<input type="radio"class="chbx" style="vertical-align:middle" name="parent_zarathushtri" value="Y" ~if $PARENTS_ZARATHUSHTRI eq 'Y'`checked~/if`> Yes &nbsp;<input type="radio" class="chbx" style="vertical-align:middle" name="parent_zarathushtri" value="N" ~if $PARENTS_ZARATHUSHTRI eq 'N'`checked~/if`> No
</div>
<div class="sp15">&nbsp;</div>
</div>
<!--Parsi-->

<div>
	~include_partial("profile/edit_native_place_fields",['sf_data'=>$sf_data])`
</div>
<div class="lf note b"><i class="green-hash">#</i><font class="fs13 b"> To edit these fields contact</font>
<a href="#" class="blink b blue" onclick="closeLayer();">Jeevansathi support team</a>
</div>
</div>


<script>
showContent();
</script>
<SCRIPT type="text/javascript" language="Javascript" SRC="~$IMG_URL`/min/?f=/js/~$editNativePlace_js`,/js/~$selectType_Page3_js`"></SCRIPT>
