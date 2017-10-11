// Confirmation box for ad request
function confirmationBox()
{
	var where_to= confirm("This will place a request for profile Ad and cannot be undone. Are you sure?");
	if(where_to== true)
		return true;
	else
		return false;
}

// Send the ad request for the profile to the manager
function sendProfileAdRequest(profileid,cid,SITE_URL,box)
{
	if(box){
		confirmation =confirmationBox();
		if(confirmation=='')
			return;
	}
        url                     = SITE_URL+"/jsadmin/ap_ad_request.php";
        parameters              ="ajax_error=2&PROFILEID="+profileid+"&cid="+cid;
        url                     =url+"?"+parameters;
        call_after_function     ="ProfileAdRequest";
        send_ajax_request(url,"",call_after_function);
}
function ProfileAdRequest()
{
        var response=result;
        if(response =='ERROR'){
                msg ="Your request could not be processed due to technical issue, try later";
                return;
        }else{
                dID('adResponse').innerHTML ="<div class='ylw_confirm f_14 drk_gry b'>Your Request for Ad has been sent to your manager</div>";
		dID('adReq').style.display="none";
		dID('adRes').style.display="block";
                return;
        }
}

function addProfileComments(profileid,matchid,cid,SITE_URL)
{
	comments =dID('get_p_comments').value;
	comments =encodeURIComponent(comments);
	
	parameters ="ajax_error=2&PROFILEID="+profileid+"&MATCHID="+matchid+"&cid="+cid+"&comments="+comments;
	if(document.getElementById("close_call"))
	{
		if(document.getElementById("close_call").checked)
			parameters+="&call_history=Y";
		else
			 parameters+="&call_history=N";
	}
	call_after_function ="ProfileComments";

	url =SITE_URL+"/jsadmin/ap_add_comments.php";
        url =url+"?"+parameters;
        send_ajax_request(url,"",call_after_function,'POST');
}

function ProfileEditComments()
{
        dID('add_comments').style.display="block";
        dID('edit_comments').style.display="none";
        return;
}

function ProfileComments()
{
        var response=result;
        if(response =='ERROR'){
                msg ="Your request could not be processed due to technical issue, try later";
                return;
        }else{
                dID('show_p_comments').innerHTML =dID('get_p_comments').value;
		dID('add_comments').style.display="none";
		dID('edit_comments').style.display="block";
                return;
        }
}

function show_detail(detail)
{
	if(detail=='B'){
		dID('show_basic').style.display		="block";
		dID('show_contact').style.display	="none";
		dID('basic_detail_id').className	="";
		dID('contact_detail_id').className	="active";
		dID('b_black').className		="blk";
		dID('c_black').className		="";
	}
	else if(detail=='C'){
		dID('show_contact').style.display	="block";
		dID('show_basic').style.display		="none";
		dID('basic_detail_id').className	="active";
		dID('contact_detail_id').className	="";
                dID('b_black').className		="";
                dID('c_black').className		="blk";
	}
}

// print the attached document in the lead
function printLeadDocument(profileid,cid,SITE_URL)
{
        url                     = SITE_URL+"/jsadmin/ap_lead_attachment.php";
        parameters              ="ajax_error=2&PROFILEID="+profileid+"&cid="+cid;
        url                     =url+"?"+parameters;
        call_after_function     ="leadAttachedDocument";
        send_ajax_request(url,"",call_after_function);
}

function leadAttachedDocument()
{
        var response=result;
        if(response =='ERROR'){
                msg ="Your request could not be processed due to technical issue, try later";
                return;
        }else if(response =='success'){
                return true;
        }
}

function removeProfileFromList(profileid,matchid,cid,SITE_URL)
{
	parameters ="ajax_error=2&PROFILEID="+profileid+"&MATCHID="+matchid+"&cid="+cid;
	call_after_function ="afterRemoveProfile";

	url =SITE_URL+"/jsadmin/ap_remove_telecaller_list.php";
        url =url+"?"+parameters;
        send_ajax_request(url,"",call_after_function,'POST');
}

function afterRemoveProfile()
{
        var response=result;
        if(response =='ERROR'){
                msg ="Your request could not be processed due to technical issue, try later !";
                alert(msg);
                return;
        }else{
               
		dID('remove_prof_link').style.display="none";
		alert("Profile requested has been removed from the queue !");
                return;
        }
}
