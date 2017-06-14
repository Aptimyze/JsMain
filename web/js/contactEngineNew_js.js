var contact_loader_big='<table align="center" width="100%" height="100%" style="text-align: center;"><tbody><tr><td><img src="IMG_URL/images/contactImages/loader_big.gif"></td></tr></tbody></table> ';
var contact_loader_small='<table align="center" width="100%" height="100%" style="text-align: center;"><tbody><tr><td><img src="IMG_URL/images/searchImages/loader_small.gif"></td></tr></tbody></table> ';
var commonData	 = {};
var commonUrl="/contacts";
var conUrl={'Accept':commonUrl+"/PostAccept",'Notinterest':commonUrl+"/PostNotinterest",'Write':commonUrl+"/PostWrite",'ClickHere':commonUrl+"/PostCalldirect", "EOI": commonUrl+"/PostEOI", "Reminder": commonUrl+"/PostSendReminder","PreUnknown":commonUrl+"/PreUnknown"};
var id="id";
var layerid="layerid";
var reloadOtherTab=0;

function onAccept(layerid)
{	
	getFormElements(layerid);
	showLoader("div_"+layerid);
	formData("divname","div",layerid);
	sendRequest(conUrl.Accept,layerid);
	
}
function unknownTab(maindiv,layerid)
{
	if(maindiv=="contact")
		getFormElements(layerid,document.cd1);
	else
		getFormElements(layerid);
	showLoader(maindiv+"_"+layerid);
	formData("divname",maindiv,layerid);
	
	sendRequest(conUrl.PreUnknown,layerid);
}
function onNotInterested(layerid)
{	
	getFormElements(layerid);
	showLoader("div_"+layerid);
	formData("divname","div",layerid);
	sendRequest(conUrl.Notinterest,layerid);
	

}

function onExpressInterest(layerid)
{
  getFormElements(layerid);  
	showLoader("div_"+layerid);
  formData("divname","div",layerid);
  sendRequest(conUrl.EOI, layerid);
}

function onSendReminder(layerid) {
  getFormElements(layerid);
  showLoader("div_" + layerid);
  formData("divname","div",layerid);
  sendRequest(conUrl.Reminder, layerid);
}

function onWrite(layerid)
{
	getFormElements(layerid);
	formData("divname","div",layerid);
	showLoader("div_"+layerid);
	sendRequest(conUrl.Write,layerid);
	
}

function showLoader(id,typeofloader)
{
	if(!typeofloader)
		typeofloader="big";
	if(typeofloader=='big')
		$("#"+id).html(contact_loader_big);
	else
		$("#"+id).html(contact_loader_small);
	
}


function PostRequest(postData,id)
{
	
	var ce_url=postData[id].URL;
	$.ajax({
				url: ce_url,
				type: "POST",
				data: postData[id],
				success: function(result){
				AfterPostRequest(postData,id,result);
				}
			});
}


function getFormElements(layerid,formId)
{
	formData(id,layerid,layerid);
	if(!formId)
		formId=document.contact_engine;
		
	profilechecksum=getProfiles(layerid);
  
	formData("profilechecksum",profilechecksum,layerid);
	
	for(i=0; i<formId.elements.length; i++)
	{
		my_form_val = formId.elements[i].value;
		my_form_name = formId.elements[i].name;
		formData(my_form_name,my_form_val,layerid);
		
	}
}
function getProfiles(layerid)
{
	return layerid;
}
function formData(name,val,layerid)
{
	
	
	if(!commonData[layerid])
		commonData[layerid]={};
	commonData[layerid][name]=val;	
	
	var dd=1;

}

function updateTextBox(ele,textAreaId)
{
	$("#"+textAreaId).val(MES[ele.value]);
}

function changeRadio(value)
{
	$("#interest").css("display","none");
	$("#notinterest").css("display","none");	
	if(value=="notinterest")
	{	
		
		updateDropdown(declineDrop);
		$("#draft").val(MES["D1"]);
		$("#"+value).css("display","block");
		//button value
	}
	else
	{
		updateDropdown(acceptDrop);
		$("#draft").val(MES["PRE_2"]);
		$("#"+value).css("display","block");
		
	}
}
function updateDropdown(jsonArray)
{
	var str="";
	var selected='';
	for(key in jsonArray)
	{
		selected='';
		if(key=='D1' || key=='PRE_2' || key=='PRE_1')
			selected='selected';
			
		str+="<option  value='"+key+"' "+selected+">"+jsonArray[key]+"</option>";
	} 
  
  $("#draft_name").html(str); 
	
}
function save_draft(isreplace)
{
	var DRAFT_ID=$("#draft_name").val();
	var DRAFT_NAME=$.trim($("#after_draft_post_id").val());
	var DRAFT_MES=$("#draftMessage").val();
	if(DRAFT_NAME == "" )
	{
		$("#errId").html("Please fill the field before save");
		return (false);
	}
	if(DRAFT_ID == "" && isreplace==1)
    {
		$("#errId").html("Please select to replace with");
        return (false);
    }
	var saveData={'DRAFT_ID':DRAFT_ID,'DRAFT_NAME':DRAFT_NAME,'DRAFT_MES':DRAFT_MES};
	var ce_url="/contacts/SaveDraft";
	showLoader("saveDraft");
	$.ajax({
				url: ce_url,
				type: "POST",
				data: saveData,
				success: function(result){
				AfterSaveDraft(result);
				}
			});
	
}
function AfterSaveDraft(result)
{
	var saveDraft = $("#saveDraft");
	if(result.substr(0,5)=='ERROR')
	{
		saveDraft.html("<div class='sp15'></div><div>"+result.substr(6,result.length)+"</div>");
	}
	else
	{
		saveDraft.html("<div class='sp15'></div><div class='fs14'><img src='IMG_URL/profile/images/small_tick.gif' align='absmiddle' /> Your message has  been saved as <b>"+result+"</b><div class='sp5'></div></div>");
	}
	
}

function onSelection(val)
{
	$("#draft").val(MES[val]);
}

function onCompleteNow()
{
	var url="/profile/viewprofile.php?ownview=1&EditWhatNew=incompletProfile";
	document.location=url;
}

function sendRequest(url,id)
{
	$.ajax({
				url: url,
				type: "POST",
				data: commonData[id],
				success: function(result){
				AfterPostRequest(id,result);
				}
			});
}
function AfterPostRequest(id,result)
{
		var divid=commonData[id]["divname"]+"_"+id;
		reloadOtherTab=id;
		
		$("#"+divid).html(result);
}
function onExpressInterestDetail(layerid)
{
		getFormElements(layerid,document.cd1)
        showLoader("contact_"+layerid);
        formData("divname","contact",layerid);
		formData('status', 'I', layerid);
		sendRequest(conUrl.EOI,layerid);
}
function onClickHereDetail(layerid)
{
	getFormElements(layerid,document.cd1);
    showLoader("contact_"+layerid);
    formData("divname","contact",layerid);
     sendRequest(conUrl.ClickHere,layerid);
}
