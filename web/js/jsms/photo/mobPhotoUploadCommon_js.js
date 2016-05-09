function displayConfirmationMessage(e){
	if ($('.photoheader').is(':visible') && $('#FadedRegion').is(':visible'))
{
		showSlider("null", e, 'posabs');
}
else if($('.photoheader').is(':visible'))
{
		showSlider(".photoheader", e);
}
        else  
{
		showSlider("null", e, 'posabs');
}
}

