<?php

class importUploadTracking
{
	/**
	* This function updates the table MIS.IMPORT_UPLOAD_TRACKING 
	* whenever a user imports/uploads a photo.
	**/
	public function photoSaveEntry($profileid,$photoSource,$noOfPhotos='1')
	{
		$obj = new IMPORT_UPLOAD_TRACKING();
		$obj->photoSaveEntry($profileid,$photoSource,$noOfPhotos);
	}

	/**
	* This function updates the table MIS.IMPORT_PAGES_TRACKING with the 
	* number of times a user has visited a page belonging to the import module.
	**/
	public function pageVisitCounterUpdate($profileid,$pageName,$importSite)
	{
		$obj = new IMPORT_PAGES_TRACKING();
		$obj->updatePageViewCounter($profileid,$pageName,$importSite);
	}
}
?>
