function showDateSelectionField(dateFieldId,startYear,endYear) {
	var count = 0;
    $('#'+dateFieldId).dateDropDowns({ dateFormat: 'DD-mm-yy',yearStart: startYear, yearEnd: endYear});
    
}   
