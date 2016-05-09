function remove_fav(senders_data, afterFunction, multiple)
{
	var url_to_call=SITE_URL+'/profile/bookmark_remove.php?TYPE_OF=S&senders_data='+senders_data+'&ajax_error=2';
	if(multiple)
		url_to_call+="&multiple="+multiple;
	send_ajax_request(url_to_call,'show_book_loader',afterFunction,'GET');
}
function add_intro_call(senders_data, afterFunction)
{
        var url_to_call=SITE_URL+'/profile/handle_intro_call.php?TYPE_OF=S&to_do=add_intro&senders_data='+senders_data+'&ajax_error=2';
        send_ajax_request(url_to_call,'show_book_loader',afterFunction,'GET');
}

function remove_intro_call(senders_data, afterFunction)
{
        var url_to_call=SITE_URL+'/profile/handle_intro_call.php?TYPE_OF=S&to_do=remove_intro&senders_data='+senders_data+'&ajax_error=2';
        send_ajax_request(url_to_call,'show_book_loader',afterFunction,'GET');
}

function show_book_loader()
{
	dID('first_layer').style.display='none';
        dID('second_layer').display='none';
        dID('third_layer').style.display='none';
        dID('error_layer').style.display='none';
        dID('second_layer').style.display='inline';
	$.colorbox.resize();
}

function show_book_congrats_contacts()
{
	if(dID('reloadFirstPage').value)
		document.location.href="/profile/contacts_made_received.php?page=favorite&filter=M";
	else
		window.location.reload( false );
}

function show_book_congrats_remove_intro()
{
	window.location.reload( false );
}

function show_book_congrats_add_intro()
{
        dID('reload').value=true;
        dID('first_layer').style.display='none';
        dID('second_layer').style.display='none';
        dID('third_layer').style.display='none';
        dID('error_layer').style.display='none';
        dID('second_layer').style.display='none';
        dID('third_layer').style.display='inline';
	$.colorbox.resize();
}

function show_book_congrats()
{
	dID('first_layer').style.display='none';
        dID('second_layer').style.display='none';
        dID('third_layer').style.display='none';
        dID('error_layer').style.display='none';
        dID('second_layer').style.display='none';
	if(result=='A_E')
	{
		dID('ERROR_MES').innerHTML=common_error;
		dID('error_layer').style.display='inline';
	}
	else if(result.substr(0,5)=='ERROR')
        {
                dID('ERROR_MES').innerHTML=result.substr(6,result.length);
		dID('error_layer').style.display='inline';
                //dID("error_message").innerHTML=result.substr(5,result.length);
        }
	else
	{
		
		dID('third_layer').style.display='inline';
		//Required in view profile template
                if(dID('bookmark'))
                {
                        dID('bookmark').style.display='inline';
                        dID('bookmark_rem').style.display='none';
                }
	}
	$.colorbox.resize();
}

