function display_more(tag_name) 
{
	var index = tag_name.substr(3);
	var show_tag = "list"+index;
	document.getElementById(tag_name).style.display = "none";
	document.getElementById(show_tag).style.display = "block";
}

function check_file(field_value,field_id)
{
	var index = field_id.substr(4);
	var tag_name = "text_box"+index;
	document.getElementById(tag_name).value = field_value;
}

function check_for_upload()
{
	window.scroll(100,100);
	document.getElementById("upload_error4").style.display = "none";
	document.getElementById("upload_btn").style.display = "none";
	document.getElementById("demo-status2").style.display = "none";
	document.getElementById("direction_text").style.display = "none";
	document.getElementById("demo-loader").style.display = "block";

	var total_tags = parseInt(document.getElementById("totalTags").value);
	var i = 0;
	var flag = 0;
	for (i=1;i<=total_tags;i++)
	{
		file_tag = "file"+i;
		if (document.getElementById(file_tag).value)
		{
			flag = 1;
			break;
		}
	}
	if (flag == 1)
	{
		document.non_flash_form.submit();
			
	}
	else
	{
		document.getElementById("demo-loader").style.display = "none";
		document.getElementById("direction_text").style.display = "block";
		document.getElementById("upload_error4").style.display = "block";
		document.getElementById("upload_btn").style.display = "block";
		document.getElementById("demo-status2").style.display = "block";
	}
}
