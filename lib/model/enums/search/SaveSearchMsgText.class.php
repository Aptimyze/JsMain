<?php

//This class performs the save search functioning 
class SaveSearchMsgEnum{
	public static $arrperform = array("delete","listing","savesearch","count");
	public static $ID_Error = "Something went wrong";
	public static $SaveSearchError = "No search has been saved";
	public static $LimitError = "You can only save upto 5 searches";
	public static $SameSearchName ="A search with the same name already exists";
	public static $BlankError = "Save column cannot be left blank";
	public static $GenderError = "You cannot save a search for profiles of your gender";
	public static $InsertError = "Something went wrong";
	public static $Successdelete ="Successfully deleted";
	public static $SuccessSaved = "Search '<Name>' saved - you can access it from the menu";
	}
?>
