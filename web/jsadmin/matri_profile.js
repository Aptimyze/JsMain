/***************************************************************************************************************************
FILE NAME		: matri_profile.js
DESCRIPTION		: This file contains the javascript functions used in various matri profile files
CREATED BY		: Sriram Viswanathan.
DATE			: July 25th 2007.
***************************************************************************************************************************/
//this function is used to enable/disabel the buttons depending on the checkbox selections.
//if no checkbox is selected then the button is disabled, else it is enabled.
function enable_disable_button(form_name,button_name,checkbox_name,substring_value)
{
	if(form_name=="form1")
		docF=document.form1
	else if(form_name=="form2")
		docF=document.form2
	var i;
	var count=0;
	for(i=0; i<docF.elements.length; i++)
	{
		if(docF.elements[i].name.substring(0,substring_value)==checkbox_name)
		{
			if(docF.elements[i].checked==true)
				count++;
		}
	}
	if(count == 0)
	{
		if(button_name == "submit")
			docF.submit.disabled=true;
		if(button_name == "put_hold")
			docF.put_hold.disabled=true;
		if(button_name == "unhold_profile")
			docF.unhold_profile.disabled=true;
	}
	else
	{
		if(button_name == "submit")
			docF.submit.disabled=false;
		if(button_name == "put_hold")
			docF.put_hold.disabled=false;
		if(button_name == "unhold_profile")
			docF.unhold_profile.disabled=false;
	}
}

//this function checks whether an executive is selected for assigning.
function validate()
{
	var docF = document.form1;
	if(docF.executive.value=="")
	{
		alert('Please select an executive to assign');
		docF.executive.focus();
		return false;
	}
}

//function to open the comment window.
function comment_window(params)
{
	var url = "matri_add_comment.php?"+params;
	window.open(url,'','width=600,height=500,scrollbars=yes,resizable=no');
	return false;
}
