var selected_index = -1;
var results_count = 0;
var docF = "";
var results_container_div_id = "";
var results_inside_div_name = "";
var suggest_box_id = "";

//Function to show the result fetched for the entered string.
function show_result()
{
	if (req.readyState != 4)
	{
		// Not ready yet.
		//document.getElementById('code_status').innerHTML = "Checking..";
		return;
	}
	if (req.status == 200)
	{
		// The good stuff happens here!
		if(req.responseText.indexOf('|#|')!=-1)
		{
			var got_response = req.responseText.split("|#|");
			var to_write_string = '';

			if(got_response != "" && got_response.length)
			{
				var to_write_string = new Array();
				results_count = got_response.length;

				var i1 = got_response.length;
				for(var i=0;i<i1;i++)
				{
					to_write_string.push("<div style=\"display:block; cursor:pointer; padding-bottom:2px; background:#f2f2f2;\" class=\"item\" id=\"");
					to_write_string.push(results_inside_div_name);
					to_write_string.push("_");
					to_write_string.push(i);
					to_write_string.push("\"");
					to_write_string.push("onmouseover=\"handle_mouseover(this.id);\"");
					to_write_string.push("onmouseout=\"handle_mouseout(this.id);\"");
					to_write_string.push("onclick=\"handle_mouseclick(this.id);\" >");
					to_write_string.push("<span class=\"itemtext\" id=\"");
					to_write_string.push(results_inside_div_name);
					to_write_string.push("text_");
					to_write_string.push(i);
					to_write_string.push("\">");
					to_write_string.push(got_response[i]);
					to_write_string.push("</span></div>");
				}

				try{
					$("#"+results_container_div_id).html(to_write_string.join(''));
					suggest_div("show");
					//document.getElementById(results_container_div_id).innerHTML = to_write_string.join('');

				}catch(e)
				{
				}
			}
			else
				suggest_div("hide");
		}
	}
	else
	{
		// The web server gave us an error
		//document.getElementById('code_status').innerHTML = "Error:";
		return;
	}
}

/*function to fetch data for autosuggest.
parameters
	this -> current object reference
	keycode -> code of the pressed key
	container_div -> id of the div which will contain the fetched results.
	inside_div_name -> prefix of the id's of each div's inside the fetched results div.
*/
function auto_suggest(obj,keycode,container_div,inside_div_name)
{
	docF = document.form1;
	var site_url = docF.site_url.value;
	results_container_div_id = container_div;
	results_inside_div_name = inside_div_name;
	suggest_box_id = obj.id;

	switch(keycode)
	{
		case 27:
			//escape key
			suggest_div("hide");
			return false;
			break;
		case 38:
			//up arrow
			handle_arrowup();
			return false;
			break;
		case 40:
			//down arrow
			handle_arrowdown();
			return false;
			break;
		case 13:
			//enter key
			handle_mouseclick();
			suggest_div("hide");
			return false;
			break;
	}

	var value = escape(obj.value);
	var name = escape(obj.name);
	var req = createNewXmlHttpObject();
	var to_post = name + "=" + value + "&autosuggest=1";

	if(value != "")
	{
		req.open("POST",site_url + "/profile/registration_ajax_validation.php",true);
		req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		req.send(to_post);

		req.onreadystatechange = show_result;
	}
	if(value == ""){
		suggest_div("hide");	
	}
}

/*Function to handle the mouseover event on the div
 *parameters div_id - id of the div to show */
function handle_mouseover(div_id)
{
	for(var i=0;i<results_count;i++)
	{
		var present_id = results_inside_div_name + "_"  + i;
		if(present_id == div_id)
		{
			selected_index = i;
			highlight_item(present_id,"selected");
		}
	}
	document.getElementById(suggest_box_id).focus();
}

/* Function to handle the mouse out event on the div
 * parameters div_id - id of the div to handle
 */
function handle_mouseout(div_id)
{
	for(var i=0;i<results_count;i++)
	{
		var present_id = results_inside_div_name + "_"  + i;
		if(present_id == div_id)
		{
			selected_index = -1;
			highlight_item(present_id);
		}
	}
	document.getElementById(suggest_box_id).focus();
}

/* Function to hadle mouse click event on the div */
function handle_mouseclick()
{
	if(selected_index>=0)
	{
		var present_id = results_inside_div_name + "_" + selected_index;
		var text_id = results_inside_div_name + "text_" + selected_index;
		var pop_val = document.getElementById(text_id).innerHTML;
		document.getElementById(suggest_box_id).value = pop_val;
		suggest_div("hide");
		document.getElementById(suggest_box_id).focus();
		selected_index = -1;
	}
}

/* Function to handle arrow key down event on the div */
function handle_arrowdown()
{
	if(selected_index == results_count - 1)
		return ;
	else if(selected_index == -1)
	{
		highlight_item(results_inside_div_name + "_0","selected");
		selected_index++;
	}
	else
	{
		var j = selected_index+1;
		var present_id = results_inside_div_name + "_" + selected_index;
		var next_id = results_inside_div_name + "_"  + j;
		highlight_item(present_id);
		highlight_item(next_id,"selected");
		selected_index++;
	}
}

/* Function to handle arrow key up event on the div */
function handle_arrowup()
{
	if(selected_index == -1)
	{
		return;
	}
	else if(selected_index == 0)
	{
		highlight_item(results_inside_div_name + "_0","selected");
		selected_index = 0;
	}
	else
	{
		var j = selected_index-1;
		var present_id = results_inside_div_name + "_"  + selected_index;
		var previous_id = results_inside_div_name + "_"  + j;
		highlight_item(present_id);
		highlight_item(previous_id,"selected");
		selected_index--;
	}
}

/* Function to show/hide a div
 * parameters action - whether to show/hide div.
 */
function suggest_div(action)
{
	var iframe_div = "#"+results_container_div_id + "_iframe";
	$('#gotra_results').css("marginBottom","-1000px");
	$('#gotra_maternal_results').css("marginBottom","-1000px");
	$('#diocese_results').css("marginBottom","-1000px");
	if(action == "hide")
	{
		$("#"+results_container_div_id).hide();
		$(iframe_div).hide();
	}
	else if(action == "show")
	{
		$("#"+results_container_div_id).show();	
		//document.getElementById(results_container_div_id).style.display = 'block';
		if(document.getElementById(iframe_div) && document.getElementById(results_container_div_id))
			document.getElementById(iframe_div).style.height = document.getElementById(results_container_div_id).offsetHeight;
		$(iframe_div).show();
	}
}

/* Function to show a div as selected/de-selected
 * parameters div_id - id of the div.
 * action - to show as selected or de-selected.
 */
function highlight_item(div_id, action)
{
	if(action == "selected")
		document.getElementById(div_id).className = "selecteditem";
	else
		document.getElementById(div_id).className = "item";
}
